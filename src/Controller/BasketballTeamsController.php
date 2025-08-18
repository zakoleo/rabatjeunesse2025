<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * BasketballTeams Controller
 *
 * @property \App\Model\Table\BasketballTeamsTable $BasketballTeams
 */
class BasketballTeamsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->BasketballTeams->find()
            ->contain(['Users']);
        $basketballTeams = $this->paginate($query);

        $this->set(compact('basketballTeams'));
    }

    /**
     * View method
     *
     * @param string|null $id Basketball Team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $basketballTeam = $this->BasketballTeams->get($id, contain: ['Users']);
        $this->set(compact('basketballTeam'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $basketballTeam = $this->BasketballTeams->newEmptyEntity();
        if ($this->request->is('post')) {
            $basketballTeam = $this->BasketballTeams->patchEntity($basketballTeam, $this->request->getData());
            if ($this->BasketballTeams->save($basketballTeam)) {
                $this->Flash->success(__('The basketball team has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The basketball team could not be saved. Please, try again.'));
        }
        $users = $this->BasketballTeams->Users->find('list', limit: 200)->all();
        $this->set(compact('basketballTeam', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Basketball Team id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $basketballTeam = $this->BasketballTeams->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $basketballTeam = $this->BasketballTeams->patchEntity($basketballTeam, $this->request->getData());
            if ($this->BasketballTeams->save($basketballTeam)) {
                $this->Flash->success(__('The basketball team has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The basketball team could not be saved. Please, try again.'));
        }
        $users = $this->BasketballTeams->Users->find('list', limit: 200)->all();
        $this->set(compact('basketballTeam', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Basketball Team id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $basketballTeam = $this->BasketballTeams->get($id);
        if ($this->BasketballTeams->delete($basketballTeam)) {
            $this->Flash->success(__('The basketball team has been deleted.'));
        } else {
            $this->Flash->error(__('The basketball team could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
