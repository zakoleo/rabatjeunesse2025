<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FootballOrganisationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FootballOrganisationsTable Test Case
 */
class FootballOrganisationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FootballOrganisationsTable
     */
    protected $FootballOrganisations;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.FootballOrganisations',
        'app.Teams',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FootballOrganisations') ? [] : ['className' => FootballOrganisationsTable::class];
        $this->FootballOrganisations = $this->getTableLocator()->get('FootballOrganisations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FootballOrganisations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\FootballOrganisationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
