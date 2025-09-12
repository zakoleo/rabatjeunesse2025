<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BasketballTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BasketballTypesTable Test Case
 */
class BasketballTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BasketballTypesTable
     */
    protected $BasketballTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BasketballTypes',
        'app.BasketballTeams',
        'app.BasketballCategories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BasketballTypes') ? [] : ['className' => BasketballTypesTable::class];
        $this->BasketballTypes = $this->getTableLocator()->get('BasketballTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BasketballTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BasketballTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\BasketballTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
