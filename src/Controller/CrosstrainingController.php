<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

class CrosstrainingController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        
        $this->CrosstrainingParticipants = $this->fetchTable('CrosstrainingParticipants');
        $this->CrosstrainingCategories = $this->fetchTable('CrosstrainingCategories');
    }

    public function index()
    {
        $user = $this->Authentication->getIdentity();
        
        $participants = $this->CrosstrainingParticipants->find()
            ->where(['CrosstrainingParticipants.user_id' => $user->id])
            ->contain(['CrosstrainingCategories'])
            ->order(['CrosstrainingParticipants.created' => 'DESC'])
            ->all();

        $this->set(compact('participants'));
    }

    public function add()
    {
        $user = $this->Authentication->getIdentity();
        
        $participant = $this->CrosstrainingParticipants->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = $user->id;
            $data['status'] = 'pending';
            
            // Handle file uploads
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'crosstraining' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Process CIN Recto
            if (!empty($data['cin_recto']) && $data['cin_recto']->getError() === UPLOAD_ERR_OK) {
                $cinRectoFile = $data['cin_recto'];
                $cinRectoName = 'cin_recto_' . time() . '_' . $cinRectoFile->getClientFilename();
                $cinRectoFile->moveTo($uploadPath . $cinRectoName);
                $data['cin_recto'] = $cinRectoName;
            } else {
                unset($data['cin_recto']);
            }
            
            // Process CIN Verso
            if (!empty($data['cin_verso']) && $data['cin_verso']->getError() === UPLOAD_ERR_OK) {
                $cinVersoFile = $data['cin_verso'];
                $cinVersoName = 'cin_verso_' . time() . '_' . $cinVersoFile->getClientFilename();
                $cinVersoFile->moveTo($uploadPath . $cinVersoName);
                $data['cin_verso'] = $cinVersoName;
            } else {
                unset($data['cin_verso']);
            }
            
            $participant = $this->CrosstrainingParticipants->patchEntity($participant, $data);
            
            if ($this->CrosstrainingParticipants->save($participant)) {
                $this->Flash->success(__('Votre inscription au Cross Training a été enregistrée avec succès.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'enregistrer votre inscription. Veuillez réessayer.'));
        }
        
        $categories = $this->CrosstrainingCategories->find('list', [
            'keyField' => 'id',
            'valueField' => function ($category) {
                return $category->gender . ' - ' . $category->age_category;
            }
        ])->where(['active' => true])->toArray();
        
        // Get categories with date ranges for validation
        $categoriesData = $this->CrosstrainingCategories->find()
            ->where(['active' => true])
            ->toArray();

        $this->set(compact('participant', 'categories', 'categoriesData'));
    }

    public function view($id = null)
    {
        $user = $this->Authentication->getIdentity();
        
        $participant = $this->CrosstrainingParticipants->get($id, [
            'contain' => ['CrosstrainingCategories', 'Users']
        ]);

        if ($participant->user_id !== $user->id && !$user->is_admin) {
            throw new NotFoundException(__('Participant non trouvé.'));
        }

        $this->set('participant', $participant);
    }

    public function edit($id = null)
    {
        $user = $this->Authentication->getIdentity();
        
        $participant = $this->CrosstrainingParticipants->get($id, [
            'contain' => []
        ]);
        
        if ($participant->user_id !== $user->id) {
            throw new NotFoundException(__('Participant non trouvé.'));
        }
        
        if ($participant->status === 'verified') {
            $this->Flash->error(__('Vous ne pouvez pas modifier une inscription déjà vérifiée.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Handle file uploads
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'crosstraining' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Process CIN Recto
            if (!empty($data['cin_recto']) && $data['cin_recto']->getError() === UPLOAD_ERR_OK) {
                $cinRectoFile = $data['cin_recto'];
                $cinRectoName = 'cin_recto_' . time() . '_' . $cinRectoFile->getClientFilename();
                $cinRectoFile->moveTo($uploadPath . $cinRectoName);
                $data['cin_recto'] = $cinRectoName;
            } else {
                unset($data['cin_recto']);
            }
            
            // Process CIN Verso
            if (!empty($data['cin_verso']) && $data['cin_verso']->getError() === UPLOAD_ERR_OK) {
                $cinVersoFile = $data['cin_verso'];
                $cinVersoName = 'cin_verso_' . time() . '_' . $cinVersoFile->getClientFilename();
                $cinVersoFile->moveTo($uploadPath . $cinVersoName);
                $data['cin_verso'] = $cinVersoName;
            } else {
                unset($data['cin_verso']);
            }
            
            $participant = $this->CrosstrainingParticipants->patchEntity($participant, $data);
            if ($this->CrosstrainingParticipants->save($participant)) {
                $this->Flash->success(__('Les informations ont été mises à jour.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error(__('Impossible de mettre à jour les informations.'));
        }
        
        $categories = $this->CrosstrainingCategories->find('list', [
            'keyField' => 'id',
            'valueField' => function ($category) {
                return $category->gender . ' - ' . $category->age_category;
            }
        ])->where(['active' => true])->toArray();
        
        // Get categories with date ranges for validation
        $categoriesData = $this->CrosstrainingCategories->find()
            ->where(['active' => true])
            ->toArray();

        $this->set(compact('participant', 'categories', 'categoriesData'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $user = $this->Authentication->getIdentity();
        $participant = $this->CrosstrainingParticipants->get($id);
        
        if ($participant->user_id !== $user->id) {
            throw new NotFoundException(__('Participant non trouvé.'));
        }
        
        if ($participant->status === 'verified') {
            $this->Flash->error(__('Vous ne pouvez pas supprimer une inscription déjà vérifiée.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->CrosstrainingParticipants->delete($participant)) {
            $this->Flash->success(__('L\'inscription a été supprimée.'));
        } else {
            $this->Flash->error(__('Impossible de supprimer l\'inscription.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}