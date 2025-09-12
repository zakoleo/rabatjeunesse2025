<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Http\Exception\NotFoundException;

/**
 * FootballManagement Controller for Admin
 * Manages football categories, types, and their relationships
 */
class FootballManagementController extends AppController
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
        $this->FootballCategories = TableRegistry::getTableLocator()->get('FootballCategories');
        $this->FootballTypes = TableRegistry::getTableLocator()->get('FootballTypes');
        $this->FootballCategoriesTypes = TableRegistry::getTableLocator()->get('FootballCategoriesTypes');
    }

    /**
     * Dashboard - Overview of all football management
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'categories_count' => $this->FootballCategories->find()->where(['active' => 1])->count(),
            'types_count' => $this->FootballTypes->find()->where(['active' => 1])->count(),
            'relationships_count' => $this->FootballCategoriesTypes->find()->count()
        ];
        
        // Get recent categories with their relationships
        $categories = $this->FootballCategories->find()
            ->where(['FootballCategories.active' => 1])
            ->contain(['FootballTypes' => function ($q) {
                return $q->where(['FootballTypes.active' => 1]);
            }])
            ->order(['FootballCategories.name' => 'ASC'])
            ->toArray();
        
        // Get types for the tabs
        $types = $this->FootballTypes->find()
            ->contain(['FootballCategories' => function ($q) {
                return $q->where(['FootballCategories.active' => 1]);
            }])
            ->order(['code' => 'ASC'])
            ->toArray();
        
        $this->set(compact('stats', 'categories', 'types'));
    }

    // =============== CATEGORIES MANAGEMENT ===============

    /**
     * List all football categories
     */
    public function categories()
    {
        $categories = $this->FootballCategories->find()
            ->contain(['FootballTypes' => function ($q) {
                return $q->where(['FootballTypes.active' => 1]);
            }])
            ->order(['name' => 'ASC'])
            ->toArray();
        
        $this->set(compact('categories'));
    }

    /**
     * Add new football category
     */
    public function addCategory()
    {
        $category = $this->FootballCategories->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $category = $this->FootballCategories->patchEntity($category, $this->request->getData());
            
            if ($this->FootballCategories->save($category)) {
                $this->Flash->success(__('Football category has been saved.'));
                return $this->redirect(['action' => 'categories']);
            }
            $this->Flash->error(__('Unable to save the football category.'));
        }
        
        $this->set(compact('category'));
    }

    /**
     * Edit football category
     */
    public function editCategory($id = null)
    {
        $category = $this->FootballCategories->get($id, [
            'contain' => ['FootballTypes']
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->FootballCategories->patchEntity($category, $this->request->getData());
            
            if ($this->FootballCategories->save($category)) {
                $this->Flash->success(__('Football category has been updated.'));
                return $this->redirect(['action' => 'categories']);
            }
            $this->Flash->error(__('Unable to update the football category.'));
        }
        
        $this->set(compact('category'));
    }

    /**
     * Delete football category
     */
    public function deleteCategory($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $category = $this->FootballCategories->get($id);
        
        if ($this->FootballCategories->delete($category)) {
            $this->Flash->success(__('Football category has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete the football category.'));
        }
        
        return $this->redirect(['action' => 'categories']);
    }

    // =============== TYPES MANAGEMENT ===============

    /**
     * List all football types
     */
    public function types()
    {
        $types = $this->FootballTypes->find()
            ->contain(['FootballCategories' => function ($q) {
                return $q->where(['FootballCategories.active' => 1]);
            }])
            ->order(['code' => 'ASC'])
            ->toArray();
        
        $this->set(compact('types'));
    }

    /**
     * Add new football type
     */
    public function addType()
    {
        $type = $this->FootballTypes->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $type = $this->FootballTypes->patchEntity($type, $this->request->getData());
            
            if ($this->FootballTypes->save($type)) {
                $this->Flash->success(__('Football type has been saved.'));
                return $this->redirect(['action' => 'types']);
            }
            $this->Flash->error(__('Unable to save the football type.'));
        }
        
        $this->set(compact('type'));
    }

    /**
     * Edit football type
     */
    public function editType($id = null)
    {
        $type = $this->FootballTypes->get($id, [
            'contain' => ['FootballCategories']
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $type = $this->FootballTypes->patchEntity($type, $this->request->getData());
            
            if ($this->FootballTypes->save($type)) {
                $this->Flash->success(__('Football type has been updated.'));
                return $this->redirect(['action' => 'types']);
            }
            $this->Flash->error(__('Unable to update the football type.'));
        }
        
        $this->set(compact('type'));
    }

    /**
     * Delete football type
     */
    public function deleteType($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $type = $this->FootballTypes->get($id);
        
        if ($this->FootballTypes->delete($type)) {
            $this->Flash->success(__('Football type has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete the football type.'));
        }
        
        return $this->redirect(['action' => 'types']);
    }

    // =============== RELATIONSHIPS MANAGEMENT ===============

    /**
     * Manage category-type relationships
     */
    public function relationships()
    {
        $categories = $this->FootballCategories->find()
            ->where(['active' => 1])
            ->contain(['FootballTypes' => function ($q) {
                return $q->where(['FootballTypes.active' => 1]);
            }])
            ->order(['name' => 'ASC'])
            ->toArray();
        
        $allTypes = $this->FootballTypes->find()
            ->where(['active' => 1])
            ->order(['code' => 'ASC'])
            ->toArray();
        
        $this->set(compact('categories', 'allTypes'));
    }

    /**
     * Add relationship between category and type
     */
    public function addRelationship()
    {
        $this->request->allowMethod(['post']);
        
        $categoryId = $this->request->getData('category_id');
        $typeId = $this->request->getData('type_id');
        
        if (!$categoryId || !$typeId) {
            $this->Flash->error(__('Please select both category and type.'));
            return $this->redirect(['action' => 'relationships']);
        }
        
        // Check if relationship already exists
        $existing = $this->FootballCategoriesTypes->find()
            ->where([
                'football_category_id' => $categoryId,
                'football_type_id' => $typeId
            ])
            ->first();
        
        if ($existing) {
            $this->Flash->error(__('This relationship already exists.'));
            return $this->redirect(['action' => 'relationships']);
        }
        
        // Create new relationship
        $relationship = $this->FootballCategoriesTypes->newEmptyEntity();
        $relationship->football_category_id = $categoryId;
        $relationship->football_type_id = $typeId;
        
        if ($this->FootballCategoriesTypes->save($relationship)) {
            $this->Flash->success(__('Relationship has been added.'));
        } else {
            $this->Flash->error(__('Unable to add the relationship.'));
        }
        
        return $this->redirect(['action' => 'relationships']);
    }

    /**
     * Remove relationship between category and type
     */
    public function removeRelationship()
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $categoryId = $this->request->getData('category_id');
        $typeId = $this->request->getData('type_id');
        
        $relationship = $this->FootballCategoriesTypes->find()
            ->where([
                'football_category_id' => $categoryId,
                'football_type_id' => $typeId
            ])
            ->first();
        
        if (!$relationship) {
            $this->Flash->error(__('Relationship not found.'));
            return $this->redirect(['action' => 'relationships']);
        }
        
        if ($this->FootballCategoriesTypes->delete($relationship)) {
            $this->Flash->success(__('Relationship has been removed.'));
        } else {
            $this->Flash->error(__('Unable to remove the relationship.'));
        }
        
        return $this->redirect(['action' => 'relationships']);
    }

    /**
     * Bulk manage relationships for a category
     */
    public function manageRelationships($categoryId = null)
    {
        if (!$categoryId) {
            throw new NotFoundException(__('Category not found.'));
        }
        
        $category = $this->FootballCategories->get($categoryId, [
            'contain' => ['FootballTypes']
        ]);
        
        $allTypes = $this->FootballTypes->find()
            ->where(['active' => 1])
            ->order(['code' => 'ASC'])
            ->toArray();
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $selectedTypeIds = $this->request->getData('type_ids') ?: [];
            
            // Remove all existing relationships for this category
            $this->FootballCategoriesTypes->deleteAll([
                'football_category_id' => $categoryId
            ]);
            
            // Add new relationships
            foreach ($selectedTypeIds as $typeId) {
                $relationship = $this->FootballCategoriesTypes->newEmptyEntity();
                $relationship->football_category_id = $categoryId;
                $relationship->football_type_id = $typeId;
                $this->FootballCategoriesTypes->save($relationship);
            }
            
            $this->Flash->success(__('Relationships have been updated for category: {0}', $category->name));
            return $this->redirect(['action' => 'relationships']);
        }
        
        // Get current relationships
        $currentTypeIds = [];
        foreach ($category->football_types as $type) {
            $currentTypeIds[] = $type->id;
        }
        
        $this->set(compact('category', 'allTypes', 'currentTypeIds'));
    }
}