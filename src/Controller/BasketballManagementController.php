<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Http\Exception\NotFoundException;

/**
 * BasketballManagement Controller for Admin
 * Manages basketball categories, types, and their relationships
 */
class BasketballManagementController extends AppController
{
    /**
     * Initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
        
        // Use admin layout for all admin pages
        $this->viewBuilder()->setLayout('admin');
        
        // Load required tables
        $this->BasketballCategories = TableRegistry::getTableLocator()->get('BasketballCategories');
        $this->BasketballTypes = TableRegistry::getTableLocator()->get('BasketballTypes');
        $this->BasketballCategoriesTypes = TableRegistry::getTableLocator()->get('BasketballCategoriesTypes');
    }

    /**
     * Dashboard - Overview of all basketball management
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'categories_count' => $this->BasketballCategories->find()->where(['active' => 1])->count(),
            'types_count' => $this->BasketballTypes->find()->where(['active' => 1])->count(),
            'relationships_count' => $this->BasketballCategoriesTypes->find()->count()
        ];
        
        // Get recent categories with their relationships
        $categories = $this->BasketballCategories->find()
            ->where(['BasketballCategories.active' => 1])
            ->contain(['BasketballTypes' => function ($q) {
                return $q->where(['BasketballTypes.active' => 1]);
            }])
            ->order(['BasketballCategories.name' => 'ASC'])
            ->toArray();
            
        // Get recent types
        $types = $this->BasketballTypes->find()
            ->where(['active' => 1])
            ->contain(['BasketballCategories' => function ($q) {
                return $q->where(['BasketballCategories.active' => 1]);
            }])
            ->order(['name' => 'ASC'])
            ->toArray();
        
        $this->set(compact('stats', 'categories', 'types'));
    }

    /**
     * Categories management
     */
    public function categories()
    {
        $categories = $this->BasketballCategories->find()
            ->contain(['BasketballTypes'])
            ->order(['name' => 'ASC'])
            ->toArray();
        
        $this->set(compact('categories'));
    }

    /**
     * Types management
     */
    public function types()
    {
        $types = $this->BasketballTypes->find()
            ->contain(['BasketballCategories'])
            ->order(['name' => 'ASC'])
            ->toArray();
        
        $this->set(compact('types'));
    }

    /**
     * Relationships management
     */
    public function relationships()
    {
        $categories = $this->BasketballCategories->find()
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();
            
        $types = $this->BasketballTypes->find()
            ->where(['active' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();
            
        // Get existing relationships in a format that's easy to work with
        $relationships = $this->BasketballCategoriesTypes->find()
            ->contain(['BasketballCategories', 'BasketballTypes'])
            ->toArray();
            
        // Create a matrix for easy display
        $relationshipMatrix = [];
        foreach ($relationships as $rel) {
            $relationshipMatrix[$rel->basketball_category_id][$rel->basketball_type_id] = true;
        }
        
        $this->set(compact('categories', 'types', 'relationships', 'relationshipMatrix'));
    }
}