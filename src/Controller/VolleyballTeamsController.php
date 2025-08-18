<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * VolleyballTeams Controller
 *
 * @property \App\Model\Table\VolleyballTeamsTable $VolleyballTeams
 */
class VolleyballTeamsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->VolleyballTeams->find()
            ->contain(['Users']);
        $volleyballTeams = $this->paginate($query);

        $this->set(compact('volleyballTeams'));
    }

    /**
     * View method
     *
     * @param string|null $id Volleyball Team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $volleyballTeam = $this->VolleyballTeams->get($id, contain: ['Users']);
        $this->set(compact('volleyballTeam'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $volleyballTeam = $this->VolleyballTeams->newEmptyEntity();
        if ($this->request->is('post')) {
            $volleyballTeam = $this->VolleyballTeams->patchEntity($volleyballTeam, $this->request->getData());
            if ($this->VolleyballTeams->save($volleyballTeam)) {
                $this->Flash->success(__('The volleyball team has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The volleyball team could not be saved. Please, try again.'));
        }
        $users = $this->VolleyballTeams->Users->find('list', limit: 200)->all();
        $this->set(compact('volleyballTeam', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Volleyball Team id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $volleyballTeam = $this->VolleyballTeams->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $volleyballTeam = $this->VolleyballTeams->patchEntity($volleyballTeam, $this->request->getData());
            if ($this->VolleyballTeams->save($volleyballTeam)) {
                $this->Flash->success(__('The volleyball team has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The volleyball team could not be saved. Please, try again.'));
        }
        $users = $this->VolleyballTeams->Users->find('list', limit: 200)->all();
        $this->set(compact('volleyballTeam', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Volleyball Team id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $volleyballTeam = $this->VolleyballTeams->get($id);
        if ($this->VolleyballTeams->delete($volleyballTeam)) {
            $this->Flash->success(__('The volleyball team has been deleted.'));
        } else {
            $this->Flash->error(__('The volleyball team could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
