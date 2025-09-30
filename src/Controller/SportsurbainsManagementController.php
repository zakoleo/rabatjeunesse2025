<?php
declare(strict_types=1);

namespace App\Controller;

class SportsurbainsManagementController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
        $this->viewBuilder()->setLayout('admin');
        
        $this->SportsurbainsParticipants = $this->fetchTable('SportsurbainsParticipants');
        $this->SportsurbainsCategories = $this->fetchTable('SportsurbainsCategories');
    }
    
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        
        $user = $this->Authentication->getIdentity();
        if (!$user || !$user->is_admin) {
            $this->Flash->error('Accès non autorisé.');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }
    
    public function index()
    {
        $participants = $this->SportsurbainsParticipants->find()
            ->contain(['SportsurbainsCategories', 'Users'])
            ->order(['SportsurbainsParticipants.created' => 'DESC']);
        
        $stats = [
            'total' => $participants->count(),
            'verified' => (clone $participants)->where(['SportsurbainsParticipants.status' => 'verified'])->count(),
            'pending' => (clone $participants)->where(['SportsurbainsParticipants.status' => 'pending'])->count(),
            'rejected' => (clone $participants)->where(['SportsurbainsParticipants.status' => 'rejected'])->count(),
        ];
        
        $this->set(compact('participants', 'stats'));
    }
    
    public function view($id = null)
    {
        $participant = $this->SportsurbainsParticipants->get($id, [
            'contain' => ['SportsurbainsCategories', 'Users']
        ]);
        
        $this->set('participant', $participant);
    }
    
    public function updateStatus()
    {
        $this->request->allowMethod(['post']);
        
        $id = $this->request->getData('id');
        $status = $this->request->getData('status');
        
        $participant = $this->SportsurbainsParticipants->get($id);
        $participant->status = $status;
        
        if ($status === 'verified') {
            $participant->verified_at = new \DateTime();
            $participant->verified_by = $this->Authentication->getIdentity()->id;
        }
        
        if ($this->SportsurbainsParticipants->save($participant)) {
            $this->Flash->success('Le statut a été mis à jour.');
        } else {
            $this->Flash->error('Erreur lors de la mise à jour du statut.');
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    public function saveVerificationNotes()
    {
        $this->request->allowMethod(['post']);
        
        $id = $this->request->getData('id');
        $notes = $this->request->getData('notes');
        
        $participant = $this->SportsurbainsParticipants->get($id);
        $participant->verification_notes = $notes;
        
        if ($this->SportsurbainsParticipants->save($participant)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
        }
        
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => false]));
    }
}