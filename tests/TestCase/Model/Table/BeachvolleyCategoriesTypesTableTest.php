<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BeachvolleyCategoriesTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BeachvolleyCategoriesTypesTable Test Case
 */
class BeachvolleyCategoriesTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BeachvolleyCategoriesTypesTable
     */
    protected $BeachvolleyCategoriesTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BeachvolleyCategoriesTypes',
        'app.BeachvolleyCategories',
        'app.BeachvolleyTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BeachvolleyCategoriesTypes') ? [] : ['className' => BeachvolleyCategoriesTypesTable::class];
        $this->BeachvolleyCategoriesTypes = $this->getTableLocator()->get('BeachvolleyCategoriesTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BeachvolleyCategoriesTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BeachvolleyCategoriesTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\BeachvolleyCategoriesTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
