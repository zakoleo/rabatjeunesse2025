<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FootballCategoriesTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FootballCategoriesTypesTable Test Case
 */
class FootballCategoriesTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FootballCategoriesTypesTable
     */
    protected $FootballCategoriesTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.FootballCategoriesTypes',
        'app.FootballCategories',
        'app.FootballTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FootballCategoriesTypes') ? [] : ['className' => FootballCategoriesTypesTable::class];
        $this->FootballCategoriesTypes = $this->getTableLocator()->get('FootballCategoriesTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FootballCategoriesTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\FootballCategoriesTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\FootballCategoriesTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
