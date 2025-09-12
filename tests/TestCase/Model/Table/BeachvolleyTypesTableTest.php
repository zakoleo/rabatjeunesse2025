<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BeachvolleyTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BeachvolleyTypesTable Test Case
 */
class BeachvolleyTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BeachvolleyTypesTable
     */
    protected $BeachvolleyTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BeachvolleyTypes',
        'app.BeachvolleyCategoriesTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BeachvolleyTypes') ? [] : ['className' => BeachvolleyTypesTable::class];
        $this->BeachvolleyTypes = $this->getTableLocator()->get('BeachvolleyTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BeachvolleyTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BeachvolleyTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\BeachvolleyTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
