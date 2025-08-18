<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * BeachvolleyTeams Controller
 *
 * @property \App\Model\Table\BeachvolleyTeamsTable $BeachvolleyTeams
 */
class BeachvolleyTeamsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->BeachvolleyTeams->find()
            ->contain(['Users']);
        $beachvolleyTeams = $this->paginate($query);

        $this->set(compact('beachvolleyTeams'));
    }

    /**
     * View method
     *
     * @param string|null $id Beachvolley Team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $beachvolleyTeam = $this->BeachvolleyTeams->get($id, contain: ['Users']);
        $this->set(compact('beachvolleyTeam'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $beachvolleyTeam = $this->BeachvolleyTeams->newEmptyEntity();
        if ($this->request->is('post')) {
            $beachvolleyTeam = $this->BeachvolleyTeams->patchEntity($beachvolleyTeam, $this->request->getData());
            if ($this->BeachvolleyTeams->save($beachvolleyTeam)) {
                $this->Flash->success(__('The beachvolley team has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The beachvolley team could not be saved. Please, try again.'));
        }
        $users = $this->BeachvolleyTeams->Users->find('list', limit: 200)->all();
        $this->set(compact('beachvolleyTeam', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Beachvolley Team id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $beachvolleyTeam = $this->BeachvolleyTeams->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $beachvolleyTeam = $this->BeachvolleyTeams->patchEntity($beachvolleyTeam, $this->request->getData());
            if ($this->BeachvolleyTeams->save($beachvolleyTeam)) {
                $this->Flash->success(__('The beachvolley team has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The beachvolley team could not be saved. Please, try again.'));
        }
        $users = $this->BeachvolleyTeams->Users->find('list', limit: 200)->all();
        $this->set(compact('beachvolleyTeam', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Beachvolley Team id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $beachvolleyTeam = $this->BeachvolleyTeams->get($id);
        if ($this->BeachvolleyTeams->delete($beachvolleyTeam)) {
            $this->Flash->success(__('The beachvolley team has been deleted.'));
        } else {
            $this->Flash->error(__('The beachvolley team could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
