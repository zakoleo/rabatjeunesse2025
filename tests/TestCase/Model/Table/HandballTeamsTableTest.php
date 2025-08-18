<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HandballTeamsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HandballTeamsTable Test Case
 */
class HandballTeamsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HandballTeamsTable
     */
    protected $HandballTeams;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.HandballTeams',
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
        $config = $this->getTableLocator()->exists('HandballTeams') ? [] : ['className' => HandballTeamsTable::class];
        $this->HandballTeams = $this->getTableLocator()->get('HandballTeams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->HandballTeams);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\HandballTeamsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\HandballTeamsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
