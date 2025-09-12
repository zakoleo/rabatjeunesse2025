<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Http\Exception\NotFoundException;

/**
 * VolleyballManagement Controller for Admin
 * Manages volleyball categories, types, and their relationships
 */
class VolleyballManagementController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
        $this->VolleyballCategories = TableRegistry::getTableLocator()->get('VolleyballCategories');
        $this->VolleyballTypes = TableRegistry::getTableLocator()->get('VolleyballTypes');
        $this->VolleyballCategoriesTypes = TableRegistry::getTableLocator()->get('VolleyballCategoriesTypes');
    }

    public function index()
    {
        $stats = [
            'categories_count' => $this->VolleyballCategories->find()->where(['active' => 1])->count(),
            'types_count' => $this->VolleyballTypes->find()->where(['active' => 1])->count(),
            'relationships_count' => $this->VolleyballCategoriesTypes->find()->count()
        ];
        
        $categories = $this->VolleyballCategories->find()
            ->where(['VolleyballCategories.active' => 1])
            ->contain(['VolleyballTypes' => function ($q) {
                return $q->where(['VolleyballTypes.active' => 1]);
            }])
            ->order(['VolleyballCategories.name' => 'ASC'])
            ->toArray();
            
        $types = $this->VolleyballTypes->find()
            ->where(['active' => 1])
            ->contain(['VolleyballCategories' => function ($q) {
                return $q->where(['VolleyballCategories.active' => 1]);
            }])
            ->order(['name' => 'ASC'])
            ->toArray();
        
        $this->set(compact('stats', 'categories', 'types'));
    }

    public function categories()
    {
        $categories = $this->VolleyballCategories->find()
            ->contain(['VolleyballTypes'])
            ->order(['name' => 'ASC'])
            ->toArray();
        $this->set(compact('categories'));
    }

    public function types()
    {
        $types = $this->VolleyballTypes->find()
            ->contain(['VolleyballCategories'])
            ->order(['name' => 'ASC'])
            ->toArray();
        $this->set(compact('types'));
    }

    public function relationships()
    {
        $categories = $this->VolleyballCategories->find()
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();
            
        $types = $this->VolleyballTypes->find()
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();
            
        $relationships = $this->VolleyballCategoriesTypes->find()
            ->contain(['VolleyballCategories', 'VolleyballTypes'])
            ->toArray();
            
        $relationshipMatrix = [];
        foreach ($relationships as $rel) {
            $relationshipMatrix[$rel->volleyball_category_id][$rel->volleyball_type_id] = true;
        }
        
        $this->set(compact('categories', 'types', 'relationships', 'relationshipMatrix'));
    }
}