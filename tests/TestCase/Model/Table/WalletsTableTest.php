<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WalletsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WalletsTable Test Case
 */
class WalletsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WalletsTable
     */
    protected $Wallets;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Wallets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Wallets') ? [] : ['className' => WalletsTable::class];
        $this->Wallets = $this->getTableLocator()->get('Wallets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Wallets);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\WalletsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\WalletsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
