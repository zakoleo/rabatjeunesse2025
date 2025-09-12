<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VolleyballTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VolleyballTypesTable Test Case
 */
class VolleyballTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VolleyballTypesTable
     */
    protected $VolleyballTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.VolleyballTypes',
        'app.VolleyballCategoriesTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('VolleyballTypes') ? [] : ['className' => VolleyballTypesTable::class];
        $this->VolleyballTypes = $this->getTableLocator()->get('VolleyballTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->VolleyballTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\VolleyballTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\VolleyballTypesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
