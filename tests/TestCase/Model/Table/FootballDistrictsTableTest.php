<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FootballDistrictsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FootballDistrictsTable Test Case
 */
class FootballDistrictsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FootballDistrictsTable
     */
    protected $FootballDistricts;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.FootballDistricts',
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
        $config = $this->getTableLocator()->exists('FootballDistricts') ? [] : ['className' => FootballDistrictsTable::class];
        $this->FootballDistricts = $this->getTableLocator()->get('FootballDistricts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FootballDistricts);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\FootballDistrictsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
