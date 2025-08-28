<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VolleyballCategoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VolleyballCategoriesTable Test Case
 */
class VolleyballCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VolleyballCategoriesTable
     */
    protected $VolleyballCategories;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.VolleyballCategories',
        'app.VolleyballTeams',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('VolleyballCategories') ? [] : ['className' => VolleyballCategoriesTable::class];
        $this->VolleyballCategories = $this->getTableLocator()->get('VolleyballCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->VolleyballCategories);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\VolleyballCategoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
