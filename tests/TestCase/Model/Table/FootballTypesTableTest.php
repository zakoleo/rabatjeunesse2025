<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FootballTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FootballTypesTable Test Case
 */
class FootballTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FootballTypesTable
     */
    protected $FootballTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.FootballTypes',
        'app.NewTeams',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FootballTypes') ? [] : ['className' => FootballTypesTable::class];
        $this->FootballTypes = $this->getTableLocator()->get('FootballTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FootballTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\FootballTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\FootballTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
