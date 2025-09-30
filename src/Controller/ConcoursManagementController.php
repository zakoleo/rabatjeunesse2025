<?php
declare(strict_types=1);

namespace App\Controller;

class ConcoursManagementController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
        $this->viewBuilder()->setLayout('admin');
        
        $this->ConcoursParticipants = $this->fetchTable('ConcoursParticipants');
        $this->ConcoursCategories = $this->fetchTable('ConcoursCategories');
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
        $participants = $this->ConcoursParticipants->find()
            ->contain(['ConcoursCategories', 'Users'])
            ->order(['ConcoursParticipants.created' => 'DESC']);
        
        $stats = [
            'total' => $participants->count(),
            'verified' => (clone $participants)->where(['ConcoursParticipants.status' => 'verified'])->count(),
            'pending' => (clone $participants)->where(['ConcoursParticipants.status' => 'pending'])->count(),
            'rejected' => (clone $participants)->where(['ConcoursParticipants.status' => 'rejected'])->count(),
        ];
        
        // Stats par type de concours
        $statsByType = [];
        $concoursTypes = \App\Model\Table\ConcoursParticipantsTable::getConcoursTypes();
        foreach ($concoursTypes as $type => $label) {
            $statsByType[$type] = (clone $participants)->where(['ConcoursParticipants.type_concours' => $type])->count();
        }
        
        $this->set(compact('participants', 'stats', 'statsByType'));
    }
    
    public function view($id = null)
    {
        $participant = $this->ConcoursParticipants->get($id, [
            'contain' => ['ConcoursCategories', 'Users']
        ]);
        
        $this->set('participant', $participant);
    }
    
    public function updateStatus()
    {
        $this->request->allowMethod(['post']);
        
        $id = $this->request->getData('id');
        $status = $this->request->getData('status');
        
        $participant = $this->ConcoursParticipants->get($id);
        $participant->status = $status;
        
        if ($status === 'verified') {
            $participant->verified_at = new \DateTime();
            $participant->verified_by = $this->Authentication->getIdentity()->id;
        }
        
        if ($this->ConcoursParticipants->save($participant)) {
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
        
        $participant = $this->ConcoursParticipants->get($id);
        $participant->verification_notes = $notes;
        
        if ($this->ConcoursParticipants->save($participant)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
        }
        
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => false]));
    }
}