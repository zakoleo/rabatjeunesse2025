<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Teams Controller
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends AppController
{
    /**
     * beforeFilter callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Allow non-authenticated users to access basketball, handball, volleyball and beach volleyball registration pages
        $this->Authentication->addUnauthenticatedActions([
            'addBasketball', 'addHandball', 'addVolleyball', 'addBeachvolley',
            'getFootballDateRanges', 'getBasketballDateRanges', 'getHandballDateRanges', 
            'getVolleyballDateRanges', 'getBeachvolleyDateRanges', 
            'getFootballCategories', 'getHandballCategories',
            'getBasketballCategories', 'getVolleyballCategories', 'getBeachvolleyCategories', 
            'getCategories', 'getSports', 'getFootballTypes', 
            'getBasketballTypes', 'getHandballTypes', 'getVolleyballTypes', 'getBeachvolleyTypes', 
            'getDistricts', 'getOrganizations', 'testEndpoint'
        ]);
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $user = $this->Authentication->getIdentity();
        
        // Get football teams
        $footballTeams = $this->Teams->find()
            ->where(['Teams.user_id' => $user->id])
            ->contain(['Users'])
            ->all();
        
        // Get basketball teams
        $basketballTeamsTable = $this->fetchTable('BasketballTeams');
        $basketballTeams = $basketballTeamsTable->find()
            ->where(['BasketballTeams.user_id' => $user->id])
            ->contain(['Users', 'BasketballTeamsJoueurs'])
            ->all();
        
        // Get handball teams
        $handballTeamsTable = $this->fetchTable('HandballTeams');
        $handballTeams = $handballTeamsTable->find()
            ->where(['HandballTeams.user_id' => $user->id])
            ->contain(['Users', 'HandballTeamsJoueurs'])
            ->all();
        
        // Get volleyball teams
        $volleyballTeamsTable = $this->fetchTable('VolleyballTeams');
        $volleyballTeams = $volleyballTeamsTable->find()
            ->where(['VolleyballTeams.user_id' => $user->id])
            ->contain(['Users', 'VolleyballTeamsJoueurs'])
            ->all();
        
        // Get beach volleyball teams
        $beachvolleyTeamsTable = $this->fetchTable('BeachvolleyTeams');
        $beachvolleyTeams = $beachvolleyTeamsTable->find()
            ->where(['BeachvolleyTeams.user_id' => $user->id])
            ->contain(['Users', 'BeachvolleyTeamsJoueurs'])
            ->all();
        
        // Get cross training participants
        $crosstrainingTable = $this->fetchTable('CrosstrainingParticipants');
        $crosstrainingParticipants = $crosstrainingTable->find()
            ->where(['CrosstrainingParticipants.user_id' => $user->id])
            ->contain(['Users', 'CrosstrainingCategories'])
            ->all();
        
        // Get sports urbains participants
        $sportsurbainsTable = $this->fetchTable('SportsurbainsParticipants');
        $sportsurbainsParticipants = $sportsurbainsTable->find()
            ->where(['SportsurbainsParticipants.user_id' => $user->id])
            ->contain(['Users', 'SportsurbainsCategories'])
            ->all();
        
        // Get concours participants
        $concoursTable = $this->fetchTable('ConcoursParticipants');
        $concoursParticipants = $concoursTable->find()
            ->where(['ConcoursParticipants.user_id' => $user->id])
            ->contain(['Users', 'ConcoursCategories'])
            ->all();

        $this->set(compact(
            'footballTeams', 'basketballTeams', 'handballTeams', 'volleyballTeams', 'beachvolleyTeams',
            'crosstrainingParticipants', 'sportsurbainsParticipants', 'concoursParticipants'
        ));
    }

    /**
     * View method
     *
     * @param string|null $id Team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $team = $this->Teams->get($id, [
            'contain' => ['Users', 'Joueurs', 'FootballCategories', 'FootballDistricts', 'FootballOrganisations']
        ]);
        $this->set(compact('team'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $team = $this->Teams->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Définir l'utilisateur connecté
            $data['user_id'] = $this->Authentication->getIdentity()->get('id');
            
            // Fill text fields from foreign keys for display purposes
            if (!empty($data['football_category_id'])) {
                $FootballCategories = $this->fetchTable('FootballCategories');
                $category = $FootballCategories->get($data['football_category_id']);
                if ($category) {
                    $data['categorie'] = $category->age_range;
                }
            }
            
            if (!empty($data['football_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['football_district_id']);
                if ($district) {
                    $data['district'] = $district->name;
                }
            }
            
            if (!empty($data['football_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['football_organisation_id']);
                if ($organisation) {
                    $data['organisation'] = $organisation->name;
                }
            }
            
            // Gérer les uploads de fichiers
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Upload des fichiers CIN du responsable
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
            
            // Gérer le cas où l'entraîneur est le même que le responsable
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                // Copier les données du responsable vers l'entraîneur
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
                $data['entraineur_cin_recto'] = $data['responsable_cin_recto'] ?? '';
                $data['entraineur_cin_verso'] = $data['responsable_cin_verso'] ?? '';
            } else {
                // Upload des fichiers CIN de l'entraîneur si différent du responsable
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
            
            // S'assurer que les joueurs sont bien indexés et valider les dates
            if (!empty($data['joueurs'])) {
                $data['joueurs'] = array_values($data['joueurs']);
                
                // Valider les dates de naissance des joueurs depuis la base de données
                $selectedCategory = $data['categorie'] ?? '';
                
                // Récupérer la catégorie avec ses date ranges depuis la DB
                $FootballCategories = $this->fetchTable('FootballCategories');
                $category = $FootballCategories->find()
                    ->where(['name' => $selectedCategory])
                    ->first();
                
                if ($category && !empty($category->min_date) && !empty($category->max_date)) {
                    $minDate = new \DateTime($category->min_date->format('Y-m-d'));
                    $maxDate = new \DateTime($category->max_date->format('Y-m-d'));
                    
                    foreach ($data['joueurs'] as $index => $joueur) {
                        if (!empty($joueur['date_naissance'])) {
                            $birthDate = new \DateTime($joueur['date_naissance']);
                            if ($birthDate < $minDate || $birthDate > $maxDate) {
                                $this->Flash->error(sprintf(
                                    'Le joueur %s doit être né entre le %s et le %s pour la catégorie %s',
                                    $joueur['nom_complet'] ?? 'n°' . ($index + 1),
                                    $minDate->format('d/m/Y'),
                                    $maxDate->format('d/m/Y'),
                                    $category->age_range
                                ));
                                return $this->redirect(['action' => 'add']);
                            }
                        }
                    }
                }
            }
            
            // Générer une référence d'inscription unique
            if (empty($data['reference_inscription'])) {
                $data['reference_inscription'] = 'FB' . date('Ymd') . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            // S'assurer que le statut est défini
            $data['status'] = $data['status'] ?? 'pending';
            
            // S'assurer que verification_notes est défini
            $data['verification_notes'] = $data['verification_notes'] ?? null;
            
            // Gérer les données associées
            $team = $this->Teams->patchEntity($team, $data, [
                'associated' => ['Joueurs']
            ]);
            
            if ($this->Teams->save($team, ['associated' => ['Joueurs']])) {
                $this->Flash->success(__('Votre équipe a été inscrite avec succès.'));

                return $this->redirect(['action' => 'view', $team->id]);
            }
            
            // Afficher les erreurs de validation
            $errors = $team->getErrors();
            $errorMessages = [];
            
            foreach ($errors as $field => $fieldErrors) {
                $this->_extractErrorMessages($field, $fieldErrors, $errorMessages);
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(__('Erreurs de validation: ') . implode(', ', $errorMessages));
            } else {
                $this->Flash->error(__('L\'inscription n\'a pas pu être enregistrée. Veuillez réessayer.'));
            }
        }
        
        // Charger les listes pour les dropdowns depuis la base de données
        $FootballCategories = $this->fetchTable('FootballCategories');
        $footballCategories = $FootballCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $FootballDistricts = $this->fetchTable('FootballDistricts');
        $footballDistricts = $FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $FootballOrganisations = $this->fetchTable('FootballOrganisations');
        $footballOrganisations = $FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $this->set(compact('team', 'footballCategories', 'footballDistricts', 'footballOrganisations'));
        $this->set('sport', 'football');
    }

    /**
     * Get football categories date ranges as JSON
     * Used by JavaScript for dynamic validation
     *
     * @return \Cake\Http\Response JSON response with date ranges
     */
    public function getFootballDateRanges()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;

        try {
            $FootballCategories = $this->fetchTable('FootballCategories');
            $categories = $FootballCategories->find()
                ->where(['active' => true])
                ->select(['name', 'age_range', 'min_date', 'max_date'])
                ->all();

            $dateRanges = [];
            foreach ($categories as $category) {
                $dateRanges[$category->age_range] = [
                    'min' => $category->min_date ? $category->min_date->format('Y-m-d') : null,
                    'max' => $category->max_date ? $category->max_date->format('Y-m-d') : null,
                    'name' => $category->name
                ];
            }

            $result = ['dateRanges' => $dateRanges];
            
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($result, JSON_PRETTY_PRINT));

        } catch (\Exception $e) {
            \Cake\Log\Log::error('Error in getFootballDateRanges: ' . $e->getMessage());
            $errorResult = [
                'dateRanges' => [],
                'error' => $e->getMessage()
            ];

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($errorResult, JSON_PRETTY_PRINT));
        }
    }

    /**
     * Get basketball categories date ranges as JSON
     */
    public function getBasketballDateRanges()
    {
        $this->request->allowMethod(['get']);
        $this->viewBuilder()->setClassName('Json');

        // Load basketball categories from unified database (sport_id = 2)
        $CategoriesTable = $this->fetchTable('Categories');
        $categories = $CategoriesTable->find()
            ->where(['active' => true, 'sport_id' => 2])
            ->select(['name', 'age_range', 'min_birth_year', 'max_birth_year'])
            ->all();

        $dateRanges = [];
        foreach ($categories as $category) {
            $dateRanges[$category->age_range] = [
                'minYear' => $category->min_birth_year,
                'maxYear' => $category->max_birth_year,
                'name' => $category->name
            ];
        }

        $this->set([
            'ageCategories' => $dateRanges,
            '_serialize' => ['ageCategories']
        ]);

        return $this->response->withType('application/json');
    }

    /**
     * Get handball categories date ranges as JSON
     */
    public function getHandballDateRanges()
    {
        $this->request->allowMethod(['get']);
        $this->viewBuilder()->setClassName('Json');

        // Load handball categories from database
        $HandballCategories = $this->fetchTable('HandballCategories');
        $categories = $HandballCategories->find()
            ->where(['active' => true])
            ->select(['name', 'age_range', 'min_birth_year', 'max_birth_year'])
            ->all();

        $dateRanges = [];
        foreach ($categories as $category) {
            $dateRanges[$category->age_range] = [
                'minYear' => $category->min_birth_year,
                'maxYear' => $category->max_birth_year,
                'name' => $category->name
            ];
        }

        $this->set([
            'ageCategories' => $dateRanges,
            '_serialize' => ['ageCategories']
        ]);

        return $this->response->withType('application/json');
    }

    /**
     * Get volleyball categories date ranges as JSON
     */
    public function getVolleyballDateRanges()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false; // Disable view rendering

        try {
            // Load volleyball categories from database
            $VolleyballCategories = $this->fetchTable('VolleyballCategories');
            
            // First check if table exists and has any data at all
            $allCategories = $VolleyballCategories->find()->all();
            \Cake\Log\Log::debug('Total volleyball categories in table: ' . $allCategories->count());
            
            // Then check active ones
            $categories = $VolleyballCategories->find()
                ->where(['active' => true])
                ->select(['name', 'age_range', 'min_date', 'max_date'])
                ->all();

            \Cake\Log\Log::debug('Active volleyball categories found: ' . $categories->count());

            $dateRanges = [];
            foreach ($categories as $category) {
                \Cake\Log\Log::debug('Processing category: ' . $category->name . ' with dates: ' . $category->min_date . ' to ' . $category->max_date);
                $dateRanges[$category->age_range] = [
                    'min' => $category->min_date ? $category->min_date->format('Y-m-d') : null,
                    'max' => $category->max_date ? $category->max_date->format('Y-m-d') : null,
                    'name' => $category->name
                ];
            }

            // If no categories found, provide default volleyball categories
            if (empty($dateRanges)) {
                \Cake\Log\Log::warning('No volleyball categories found in database, using defaults');
                $dateRanges = [
                    '-15 ans' => ['min' => '2010-01-01', 'max' => '2025-12-31', 'name' => '-15'],
                    '-17 ans' => ['min' => '2008-01-01', 'max' => '2025-12-31', 'name' => '-17'],
                    '-19 ans' => ['min' => '2006-01-01', 'max' => '2025-12-31', 'name' => '-19']
                ];
            } else {
                \Cake\Log\Log::debug('Using volleyball categories from database');
            }

            $result = ['dateRanges' => $dateRanges];
            \Cake\Log\Log::debug('Final result: ' . json_encode($result));

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($result, JSON_PRETTY_PRINT));

        } catch (\Exception $e) {
            \Cake\Log\Log::error('Error in getVolleyballDateRanges: ' . $e->getMessage());
            // Provide fallback data even on error
            $fallbackRanges = [
                '-15 ans' => ['min' => '2010-01-01', 'max' => '2025-12-31', 'name' => '-15'],
                '-17 ans' => ['min' => '2008-01-01', 'max' => '2025-12-31', 'name' => '-17'],
                '-19 ans' => ['min' => '2006-01-01', 'max' => '2025-12-31', 'name' => '-19']
            ];
            
            $errorResult = [
                'dateRanges' => $fallbackRanges,
                'error' => $e->getMessage()
            ];

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($errorResult, JSON_PRETTY_PRINT));
        }
    }

    /**
     * Get beachvolley categories date ranges as JSON
     */
    public function getBeachvolleyDateRanges()
    {
        $this->request->allowMethod(['get']);
        $this->viewBuilder()->setClassName('Json');

        // Load beachvolley categories from database
        $BeachvolleyCategories = $this->fetchTable('BeachvolleyCategories');
        $categories = $BeachvolleyCategories->find()
            ->where(['active' => true])
            ->select(['name', 'age_range', 'min_birth_year', 'max_birth_year'])
            ->all();

        $dateRanges = [];
        foreach ($categories as $category) {
            $dateRanges[$category->age_range] = [
                'minYear' => $category->min_birth_year,
                'maxYear' => $category->max_birth_year,
                'name' => $category->name
            ];
        }

        $this->set([
            'ageCategories' => $dateRanges,
            '_serialize' => ['ageCategories']
        ]);

        return $this->response->withType('application/json');
    }


    /**
     * Edit method
     *
     * @param string|null $id Team id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $team = $this->Teams->get($id, [
            'contain' => ['Joueurs']
        ]);
        
        // Vérifier si l'équipe est vérifiée
        if ($team->status === 'verified') {
            $this->Flash->error(__('Cette équipe a été vérifiée et ne peut plus être modifiée.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Mapper les champs des relations vers les champs texte attendus
            if (!empty($data['football_category_id'])) {
                $category = $this->Teams->FootballCategories->get($data['football_category_id']);
                $data['categorie'] = $category->name;
            }
            
            if (!empty($data['football_district_id'])) {
                $district = $this->Teams->FootballDistricts->get($data['football_district_id']);
                $data['district'] = $district->name;
            }
            
            if (!empty($data['football_organisation_id'])) {
                $organisation = $this->Teams->FootballOrganisations->get($data['football_organisation_id']);
                $data['organisation'] = $organisation->name;
            }
            
            // Gérer le cas où l'entraîneur est le même que le responsable
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
            }
            
            // Gérer les joueurs
            if (!empty($data['joueurs'])) {
                // Filtrer les joueurs supprimés
                $data['joueurs'] = array_values(array_filter($data['joueurs'], function($joueur) {
                    return !empty($joueur['nom_complet']);
                }));
                
                // Valider les dates de naissance des joueurs depuis la base de données
                $selectedCategory = $data['categorie'] ?? $team->categorie ?? '';
                
                // Récupérer la catégorie avec ses date ranges depuis la DB
                $FootballCategories = $this->fetchTable('FootballCategories');
                $category = $FootballCategories->find()
                    ->where(['name' => $selectedCategory])
                    ->first();
                
                if ($category && !empty($category->min_date) && !empty($category->max_date)) {
                    $minDate = new \DateTime($category->min_date->format('Y-m-d'));
                    $maxDate = new \DateTime($category->max_date->format('Y-m-d'));
                    
                    foreach ($data['joueurs'] as $index => $joueur) {
                        if (!empty($joueur['date_naissance'])) {
                            $birthDate = new \DateTime($joueur['date_naissance']);
                            if ($birthDate < $minDate || $birthDate > $maxDate) {
                                $this->Flash->error(sprintf(
                                    'Le joueur %s doit être né entre le %s et le %s pour la catégorie %s',
                                    $joueur['nom_complet'] ?? 'n°' . ($index + 1),
                                    $minDate->format('d/m/Y'),
                                    $maxDate->format('d/m/Y'),
                                    $category->age_range
                                ));
                                return $this->redirect(['action' => 'edit', $id]);
                            }
                        }
                    }
                }
            }
            
            $team = $this->Teams->patchEntity($team, $data, [
                'associated' => ['Joueurs']
            ]);
            
            if ($this->Teams->save($team, ['associated' => ['Joueurs']])) {
                $this->Flash->success(__('L\'équipe a été mise à jour avec succès.'));

                return $this->redirect(['action' => 'view', $id]);
            }
            
            // Afficher les erreurs
            $errors = $team->getErrors();
            $errorMessages = [];
            
            foreach ($errors as $field => $fieldErrors) {
                $this->_extractErrorMessages($field, $fieldErrors, $errorMessages);
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(__('Erreurs de validation: ') . implode(', ', $errorMessages));
            } else {
                $this->Flash->error(__('L\'équipe n\'a pas pu être mise à jour. Veuillez réessayer.'));
            }
        }
        
        // Charger les listes pour les dropdowns depuis la base de données
        $FootballCategories = $this->fetchTable('FootballCategories');
        $footballCategories = $FootballCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $FootballDistricts = $this->fetchTable('FootballDistricts');
        $footballDistricts = $FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $FootballOrganisations = $this->fetchTable('FootballOrganisations');
        $footballOrganisations = $FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        // Définir les IDs correspondants aux valeurs texte pour l'affichage du formulaire
        if (!empty($team->football_category_id)) {
            $team->categorie = $FootballCategories->get($team->football_category_id)->name;
        }
        
        if (!empty($team->football_district_id)) {
            $team->district = $FootballDistricts->get($team->football_district_id)->name;
        }
        
        if (!empty($team->football_organisation_id)) {
            $team->organisation = $FootballOrganisations->get($team->football_organisation_id)->name;
        }
        
        $this->set(compact('team', 'footballCategories', 'footballDistricts', 'footballOrganisations'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Team id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $team = $this->Teams->get($id, [
            'contain' => ['Joueurs']
        ]);
        
        // Vérifier que l'utilisateur a le droit de supprimer cette équipe
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de supprimer cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Vérifier si l'équipe est vérifiée
        if ($team->status === 'verified') {
            $this->Flash->error(__('Cette équipe a été vérifiée et ne peut pas être supprimée.'));
            return $this->redirect(['action' => 'view', $id]);
        }
        
        try {
            if ($this->Teams->delete($team)) {
                $this->Flash->success(__('L\'équipe "{0}" a été supprimée avec succès.', $team->nom_equipe));
            } else {
                // Récupérer les erreurs de validation
                $errors = $team->getErrors();
                if (!empty($errors)) {
                    $errorMessages = [];
                    foreach ($errors as $field => $fieldErrors) {
                        foreach ($fieldErrors as $error) {
                            $errorMessages[] = $error;
                        }
                    }
                    $this->Flash->error(__('Impossible de supprimer l\'équipe : {0}', implode(', ', $errorMessages)));
                } else {
                    $this->Flash->error(__('L\'équipe n\'a pas pu être supprimée. Veuillez réessayer.'));
                }
            }
        } catch (\Exception $e) {
            $this->Flash->error(__('Une erreur est survenue lors de la suppression : {0}', $e->getMessage()));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Download PDF method
     *
     * @param string|null $id Team id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function downloadPdf($id = null)
    {
        $team = $this->Teams->get($id, [
            'contain' => ['Users', 'Joueurs', 'FootballCategories', 'FootballDistricts', 'FootballOrganisations']
        ]);
        
        // Vérifier que l'utilisateur a le droit de télécharger ce PDF
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de télécharger ce PDF.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Générer le PDF avec DomPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new \Dompdf\Dompdf($options);
        
        // Créer le HTML pour le PDF
        $html = $this->generatePdfHtml($team);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Nom du fichier
        $filename = sprintf('inscription_%s_%s.pdf', 
            $team->reference_inscription ?? 'FBTEMP',
            \Cake\Utility\Text::slug($team->nom_equipe)
        );
        
        // Envoyer le PDF au navigateur
        $this->response = $this->response->withType('pdf');
        $this->response = $this->response->withDownload($filename);
        $this->response = $this->response->withStringBody($dompdf->output());
        
        return $this->response;
    }
    
    /**
     * Génère le HTML pour le PDF
     *
     * @param \App\Model\Entity\Team $team
     * @return string
     */
    private function generatePdfHtml($team): string
    {
        // Skip logo processing to avoid GD extension requirement
        $logoData = '';
        
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20mm; }
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2C3E50;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        h1 {
            color: #2C3E50;
            margin: 10px 0;
            font-size: 24pt;
        }
        .reference {
            background: #E8F4F8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            color: #2C3E50;
        }
        h2 {
            color: #34495E;
            border-bottom: 1px solid #E0E0E0;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 16pt;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #555;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            color: #333;
        }
        .players-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .players-table th {
            background: #34495E;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }
        .players-table td {
            border-bottom: 1px solid #E0E0E0;
            padding: 8px;
            font-size: 9pt;
        }
        .players-table tr:nth-child(even) {
            background: #F8F9FA;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E0E0E0;
            text-align: center;
            font-size: 8pt;
            color: #777;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }
        .badge-info {
            background: #3498DB;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">';
        
        if ($logoData) {
            $html .= '<img src="' . $logoData . '" class="logo" alt="Logo">';
        }
        
        $html .= '
        <h1>Tournoi de Football</h1>
        <p>Fiche d\'inscription</p>
    </div>
    
    <div class="reference">
        Référence : ' . h($team->reference_inscription ?? 'En cours de génération') . '
    </div>
    
    <h2>Informations de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom de l\'équipe :</div>
            <div class="info-value">' . h($team->nom_equipe) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Catégorie :</div>
            <div class="info-value">' . h($team->categorie) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Genre :</div>
            <div class="info-value">' . h($team->genre) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Type de football :</div>
            <div class="info-value"><span class="badge badge-info">' . h($team->type_football) . '</span></div>
        </div>
        <div class="info-row">
            <div class="info-label">District :</div>
            <div class="info-value">' . h($team->district) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Organisation :</div>
            <div class="info-value">' . h($team->organisation) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Adresse :</div>
            <div class="info-value">' . nl2br(h($team->adresse)) . '</div>
        </div>
    </div>
    
    <h2>Responsable de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->responsable_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->responsable_date_naissance ? h($team->responsable_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->responsable_tel) . '</div>
        </div>';
        
        if (!empty($team->responsable_whatsapp)) {
            $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->responsable_whatsapp) . '</div>
        </div>';
        }
        
        $html .= '
    </div>
    
    <h2>Entraîneur</h2>
    <div class="info-section">';
        
        if ($team->entraineur_same_as_responsable) {
            $html .= '<p><em>L\'entraîneur est le même que le responsable</em></p>';
        } else {
            $html .= '
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->entraineur_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->entraineur_date_naissance ? h($team->entraineur_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->entraineur_tel) . '</div>
        </div>';
            
            if (!empty($team->entraineur_whatsapp)) {
                $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->entraineur_whatsapp) . '</div>
        </div>';
            }
        }
        
        $html .= '
    </div>
    
    <h2>Liste des joueurs (' . count($team->joueurs) . ')</h2>
    <table class="players-table">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 35%">Nom complet</th>
                <th style="width: 20%">Date de naissance</th>
                <th style="width: 25%">Identifiant</th>
                <th style="width: 15%">Taille</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($team->joueurs as $index => $joueur) {
            $html .= '
            <tr>
                <td>' . ($index + 1) . '</td>
                <td>' . h($joueur->nom_complet) . '</td>
                <td>' . ($joueur->date_naissance ? h($joueur->date_naissance->format('d/m/Y')) : '') . '</td>
                <td>' . h($joueur->identifiant) . '</td>
                <td>' . h($joueur->taille_vestimentaire) . '</td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré le ' . date('d/m/Y à H:i') . '</p>
        <p>Ce document constitue votre preuve d\'inscription au tournoi</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Add Basketball team page
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function addBasketball()
    {
        $basketballTeamsTable = $this->fetchTable('BasketballTeams');
        $team = $basketballTeamsTable->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Set basketball-specific defaults
            $data['sport'] = 'Basketball';
            $data['user_id'] = $this->Authentication->getIdentity() ? $this->Authentication->getIdentity()->get('id') : null;
            
            if (!$data['user_id']) {
                $this->Flash->error(__('Vous devez être connecté pour inscrire une équipe.'));
                return $this->redirect(['controller' => 'Sports', 'action' => 'basketball']);
            }
            
            // Mapper les champs des relations vers les champs texte attendus
            if (!empty($data['basketball_category_id'])) {
                $BasketballCategories = $this->fetchTable('BasketballCategories');
                $category = $BasketballCategories->get($data['basketball_category_id']);
                $data['categorie'] = $category->age_range;
            }
            
            if (!empty($data['basketball_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['basketball_district_id']);
                $data['district'] = $district->name;
            }
            
            if (!empty($data['basketball_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['basketball_organisation_id']);
                $data['organisation'] = $organisation->name;
            }
            
            // Handle file uploads
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Process file uploads for responsable
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
            
            // Handle entraineur same as responsable
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
                $data['entraineur_cin_recto'] = $data['responsable_cin_recto'] ?? '';
                $data['entraineur_cin_verso'] = $data['responsable_cin_verso'] ?? '';
            } else {
                // Process file uploads for entraineur
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
            
            // Générer une référence d'inscription unique pour basketball
            if (empty($data['reference_inscription'])) {
                $data['reference_inscription'] = 'BB' . date('Ymd') . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            // S'assurer que le statut est défini
            $data['status'] = $data['status'] ?? 'pending';
            
            // S'assurer que les joueurs sont bien indexés et valider les dates
            if (!empty($data['joueurs'])) {
                $data['joueurs'] = array_values($data['joueurs']);
                
                // Valider les dates de naissance des joueurs depuis la base de données
                $selectedCategory = $data['categorie'] ?? '';
                
                // Récupérer la catégorie avec ses date ranges depuis la DB
                $BasketballCategories = $this->fetchTable('BasketballCategories');
                $category = $BasketballCategories->find()
                    ->where(['age_range' => $selectedCategory])
                    ->first();
                
                if ($category && !empty($category->min_date) && !empty($category->max_date)) {
                    $minDate = new \DateTime($category->min_date->format('Y-m-d'));
                    $maxDate = new \DateTime($category->max_date->format('Y-m-d'));
                    
                    foreach ($data['joueurs'] as $index => $joueur) {
                        if (!empty($joueur['date_naissance'])) {
                            $birthDate = new \DateTime($joueur['date_naissance']);
                            if ($birthDate < $minDate || $birthDate > $maxDate) {
                                $this->Flash->error(sprintf(
                                    'Le joueur %s doit être né entre le %s et le %s pour la catégorie %s',
                                    $joueur['nom_complet'] ?? 'n°' . ($index + 1),
                                    $minDate->format('d/m/Y'),
                                    $maxDate->format('d/m/Y'),
                                    $category->age_range
                                ));
                                return $this->redirect(['action' => 'addBasketball']);
                            }
                        }
                    }
                }
                
                // Map to basketball_teams_joueurs for saving
                $data['basketball_teams_joueurs'] = array_values($data['joueurs']);
                unset($data['joueurs']); // Remove the original to avoid confusion
            }
            
            // Gérer les données associées
            $team = $basketballTeamsTable->patchEntity($team, $data, [
                'associated' => ['BasketballTeamsJoueurs']
            ]);
            
            if ($basketballTeamsTable->save($team, ['associated' => ['BasketballTeamsJoueurs']])) {
                $this->Flash->success(__('Votre équipe de basketball a été inscrite avec succès.'));
                return $this->redirect(['action' => 'basketballTeamView', $team->id]);
            }
            
            // Afficher les erreurs de validation
            $errors = $team->getErrors();
            $errorMessages = [];
            
            foreach ($errors as $field => $fieldErrors) {
                $this->_extractErrorMessages($field, $fieldErrors, $errorMessages);
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(__('Erreurs de validation: ') . implode(', ', $errorMessages));
            } else {
                $this->Flash->error(__('L\'inscription n\'a pas pu être enregistrée. Veuillez réessayer.'));
            }
        }
        
        // Load basketball categories - try sport-specific table first
        $basketballCategories = [];
        try {
            $BasketballCategoriesTable = $this->fetchTable('BasketballCategories');
            $basketballCategories = $BasketballCategoriesTable->find('list', [
                'keyField' => 'id',
                'valueField' => 'name'
            ])->where(['active' => true])->toArray();
        } catch (\Exception $e) {
            // Fallback to unified Categories table if BasketballCategories doesn't exist
            try {
                $CategoriesTable = $this->fetchTable('Categories');
                $basketballCategories = $CategoriesTable->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'name'
                ])->where(['active' => true, 'sport_id' => 2])->toArray();
            } catch (\Exception $e2) {
                \Cake\Log\Log::error('Could not load basketball categories: ' . $e2->getMessage());
                // Use hardcoded fallback categories
                $basketballCategories = [
                    1 => '-12 ans',
                    2 => '-15 ans',
                    3 => 'U17',
                    4 => 'Senior'
                ];
            }
        }
        
        $footballDistricts = $basketballTeamsTable->FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballOrganisations = $basketballTeamsTable->FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $user = $this->Authentication->getIdentity();
        $this->set(compact('team', 'user', 'basketballCategories', 'footballDistricts', 'footballOrganisations'));
        $this->set('sport', 'basketball');
        $this->render('add_basketball');
    }
    
    /**
     * Basketball Team View method
     *
     * @param string|null $id Basketball Team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function basketballTeamView($id = null)
    {
        $basketballTeamsTable = $this->fetchTable('BasketballTeams');
        $team = $basketballTeamsTable->get($id, [
            'contain' => ['Users', 'BasketballTeamsJoueurs', 'BasketballCategories', 'FootballDistricts', 'FootballOrganisations']
        ]);
        
        // Check if user has permission to view this team
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de voir cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Map basketball players to joueurs for view compatibility
        if (!empty($team->basketball_teams_joueurs)) {
            $team->joueurs = $team->basketball_teams_joueurs;
        }
        
        $this->set(compact('team'));
        $this->render('view');
    }
    
    /**
     * Delete Basketball team method
     *
     * @param string|null $id Basketball Team id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteBasketball($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $basketballTeamsTable = $this->fetchTable('BasketballTeams');
        $team = $basketballTeamsTable->get($id, [
            'contain' => ['BasketballTeamsJoueurs']
        ]);
        
        // Check if user has permission to delete this team
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de supprimer cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Vérifier si l'équipe est vérifiée
        if ($team->status === 'verified') {
            $this->Flash->error(__('Cette équipe a été vérifiée et ne peut pas être supprimée.'));
            return $this->redirect(['action' => 'basketballTeamView', $id]);
        }
        
        try {
            if ($basketballTeamsTable->delete($team)) {
                $this->Flash->success(__('L\'équipe de basketball "{0}" a été supprimée avec succès.', $team->nom_equipe));
            } else {
                $this->Flash->error(__('L\'équipe n\'a pas pu être supprimée. Veuillez réessayer.'));
            }
        } catch (\Exception $e) {
            $this->Flash->error(__('Une erreur est survenue lors de la suppression : {0}', $e->getMessage()));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Download Basketball PDF method
     *
     * @param string|null $id Basketball Team id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function downloadBasketballPdf($id = null)
    {
        $basketballTeamsTable = $this->fetchTable('BasketballTeams');
        $team = $basketballTeamsTable->get($id, [
            'contain' => ['Users', 'BasketballTeamsJoueurs', 'BasketballCategories', 'FootballDistricts', 'FootballOrganisations']
        ]);
        
        // Check if user has permission to download this PDF
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de télécharger ce PDF.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Generate PDF with DomPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new \Dompdf\Dompdf($options);
        
        // Create HTML for PDF
        $html = $this->generateBasketballPdfHtml($team);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Filename
        $filename = sprintf('inscription_basketball_%s_%s.pdf', 
            $team->reference_inscription ?? 'BBTEMP',
            \Cake\Utility\Text::slug($team->nom_equipe)
        );
        
        // Send PDF to browser
        $this->response = $this->response->withType('pdf');
        $this->response = $this->response->withDownload($filename);
        $this->response = $this->response->withStringBody($dompdf->output());
        
        return $this->response;
    }
    
    /**
     * Generate HTML for Basketball PDF
     *
     * @param \App\Model\Entity\BasketballTeam $team
     * @return string
     */
    private function generateBasketballPdfHtml($team): string
    {
        // Skip logo processing to avoid GD extension requirement
        $logoData = '';
        
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20mm; }
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #FF6B35;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        h1 {
            color: #FF6B35;
            margin: 10px 0;
            font-size: 24pt;
        }
        .reference {
            background: #FFF5F0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            color: #FF6B35;
        }
        h2 {
            color: #FF6B35;
            border-bottom: 1px solid #E0E0E0;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 16pt;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #555;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            color: #333;
        }
        .players-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .players-table th {
            background: #FF6B35;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }
        .players-table td {
            border-bottom: 1px solid #E0E0E0;
            padding: 8px;
            font-size: 9pt;
        }
        .players-table tr:nth-child(even) {
            background: #FFF5F0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E0E0E0;
            text-align: center;
            font-size: 8pt;
            color: #777;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }
        .badge-basketball {
            background: #FF6B35;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">';
        
        if ($logoData) {
            $html .= '<img src="' . $logoData . '" class="logo" alt="Logo">';
        }
        
        $html .= '
        <h1>Tournoi de Basketball</h1>
        <p>Fiche d\'inscription</p>
    </div>
    
    <div class="reference">
        Référence : ' . h($team->reference_inscription ?? 'En cours de génération') . '
    </div>
    
    <h2>Informations de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom de l\'équipe :</div>
            <div class="info-value">' . h($team->nom_equipe) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Catégorie :</div>
            <div class="info-value">' . h($team->categorie) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Genre :</div>
            <div class="info-value">' . h($team->genre) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Type de basketball :</div>
            <div class="info-value"><span class="badge badge-basketball">' . h($team->type_basketball) . '</span></div>
        </div>
        <div class="info-row">
            <div class="info-label">District :</div>
            <div class="info-value">' . h($team->district) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Organisation :</div>
            <div class="info-value">' . h($team->organisation) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Adresse :</div>
            <div class="info-value">' . nl2br(h($team->adresse)) . '</div>
        </div>
    </div>
    
    <h2>Responsable de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->responsable_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->responsable_date_naissance ? h($team->responsable_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->responsable_tel) . '</div>
        </div>';
        
        if (!empty($team->responsable_whatsapp)) {
            $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->responsable_whatsapp) . '</div>
        </div>';
        }
        
        $html .= '
    </div>
    
    <h2>Entraîneur</h2>
    <div class="info-section">';
        
        if ($team->entraineur_same_as_responsable) {
            $html .= '<p><em>L\'entraîneur est le même que le responsable</em></p>';
        } else {
            $html .= '
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->entraineur_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->entraineur_date_naissance ? h($team->entraineur_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->entraineur_tel) . '</div>
        </div>';
            
            if (!empty($team->entraineur_whatsapp)) {
                $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->entraineur_whatsapp) . '</div>
        </div>';
            }
        }
        
        $html .= '
    </div>
    
    <h2>Liste des joueurs (' . count($team->basketball_teams_joueurs) . ')</h2>
    <table class="players-table">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 35%">Nom complet</th>
                <th style="width: 20%">Date de naissance</th>
                <th style="width: 25%">Identifiant</th>
                <th style="width: 15%">Taille</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($team->basketball_teams_joueurs as $index => $joueur) {
            $html .= '
            <tr>
                <td>' . ($index + 1) . '</td>
                <td>' . h($joueur->nom_complet) . '</td>
                <td>' . ($joueur->date_naissance ? h($joueur->date_naissance->format('d/m/Y')) : '') . '</td>
                <td>' . h($joueur->identifiant) . '</td>
                <td>' . h($joueur->taille_vestimentaire) . '</td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré le ' . date('d/m/Y à H:i') . '</p>
        <p>Ce document constitue votre preuve d\'inscription au tournoi de basketball</p>
    </div>
</body>
</html>';
        
        return $html;
    }

    /**
     * Edit Basketball team method
     *
     * @param string|null $id Basketball team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editBasketball($id = null)
    {
        $basketballTeamsTable = $this->fetchTable('BasketballTeams');
        $team = $basketballTeamsTable->get($id, [
            'contain' => ['BasketballTeamsJoueurs']
        ]);
        
        // Vérifier que l'utilisateur a le droit de modifier cette équipe
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de modifier cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Vérifier si l'équipe est vérifiée
        if ($team->status === 'verified') {
            $this->Flash->error(__('Cette équipe a été vérifiée et ne peut plus être modifiée.'));
            return $this->redirect(['action' => 'basketballTeamView', $id]);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Mapper les champs des relations vers les champs texte attendus
            if (!empty($data['basketball_category_id'])) {
                $CategoriesTable = $this->fetchTable('Categories');
                $category = $CategoriesTable->get($data['basketball_category_id']);
                $data['categorie'] = $category->name;
            }
            
            if (!empty($data['basketball_district_id'])) {
                $district = $basketballTeamsTable->FootballDistricts->get($data['basketball_district_id']);
                $data['district'] = $district->name;
            }
            
            if (!empty($data['basketball_organisation_id'])) {
                $organisation = $basketballTeamsTable->FootballOrganisations->get($data['basketball_organisation_id']);
                $data['organisation'] = $organisation->name;
            }
            
            // Gérer le cas où l'entraîneur est le même que le responsable
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
            }
            
            // Gérer les uploads de fichiers
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Upload des fichiers CIN du responsable
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
            
            // Handle entraineur file uploads
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_cin_recto'] = $data['responsable_cin_recto'] ?? '';
                $data['entraineur_cin_verso'] = $data['responsable_cin_verso'] ?? '';
            } else {
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
            
            // Gérer les joueurs de basketball
            if (!empty($data['joueurs'])) {
                // Filtrer les joueurs supprimés
                $data['basketball_teams_joueurs'] = array_values(array_filter($data['joueurs'], function($joueur) {
                    return !empty($joueur['nom_complet']);
                }));
                unset($data['joueurs']); // Remove the original to avoid confusion
                
                // Valider le nombre de joueurs selon le type de basketball
                $typeBasketball = $data['type_basketball'] ?? $team->type_basketball ?? '';
                $playerCount = count($data['basketball_teams_joueurs']);
                $limits = ['3x3' => ['min' => 3, 'max' => 4], '5x5' => ['min' => 5, 'max' => 8]];
                
                if (isset($limits[$typeBasketball])) {
                    $min = $limits[$typeBasketball]['min'];
                    $max = $limits[$typeBasketball]['max'];
                    
                    if ($playerCount < $min || $playerCount > $max) {
                        $this->Flash->error(sprintf(
                            'Le nombre de joueurs doit être entre %d et %d pour le type %s (actuellement %d joueurs)',
                            $min, $max, $typeBasketball, $playerCount
                        ));
                        return $this->redirect(['action' => 'editBasketball', $id]);
                    }
                }
            }
            
            $team = $basketballTeamsTable->patchEntity($team, $data, [
                'associated' => ['BasketballTeamsJoueurs']
            ]);
            
            if ($basketballTeamsTable->save($team, ['associated' => ['BasketballTeamsJoueurs']])) {
                $this->Flash->success(__('L\'équipe de basketball a été mise à jour avec succès.'));
                return $this->redirect(['action' => 'basketballTeamView', $id]);
            }
            
            // Afficher les erreurs
            $errors = $team->getErrors();
            $errorMessages = [];
            
            foreach ($errors as $field => $fieldErrors) {
                $this->_extractErrorMessages($field, $fieldErrors, $errorMessages);
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(__('Erreurs de validation: ') . implode(', ', $errorMessages));
            } else {
                $this->Flash->error(__('L\'équipe n\'a pas pu être mise à jour. Veuillez réessayer.'));
            }
        }
        
        // Load basketball categories from unified Categories table (sport_id = 2)
        $CategoriesTable = $this->fetchTable('Categories');
        $basketballCategories = $CategoriesTable->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true, 'sport_id' => 2])->toArray();
        
        $footballDistricts = $basketballTeamsTable->FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballOrganisations = $basketballTeamsTable->FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        // Trouver les IDs correspondants aux valeurs texte stockées
        if (!empty($team->categorie)) {
            $category = $basketballTeamsTable->BasketballCategories->find()
                ->where(['name' => $team->categorie])
                ->first();
            if ($category) {
                $team->basketball_category_id = $category->id;
            }
        }
        
        if (!empty($team->district)) {
            $district = $basketballTeamsTable->FootballDistricts->find()
                ->where(['name' => $team->district])
                ->first();
            if ($district) {
                $team->basketball_district_id = $district->id;
            }
        }
        
        if (!empty($team->organisation)) {
            $organisation = $basketballTeamsTable->FootballOrganisations->find()
                ->where(['name' => $team->organisation])
                ->first();
            if ($organisation) {
                $team->basketball_organisation_id = $organisation->id;
            }
        }
        
        // Map basketball_teams_joueurs to joueurs for form compatibility
        if (!empty($team->basketball_teams_joueurs)) {
            $team->joueurs = $team->basketball_teams_joueurs;
        }
        
        $this->set(compact('team', 'basketballCategories', 'footballDistricts', 'footballOrganisations'));
    }

    /**
     * Add Handball team page
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function addHandball()
    {
        $handballTeamsTable = $this->fetchTable('HandballTeams');
        $team = $handballTeamsTable->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Debug: Log the incoming data
            \Cake\Log\Log::debug('Handball form data: ' . json_encode($data));
            
            // Set handball-specific defaults
            $data['user_id'] = $this->Authentication->getIdentity() ? $this->Authentication->getIdentity()->get('id') : null;
            
            if (!$data['user_id']) {
                $this->Flash->error(__('Vous devez être connecté pour inscrire une équipe.'));
                return $this->redirect(['controller' => 'Sports', 'action' => 'handball']);
            }
            
            // Mapper les champs des relations vers les champs texte attendus
            if (!empty($data['handball_category_id'])) {
                $HandballCategories = $this->fetchTable('HandballCategories');
                $category = $HandballCategories->get($data['handball_category_id']);
                $data['categorie'] = $category->age_range;
            }
            
            if (!empty($data['handball_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['handball_district_id']);
                $data['district'] = $district->name;
            }
            
            if (!empty($data['handball_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['handball_organisation_id']);
                $data['organisation'] = $organisation->name;
            }
            
            // Gérer les uploads de fichiers
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Upload des fichiers CIN du responsable
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
            
            // Handle entraineur same as responsable
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
                $data['entraineur_cin_recto'] = $data['responsable_cin_recto'] ?? '';
                $data['entraineur_cin_verso'] = $data['responsable_cin_verso'] ?? '';
            } else {
                // Process file uploads for entraineur
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
            
            // Fill text fields from foreign keys for display purposes
            if (!empty($data['handball_category_id'])) {
                $HandballCategories = $this->fetchTable('HandballCategories');
                $category = $HandballCategories->get($data['handball_category_id']);
                if ($category) {
                    $data['categorie'] = $category->age_range;
                }
            }
            
            if (!empty($data['handball_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['handball_district_id']);
                if ($district) {
                    $data['district'] = $district->name;
                }
            }
            
            if (!empty($data['handball_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['handball_organisation_id']);
                if ($organisation) {
                    $data['organisation'] = $organisation->name;
                }
            }
            
            // Générer une référence d'inscription unique pour handball
            if (empty($data['reference_inscription'])) {
                $data['reference_inscription'] = 'HB' . date('Ymd') . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            // S'assurer que le statut est défini
            $data['status'] = $data['status'] ?? 'pending';
            
            // S'assurer que les joueurs sont bien indexés et valider les dates
            if (!empty($data['joueurs'])) {
                $data['joueurs'] = array_values($data['joueurs']);
                
                // Valider les dates de naissance des joueurs depuis la base de données
                $selectedCategory = $data['categorie'] ?? '';
                
                // Récupérer la catégorie avec ses date ranges depuis la DB
                $HandballCategories = $this->fetchTable('HandballCategories');
                $category = $HandballCategories->find()
                    ->where(['age_range' => $selectedCategory])
                    ->first();
                
                if ($category && !empty($category->min_date) && !empty($category->max_date)) {
                    $minDate = new \DateTime($category->min_date->format('Y-m-d'));
                    $maxDate = new \DateTime($category->max_date->format('Y-m-d'));
                    
                    foreach ($data['joueurs'] as $index => $joueur) {
                        if (!empty($joueur['date_naissance'])) {
                            $birthDate = new \DateTime($joueur['date_naissance']);
                            if ($birthDate < $minDate || $birthDate > $maxDate) {
                                $this->Flash->error(sprintf(
                                    'Le joueur %s doit être né entre le %s et le %s pour la catégorie %s',
                                    $joueur['nom_complet'] ?? 'n°' . ($index + 1),
                                    $minDate->format('d/m/Y'),
                                    $maxDate->format('d/m/Y'),
                                    $category->age_range
                                ));
                                return $this->redirect(['action' => 'addHandball']);
                            }
                        }
                    }
                }
                
                // Ensure joueurs are properly indexed and map to handball_teams_joueurs
                $playersData = [];
                foreach ($data['joueurs'] as $joueurData) {
                    // Clean up player data and ensure it's properly structured
                    $playerData = [
                        'nom_complet' => $joueurData['nom_complet'] ?? '',
                        'date_naissance' => $joueurData['date_naissance'] ?? '',
                        'identifiant' => $joueurData['identifiant'] ?? '',
                        'taille_vestimentaire' => $joueurData['taille_vestimentaire'] ?? ''
                    ];
                    $playersData[] = $playerData;
                }
                $data['handball_teams_joueurs'] = $playersData;
                unset($data['joueurs']); // Remove the original to avoid confusion
            }
            
            $team = $handballTeamsTable->patchEntity($team, $data, [
                'associated' => ['HandballTeamsJoueurs']
            ]);
            
            if ($handballTeamsTable->save($team, ['associated' => ['HandballTeamsJoueurs']])) {
                $this->Flash->success(__('Votre équipe de handball a été inscrite avec succès.'));
                return $this->redirect(['action' => 'handballTeamView', $team->id]);
            } else {
                // Collect and display validation errors
                $errors = $team->getErrors();
                $errorMessages = [];
                
                foreach ($errors as $field => $fieldErrors) {
                    if (is_array($fieldErrors)) {
                        foreach ($fieldErrors as $rule => $message) {
                            if (is_string($message)) {
                                $errorMessages[] = "• $field: $message";
                            }
                        }
                    } else {
                        $errorMessages[] = "• $field: $fieldErrors";
                    }
                }
                
                // Also check for player validation errors
                if (!empty($team->handball_teams_joueurs)) {
                    foreach ($team->handball_teams_joueurs as $index => $joueur) {
                        if ($joueur->hasErrors()) {
                            $joueurErrors = $joueur->getErrors();
                            foreach ($joueurErrors as $field => $fieldErrors) {
                                if (is_array($fieldErrors)) {
                                    foreach ($fieldErrors as $rule => $message) {
                                        $errorMessages[] = "• Joueur " . ($index + 1) . " - $field: $message";
                                    }
                                } else {
                                    $errorMessages[] = "• Joueur " . ($index + 1) . " - $field: $fieldErrors";
                                }
                            }
                        }
                    }
                }
                
                if (!empty($errorMessages)) {
                    $fullErrorMessage = "Erreurs de validation :\n" . implode("\n", $errorMessages);
                    $this->Flash->error(__($fullErrorMessage));
                } else {
                    $this->Flash->error(__('L\'inscription n\'a pas pu être enregistrée. Veuillez vérifier que tous les champs requis sont remplis.'));
                }
            }
        }
        
        // Load dropdown options
        $HandballCategories = $this->fetchTable('HandballCategories');
        $handballCategories = $HandballCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $footballDistricts = $handballTeamsTable->FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballOrganisations = $handballTeamsTable->FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $user = $this->Authentication->getIdentity();
        $this->set(compact('team', 'handballCategories', 'footballDistricts', 'footballOrganisations', 'user'));
        $this->set('sport', 'handball');
        $this->render('add_handball');
    }

    /**
     * View Handball team method
     *
     * @param string|null $id Handball team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function handballTeamView($id = null)
    {
        $handballTeamsTable = $this->fetchTable('HandballTeams');
        $team = $handballTeamsTable->get($id, [
            'contain' => ['Users', 'HandballTeamsJoueurs', 'HandballCategories', 'FootballDistricts', 'FootballOrganisations']
        ]);
        
        // Check if user has permission to view this team
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de voir cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }

        // Map handball players to joueurs for view compatibility
        if (!empty($team->handball_teams_joueurs)) {
            $team->joueurs = $team->handball_teams_joueurs;
        }

        $this->set(compact('team'));
        $this->render('view');
    }

    /**
     * Edit Handball team method
     *
     * @param string|null $id Handball team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editHandball($id = null)
    {
        $handballTeamsTable = $this->fetchTable('HandballTeams');
        $team = $handballTeamsTable->get($id, [
            'contain' => ['HandballTeamsJoueurs']
        ]);
        
        // Vérifier que l'utilisateur a le droit de modifier cette équipe
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de modifier cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Vérifier si l'équipe est vérifiée
        if ($team->status === 'verified') {
            $this->Flash->error(__('Cette équipe a été vérifiée et ne peut plus être modifiée.'));
            return $this->redirect(['action' => 'handballTeamView', $id]);
        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Mapper les champs des relations vers les champs texte attendus
            if (!empty($data['handball_category_id'])) {
                $category = $handballTeamsTable->HandballCategories->get($data['handball_category_id']);
                $data['categorie'] = $category->name;
            }
            
            if (!empty($data['handball_district_id'])) {
                $district = $handballTeamsTable->FootballDistricts->get($data['handball_district_id']);
                $data['district'] = $district->name;
            }
            
            if (!empty($data['handball_organisation_id'])) {
                $organisation = $handballTeamsTable->FootballOrganisations->get($data['handball_organisation_id']);
                $data['organisation'] = $organisation->name;
            }
            
            // Gérer le cas où l'entraîneur est le même que le responsable
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
            }
            
            // Gérer les uploads de fichiers
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Upload des fichiers CIN du responsable
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
            
            // Handle entraineur file uploads
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_cin_recto'] = $data['responsable_cin_recto'] ?? '';
                $data['entraineur_cin_verso'] = $data['responsable_cin_verso'] ?? '';
            } else {
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
            
            // Gérer les joueurs de handball
            if (!empty($data['joueurs'])) {
                // Filtrer les joueurs supprimés
                $data['handball_teams_joueurs'] = array_values(array_filter($data['joueurs'], function($joueur) {
                    return !empty($joueur['nom_complet']);
                }));
                unset($data['joueurs']); // Remove the original to avoid confusion
                
                // Valider le nombre de joueurs selon le type de handball
                $typeHandball = $data['type_handball'] ?? $team->type_handball ?? '';
                $playerCount = count($data['handball_teams_joueurs']);
                $limits = ['7x7' => ['min' => 7, 'max' => 12], '5x5' => ['min' => 5, 'max' => 8]];
                
                if (isset($limits[$typeHandball])) {
                    $min = $limits[$typeHandball]['min'];
                    $max = $limits[$typeHandball]['max'];
                    
                    if ($playerCount < $min || $playerCount > $max) {
                        $this->Flash->error(sprintf(
                            'Le nombre de joueurs doit être entre %d et %d pour le type %s (actuellement %d joueurs)',
                            $min, $max, $typeHandball, $playerCount
                        ));
                        return $this->redirect(['action' => 'editHandball', $id]);
                    }
                }
            }
            
            $team = $handballTeamsTable->patchEntity($team, $data, [
                'associated' => ['HandballTeamsJoueurs']
            ]);
            
            if ($handballTeamsTable->save($team, ['associated' => ['HandballTeamsJoueurs']])) {
                $this->Flash->success(__('L\'équipe de handball a été mise à jour avec succès.'));
                return $this->redirect(['action' => 'handballTeamView', $id]);
            }
            
            // Afficher les erreurs
            $errors = $team->getErrors();
            $errorMessages = [];
            
            foreach ($errors as $field => $fieldErrors) {
                $this->_extractErrorMessages($field, $fieldErrors, $errorMessages);
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(__('Erreurs de validation: ') . implode(', ', $errorMessages));
            } else {
                $this->Flash->error(__('L\'équipe n\'a pas pu être mise à jour. Veuillez réessayer.'));
            }
        }
        
        // Charger les listes pour les dropdowns
        $HandballCategories = $this->fetchTable('HandballCategories');
        $handballCategories = $HandballCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $footballDistricts = $handballTeamsTable->FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballOrganisations = $handballTeamsTable->FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        // Trouver les IDs correspondants aux valeurs texte stockées
        if (!empty($team->categorie)) {
            $category = $handballTeamsTable->HandballCategories->find()
                ->where(['name' => $team->categorie])
                ->first();
            if ($category) {
                $team->handball_category_id = $category->id;
            }
        }
        
        if (!empty($team->district)) {
            $district = $handballTeamsTable->FootballDistricts->find()
                ->where(['name' => $team->district])
                ->first();
            if ($district) {
                $team->handball_district_id = $district->id;
            }
        }
        
        if (!empty($team->organisation)) {
            $organisation = $handballTeamsTable->FootballOrganisations->find()
                ->where(['name' => $team->organisation])
                ->first();
            if ($organisation) {
                $team->handball_organisation_id = $organisation->id;
            }
        }
        
        // Map handball_teams_joueurs to joueurs for form compatibility
        if (!empty($team->handball_teams_joueurs)) {
            $team->joueurs = $team->handball_teams_joueurs;
        }
        
        $this->set(compact('team', 'handballCategories', 'footballDistricts', 'footballOrganisations'));
    }

    /**
     * Delete Handball team method
     *
     * @param string|null $id Handball team id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteHandball($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $handballTeamsTable = $this->fetchTable('HandballTeams');
        $team = $handballTeamsTable->get($id, [
            'contain' => ['HandballTeamsJoueurs']
        ]);
        
        // Vérifier que l'utilisateur a le droit de supprimer cette équipe
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de supprimer cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Vérifier si l'équipe est vérifiée
        if ($team->status === 'verified') {
            $this->Flash->error(__('Cette équipe a été vérifiée et ne peut pas être supprimée.'));
            return $this->redirect(['action' => 'handballTeamView', $id]);
        }
        
        try {
            if ($handballTeamsTable->delete($team)) {
                $this->Flash->success(__('L\'équipe de handball "{0}" a été supprimée avec succès.', $team->nom_equipe));
            } else {
                // Récupérer les erreurs de validation
                $errors = $team->getErrors();
                if (!empty($errors)) {
                    $errorMessages = [];
                    foreach ($errors as $field => $fieldErrors) {
                        foreach ($fieldErrors as $error) {
                            $errorMessages[] = $error;
                        }
                    }
                    $this->Flash->error(__('Impossible de supprimer l\'équipe : {0}', implode(', ', $errorMessages)));
                } else {
                    $this->Flash->error(__('L\'équipe n\'a pas pu être supprimée. Veuillez réessayer.'));
                }
            }
        } catch (\Exception $e) {
            $this->Flash->error(__('Une erreur est survenue lors de la suppression : {0}', $e->getMessage()));
        }
        
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Download Handball PDF method
     *
     * @param string|null $id Handball team id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function downloadHandballPdf($id = null)
    {
        $handballTeamsTable = $this->fetchTable('HandballTeams');
        $team = $handballTeamsTable->get($id, [
            'contain' => ['Users', 'HandballTeamsJoueurs', 'HandballCategories', 'FootballDistricts', 'FootballOrganisations']
        ]);
        
        // Vérifier que l'utilisateur a le droit de télécharger ce PDF
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de télécharger ce PDF.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Générer le PDF avec DomPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new \Dompdf\Dompdf($options);
        
        // Créer le HTML pour le PDF
        $html = $this->generateHandballPdfHtml($team);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Nom du fichier
        $filename = sprintf('inscription_%s_%s.pdf', 
            $team->reference_inscription ?? 'HBTEMP',
            \Cake\Utility\Text::slug($team->nom_equipe)
        );
        
        // Envoyer le PDF au navigateur
        $this->response = $this->response->withType('pdf');
        $this->response = $this->response->withDownload($filename);
        $this->response = $this->response->withStringBody($dompdf->output());
        
        return $this->response;
    }

    /**
     * Generate HTML for Handball PDF
     *
     * @param \App\Model\Entity\HandballTeam $team
     * @return string
     */
    private function generateHandballPdfHtml($team): string
    {
        // Skip logo processing to avoid GD extension requirement
        $logoData = '';
        
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20mm; }
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2C3E50;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        h1 {
            color: #2C3E50;
            margin: 10px 0;
            font-size: 24pt;
        }
        .reference {
            background: #FFE8DC;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            color: #D2691E;
        }
        h2 {
            color: #34495E;
            border-bottom: 1px solid #E0E0E0;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 16pt;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #555;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            color: #333;
        }
        .players-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .players-table th {
            background: #D2691E;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }
        .players-table td {
            border-bottom: 1px solid #E0E0E0;
            padding: 8px;
            font-size: 9pt;
        }
        .players-table tr:nth-child(even) {
            background: #F8F9FA;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E0E0E0;
            text-align: center;
            font-size: 8pt;
            color: #777;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }
        .badge-handball {
            background: #D2691E;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">';
        
        if ($logoData) {
            $html .= '<img src="' . $logoData . '" class="logo" alt="Logo">';
        }
        
        $html .= '
        <h1>Tournoi de Handball</h1>
        <p>Fiche d\'inscription</p>
    </div>
    
    <div class="reference">
        Référence : ' . h($team->reference_inscription ?? 'En cours de génération') . '
    </div>
    
    <h2>Informations de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom de l\'équipe :</div>
            <div class="info-value">' . h($team->nom_equipe) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Catégorie :</div>
            <div class="info-value">' . h($team->categorie) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Genre :</div>
            <div class="info-value">' . h($team->genre) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Type de handball :</div>
            <div class="info-value"><span class="badge badge-handball">' . h($team->type_handball) . '</span></div>
        </div>
        <div class="info-row">
            <div class="info-label">District :</div>
            <div class="info-value">' . h($team->district) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Organisation :</div>
            <div class="info-value">' . h($team->organisation) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Adresse :</div>
            <div class="info-value">' . nl2br(h($team->adresse)) . '</div>
        </div>
    </div>
    
    <h2>Responsable de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->responsable_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->responsable_date_naissance ? h($team->responsable_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->responsable_tel) . '</div>
        </div>';
        
        if (!empty($team->responsable_whatsapp)) {
            $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->responsable_whatsapp) . '</div>
        </div>';
        }
        
        $html .= '
    </div>
    
    <h2>Entraîneur</h2>
    <div class="info-section">';
        
        if ($team->entraineur_same_as_responsable) {
            $html .= '<p><em>L\'entraîneur est le même que le responsable</em></p>';
        } else {
            $html .= '
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->entraineur_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->entraineur_date_naissance ? h($team->entraineur_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->entraineur_tel) . '</div>
        </div>';
            
            if (!empty($team->entraineur_whatsapp)) {
                $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->entraineur_whatsapp) . '</div>
        </div>';
            }
        }
        
        $html .= '
    </div>
    
    <h2>Liste des joueurs (' . count($team->handball_teams_joueurs) . ')</h2>
    <table class="players-table">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 35%">Nom complet</th>
                <th style="width: 20%">Date de naissance</th>
                <th style="width: 25%">Identifiant</th>
                <th style="width: 15%">Taille</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($team->handball_teams_joueurs as $index => $joueur) {
            $html .= '
            <tr>
                <td>' . ($index + 1) . '</td>
                <td>' . h($joueur->nom_complet) . '</td>
                <td>' . ($joueur->date_naissance ? h($joueur->date_naissance->format('d/m/Y')) : '') . '</td>
                <td>' . h($joueur->identifiant) . '</td>
                <td>' . h($joueur->taille_vestimentaire) . '</td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré le ' . date('d/m/Y à H:i') . '</p>
        <p>Ce document constitue votre preuve d\'inscription au tournoi de handball</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Volleyball team registration method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function addVolleyball()
    {
        $volleyballTeamsTable = $this->fetchTable('VolleyballTeams');
        $team = $volleyballTeamsTable->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Debug: Log the incoming data
            \Cake\Log\Log::debug('Volleyball form data: ' . json_encode($data));
            
            // Set volleyball-specific defaults
            $data['user_id'] = $this->Authentication->getIdentity() ? $this->Authentication->getIdentity()->get('id') : null;
            
            if (!$data['user_id']) {
                $this->Flash->error(__('Vous devez être connecté pour inscrire une équipe.'));
                return $this->redirect(['controller' => 'Sports', 'action' => 'volleyball']);
            }
            
            // Mapper les champs des relations vers les champs texte attendus
            if (!empty($data['volleyball_category_id'])) {
                $VolleyballCategories = $this->fetchTable('VolleyballCategories');
                $category = $VolleyballCategories->get($data['volleyball_category_id']);
                $data['categorie'] = $category->age_range;
            }
            
            if (!empty($data['volleyball_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['volleyball_district_id']);
                $data['district'] = $district->name;
            }
            
            if (!empty($data['volleyball_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['volleyball_organisation_id']);
                $data['organisation'] = $organisation->name;
            }
            
            // Gérer les uploads de fichiers
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Upload des fichiers CIN du responsable
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
            
            // Handle entraineur same as responsable
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
                $data['entraineur_cin_recto'] = $data['responsable_cin_recto'] ?? '';
                $data['entraineur_cin_verso'] = $data['responsable_cin_verso'] ?? '';
            } else {
                // Process file uploads for entraineur
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
            
            // Fill text fields from foreign keys for display purposes
            if (!empty($data['volleyball_category_id'])) {
                $VolleyballCategories = $this->fetchTable('VolleyballCategories');
                $category = $VolleyballCategories->get($data['volleyball_category_id']);
                if ($category) {
                    $data['categorie'] = $category->age_range;
                }
            }
            
            if (!empty($data['volleyball_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['volleyball_district_id']);
                if ($district) {
                    $data['district'] = $district->name;
                }
            }
            
            if (!empty($data['volleyball_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['volleyball_organisation_id']);
                if ($organisation) {
                    $data['organisation'] = $organisation->name;
                }
            }
            
            // Générer une référence d'inscription unique pour volleyball
            if (empty($data['reference_inscription'])) {
                $data['reference_inscription'] = 'VB' . date('Ymd') . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            // S'assurer que le statut est défini
            $data['status'] = $data['status'] ?? 'pending';
            
            // S'assurer que les joueurs sont bien indexés et valider les dates
            if (!empty($data['joueurs'])) {
                $data['joueurs'] = array_values($data['joueurs']);
                
                // Valider les dates de naissance des joueurs depuis la base de données
                $selectedCategory = $data['categorie'] ?? '';
                
                // Récupérer la catégorie avec ses date ranges depuis la DB
                $VolleyballCategories = $this->fetchTable('VolleyballCategories');
                $category = $VolleyballCategories->find()
                    ->where(['age_range' => $selectedCategory])
                    ->first();
                
                if ($category && !empty($category->min_date) && !empty($category->max_date)) {
                    $minDate = new \DateTime($category->min_date->format('Y-m-d'));
                    $maxDate = new \DateTime($category->max_date->format('Y-m-d'));
                    
                    foreach ($data['joueurs'] as $index => $joueur) {
                        if (!empty($joueur['date_naissance'])) {
                            $birthDate = new \DateTime($joueur['date_naissance']);
                            if ($birthDate < $minDate || $birthDate > $maxDate) {
                                $this->Flash->error(sprintf(
                                    'Le joueur %s doit être né entre le %s et le %s pour la catégorie %s',
                                    $joueur['nom_complet'] ?? 'n°' . ($index + 1),
                                    $minDate->format('d/m/Y'),
                                    $maxDate->format('d/m/Y'),
                                    $category->age_range
                                ));
                                return $this->redirect(['action' => 'addVolleyball']);
                            }
                        }
                    }
                }
                
                // Ensure joueurs are properly indexed and map to volleyball_teams_joueurs
                $playersData = [];
                foreach ($data['joueurs'] as $joueurData) {
                    // Clean up player data and ensure it's properly structured
                    $playerData = [
                        'nom_complet' => $joueurData['nom_complet'] ?? '',
                        'date_naissance' => $joueurData['date_naissance'] ?? '',
                        'identifiant' => $joueurData['identifiant'] ?? '',
                        'taille_vestimentaire' => $joueurData['taille_vestimentaire'] ?? ''
                    ];
                    $playersData[] = $playerData;
                }
                $data['volleyball_teams_joueurs'] = $playersData;
                unset($data['joueurs']); // Remove the original to avoid confusion
            }
            
            $team = $volleyballTeamsTable->patchEntity($team, $data, [
                'associated' => ['VolleyballTeamsJoueurs']
            ]);
            
            if ($volleyballTeamsTable->save($team, ['associated' => ['VolleyballTeamsJoueurs']])) {
                $this->Flash->success(__('Votre équipe de volleyball a été inscrite avec succès.'));
                return $this->redirect(['action' => 'volleyballTeamView', $team->id]);
            } else {
                // Collect and display validation errors
                $errors = $team->getErrors();
                $errorMessages = [];
                
                foreach ($errors as $field => $fieldErrors) {
                    if (is_array($fieldErrors)) {
                        foreach ($fieldErrors as $rule => $message) {
                            if (is_string($message)) {
                                $errorMessages[] = "• $field: $message";
                            }
                        }
                    } else {
                        $errorMessages[] = "• $field: $fieldErrors";
                    }
                }
                
                // Also check for player validation errors
                if (!empty($team->volleyball_teams_joueurs)) {
                    foreach ($team->volleyball_teams_joueurs as $index => $joueur) {
                        if ($joueur->hasErrors()) {
                            $joueurErrors = $joueur->getErrors();
                            foreach ($joueurErrors as $field => $fieldErrors) {
                                if (is_array($fieldErrors)) {
                                    foreach ($fieldErrors as $rule => $message) {
                                        $errorMessages[] = "• Joueur " . ($index + 1) . " - $field: $message";
                                    }
                                } else {
                                    $errorMessages[] = "• Joueur " . ($index + 1) . " - $field: $fieldErrors";
                                }
                            }
                        }
                    }
                }
                
                if (!empty($errorMessages)) {
                    $fullErrorMessage = "Erreurs de validation :\n" . implode("\n", $errorMessages);
                    $this->Flash->error(__($fullErrorMessage));
                } else {
                    $this->Flash->error(__('L\'inscription n\'a pas pu être enregistrée. Veuillez vérifier que tous les champs requis sont remplis.'));
                }
            }
        }
        
        // Load dropdown options
        $VolleyballCategories = $this->fetchTable('VolleyballCategories');
        $volleyballCategories = $VolleyballCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $footballDistricts = $volleyballTeamsTable->FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballOrganisations = $volleyballTeamsTable->FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $user = $this->Authentication->getIdentity();
        $this->set(compact('team', 'volleyballCategories', 'footballDistricts', 'footballOrganisations', 'user'));
        $this->set('sport', 'volleyball');
        $this->render('add_volleyball');
    }

    /**
     * View Volleyball team method
     *
     * @param string|null $id Volleyball team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function volleyballTeamView($id = null)
    {
        $volleyballTeamsTable = $this->fetchTable('VolleyballTeams');
        $team = $volleyballTeamsTable->get($id, [
            'contain' => ['Users', 'VolleyballTeamsJoueurs', 'VolleyballCategories', 'FootballDistricts', 'FootballOrganisations']
        ]);
        
        // Check if user has permission to view this team
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de voir cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }

        // Map volleyball players to joueurs for view compatibility
        if (!empty($team->volleyball_teams_joueurs)) {
            $team->joueurs = $team->volleyball_teams_joueurs;
        }

        $this->set(compact('team'));
        $this->render('view');
    }
    
    /**
     * Beach volleyball team registration method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function addBeachvolley()
    {
        $beachvolleyTeamsTable = $this->fetchTable('BeachvolleyTeams');
        $team = $beachvolleyTeamsTable->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Debug: Log the incoming data
            \Cake\Log\Log::debug('Beach volleyball form data: ' . json_encode($data));
            
            // Set beach volleyball-specific defaults
            $data['user_id'] = $this->Authentication->getIdentity() ? $this->Authentication->getIdentity()->get('id') : null;
            
            if (!$data['user_id']) {
                $this->Flash->error(__('Vous devez être connecté pour inscrire une équipe.'));
                return $this->redirect(['controller' => 'Sports', 'action' => 'beachvolley']);
            }
            
            // Mapper les champs des relations vers les champs texte attendus
            if (!empty($data['beachvolley_category_id'])) {
                $BeachvolleyCategories = $this->fetchTable('BeachvolleyCategories');
                $category = $BeachvolleyCategories->get($data['beachvolley_category_id']);
                $data['categorie'] = $category->name;
            }
            
            if (!empty($data['football_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['football_district_id']);
                $data['district'] = $district->name;
            }
            
            if (!empty($data['football_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['football_organisation_id']);
                $data['organisation'] = $organisation->name;
            }
            
            // Gérer les uploads de fichiers
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Upload des fichiers CIN du responsable
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
            
            // Handle entraineur same as responsable
            if (!empty($data['entraineur_same_as_responsable']) && $data['entraineur_same_as_responsable']) {
                $data['entraineur_nom_complet'] = $data['responsable_nom_complet'] ?? '';
                $data['entraineur_date_naissance'] = $data['responsable_date_naissance'] ?? '';
                $data['entraineur_tel'] = $data['responsable_tel'] ?? '';
                $data['entraineur_whatsapp'] = $data['responsable_whatsapp'] ?? '';
                $data['entraineur_cin_recto'] = $data['responsable_cin_recto'] ?? '';
                $data['entraineur_cin_verso'] = $data['responsable_cin_verso'] ?? '';
            } else {
                // Process file uploads for entraineur
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
            
            // Fill text fields from foreign keys for display purposes
            if (!empty($data['beachvolley_category_id'])) {
                $BeachvolleyCategories = $this->fetchTable('BeachvolleyCategories');
                $category = $BeachvolleyCategories->get($data['beachvolley_category_id']);
                if ($category) {
                    $data['categorie'] = $category->name;
                }
            }
            
            if (!empty($data['football_district_id'])) {
                $FootballDistricts = $this->fetchTable('FootballDistricts');
                $district = $FootballDistricts->get($data['football_district_id']);
                if ($district) {
                    $data['district'] = $district->name;
                }
            }
            
            if (!empty($data['football_organisation_id'])) {
                $FootballOrganisations = $this->fetchTable('FootballOrganisations');
                $organisation = $FootballOrganisations->get($data['football_organisation_id']);
                if ($organisation) {
                    $data['organisation'] = $organisation->name;
                }
            }
            
            // Générer une référence d'inscription unique pour beach volleyball
            if (empty($data['reference_inscription'])) {
                $data['reference_inscription'] = 'BV' . date('Ymd') . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            // S'assurer que le statut est défini
            $data['status'] = $data['status'] ?? 'pending';
            
            // S'assurer que les joueurs sont bien indexés et valider les dates
            if (!empty($data['joueurs'])) {
                $data['joueurs'] = array_values($data['joueurs']);
                
                // Valider les dates de naissance des joueurs depuis la base de données
                $selectedCategory = $data['categorie'] ?? '';
                
                // Récupérer la catégorie avec ses date ranges depuis la DB
                $BeachvolleyCategories = $this->fetchTable('BeachvolleyCategories');
                $category = $BeachvolleyCategories->find()
                    ->where(['age_range' => $selectedCategory])
                    ->first();
                
                if ($category && !empty($category->min_date) && !empty($category->max_date)) {
                    $minDate = new \DateTime($category->min_date->format('Y-m-d'));
                    $maxDate = new \DateTime($category->max_date->format('Y-m-d'));
                    
                    foreach ($data['joueurs'] as $index => $joueur) {
                        if (!empty($joueur['date_naissance'])) {
                            $birthDate = new \DateTime($joueur['date_naissance']);
                            if ($birthDate < $minDate || $birthDate > $maxDate) {
                                $this->Flash->error(sprintf(
                                    'Le joueur %s doit être né entre le %s et le %s pour la catégorie %s',
                                    $joueur['nom_complet'] ?? 'n°' . ($index + 1),
                                    $minDate->format('d/m/Y'),
                                    $maxDate->format('d/m/Y'),
                                    $category->age_range
                                ));
                                return $this->redirect(['action' => 'addBeachvolley']);
                            }
                        }
                    }
                }
                
                // Ensure joueurs are properly indexed and map to beachvolley_teams_joueurs
                $playersData = [];
                foreach ($data['joueurs'] as $joueurData) {
                    // Clean up player data and ensure it's properly structured
                    $playerData = [
                        'nom_complet' => $joueurData['nom_complet'] ?? '',
                        'date_naissance' => $joueurData['date_naissance'] ?? '',
                        'identifiant' => $joueurData['identifiant'] ?? '',
                        'taille_vestimentaire' => $joueurData['taille_vestimentaire'] ?? ''
                    ];
                    $playersData[] = $playerData;
                }
                $data['beachvolley_teams_joueurs'] = $playersData;
                unset($data['joueurs']); // Remove the original to avoid confusion
            }
            
            $team = $beachvolleyTeamsTable->patchEntity($team, $data, [
                'associated' => ['BeachvolleyTeamsJoueurs']
            ]);
            
            if ($beachvolleyTeamsTable->save($team, ['associated' => ['BeachvolleyTeamsJoueurs']])) {
                $this->Flash->success(__('Votre équipe de beach volleyball a été inscrite avec succès.'));
                return $this->redirect(['action' => 'beachvolleyTeamView', $team->id]);
            } else {
                // Collect and display validation errors
                $errors = $team->getErrors();
                $errorMessages = [];
                
                foreach ($errors as $field => $fieldErrors) {
                    if (is_array($fieldErrors)) {
                        foreach ($fieldErrors as $rule => $message) {
                            if (is_string($message)) {
                                $errorMessages[] = "• $field: $message";
                            }
                        }
                    } else {
                        $errorMessages[] = "• $field: $fieldErrors";
                    }
                }
                
                // Also check for player validation errors
                if (!empty($team->beachvolley_teams_joueurs)) {
                    foreach ($team->beachvolley_teams_joueurs as $index => $joueur) {
                        if ($joueur->hasErrors()) {
                            $joueurErrors = $joueur->getErrors();
                            foreach ($joueurErrors as $field => $fieldErrors) {
                                if (is_array($fieldErrors)) {
                                    foreach ($fieldErrors as $rule => $message) {
                                        $errorMessages[] = "• Joueur " . ($index + 1) . " - $field: $message";
                                    }
                                } else {
                                    $errorMessages[] = "• Joueur " . ($index + 1) . " - $field: $fieldErrors";
                                }
                            }
                        }
                    }
                }
                
                if (!empty($errorMessages)) {
                    $fullErrorMessage = "Erreurs de validation :\n" . implode("\n", $errorMessages);
                    $this->Flash->error(__($fullErrorMessage));
                } else {
                    $this->Flash->error(__('L\'inscription n\'a pas pu être enregistrée. Veuillez vérifier que tous les champs requis sont remplis.'));
                }
            }
        }
        
        // Load dropdown options
        $BeachvolleyCategories = $this->fetchTable('BeachvolleyCategories');
        $beachvolleyCategories = $BeachvolleyCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true])->toArray();
        
        $footballDistricts = $beachvolleyTeamsTable->FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballOrganisations = $beachvolleyTeamsTable->FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $user = $this->Authentication->getIdentity();
        $this->set(compact('team', 'beachvolleyCategories', 'footballDistricts', 'footballOrganisations', 'user'));
        $this->set('sport', 'beachvolley');
        $this->render('add_beachvolley');
    }

    /**
     * View Beach volleyball team method
     *
     * @param string|null $id Beach volleyball team id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function beachvolleyTeamView($id = null)
    {
        $beachvolleyTeamsTable = $this->fetchTable('BeachvolleyTeams');
        $team = $beachvolleyTeamsTable->get($id, [
            'contain' => ['Users', 'BeachvolleyTeamsJoueurs', 'BeachvolleyCategories', 'FootballDistricts', 'FootballOrganisations']
        ]);
        
        // Check if user has permission to view this team
        $user = $this->Authentication->getIdentity();
        if ($team->user_id !== $user->id) {
            $this->Flash->error(__('Vous n\'avez pas l\'autorisation de voir cette équipe.'));
            return $this->redirect(['action' => 'index']);
        }

        // Map beach volleyball players to joueurs for view compatibility
        if (!empty($team->beachvolley_teams_joueurs)) {
            $team->joueurs = $team->beachvolley_teams_joueurs;
        }

        $this->set(compact('team'));
        $this->render('view');
    }

    /**
     * Get football categories with detailed date ranges for JavaScript validation
     *
     * @return \Cake\Http\Response|null JSON response with categories data
     */
    public function getFootballCategories()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');

        $footballCategoriesTable = $this->fetchTable('FootballCategories');
        $categories = $footballCategoriesTable->find()
            ->select([
                'id',
                'name', 
                'age_range',
                'min_birth_year',
                'max_birth_year', 
                'min_date',
                'max_date'
            ])
            ->contain(['FootballTypes' => [
                'fields' => ['id', 'name', 'code', 'min_players', 'max_players']
            ]])
            ->where(['FootballCategories.active' => true])
            ->all();

        $result = [];
        foreach ($categories as $category) {
            // Extract allowed football types for this category
            $allowedTypes = [];
            foreach ($category->football_types as $type) {
                $allowedTypes[] = [
                    'id' => $type->id,
                    'name' => $type->name,
                    'code' => $type->code,
                    'min_players' => $type->min_players,
                    'max_players' => $type->max_players
                ];
            }
            
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
                'age_range' => $category->age_range,
                'min_birth_year' => $category->min_birth_year,
                'max_birth_year' => $category->max_birth_year,
                'min_date' => $category->min_date,
                'max_date' => $category->max_date,
                'allowed_football_types' => $allowedTypes
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['categories' => $result]));
    }

    /**
     * Get Basketball Categories API method
     *
     * @return \Cake\Http\Response|null JSON response with basketball categories data
     */
    public function getBasketballCategories()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');

        $basketballCategoriesTable = $this->fetchTable('BasketballCategories');
        $categories = $basketballCategoriesTable->find()
            ->select([
                'id',
                'name', 
                'age_range',
                'min_birth_year',
                'max_birth_year', 
                'min_date',
                'max_date'
            ])
            ->where(['BasketballCategories.active' => true])
            ->all();

        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
                'age_range' => $category->age_range,
                'min_birth_year' => $category->min_birth_year,
                'max_birth_year' => $category->max_birth_year,
                'min_date' => $category->min_date ? $category->min_date->format('Y-m-d') : null,
                'max_date' => $category->max_date ? $category->max_date->format('Y-m-d') : null
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['categories' => $result]));
    }

    /**
     * Get Handball Categories API method
     *
     * @return \Cake\Http\Response|null JSON response with handball categories data
     */
    public function getHandballCategories()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');

        $handballCategoriesTable = $this->fetchTable('HandballCategories');
        $categories = $handballCategoriesTable->find()
            ->select([
                'id',
                'name', 
                'age_range',
                'min_birth_year',
                'max_birth_year', 
                'min_date',
                'max_date'
            ])
            ->contain(['HandballTypes' => [
                'fields' => ['id', 'name', 'code', 'min_players', 'max_players']
            ]])
            ->where(['HandballCategories.active' => true])
            ->all();

        $result = [];
        foreach ($categories as $category) {
            // Extract allowed handball types for this category
            $allowedTypes = [];
            foreach ($category->handball_types as $type) {
                $allowedTypes[] = [
                    'id' => $type->id,
                    'name' => $type->name,
                    'code' => $type->code,
                    'min_players' => $type->min_players,
                    'max_players' => $type->max_players
                ];
            }
            
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
                'age_range' => $category->age_range,
                'min_birth_year' => $category->min_birth_year,
                'max_birth_year' => $category->max_birth_year,
                'min_date' => $category->min_date ? $category->min_date->format('Y-m-d') : null,
                'max_date' => $category->max_date ? $category->max_date->format('Y-m-d') : null,
                'allowed_handball_types' => $allowedTypes
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['categories' => $result]));
    }

    /**
     * Get Handball Types API method
     *
     * @return \Cake\Http\Response|null JSON response with handball types data
     */
    public function getHandballTypes()
    {
        $this->request->allowMethod(['get']);
        
        $handballTypesTable = $this->fetchTable('HandballTypes');
        $handballTypes = $handballTypesTable->find()
            ->select(['id', 'name', 'code', 'min_players', 'max_players'])
            ->where(['active' => true])
            ->all();

        $result = [];
        foreach ($handballTypes as $type) {
            $result[] = [
                'id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'min_players' => $type->min_players,
                'max_players' => $type->max_players
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['handball_types' => $result]));
    }

    public function getSports()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');

        $sportsTable = $this->fetchTable('Sports');
        $sports = $sportsTable->find()
            ->select(['id', 'name', 'code', 'min_players', 'max_players'])
            ->where(['active' => true])
            ->all();

        $result = [];
        foreach ($sports as $sport) {
            $result[] = [
                'id' => $sport->id,
                'name' => $sport->name,
                'code' => $sport->code,
                'min_players' => $sport->min_players,
                'max_players' => $sport->max_players
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['sports' => $result]));
    }

    public function getCategories()
    {
        // Debug logging
        \Cake\Log\Log::debug('getCategories method called');
        error_log('DEBUG: getCategories method accessed at ' . date('Y-m-d H:i:s'));
        
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');

        $sportId = $this->request->getQuery('sport_id');
        
        try {
            $result = [];
            
            // Determine which categories table to use based on sport_id
            switch ($sportId) {
                case '1': // Football
                    $categoriesTable = $this->fetchTable('FootballCategories');
                    $categories = $categoriesTable->find()
                        ->select([
                            'id', 'name', 'age_range', 'min_birth_year', 'max_birth_year', 
                            'min_birth_date', 'max_birth_date'
                        ])
                        ->where(['active' => true])
                        ->all();
                    break;
                    
                case '2': // Basketball
                    $categoriesTable = $this->fetchTable('BasketballCategories');
                    $categories = $categoriesTable->find()
                        ->select([
                            'id', 'name', 'age_range', 'min_birth_year', 'max_birth_year', 
                            'min_birth_date', 'max_birth_date'
                        ])
                        ->where(['active' => true])
                        ->all();
                    break;
                    
                case '3': // Handball
                    $categoriesTable = $this->fetchTable('HandballCategories');
                    $categories = $categoriesTable->find()
                        ->select([
                            'id', 'name', 'age_range', 'min_birth_year', 'max_birth_year', 
                            'min_birth_date', 'max_birth_date'
                        ])
                        ->where(['active' => true])
                        ->all();
                    break;
                    
                case '4': // Volleyball
                    $categoriesTable = $this->fetchTable('VolleyballCategories');
                    $categories = $categoriesTable->find()
                        ->select([
                            'id', 'name', 'age_range', 'min_birth_year', 'max_birth_year', 
                            'min_birth_date', 'max_birth_date'
                        ])
                        ->where(['active' => true])
                        ->all();
                    break;
                    
                case '5': // Beach Volleyball
                    $categoriesTable = $this->fetchTable('BeachvolleyCategories');
                    $categories = $categoriesTable->find()
                        ->select([
                            'id', 'name', 'age_range', 'min_birth_year', 'max_birth_year', 
                            'min_birth_date', 'max_birth_date'
                        ])
                        ->where(['active' => true])
                        ->all();
                    break;
                    
                default:
                    // Try generic Categories table as fallback
                    try {
                        $categoriesTable = $this->fetchTable('Categories');
                        $query = $categoriesTable->find()
                            ->select([
                                'id', 'name', 'age_range', 'min_birth_year', 'max_birth_year', 
                                'min_birth_date', 'max_birth_date'
                            ])
                            ->where(['active' => true]);
                        
                        if ($sportId) {
                            $query->where(['sport_id' => $sportId]);
                        }
                        
                        $categories = $query->all();
                    } catch (\Exception $e) {
                        // If Categories table doesn't exist, return empty result
                        $categories = [];
                    }
                    break;
            }
            
            foreach ($categories as $category) {
                $result[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'age_range' => $category->age_range,
                    'min_birth_year' => $category->min_birth_year ?? null,
                    'max_birth_year' => $category->max_birth_year ?? null,
                    'min_birth_date' => $category->min_birth_date ? $category->min_birth_date->format('Y-m-d') : null,
                    'max_birth_date' => $category->max_birth_date ? $category->max_birth_date->format('Y-m-d') : null,
                    'min_age' => $category->min_age ?? null,
                    'max_age' => $category->max_age ?? null,
                    'format' => 'database'
                ];
            }
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Error in getCategories: ' . $e->getMessage());
            error_log('ERROR in getCategories: ' . $e->getMessage());
            $result = [];
        }

        $response = [
            'categories' => $result
        ];
        
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($response));
            
        return $this->response;
    }

    /**
     * Test endpoint to verify routing is working
     */
    public function testEndpoint()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');
        
        error_log('DEBUG: testEndpoint method accessed at ' . date('Y-m-d H:i:s'));
        
        $result = [
            'status' => 'success',
            'message' => 'Test endpoint is working',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($result));
    }

    public function getFootballTypes()
    {
        $this->request->allowMethod(['get']);
        
        $footballTypesTable = $this->fetchTable('FootballTypes');
        $footballTypes = $footballTypesTable->find()
            ->select(['id', 'name', 'code', 'min_players', 'max_players'])
            ->where(['active' => true])
            ->all();

        $result = [];
        foreach ($footballTypes as $type) {
            $result[] = [
                'id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'min_players' => $type->min_players,
                'max_players' => $type->max_players
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['football_types' => $result]));
    }

    /**
     * Get basketball types with player limits
     * Used by JavaScript for dynamic validation
     *
     * @return \Cake\Http\Response JSON response with basketball types
     */
    public function getBasketballTypes()
    {
        $this->request->allowMethod(['get']);
        
        // Static basketball types since they are standardized
        $result = [
            [
                'id' => 1,
                'name' => 'Basketball à 3 (3x3)',
                'code' => '3x3',
                'min_players' => 3,
                'max_players' => 4
            ],
            [
                'id' => 2,
                'name' => 'Basketball à 5 (5x5)',
                'code' => '5x5',
                'min_players' => 5,
                'max_players' => 8
            ]
        ];

        $response = [
            'basketball_types' => $result
        ];
        
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($response));
            
        return $this->response;
    }

    public function getDistricts()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');

        $districtsTable = $this->fetchTable('Districts');
        $districts = $districtsTable->find()
            ->select(['id', 'name', 'code'])
            ->where(['active' => true])
            ->all();

        $result = [];
        foreach ($districts as $district) {
            $result[] = [
                'id' => $district->id,
                'name' => $district->name,
                'code' => $district->code
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['districts' => $result]));
    }

    public function getOrganizations()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');

        $organizationsTable = $this->fetchTable('Organizations');
        $organizations = $organizationsTable->find()
            ->select(['id', 'name', 'code'])
            ->where(['active' => true])
            ->all();

        $result = [];
        foreach ($organizations as $organization) {
            $result[] = [
                'id' => $organization->id,
                'name' => $organization->name,
                'code' => $organization->code
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['organizations' => $result]));
    }

    /**
     * Get volleyball categories with their allowed types
     * 
     * @return \Cake\Http\Response|null
     */
    public function getVolleyballCategories()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');
        
        $volleyballCategoriesTable = $this->fetchTable('VolleyballCategories');
        $categories = $volleyballCategoriesTable->find()
            ->contain(['VolleyballTypes' => [
                'fields' => ['id', 'name', 'code', 'min_players', 'max_players']
            ]])
            ->where(['VolleyballCategories.active' => true])
            ->all();
        
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
                'age_range' => $category->age_range,
                'min_age' => $category->min_age,
                'max_age' => $category->max_age,
                'min_birth_year' => $category->min_birth_year,
                'max_birth_year' => $category->max_birth_year,
                'min_date' => $category->min_date ? $category->min_date->format('Y-m-d') : null,
                'max_date' => $category->max_date ? $category->max_date->format('Y-m-d') : null,
                'allowed_volleyball_types' => $category->volleyball_types
            ];
        }
        
        return $this->response->withStringBody(json_encode(['categories' => $result]));
    }

    /**
     * Get volleyball types 
     * 
     * @return \Cake\Http\Response|null
     */
    public function getVolleyballTypes()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');
        
        $volleyballTypesTable = $this->fetchTable('VolleyballTypes');
        $types = $volleyballTypesTable->find()
            ->where(['active' => true])
            ->all();
        
        $result = [];
        foreach ($types as $type) {
            $result[] = [
                'id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'min_players' => $type->min_players,
                'max_players' => $type->max_players
            ];
        }
        
        return $this->response->withStringBody(json_encode(['volleyball_types' => $result]));
    }

    /**
     * Get beachvolley categories with their allowed types
     * 
     * @return \Cake\Http\Response|null
     */
    public function getBeachvolleyCategories()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');
        
        $beachvolleyCategoriesTable = $this->fetchTable('BeachvolleyCategories');
        $categories = $beachvolleyCategoriesTable->find()
            ->contain(['BeachvolleyTypes' => [
                'fields' => ['id', 'name', 'code', 'min_players', 'max_players']
            ]])
            ->where(['BeachvolleyCategories.active' => true])
            ->all();
        
        $result = [];
        foreach ($categories as $category) {
            // Calculate min/max age from birth years
            $currentYear = date('Y');
            $minAge = $category->max_birth_year ? $currentYear - $category->max_birth_year : null;
            $maxAge = $category->min_birth_year ? $currentYear - $category->min_birth_year : null;
            
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
                'age_range' => $category->age_range,
                'min_age' => $minAge,
                'max_age' => $maxAge,
                'min_birth_year' => $category->min_birth_year,
                'max_birth_year' => $category->max_birth_year,
                'min_date' => $category->min_date ? $category->min_date->format('Y-m-d') : null,
                'max_date' => $category->max_date ? $category->max_date->format('Y-m-d') : null,
                'allowed_beachvolley_types' => $category->beachvolley_types
            ];
        }
        
        return $this->response->withStringBody(json_encode(['categories' => $result]));
    }

    /**
     * Get beachvolley types 
     * 
     * @return \Cake\Http\Response|null
     */
    public function getBeachvolleyTypes()
    {
        $this->request->allowMethod(['get']);
        $this->response = $this->response->withType('application/json');
        
        $beachvolleyTypesTable = $this->fetchTable('BeachvolleyTypes');
        $types = $beachvolleyTypesTable->find()
            ->where(['active' => true])
            ->all();
        
        $result = [];
        foreach ($types as $type) {
            $result[] = [
                'id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'min_players' => $type->min_players,
                'max_players' => $type->max_players
            ];
        }
        
        return $this->response->withStringBody(json_encode(['beachvolley_types' => $result]));
    }

    /**
     * Helper method to extract error messages from nested validation error structures
     *
     * @param string $field The field name
     * @param mixed $fieldErrors The error data (can be nested arrays)
     * @param array &$errorMessages Reference to the error messages array to populate
     * @return void
     */
    private function _extractErrorMessages($field, $fieldErrors, &$errorMessages)
    {
        if (is_array($fieldErrors)) {
            // Check if this is an associative array with nested validation errors
            foreach ($fieldErrors as $key => $value) {
                if (is_numeric($key) && is_array($value)) {
                    // This is likely indexed validation errors (e.g., basketball_teams_joueurs.0.nom_complet)
                    foreach ($value as $subField => $subErrors) {
                        $nestedField = $field . '.' . $key . '.' . $subField;
                        $this->_extractErrorMessages($nestedField, $subErrors, $errorMessages);
                    }
                } elseif (is_array($value)) {
                    // This is nested field validation errors
                    $nestedField = $field . '.' . $key;
                    $this->_extractErrorMessages($nestedField, $value, $errorMessages);
                } else {
                    // This is a simple error message
                    $errorMessages[] = $field . '.' . $key . ': ' . $value;
                }
            }
        } else {
            // Simple string error
            $errorMessages[] = $field . ': ' . $fieldErrors;
        }
    }
    
    /**
     * Download Volleyball team PDF
     */
    public function downloadVolleyballPdf($id = null)
    {
        $volleyballTeamsTable = $this->fetchTable('VolleyballTeams');
        $team = $volleyballTeamsTable->get($id, [
            'contain' => ['VolleyballTeamsJoueurs']
        ]);
        
        // Generate PDF with DomPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new \Dompdf\Dompdf($options);
        
        // Create HTML for PDF
        $html = $this->generateVolleyballPdfHtml($team);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Filename
        $filename = sprintf('inscription_volleyball_%s_%s.pdf', 
            $team->reference_inscription ?? 'VOTEMP',
            \Cake\Utility\Text::slug($team->nom_equipe)
        );
        
        // Send PDF to browser
        $this->response = $this->response->withType('pdf');
        $this->response = $this->response->withDownload($filename);
        $this->response = $this->response->withStringBody($dompdf->output());
        
        return $this->response;
    }
    
    /**
     * Generate HTML for Volleyball team PDF
     */
    private function generateVolleyballPdfHtml($team)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fiche d\'inscription - Volleyball</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #8E44AD;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 18pt;
            font-weight: bold;
            color: #8E44AD;
            margin-bottom: 10px;
        }
        .title {
            font-size: 16pt;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12pt;
            color: #777;
        }
        h2 {
            color: #8E44AD;
            font-size: 14pt;
            border-bottom: 1px solid #8E44AD;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #555;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            color: #333;
        }
        .players-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .players-table th {
            background: #8E44AD;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }
        .players-table td {
            border-bottom: 1px solid #E0E0E0;
            padding: 8px;
            font-size: 9pt;
        }
        .players-table tr:nth-child(even) {
            background: #F8F9FA;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E0E0E0;
            text-align: center;
            font-size: 8pt;
            color: #777;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }
        .badge-volleyball {
            background: #8E44AD;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">RABAT JEUNESSE 2025</div>
        <div class="title">FICHE D\'INSCRIPTION VOLLEYBALL</div>
        <div class="subtitle">Équipe : ' . h($team->nom_equipe) . '</div>
    </div>
    
    <h2>Informations de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Référence :</div>
            <div class="info-value">' . h($team->reference_inscription) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nom de l\'équipe :</div>
            <div class="info-value">' . h($team->nom_equipe) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Type :</div>
            <div class="info-value"><span class="badge badge-volleyball">' . h($team->type_volleyball) . '</span></div>
        </div>
        <div class="info-row">
            <div class="info-label">Catégorie :</div>
            <div class="info-value">' . h($team->categorie) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">District :</div>
            <div class="info-value">' . h($team->district) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Organisation :</div>
            <div class="info-value">' . h($team->organisation) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date d\'inscription :</div>
            <div class="info-value">' . h($team->created->format('d/m/Y H:i')) . '</div>
        </div>
    </div>
    
    <h2>Responsable de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->responsable_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->responsable_date_naissance ? h($team->responsable_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->responsable_tel) . '</div>
        </div>';
        
        if (!empty($team->responsable_whatsapp)) {
            $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->responsable_whatsapp) . '</div>
        </div>';
        }
        
        $html .= '
    </div>
    
    <h2>Entraîneur</h2>
    <div class="info-section">';
        
        if ($team->entraineur_same_as_responsable) {
            $html .= '<p><em>L\'entraîneur est le même que le responsable</em></p>';
        } else {
            $html .= '
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->entraineur_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->entraineur_date_naissance ? h($team->entraineur_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->entraineur_tel) . '</div>
        </div>';
            
            if (!empty($team->entraineur_whatsapp)) {
                $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->entraineur_whatsapp) . '</div>
        </div>';
            }
        }
        
        $html .= '
    </div>
    
    <h2>Liste des joueurs (' . count($team->volleyball_teams_joueurs) . ')</h2>
    <table class="players-table">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nom complet</th>
                <th>Date de naissance</th>
                <th>CIN</th>
                <th>Taille maillot</th>
            </tr>
        </thead>
        <tbody>';
        
        $num = 1;
        foreach ($team->volleyball_teams_joueurs as $joueur) {
            $html .= '
            <tr>
                <td>' . $num++ . '</td>
                <td>' . h($joueur->nom_complet) . '</td>
                <td>' . ($joueur->date_naissance ? h($joueur->date_naissance->format('d/m/Y')) : '') . '</td>
                <td>' . h($joueur->cin) . '</td>
                <td>' . h($joueur->taille_maillot) . '</td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré le ' . date('d/m/Y à H:i') . '</p>
        <p>Rabat Jeunesse 2025 - Système d\'inscription en ligne</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Download Beach Volleyball team PDF
     */
    public function downloadBeachvolleyPdf($id = null)
    {
        $beachvolleyTeamsTable = $this->fetchTable('BeachvolleyTeams');
        $team = $beachvolleyTeamsTable->get($id, [
            'contain' => ['BeachvolleyTeamsJoueurs']
        ]);
        
        // Generate PDF with DomPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new \Dompdf\Dompdf($options);
        
        // Create HTML for PDF
        $html = $this->generateBeachvolleyPdfHtml($team);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Filename
        $filename = sprintf('inscription_beachvolley_%s_%s.pdf', 
            $team->reference_inscription ?? 'BVTEMP',
            \Cake\Utility\Text::slug($team->nom_equipe)
        );
        
        // Send PDF to browser
        $this->response = $this->response->withType('pdf');
        $this->response = $this->response->withDownload($filename);
        $this->response = $this->response->withStringBody($dompdf->output());
        
        return $this->response;
    }
    
    /**
     * Generate HTML for Beach Volleyball team PDF
     */
    private function generateBeachvolleyPdfHtml($team)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fiche d\'inscription - Beach Volleyball</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #F39C12;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 18pt;
            font-weight: bold;
            color: #F39C12;
            margin-bottom: 10px;
        }
        .title {
            font-size: 16pt;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12pt;
            color: #777;
        }
        h2 {
            color: #F39C12;
            font-size: 14pt;
            border-bottom: 1px solid #F39C12;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #555;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            color: #333;
        }
        .players-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .players-table th {
            background: #F39C12;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }
        .players-table td {
            border-bottom: 1px solid #E0E0E0;
            padding: 8px;
            font-size: 9pt;
        }
        .players-table tr:nth-child(even) {
            background: #F8F9FA;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E0E0E0;
            text-align: center;
            font-size: 8pt;
            color: #777;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }
        .badge-beachvolley {
            background: #F39C12;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">RABAT JEUNESSE 2025</div>
        <div class="title">FICHE D\'INSCRIPTION BEACH VOLLEYBALL</div>
        <div class="subtitle">Équipe : ' . h($team->nom_equipe) . '</div>
    </div>
    
    <h2>Informations de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Référence :</div>
            <div class="info-value">' . h($team->reference_inscription) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nom de l\'équipe :</div>
            <div class="info-value">' . h($team->nom_equipe) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Type :</div>
            <div class="info-value"><span class="badge badge-beachvolley">' . h($team->type_beachvolley) . '</span></div>
        </div>
        <div class="info-row">
            <div class="info-label">Catégorie :</div>
            <div class="info-value">' . h($team->categorie) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">District :</div>
            <div class="info-value">' . h($team->district) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Organisation :</div>
            <div class="info-value">' . h($team->organisation) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date d\'inscription :</div>
            <div class="info-value">' . h($team->created->format('d/m/Y H:i')) . '</div>
        </div>
    </div>
    
    <h2>Responsable de l\'équipe</h2>
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->responsable_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->responsable_date_naissance ? h($team->responsable_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->responsable_tel) . '</div>
        </div>';
        
        if (!empty($team->responsable_whatsapp)) {
            $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->responsable_whatsapp) . '</div>
        </div>';
        }
        
        $html .= '
    </div>
    
    <h2>Entraîneur</h2>
    <div class="info-section">';
        
        if ($team->entraineur_same_as_responsable) {
            $html .= '<p><em>L\'entraîneur est le même que le responsable</em></p>';
        } else {
            $html .= '
        <div class="info-row">
            <div class="info-label">Nom complet :</div>
            <div class="info-value">' . h($team->entraineur_nom_complet) . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de naissance :</div>
            <div class="info-value">' . ($team->entraineur_date_naissance ? h($team->entraineur_date_naissance->format('d/m/Y')) : '') . '</div>
        </div>
        <div class="info-row">
            <div class="info-label">Téléphone :</div>
            <div class="info-value">' . h($team->entraineur_tel) . '</div>
        </div>';
            
            if (!empty($team->entraineur_whatsapp)) {
                $html .= '
        <div class="info-row">
            <div class="info-label">WhatsApp :</div>
            <div class="info-value">' . h($team->entraineur_whatsapp) . '</div>
        </div>';
            }
        }
        
        $html .= '
    </div>
    
    <h2>Liste des joueurs (' . count($team->beachvolley_teams_joueurs) . ')</h2>
    <table class="players-table">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nom complet</th>
                <th>Date de naissance</th>
                <th>CIN</th>
                <th>Taille maillot</th>
            </tr>
        </thead>
        <tbody>';
        
        $num = 1;
        foreach ($team->beachvolley_teams_joueurs as $joueur) {
            $html .= '
            <tr>
                <td>' . $num++ . '</td>
                <td>' . h($joueur->nom_complet) . '</td>
                <td>' . ($joueur->date_naissance ? h($joueur->date_naissance->format('d/m/Y')) : '') . '</td>
                <td>' . h($joueur->cin) . '</td>
                <td>' . h($joueur->taille_maillot) . '</td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré le ' . date('d/m/Y à H:i') . '</p>
        <p>Rabat Jeunesse 2025 - Système d\'inscription en ligne</p>
    </div>
</body>
</html>';
        
        return $html;
    }
}
