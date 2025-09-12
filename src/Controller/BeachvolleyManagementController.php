<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Http\Exception\NotFoundException;

/**
 * BeachvolleyManagement Controller for Admin
 * Manages beach volleyball categories, types, and their relationships
 */
class BeachvolleyManagementController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
        $this->BeachvolleyCategories = TableRegistry::getTableLocator()->get('BeachvolleyCategories');
        $this->BeachvolleyTypes = TableRegistry::getTableLocator()->get('BeachvolleyTypes');
        $this->BeachvolleyCategoriesTypes = TableRegistry::getTableLocator()->get('BeachvolleyCategoriesTypes');
    }

    public function index()
    {
        $stats = [
            'categories_count' => $this->BeachvolleyCategories->find()->where(['active' => 1])->count(),
            'types_count' => $this->BeachvolleyTypes->find()->where(['active' => 1])->count(),
            'relationships_count' => $this->BeachvolleyCategoriesTypes->find()->count()
        ];
        
        $categories = $this->BeachvolleyCategories->find()
            ->where(['BeachvolleyCategories.active' => 1])
            ->contain(['BeachvolleyTypes' => function ($q) {
                return $q->where(['BeachvolleyTypes.active' => 1]);
            }])
            ->order(['BeachvolleyCategories.name' => 'ASC'])
            ->toArray();
            
        $types = $this->BeachvolleyTypes->find()
            ->where(['active' => 1])
            ->contain(['BeachvolleyCategories' => function ($q) {
                return $q->where(['BeachvolleyCategories.active' => 1]);
            }])
            ->order(['name' => 'ASC'])
            ->toArray();
        
        $this->set(compact('stats', 'categories', 'types'));
    }

    public function categories()
    {
        $categories = $this->BeachvolleyCategories->find()
            ->contain(['BeachvolleyTypes'])
            ->order(['name' => 'ASC'])
            ->toArray();
        $this->set(compact('categories'));
    }

    public function types()
    {
        $types = $this->BeachvolleyTypes->find()
            ->contain(['BeachvolleyCategories'])
            ->order(['name' => 'ASC'])
            ->toArray();
        $this->set(compact('types'));
    }

    public function relationships()
    {
        $categories = $this->BeachvolleyCategories->find()
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();
            
        $types = $this->BeachvolleyTypes->find()
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();
            
        $relationships = $this->BeachvolleyCategoriesTypes->find()
            ->contain(['BeachvolleyCategories', 'BeachvolleyTypes'])
            ->toArray();
            
        $relationshipMatrix = [];
        foreach ($relationships as $rel) {
            $relationshipMatrix[$rel->beachvolley_category_id][$rel->beachvolley_type_id] = true;
        }
        
        $this->set(compact('categories', 'types', 'relationships', 'relationshipMatrix'));
    }
}