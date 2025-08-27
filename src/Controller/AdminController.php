<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Admin Controller - COMPLETELY REBUILT
 */
class AdminController extends AppController
{
    /**
     * beforeFilter - Simplified authentication
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        
        // For now, allow access without authentication for testing
        // TODO: Re-enable proper authentication later
    }
    
    /**
     * Admin Dashboard
     */
    public function index()
    {
        $this->Flash->success('Bienvenue dans l\'administration');
    }
    
    /**
     * Users management - REBUILT FROM SCRATCH
     */
    public function users()
    {
        try {
            // Load Users table
            $usersTable = $this->fetchTable('Users');
            
            // Get all users with simple query
            $users = $usersTable->find('all')
                ->order(['created' => 'DESC'])
                ->toArray();
            
            // Success message
            $this->Flash->success('Chargement réussi de ' . count($users) . ' utilisateurs');
            
            // Set data for view
            $this->set([
                'users' => $users,
                'title' => 'Gestion des Utilisateurs'
            ]);
            
        } catch (\Exception $e) {
            // Error handling
            $this->Flash->error('Erreur: ' . $e->getMessage());
            $this->set([
                'users' => [],
                'title' => 'Gestion des Utilisateurs - Erreur'
            ]);
        }
    }
    
    /**
     * Export users to CSV
     */
    public function exportUsers()
    {
        $this->Flash->info('Fonctionnalité d\'export en cours de développement');
        return $this->redirect(['action' => 'users']);
    }
    
    /**
     * View user details
     */
    public function userView($id)
    {
        $this->Flash->info('Page de détails utilisateur en cours de développement');
        return $this->redirect(['action' => 'users']);
    }
    
    /**
     * Make user admin
     */
    public function makeAdmin($id)
    {
        $this->request->allowMethod(['post']);
        
        try {
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->get($id);
            
            $user->is_admin = true;
            
            if ($usersTable->save($user)) {
                $this->Flash->success('Utilisateur promu administrateur avec succès');
            } else {
                $this->Flash->error('Erreur lors de la promotion');
            }
        } catch (\Exception $e) {
            $this->Flash->error('Erreur: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'users']);
    }
    
    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $this->request->allowMethod(['post']);
        
        try {
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->get($id);
            
            if ($usersTable->delete($user)) {
                $this->Flash->success('Utilisateur supprimé avec succès');
            } else {
                $this->Flash->error('Erreur lors de la suppression');
            }
        } catch (\Exception $e) {
            $this->Flash->error('Erreur: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'users']);
    }
}
?>