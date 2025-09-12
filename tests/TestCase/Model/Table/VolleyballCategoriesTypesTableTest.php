<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VolleyballCategoriesTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VolleyballCategoriesTypesTable Test Case
 */
class VolleyballCategoriesTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VolleyballCategoriesTypesTable
     */
    protected $VolleyballCategoriesTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.VolleyballCategoriesTypes',
        'app.VolleyballCategories',
        'app.VolleyballTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('VolleyballCategoriesTypes') ? [] : ['className' => VolleyballCategoriesTypesTable::class];
        $this->VolleyballCategoriesTypes = $this->getTableLocator()->get('VolleyballCategoriesTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->VolleyballCategoriesTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\VolleyballCategoriesTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\VolleyballCategoriesTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
