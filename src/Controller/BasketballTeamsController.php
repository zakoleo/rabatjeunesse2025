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
            $data = $this->request->getData();
            
            // Set authenticated user
            $data['user_id'] = $this->Authentication->getIdentity()->get('id');
            
            // Fill text fields from foreign keys for display purposes
            if (!empty($data['basketball_category_id'])) {
                $BasketballCategories = $this->fetchTable('BasketballCategories');
                $category = $BasketballCategories->get($data['basketball_category_id']);
                if ($category) {
                    $data['categorie'] = $category->age_range;
                }
            }
            
            if (!empty($data['basketball_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['basketball_district_id']);
                if ($district) {
                    $data['district'] = $district->name;
                }
            }
            
            if (!empty($data['basketball_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['basketball_organisation_id']);
                if ($organisation) {
                    $data['organisation'] = $organisation->name;
                }
            }
            
            // Handle file uploads
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Upload responsable CIN files
            if (!empty($data['responsable_cin_recto']) && $data['responsable_cin_recto']->getError() === UPLOAD_ERR_OK) {
                $fileName = time() . '_responsable_recto_' . $data['responsable_cin_recto']->getClientFilename();
                $data['responsable_cin_recto']->moveTo($uploadPath . $fileName);
                $data['responsable_cin_recto'] = 'uploads/teams/' . $fileName;
            } else {
                unset($data['responsable_cin_recto']);
            }
            
            if (!empty($data['responsable_cin_verso']) && $data['responsable_cin_verso']->getError() === UPLOAD_ERR_OK) {
                $fileName = time() . '_responsable_verso_' . $data['responsable_cin_verso']->getClientFilename();
                $data['responsable_cin_verso']->moveTo($uploadPath . $fileName);
                $data['responsable_cin_verso'] = 'uploads/teams/' . $fileName;
            } else {
                unset($data['responsable_cin_verso']);
            }
            
            // Handle trainer same as manager
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
                $data['entraineur_cin_recto'] = $data['responsable_cin_recto'] ?? '';
                $data['entraineur_cin_verso'] = $data['responsable_cin_verso'] ?? '';
            } else {
                // Upload trainer CIN files if different from manager
                if (!empty($data['entraineur_cin_recto']) && $data['entraineur_cin_recto']->getError() === UPLOAD_ERR_OK) {
                    $fileName = time() . '_entraineur_recto_' . $data['entraineur_cin_recto']->getClientFilename();
                    $data['entraineur_cin_recto']->moveTo($uploadPath . $fileName);
                    $data['entraineur_cin_recto'] = 'uploads/teams/' . $fileName;
                } else {
                    unset($data['entraineur_cin_recto']);
                }
                
                if (!empty($data['entraineur_cin_verso']) && $data['entraineur_cin_verso']->getError() === UPLOAD_ERR_OK) {
                    $fileName = time() . '_entraineur_verso_' . $data['entraineur_cin_verso']->getClientFilename();
                    $data['entraineur_cin_verso']->moveTo($uploadPath . $fileName);
                    $data['entraineur_cin_verso'] = 'uploads/teams/' . $fileName;
                } else {
                    unset($data['entraineur_cin_verso']);
                }
            }
            
            // Ensure players are properly indexed
            if (!empty($data['basketball_teams_joueurs'])) {
                $data['basketball_teams_joueurs'] = array_values($data['basketball_teams_joueurs']);
            }
            
            $basketballTeam = $this->BasketballTeams->patchEntity($basketballTeam, $data, [
                'associated' => ['BasketballTeamsJoueurs']
            ]);
            
            if ($this->BasketballTeams->save($basketballTeam)) {
                $this->Flash->success(__('Équipe de basketball inscrite avec succès! Référence: {0}', $basketballTeam->reference_inscription));
                return $this->redirect(['controller' => 'Teams', 'action' => 'index']);
            }
            $this->Flash->error(__('L\'équipe n\'a pas pu être sauvegardée. Veuillez réessayer.'));
        }
        
        // Load categories, districts and organisations for the form
        $basketballCategories = $this->fetchTable('BasketballCategories')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $footballDistricts = $this->fetchTable('FootballDistricts')->find('list', [
            'keyField' => 'id', 
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $footballOrganisations = $this->fetchTable('FootballOrganisations')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $this->set(compact('basketballTeam', 'basketballCategories', 'footballDistricts', 'footballOrganisations'));
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
