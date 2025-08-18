<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * HandballTeams Controller
 *
 * @property \App\Model\Table\HandballTeamsTable $HandballTeams
 */
class HandballTeamsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->HandballTeams->find()
            ->contain(['Users']);
        $handballTeams = $this->paginate($query);

        $this->set(compact('handballTeams'));
    }

    /**
     * View method
     *
     * @param string|null $id Handball Team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $handballTeam = $this->HandballTeams->get($id, contain: ['Users']);
        $this->set(compact('handballTeam'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $handballTeam = $this->HandballTeams->newEmptyEntity();
        if ($this->request->is('post')) {
            $handballTeam = $this->HandballTeams->patchEntity($handballTeam, $this->request->getData());
            if ($this->HandballTeams->save($handballTeam)) {
                $this->Flash->success(__('The handball team has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The handball team could not be saved. Please, try again.'));
        }
        $users = $this->HandballTeams->Users->find('list', limit: 200)->all();
        $this->set(compact('handballTeam', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Handball Team id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $handballTeam = $this->HandballTeams->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $handballTeam = $this->HandballTeams->patchEntity($handballTeam, $this->request->getData());
            if ($this->HandballTeams->save($handballTeam)) {
                $this->Flash->success(__('The handball team has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The handball team could not be saved. Please, try again.'));
        }
        $users = $this->HandballTeams->Users->find('list', limit: 200)->all();
        $this->set(compact('handballTeam', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Handball Team id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $handballTeam = $this->HandballTeams->get($id);
        if ($this->HandballTeams->delete($handballTeam)) {
            $this->Flash->success(__('The handball team has been deleted.'));
        } else {
            $this->Flash->error(__('The handball team could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
