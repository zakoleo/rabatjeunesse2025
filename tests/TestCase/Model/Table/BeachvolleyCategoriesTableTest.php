<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BeachvolleyCategoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BeachvolleyCategoriesTable Test Case
 */
class BeachvolleyCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BeachvolleyCategoriesTable
     */
    protected $BeachvolleyCategories;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BeachvolleyCategories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BeachvolleyCategories') ? [] : ['className' => BeachvolleyCategoriesTable::class];
        $this->BeachvolleyCategories = $this->getTableLocator()->get('BeachvolleyCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BeachvolleyCategories);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BeachvolleyCategoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
