<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HandballCategoriesTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HandballCategoriesTypesTable Test Case
 */
class HandballCategoriesTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HandballCategoriesTypesTable
     */
    protected $HandballCategoriesTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.HandballCategoriesTypes',
        'app.HandballCategories',
        'app.HandballTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('HandballCategoriesTypes') ? [] : ['className' => HandballCategoriesTypesTable::class];
        $this->HandballCategoriesTypes = $this->getTableLocator()->get('HandballCategoriesTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->HandballCategoriesTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\HandballCategoriesTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\HandballCategoriesTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
