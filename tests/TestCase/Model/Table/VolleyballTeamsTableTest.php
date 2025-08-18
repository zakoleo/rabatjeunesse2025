<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VolleyballTeamsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VolleyballTeamsTable Test Case
 */
class VolleyballTeamsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VolleyballTeamsTable
     */
    protected $VolleyballTeams;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.VolleyballTeams',
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
        $config = $this->getTableLocator()->exists('VolleyballTeams') ? [] : ['className' => VolleyballTeamsTable::class];
        $this->VolleyballTeams = $this->getTableLocator()->get('VolleyballTeams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->VolleyballTeams);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\VolleyballTeamsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\VolleyballTeamsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
