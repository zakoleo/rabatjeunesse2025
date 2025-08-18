<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BasketballTeamsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BasketballTeamsTable Test Case
 */
class BasketballTeamsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BasketballTeamsTable
     */
    protected $BasketballTeams;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BasketballTeams',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BasketballTeams') ? [] : ['className' => BasketballTeamsTable::class];
        $this->BasketballTeams = $this->getTableLocator()->get('BasketballTeams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BasketballTeams);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BasketballTeamsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\BasketballTeamsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
