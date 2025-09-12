<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BasketballCategoriesTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BasketballCategoriesTypesTable Test Case
 */
class BasketballCategoriesTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BasketballCategoriesTypesTable
     */
    protected $BasketballCategoriesTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BasketballCategoriesTypes',
        'app.BasketballCategories',
        'app.BasketballTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BasketballCategoriesTypes') ? [] : ['className' => BasketballCategoriesTypesTable::class];
        $this->BasketballCategoriesTypes = $this->getTableLocator()->get('BasketballCategoriesTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BasketballCategoriesTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BasketballCategoriesTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\BasketballCategoriesTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
