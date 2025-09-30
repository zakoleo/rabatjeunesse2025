<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

class CrosstrainingManagementController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        
        $this->viewBuilder()->setLayout('admin');
        
        $this->CrosstrainingCategories = TableRegistry::getTableLocator()->get('CrosstrainingCategories');
        $this->CrosstrainingParticipants = TableRegistry::getTableLocator()->get('CrosstrainingParticipants');
    }

    public function index()
    {
        $stats = [
            'categories_count' => $this->CrosstrainingCategories->find()->where(['active' => 1])->count(),
            'participants_count' => $this->CrosstrainingParticipants->find()->count(),
            'pending_count' => $this->CrosstrainingParticipants->find()->where(['status' => 'pending'])->count(),
            'verified_count' => $this->CrosstrainingParticipants->find()->where(['status' => 'verified'])->count()
        ];
        
        $categories = $this->CrosstrainingCategories->find()
            ->where(['CrosstrainingCategories.active' => 1])
            ->contain(['CrosstrainingParticipants' => function ($q) {
                return $q->select([
                    'CrosstrainingParticipants.category_id',
                    'participants_count' => $q->func()->count('CrosstrainingParticipants.id')
                ])
                ->group(['CrosstrainingParticipants.category_id']);
            }])
            ->order(['CrosstrainingCategories.gender' => 'ASC', 'CrosstrainingCategories.age_category' => 'ASC'])
            ->toArray();

        $this->set(compact('stats', 'categories'));
    }

    public function categories()
    {
        $query = $this->CrosstrainingCategories->find()
            ->order(['gender' => 'ASC', 'age_category' => 'ASC']);
        
        $categories = $this->paginate($query);

        $this->set(compact('categories'));
    }

    public function participants()
    {
        $query = $this->CrosstrainingParticipants->find()
            ->contain(['CrosstrainingCategories', 'Users']);
        
        if ($this->request->getQuery('status')) {
            $query->where(['CrosstrainingParticipants.status' => $this->request->getQuery('status')]);
        }
        
        if ($this->request->getQuery('category_id')) {
            $query->where(['CrosstrainingParticipants.category_id' => $this->request->getQuery('category_id')]);
        }
        
        $query->order(['CrosstrainingParticipants.created' => 'DESC']);

        $participants = $this->paginate($query);

        $categories = $this->CrosstrainingCategories->find('list')->where(['active' => 1])->toArray();

        $this->set(compact('participants', 'categories'));
    }

    public function verify($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        
        $participant = $this->CrosstrainingParticipants->get($id);
        
        $data = [
            'status' => 'verified',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $this->Authentication->getIdentity()->id,
            'verification_notes' => $this->request->getData('verification_notes')
        ];
        
        $participant = $this->CrosstrainingParticipants->patchEntity($participant, $data);
        
        if ($this->CrosstrainingParticipants->save($participant)) {
            $this->Flash->success(__('Le participant a été vérifié avec succès.'));
        } else {
            $this->Flash->error(__('Impossible de vérifier le participant.'));
        }
        
        return $this->redirect(['action' => 'participants']);
    }

    public function reject($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        
        $participant = $this->CrosstrainingParticipants->get($id);
        
        $data = [
            'status' => 'rejected',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $this->Authentication->getIdentity()->id,
            'verification_notes' => $this->request->getData('verification_notes')
        ];
        
        $participant = $this->CrosstrainingParticipants->patchEntity($participant, $data);
        
        if ($this->CrosstrainingParticipants->save($participant)) {
            $this->Flash->success(__('Le participant a été rejeté.'));
        } else {
            $this->Flash->error(__('Impossible de rejeter le participant.'));
        }
        
        return $this->redirect(['action' => 'participants']);
    }

    public function viewParticipant($id = null)
    {
        $participant = $this->CrosstrainingParticipants->get($id, [
            'contain' => ['CrosstrainingCategories', 'Users']
        ]);

        $this->set('participant', $participant);
    }
}