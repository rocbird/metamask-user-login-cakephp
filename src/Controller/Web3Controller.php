<?php           
declare(strict_types=1);
        
namespace App\Controller; 
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;

use Elliptic\EC;
use kornrunner\Keccak;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Kslogs Controller
 *              
 * @property \App\Model\Table\KslogsTable $Kslogs
 * @method \App\Model\Entity\Kslog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */     
class Web3Controller extends AppController
{       
	var $JWT_secret = '4Eac8AS2cw84easd65araADX';
	function index(){
	}		
	function login() {
		$data = json_decode(file_get_contents("php://input"));
		$request = $data->request;

		if (!empty($data->address)) {
			$data->address = strtolower($data->address);
		}

		$address = $data->address;

		$this->loadModel('Wallets');
		$nonce = $this->Wallets->find()->where(['address' => $address])->first();

		if($nonce){
			echo("Sign this message to validate that you are the owner of the account. Random string: " . $nonce->nonce);
		}
		else {
			$nonce = uniqid();
			$this->loadModel('Wallets');
			$d = $this->Wallets->newEmptyEntity();
			$d->address = $address;
			$d->nonce = $nonce;
			$this->Wallets->save($d);

			echo ("Sign this message to validate that you are the owner of the account. Random string: " . $nonce);
		}
		exit;

	}
	function auth(){
		$data = json_decode(file_get_contents("php://input"));
		$request = $data->request;

		$address = $data->address;
		$signature = $data->signature;

		$this->loadModel('Wallets');
		$nonce = $this->Wallets->find()->where(['address' => $address])->first();

		$message = "Sign this message to validate that you are the owner of the account. Random string: " . $nonce->nonce;

		if ($this->verifySignature($message, $signature, $address)) {
			$q = $this->Wallets->find()->where(['address' => $address])->first();
			$publicName = htmlspecialchars($q->publicName ?? "", ENT_QUOTES, 'UTF-8');

			$nonce = uniqid();
			$q->nonce = $nonce;
			$this->Wallets->save($q);

			$token = array();
			$token['address'] = $address;
			$JWT = JWT::encode($token, $this->JWT_secret, 'HS256');
			echo(json_encode(["Success", $publicName, $JWT]));
		}
		else {
			echo "error";
		}
		exit;
	}
	function updatepn(){
		$data = json_decode(file_get_contents("php://input"));

		$publicName = $data->publicName;
		$address = $data->address;

		try { $JWT = JWT::decode($data->JWT, new Key($this->JWT_secret, 'HS256')); }
		catch (\Exception $e) { echo 'Authentication error'; exit; }

		$this->loadModel('Wallets');
		$q = $this->Wallets->find()->where(['address' => $address])->first();
		$q->publicName = $publicName;
		$this->Wallets->save($q);

		echo "Public name for $address updated to $publicName";
		exit;
	}
	function pubKeyToAddress($pubkey) {
		return "0x" . substr(Keccak::hash(substr(hex2bin($pubkey->encode("hex")), 1), 256), 24);
	}
	function verifySignature($message, $signature, $address) {
		$msglen = strlen($message);
		$hash   = Keccak::hash("\x19Ethereum Signed Message:\n{$msglen}{$message}", 256);
		$sign   = [
			"r" => substr($signature, 2, 64),
			"s" => substr($signature, 66, 64)
		];
		$recid  = ord(hex2bin(substr($signature, 130, 2))) - 27;
		if ($recid != ($recid & 1)) {
			return false;
		}
		
		$ec = new EC('secp256k1');
		$pubkey = $ec->recoverPubKey($hash, $sign, $recid);

		return $address == $this->pubKeyToAddress($pubkey);
	}
}
