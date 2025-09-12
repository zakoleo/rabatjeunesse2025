<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Http\Exception\NotFoundException;

/**
 * HandballManagement Controller for Admin
 * Manages handball categories, types, and their relationships
 */
class HandballManagementController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
        $this->HandballCategories = TableRegistry::getTableLocator()->get('HandballCategories');
        $this->HandballTypes = TableRegistry::getTableLocator()->get('HandballTypes');
        $this->HandballCategoriesTypes = TableRegistry::getTableLocator()->get('HandballCategoriesTypes');
    }

    public function index()
    {
        $stats = [
            'categories_count' => $this->HandballCategories->find()->where(['active' => 1])->count(),
            'types_count' => $this->HandballTypes->find()->where(['active' => 1])->count(),
            'relationships_count' => $this->HandballCategoriesTypes->find()->count()
        ];
        
        $categories = $this->HandballCategories->find()
            ->where(['HandballCategories.active' => 1])
            ->contain(['HandballTypes' => function ($q) {
                return $q->where(['HandballTypes.active' => 1]);
            }])
            ->order(['HandballCategories.name' => 'ASC'])
            ->toArray();
            
        $types = $this->HandballTypes->find()
            ->where(['active' => 1])
            ->contain(['HandballCategories' => function ($q) {
                return $q->where(['HandballCategories.active' => 1]);
            }])
            ->order(['name' => 'ASC'])
            ->toArray();
        
        $this->set(compact('stats', 'categories', 'types'));
    }

    public function categories()
    {
        $categories = $this->HandballCategories->find()
            ->contain(['HandballTypes'])
            ->order(['name' => 'ASC'])
            ->toArray();
        $this->set(compact('categories'));
    }

    public function types()
    {
        $types = $this->HandballTypes->find()
            ->contain(['HandballCategories'])
            ->order(['name' => 'ASC'])
            ->toArray();
        $this->set(compact('types'));
    }

    public function relationships()
    {
        $categories = $this->HandballCategories->find()
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();
            
        $types = $this->HandballTypes->find()
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();
            
        $relationships = $this->HandballCategoriesTypes->find()
            ->contain(['HandballCategories', 'HandballTypes'])
            ->toArray();
            
        $relationshipMatrix = [];
        foreach ($relationships as $rel) {
            $relationshipMatrix[$rel->handball_category_id][$rel->handball_type_id] = true;
        }
        
        $this->set(compact('categories', 'types', 'relationships', 'relationshipMatrix'));
    }
}