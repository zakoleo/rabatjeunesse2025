<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BeachvolleyTeamsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BeachvolleyTeamsTable Test Case
 */
class BeachvolleyTeamsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BeachvolleyTeamsTable
     */
    protected $BeachvolleyTeams;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BeachvolleyTeams',
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
        $config = $this->getTableLocator()->exists('BeachvolleyTeams') ? [] : ['className' => BeachvolleyTeamsTable::class];
        $this->BeachvolleyTeams = $this->getTableLocator()->get('BeachvolleyTeams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BeachvolleyTeams);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BeachvolleyTeamsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\BeachvolleyTeamsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
