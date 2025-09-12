<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HandballTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HandballTypesTable Test Case
 */
class HandballTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HandballTypesTable
     */
    protected $HandballTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.HandballTypes',
        'app.HandballCategoriesTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('HandballTypes') ? [] : ['className' => HandballTypesTable::class];
        $this->HandballTypes = $this->getTableLocator()->get('HandballTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->HandballTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\HandballTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\HandballTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
