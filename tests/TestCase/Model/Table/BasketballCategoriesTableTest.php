<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BasketballCategoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BasketballCategoriesTable Test Case
 */
class BasketballCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BasketballCategoriesTable
     */
    protected $BasketballCategories;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BasketballCategories',
        'app.BasketballTeams',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BasketballCategories') ? [] : ['className' => BasketballCategoriesTable::class];
        $this->BasketballCategories = $this->getTableLocator()->get('BasketballCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BasketballCategories);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BasketballCategoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
