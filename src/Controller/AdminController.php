<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Admin Controller - COMPLETELY REBUILT
 */
class AdminController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // Use admin layout for all admin pages
        $this->viewBuilder()->setLayout('admin');
        
        // Disable FormProtection component entirely for admin panel
        // We'll handle security manually where needed
        // $this->loadComponent('FormProtection');
    }

    /**
     * beforeFilter - Simplified authentication
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        
        // Debug logging for action names
        $currentAction = $this->request->getParam('action');
        error_log('Admin action called: ' . $currentAction);
        
        // For now, allow access without authentication for testing
        // TODO: Re-enable proper authentication later
    }
    
    /**
     * Admin Dashboard
     */
    public function index()
    {
        // Initialize table objects as needed within try block
        
        // Calculate statistics for dashboard
        $stats = [
            'football' => [
                'total' => 0,
                'pending' => 0,
                'verified' => 0,
                'rejected' => 0,
                'recent' => 0,
                'status' => 'Inscriptions ouvertes'
            ],
            'basketball' => [
                'total' => 0,
                'pending' => 0,
                'verified' => 0,
                'rejected' => 0,
                'recent' => 0,
                'status' => 'Inscriptions ouvertes'
            ],
            'handball' => [
                'total' => 0,
                'pending' => 0,
                'verified' => 0,
                'rejected' => 0,
                'recent' => 0,
                'status' => 'Inscriptions ouvertes'
            ],
            'volleyball' => [
                'total' => 0,
                'pending' => 0,
                'verified' => 0,
                'rejected' => 0,
                'recent' => 0,
                'status' => 'Inscriptions ouvertes'
            ],
            'beachvolley' => [
                'total' => 0,
                'pending' => 0,
                'verified' => 0,
                'rejected' => 0,
                'recent' => 0,
                'status' => 'Inscriptions ouvertes'
            ]
        ];
        
        try {
            // One week ago for recent stats
            $oneWeekAgo = new \DateTime('-1 week');
            
            // Get team counts for each sport - Football teams are in the main Teams table
            $teamsTable = $this->fetchTable('Teams');
            $footballTeams = $teamsTable->find(); // All teams from main Teams table
            $stats['football']['total'] = $footballTeams->count();
            $stats['football']['pending'] = $footballTeams->where(['status' => 'pending'])->count();
            $stats['football']['verified'] = $footballTeams->where(['status' => 'verified'])->count();
            $stats['football']['rejected'] = $footballTeams->where(['status' => 'rejected'])->count();
            $stats['football']['recent'] = $footballTeams->where(['Teams.created >=' => $oneWeekAgo])->count();
            
            // Basketball teams
            $basketballTeamsTable = $this->fetchTable('BasketballTeams');
            $basketballTeams = $basketballTeamsTable->find();
            $stats['basketball']['total'] = $basketballTeams->count();
            $stats['basketball']['pending'] = $basketballTeams->where(['status' => 'pending'])->count();
            $stats['basketball']['verified'] = $basketballTeams->where(['status' => 'verified'])->count();
            $stats['basketball']['rejected'] = $basketballTeams->where(['status' => 'rejected'])->count();
            $stats['basketball']['recent'] = $basketballTeams->where(['BasketballTeams.created >=' => $oneWeekAgo])->count();
            
            // Handball teams
            $handballTeamsTable = $this->fetchTable('HandballTeams');
            $handballTeams = $handballTeamsTable->find();
            $stats['handball']['total'] = $handballTeams->count();
            $stats['handball']['pending'] = $handballTeams->where(['status' => 'pending'])->count();
            $stats['handball']['verified'] = $handballTeams->where(['status' => 'verified'])->count();
            $stats['handball']['rejected'] = $handballTeams->where(['status' => 'rejected'])->count();
            $stats['handball']['recent'] = $handballTeams->where(['HandballTeams.created >=' => $oneWeekAgo])->count();
            
            // Volleyball teams
            $volleyballTeamsTable = $this->fetchTable('VolleyballTeams');
            $volleyballTeams = $volleyballTeamsTable->find();
            $stats['volleyball']['total'] = $volleyballTeams->count();
            $stats['volleyball']['pending'] = $volleyballTeams->where(['status' => 'pending'])->count();
            $stats['volleyball']['verified'] = $volleyballTeams->where(['status' => 'verified'])->count();
            $stats['volleyball']['rejected'] = $volleyballTeams->where(['status' => 'rejected'])->count();
            $stats['volleyball']['recent'] = $volleyballTeams->where(['VolleyballTeams.created >=' => $oneWeekAgo])->count();
            
            // Beach volleyball teams
            $beachvolleyTeamsTable = $this->fetchTable('BeachvolleyTeams');
            $beachvolleyTeams = $beachvolleyTeamsTable->find();
            $stats['beachvolley']['total'] = $beachvolleyTeams->count();
            $stats['beachvolley']['pending'] = $beachvolleyTeams->where(['status' => 'pending'])->count();
            $stats['beachvolley']['verified'] = $beachvolleyTeams->where(['status' => 'verified'])->count();
            $stats['beachvolley']['rejected'] = $beachvolleyTeams->where(['status' => 'rejected'])->count();
            $stats['beachvolley']['recent'] = $beachvolleyTeams->where(['BeachvolleyTeams.created >=' => $oneWeekAgo])->count();
            
        } catch (\Exception $e) {
            // If there's an error getting stats, keep default values
            \Cake\Log\Log::error('Error getting admin stats: ' . $e->getMessage());
        }
        
        // Get additional dashboard data
        $totalUsers = 0;
        $recentTeams = [];
        $recentActivity = [];
        
        try {
            // Total users count
            $usersTable = $this->fetchTable('Users');
            $totalUsers = $usersTable->find()->count();
            
            // Get recent teams from all sports (last 10)
            $recentTeams = [];
            $oneWeekAgo = new \DateTime('-1 week');
            
            // Collect recent teams from all sport tables
            $allRecentTeams = [];
            
            // Basketball teams
            try {
                $basketballTeamsTable = $this->fetchTable('BasketballTeams');
                $basketballRecent = $basketballTeamsTable->find()
                    ->contain(['Users'])
                    ->where(['BasketballTeams.created >=' => $oneWeekAgo])
                    ->orderDesc('BasketballTeams.created')
                    ->limit(5)
                    ->toArray();
                    
                foreach ($basketballRecent as $team) {
                    $team->sport_type = 'basketball';
                    $allRecentTeams[] = $team;
                }
            } catch (\Exception $e) {
                // Continue if table doesn't exist
            }
            
            // Handball teams
            try {
                $handballTeamsTable = $this->fetchTable('HandballTeams');
                $handballRecent = $handballTeamsTable->find()
                    ->contain(['Users'])
                    ->where(['HandballTeams.created >=' => $oneWeekAgo])
                    ->orderDesc('HandballTeams.created')
                    ->limit(5)
                    ->toArray();
                    
                foreach ($handballRecent as $team) {
                    $team->sport_type = 'handball';
                    $allRecentTeams[] = $team;
                }
            } catch (\Exception $e) {
                // Continue if table doesn't exist
            }
            
            // Sort all recent teams by creation date and take top 10
            usort($allRecentTeams, function($a, $b) {
                return $b->created <=> $a->created;
            });
            $recentTeams = array_slice($allRecentTeams, 0, 10);
            
            // Create activity feed based on recent teams
            foreach ($recentTeams as $team) {
                $activity = [
                    'type' => 'info',
                    'icon' => 'fa-plus-circle',
                    'message' => sprintf('Nouvelle équipe "%s" inscrite en %s', 
                        $team->nom_equipe, 
                        ucfirst($team->sport_type ?? 'football')
                    ),
                    'time' => $team->created->format('d/m/Y à H:i')
                ];
                
                // Change activity type based on status
                if (isset($team->status)) {
                    switch ($team->status) {
                        case 'verified':
                            $activity['type'] = 'success';
                            $activity['icon'] = 'fa-check-circle';
                            break;
                        case 'rejected':
                            $activity['type'] = 'danger';
                            $activity['icon'] = 'fa-times-circle';
                            break;
                        case 'pending':
                            $activity['type'] = 'warning';
                            $activity['icon'] = 'fa-clock';
                            break;
                    }
                }
                
                $recentActivity[] = $activity;
            }
            
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Error getting dashboard additional data: ' . $e->getMessage());
        }
        
        $this->set(compact('stats', 'totalUsers', 'recentTeams', 'recentActivity'));
        $this->Flash->success('Bienvenue dans l\'administration');
    }
    
    /**
     * Teams management - View and manage all teams across sports
     */
    public function teams()
    {
        $sport = $this->request->getQuery('sport', 'all');
        $status = $this->request->getQuery('status', 'all');
        $page = (int)$this->request->getQuery('page', 1);
        
        $teams = [];
        $pagination = null;
        
        try {
            if ($sport === 'all') {
                // Get teams from all sports - Football teams are in the main Teams table
                $footballTeams = $this->fetchTable('Teams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username', 'type_football', 'district', 'categorie', 'reference_inscription'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Football';
                            return $row;
                        });
                    });
                
                $basketballTeams = $this->fetchTable('BasketballTeams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username', 'type_basketball', 'district', 'categorie', 'reference_inscription'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Basketball';
                            $row['type_football'] = $row['type_basketball'] ?? null; // Map to unified field name
                            return $row;
                        });
                    });
                
                $handballTeams = $this->fetchTable('HandballTeams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username', 'type_handball', 'district', 'categorie', 'reference_inscription'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Handball';
                            $row['type_football'] = $row['type_handball'] ?? null; // Map to unified field name
                            return $row;
                        });
                    });
                
                $volleyballTeams = $this->fetchTable('VolleyballTeams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username', 'type_volleyball', 'district', 'categorie', 'reference_inscription'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Volleyball';
                            $row['type_football'] = $row['type_volleyball'] ?? null; // Map to unified field name
                            return $row;
                        });
                    });
                
                $beachvolleyTeams = $this->fetchTable('BeachvolleyTeams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username', 'type_beachvolley', 'district', 'categorie', 'reference_inscription'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Beach Volleyball';
                            $row['type_football'] = $row['type_beachvolley'] ?? null; // Map to unified field name
                            return $row;
                        });
                    });
                
                // Combine all teams
                $allTeams = array_merge(
                    $footballTeams->toArray(),
                    $basketballTeams->toArray(),
                    $handballTeams->toArray(),
                    $volleyballTeams->toArray(),
                    $beachvolleyTeams->toArray()
                );
                
                // Filter by status if specified
                if ($status !== 'all') {
                    $allTeams = array_filter($allTeams, function($team) use ($status) {
                        return $team['status'] === $status;
                    });
                }
                
                // Sort by created date (newest first)
                usort($allTeams, function($a, $b) {
                    return $b['created'] <=> $a['created'];
                });
                
                $teams = $allTeams;
                
            } else {
                // Get teams for specific sport
                $table = $this->getSportTable($sport);
                if ($table) {
                    $query = $table->find()->contain(['Users']);
                    
                    if ($status !== 'all') {
                        $query->where(['status' => $status]);
                    }
                    
                    // Use the correct table name for ORDER BY
                    $tableName = $this->getSportTableName($sport);
                    $query->orderDesc("{$tableName}.created");
                    $teams = $query->toArray();
                    
                    // Add sport field for consistency
                    foreach ($teams as &$team) {
                        $team['sport'] = ucfirst($sport);
                    }
                }
            }
            
        } catch (\Exception $e) {
            $this->Flash->error('Erreur lors du chargement des équipes: ' . $e->getMessage());
            $teams = [];
        }
        
        $this->set(compact('teams', 'sport', 'status'));
    }
    
    /**
     * Update team status via AJAX
     */
    public function updateTeamStatus()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $sport = $this->request->getData('sport');
        $newStatus = $this->request->getData('status');
        
        try {
            $table = $this->getSportTable($sport);
            if (!$table) {
                throw new \Exception('Sport invalide');
            }
            
            $team = $table->get($teamId);
            $team->status = $newStatus;
            
            if ($table->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Statut mis à jour avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * View detailed team information
     */
    public function viewTeam($sport = null, $id = null)
    {
        if (!$sport || !$id) {
            $this->Flash->error('Paramètres manquants');
            return $this->redirect(['action' => 'teams']);
        }
        
        try {
            $table = $this->getSportTable($sport);
            if (!$table) {
                throw new \Exception('Sport invalide');
            }
            
            // Get team with all related data
            $team = $table->get($id, [
                'contain' => ['Users']
            ]);
            
            // Get players based on sport
            $players = [];
            switch (strtolower($sport)) {
                case 'football':
                    $joueurs = $this->fetchTable('Joueurs')->find()
                        ->where(['team_id' => $id])
                        ->all();
                    $players = $joueurs->toArray();
                    break;
                case 'basketball':
                    $players = $this->fetchTable('BasketballTeamsJoueurs')->find()
                        ->where(['basketball_team_id' => $id])
                        ->all()->toArray();
                    break;
                case 'handball':
                    $players = $this->fetchTable('HandballTeamsJoueurs')->find()
                        ->where(['handball_team_id' => $id])
                        ->all()->toArray();
                    break;
                case 'volleyball':
                    $players = $this->fetchTable('VolleyballTeamsJoueurs')->find()
                        ->where(['volleyball_team_id' => $id])
                        ->all()->toArray();
                    break;
                case 'beachvolley':
                    $players = $this->fetchTable('BeachvolleyTeamsJoueurs')->find()
                        ->where(['beachvolley_team_id' => $id])
                        ->all()->toArray();
                    break;
            }
            
            // Get sport-specific category and district names
            // First, try to use direct values from team object
            $categoryName = $team->categorie ?? '';
            $districtName = $team->district ?? '';
            $organisationName = $team->organisation ?? '';
            
            // If no direct values, try to get from reference tables
            if (empty($categoryName) || empty($districtName) || empty($organisationName)) {
                try {
                    switch (strtolower($sport)) {
                        case 'football':
                            if (empty($categoryName) && !empty($team->football_category_id)) {
                                $category = $this->fetchTable('FootballCategories')->get($team->football_category_id);
                                $categoryName = $category->name ?? '';
                            }
                            if (empty($districtName) && !empty($team->football_district_id)) {
                                $district = $this->fetchTable('FootballDistricts')->get($team->football_district_id);
                                $districtName = $district->name ?? '';
                            }
                            if (empty($organisationName) && !empty($team->football_organisation_id)) {
                                $organisation = $this->fetchTable('FootballOrganisations')->get($team->football_organisation_id);
                                $organisationName = $organisation->name ?? '';
                            }
                            break;
                        case 'basketball':
                            if (empty($categoryName) && !empty($team->basketball_category_id)) {
                                $category = $this->fetchTable('BasketballCategories')->get($team->basketball_category_id);
                                $categoryName = $category->name ?? '';
                            }
                            break;
                        case 'handball':
                            if (empty($categoryName) && !empty($team->handball_category_id)) {
                                $category = $this->fetchTable('HandballCategories')->get($team->handball_category_id);
                                $categoryName = $category->name ?? '';
                            }
                            break;
                        case 'volleyball':
                            if (empty($categoryName) && !empty($team->volleyball_category_id)) {
                                $category = $this->fetchTable('VolleyballCategories')->get($team->volleyball_category_id);
                                $categoryName = $category->name ?? '';
                            }
                            break;
                        case 'beachvolley':
                            if (empty($categoryName) && !empty($team->beachvolley_category_id)) {
                                $category = $this->fetchTable('BeachvolleyCategories')->get($team->beachvolley_category_id);
                                $categoryName = $category->name ?? '';
                            }
                            break;
                    }
                } catch (\Exception $e) {
                    // If category/district lookup fails, continue with empty names
                }
            }
            
            $this->set(compact('team', 'sport', 'players', 'categoryName', 'districtName', 'organisationName'));
            
        } catch (\Exception $e) {
            $this->Flash->error('Équipe non trouvée: ' . $e->getMessage());
            return $this->redirect(['action' => 'teams']);
        }
    }
    
    /**
     * Helper method to get the appropriate table for a sport
     */
    private function getSportTable($sport)
    {
        switch (strtolower($sport)) {
            case 'football':
                return $this->fetchTable('Teams');
            case 'basketball':
                return $this->fetchTable('BasketballTeams');
            case 'handball':
                return $this->fetchTable('HandballTeams');
            case 'volleyball':
                return $this->fetchTable('VolleyballTeams');
            case 'beach volleyball':
            case 'beachvolley':
                return $this->fetchTable('BeachvolleyTeams');
            default:
                return null;
        }
    }
    
    /**
     * Get the table name for a specific sport for ORDER BY clauses
     */
    private function getSportTableName($sport)
    {
        switch (strtolower($sport)) {
            case 'football':
                return 'Teams';
            case 'basketball':
                return 'BasketballTeams';
            case 'handball':
                return 'HandballTeams';
            case 'volleyball':
                return 'VolleyballTeams';
            case 'beach volleyball':
            case 'beachvolley':
                return 'BeachvolleyTeams';
            default:
                return 'Teams';
        }
    }
    
    /**
     * Users management - REBUILT FROM SCRATCH
     */
    public function users()
    {
        try {
            // Load Users table
            $usersTable = $this->fetchTable('Users');
            
            // Get all users with simple query
            $users = $usersTable->find('all')
                ->order(['created' => 'DESC'])
                ->toArray();
            
            // Success message
            $this->Flash->success('Chargement réussi de ' . count($users) . ' utilisateurs');
            
            // Set data for view
            $this->set([
                'users' => $users,
                'title' => 'Gestion des Utilisateurs'
            ]);
            
        } catch (\Exception $e) {
            // Error handling
            $this->Flash->error('Erreur: ' . $e->getMessage());
            $this->set([
                'users' => [],
                'title' => 'Gestion des Utilisateurs - Erreur'
            ]);
        }
    }
    
    /**
     * Export users to CSV
     */
    public function exportUsers()
    {
        $this->Flash->info('Fonctionnalité d\'export en cours de développement');
        return $this->redirect(['action' => 'users']);
    }
    
    /**
     * Save verification notes for a team
     */
    public function saveVerificationNotes()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        // Debug logging
        error_log('saveVerificationNotes called');
        error_log('Request data: ' . json_encode($this->request->getData()));
        error_log('Headers: ' . json_encode($this->request->getHeaders()));
        
        $teamId = $this->request->getData('team_id');
        $sport = $this->request->getData('sport');
        $notes = $this->request->getData('notes');
        
        error_log("Team ID: $teamId, Sport: $sport, Notes length: " . strlen($notes ?? ''));
        
        if (empty($teamId) || empty($sport)) {
            $response = [
                'success' => false,
                'message' => 'Données manquantes: team_id ou sport'
            ];
            error_log('Missing data - team_id: ' . var_export($teamId, true) . ', sport: ' . var_export($sport, true));
        } else {
            try {
                $table = $this->getSportTable($sport);
                if (!$table) {
                    throw new \Exception("Sport invalide: $sport");
                }
                
                error_log('Table fetched successfully: ' . get_class($table));
                
                $team = $table->get($teamId);
                error_log('Team found: ' . json_encode($team->toArray()));
                
                $team->verification_notes = $notes;
                
                if ($table->save($team)) {
                    $response = [
                        'success' => true,
                        'message' => 'Notes sauvegardées avec succès'
                    ];
                    error_log('Team saved successfully');
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Erreur lors de la sauvegarde',
                        'validation_errors' => $team->getErrors()
                    ];
                    error_log('Save failed: ' . json_encode($team->getErrors()));
                }
                
            } catch (\Exception $e) {
                $response = [
                    'success' => false,
                    'message' => 'Erreur: ' . $e->getMessage()
                ];
                error_log('Exception in saveVerificationNotes: ' . $e->getMessage());
                error_log('Exception trace: ' . $e->getTraceAsString());
            }
        }
        
        error_log('Final response: ' . json_encode($response));
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    // ===== SPORT-SPECIFIC STATUS UPDATE METHODS =====
    
    /**
     * Update football team status
     */
    public function updateFootballTeamStatus()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        // Debug logging
        error_log('updateFootballTeamStatus called');
        error_log('Request data: ' . json_encode($this->request->getData()));
        error_log('Headers: ' . json_encode($this->request->getHeaders()));
        
        $teamId = $this->request->getData('team_id');
        $newStatus = $this->request->getData('status');
        
        error_log("Team ID: $teamId, New Status: $newStatus");
        
        if (empty($teamId) || empty($newStatus)) {
            $response = [
                'success' => false,
                'message' => 'Données manquantes: team_id ou status'
            ];
            $this->set(compact('response'));
            $this->viewBuilder()->setOption('serialize', 'response');
            return;
        }
        
        try {
            $teamsTable = $this->fetchTable('Teams');
            $team = $teamsTable->get($teamId);
            
            error_log('Team found: ' . json_encode($team->toArray()));
            
            $team->status = $newStatus;
            
            if ($teamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Statut de l\'équipe de football mis à jour avec succès'
                ];
                error_log('Team status updated successfully');
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du statut',
                    'validation_errors' => $team->getErrors()
                ];
                error_log('Save failed: ' . json_encode($team->getErrors()));
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
            error_log('Exception: ' . $e->getMessage());
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * Update basketball team status
     */
    public function updateBasketballTeamStatus()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $newStatus = $this->request->getData('status');
        
        try {
            $basketballTeamsTable = $this->fetchTable('BasketballTeams');
            $team = $basketballTeamsTable->get($teamId);
            $team->status = $newStatus;
            
            if ($basketballTeamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Statut de l\'équipe de basketball mis à jour avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du statut'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * Update handball team status
     */
    public function updateHandballTeamStatus()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $newStatus = $this->request->getData('status');
        
        try {
            $handballTeamsTable = $this->fetchTable('HandballTeams');
            $team = $handballTeamsTable->get($teamId);
            $team->status = $newStatus;
            
            if ($handballTeamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Statut de l\'équipe de handball mis à jour avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du statut'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * Update volleyball team status
     */
    public function updateVolleyballTeamStatus()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $newStatus = $this->request->getData('status');
        
        try {
            $volleyballTeamsTable = $this->fetchTable('VolleyballTeams');
            $team = $volleyballTeamsTable->get($teamId);
            $team->status = $newStatus;
            
            if ($volleyballTeamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Statut de l\'équipe de volleyball mis à jour avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du statut'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * Update beachvolley team status
     */
    public function updateBeachvolleyTeamStatus()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $newStatus = $this->request->getData('status');
        
        try {
            $beachvolleyTeamsTable = $this->fetchTable('BeachvolleyTeams');
            $team = $beachvolleyTeamsTable->get($teamId);
            $team->status = $newStatus;
            
            if ($beachvolleyTeamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Statut de l\'équipe de beach volleyball mis à jour avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du statut'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    // ===== SPORT-SPECIFIC VERIFICATION NOTES METHODS =====
    
    /**
     * Save football team verification notes
     */
    public function saveFootballVerificationNotes()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        // Debug logging
        error_log('saveFootballVerificationNotes called');
        error_log('Request URI: ' . $this->request->getUri()->getPath());
        error_log('Request data: ' . json_encode($this->request->getData()));
        error_log('Request headers: ' . json_encode($this->request->getHeaders()));
        
        
        $teamId = $this->request->getData('team_id');
        $notes = $this->request->getData('notes');
        
        error_log("Football Team ID: $teamId, Notes length: " . strlen($notes ?? ''));
        
        if (empty($teamId)) {
            $response = [
                'success' => false,
                'message' => 'ID d\'équipe manquant'
            ];
            error_log('Missing team_id for football notes');
        } else {
            try {
                // Football teams are stored in the Teams table
                $teamsTable = $this->fetchTable('Teams');
                $team = $teamsTable->get($teamId);
                
                error_log('Football team found: ' . json_encode($team->toArray()));
                
                $team->verification_notes = $notes;
                
                if ($teamsTable->save($team)) {
                    $response = [
                        'success' => true,
                        'message' => 'Notes de vérification sauvegardées avec succès'
                    ];
                    error_log('Football team notes saved successfully');
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Erreur lors de la sauvegarde des notes',
                        'validation_errors' => $team->getErrors()
                    ];
                    error_log('Football team notes save failed: ' . json_encode($team->getErrors()));
                }
                
            } catch (\Exception $e) {
                $response = [
                    'success' => false,
                    'message' => 'Erreur: ' . $e->getMessage()
                ];
                error_log('Exception in saveFootballVerificationNotes: ' . $e->getMessage());
                error_log('Exception trace: ' . $e->getTraceAsString());
            }
        }
        
        error_log('Football notes final response: ' . json_encode($response));
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    
    /**
     * Save basketball team verification notes
     */
    public function saveBasketballVerificationNotes()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $notes = $this->request->getData('notes');
        
        try {
            $basketballTeamsTable = $this->fetchTable('BasketballTeams');
            $team = $basketballTeamsTable->get($teamId);
            $team->verification_notes = $notes;
            
            if ($basketballTeamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Notes de vérification basketball sauvegardées avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la sauvegarde des notes'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * Save handball team verification notes
     */
    public function saveHandballVerificationNotes()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $notes = $this->request->getData('notes');
        
        try {
            $handballTeamsTable = $this->fetchTable('HandballTeams');
            $team = $handballTeamsTable->get($teamId);
            $team->verification_notes = $notes;
            
            if ($handballTeamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Notes de vérification handball sauvegardées avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la sauvegarde des notes'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * Save volleyball team verification notes
     */
    public function saveVolleyballVerificationNotes()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $notes = $this->request->getData('notes');
        
        try {
            $volleyballTeamsTable = $this->fetchTable('VolleyballTeams');
            $team = $volleyballTeamsTable->get($teamId);
            $team->verification_notes = $notes;
            
            if ($volleyballTeamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Notes de vérification volleyball sauvegardées avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la sauvegarde des notes'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * Save beachvolley team verification notes
     */
    public function saveBeachvolleyVerificationNotes()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');
        
        $teamId = $this->request->getData('team_id');
        $notes = $this->request->getData('notes');
        
        try {
            $beachvolleyTeamsTable = $this->fetchTable('BeachvolleyTeams');
            $team = $beachvolleyTeamsTable->get($teamId);
            $team->verification_notes = $notes;
            
            if ($beachvolleyTeamsTable->save($team)) {
                $response = [
                    'success' => true,
                    'message' => 'Notes de vérification beach volleyball sauvegardées avec succès'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Erreur lors de la sauvegarde des notes'
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
        
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', 'response');
    }
    
    /**
     * Show detailed team information
     */
    public function teamDetails($sport = null, $id = null)
    {
        // Get parameters from URL if not passed directly
        if (!$sport || !$id) {
            $passedArgs = $this->request->getParam('pass');
            $sport = $passedArgs[0] ?? null;
            $id = $passedArgs[1] ?? null;
        }
        
        // Debug logging
        \Cake\Log\Log::debug('teamDetails called with sport: ' . $sport . ', id: ' . $id);
        
        if (!$sport || !$id) {
            $this->Flash->error('Paramètres manquants');
            return $this->redirect(['action' => 'teams']);
        }
        
        try {
            $table = $this->getSportTable($sport);
            if (!$table) {
                throw new \Exception('Sport invalide');
            }
            
            // Get team with user data
            $team = $table->get($id, [
                'contain' => ['Users']
            ]);
            
            // Get players based on sport
            $players = [];
            switch (strtolower($sport)) {
                case 'football':
                    $players = $this->fetchTable('Joueurs')->find()
                        ->where(['team_id' => $id])
                        ->all()->toArray();
                    
                    // Debug logging
                    error_log('Football players query - team_id: ' . $id);
                    error_log('Football players found: ' . count($players));
                    if (!empty($players)) {
                        error_log('First player data: ' . json_encode($players[0]));
                    }
                    break;
                case 'basketball':
                    $players = $this->fetchTable('BasketballTeamsJoueurs')->find()
                        ->where(['basketball_team_id' => $id])
                        ->all()->toArray();
                    break;
                case 'handball':
                    $players = $this->fetchTable('HandballTeamsJoueurs')->find()
                        ->where(['handball_team_id' => $id])
                        ->all()->toArray();
                    break;
                case 'volleyball':
                    $players = $this->fetchTable('VolleyballTeamsJoueurs')->find()
                        ->where(['volleyball_team_id' => $id])
                        ->all()->toArray();
                    break;
                case 'beachvolley':
                    $players = $this->fetchTable('BeachvolleyTeamsJoueurs')->find()
                        ->where(['beachvolley_team_id' => $id])
                        ->all()->toArray();
                    break;
            }
            
            // Get sport-specific category and district names
            $categoryName = '';
            $districtName = '';
            $organisationName = '';
            
            try {
                switch (strtolower($sport)) {
                    case 'football':
                        if (!empty($team->football_category_id)) {
                            $category = $this->fetchTable('FootballCategories')->get($team->football_category_id);
                            $categoryName = $category->name ?? '';
                        }
                        if (!empty($team->football_district_id)) {
                            $district = $this->fetchTable('FootballDistricts')->get($team->football_district_id);
                            $districtName = $district->name ?? '';
                        }
                        if (!empty($team->football_organisation_id)) {
                            $organisation = $this->fetchTable('FootballOrganisations')->get($team->football_organisation_id);
                            $organisationName = $organisation->name ?? '';
                        }
                        break;
                    case 'basketball':
                        if (!empty($team->basketball_category_id)) {
                            $category = $this->fetchTable('BasketballCategories')->get($team->basketball_category_id);
                            $categoryName = $category->name ?? '';
                        }
                        break;
                    case 'handball':
                        if (!empty($team->handball_category_id)) {
                            $category = $this->fetchTable('HandballCategories')->get($team->handball_category_id);
                            $categoryName = $category->name ?? '';
                        }
                        break;
                    case 'volleyball':
                        if (!empty($team->volleyball_category_id)) {
                            $category = $this->fetchTable('VolleyballCategories')->get($team->volleyball_category_id);
                            $categoryName = $category->name ?? '';
                        }
                        break;
                    case 'beachvolley':
                        if (!empty($team->beachvolley_category_id)) {
                            $category = $this->fetchTable('BeachvolleyCategories')->get($team->beachvolley_category_id);
                            $categoryName = $category->name ?? '';
                        }
                        break;
                }
            } catch (\Exception $e) {
                // Continue with empty names if lookup fails
            }
            
            $this->set(compact('team', 'sport', 'players', 'categoryName', 'districtName', 'organisationName'));
            
        } catch (\Exception $e) {
            $this->Flash->error('Équipe non trouvée: ' . $e->getMessage());
            return $this->redirect(['action' => 'teams']);
        }
    }
    
    /**
     * View user details
     */
    public function userView($id)
    {
        $this->Flash->info('Page de détails utilisateur en cours de développement');
        return $this->redirect(['action' => 'users']);
    }
    
    /**
     * Make user admin
     */
    public function makeAdmin($id)
    {
        $this->request->allowMethod(['post']);
        
        try {
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->get($id);
            
            $user->is_admin = true;
            
            if ($usersTable->save($user)) {
                $this->Flash->success('Utilisateur promu administrateur avec succès');
            } else {
                $this->Flash->error('Erreur lors de la promotion');
            }
        } catch (\Exception $e) {
            $this->Flash->error('Erreur: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'users']);
    }
    
    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $this->request->allowMethod(['post']);
        
        try {
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->get($id);
            
            if ($usersTable->delete($user)) {
                $this->Flash->success('Utilisateur supprimé avec succès');
            } else {
                $this->Flash->error('Erreur lors de la suppression');
            }
        } catch (\Exception $e) {
            $this->Flash->error('Erreur: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'users']);
    }
    
    /**
     * Sports Urbains management
     */
    public function sportsurbains()
    {
        try {
            $sportsurbainsTable = $this->fetchTable('SportsurbainsParticipants');
            
            // Get filter parameters
            $search = $this->request->getQuery('search');
            $statusFilter = $this->request->getQuery('status');
            $typeFilter = $this->request->getQuery('type');
            $categoryFilter = $this->request->getQuery('category');
            
            // Build query
            $query = $sportsurbainsTable->find()
                ->contain(['Users', 'SportsurbainsCategories'])
                ->order(['SportsurbainsParticipants.created' => 'DESC']);
            
            // Apply filters
            if ($search) {
                $query->where([
                    'OR' => [
                        'SportsurbainsParticipants.nom_complet LIKE' => '%' . $search . '%',
                        'SportsurbainsParticipants.cin LIKE' => '%' . $search . '%',
                        'SportsurbainsParticipants.reference_inscription LIKE' => '%' . $search . '%',
                        'Users.email LIKE' => '%' . $search . '%'
                    ]
                ]);
            }
            
            if ($statusFilter) {
                $query->where(['SportsurbainsParticipants.status' => $statusFilter]);
            }
            
            if ($typeFilter) {
                $query->where(['SportsurbainsParticipants.type_sport' => $typeFilter]);
            }
            
            if ($categoryFilter) {
                $query->where(['SportsurbainsParticipants.category_id' => $categoryFilter]);
            }
            
            $participants = $this->paginate($query, ['limit' => 20]);
            
            // Get stats
            $stats = [
                'total' => $sportsurbainsTable->find()->count(),
                'pending' => $sportsurbainsTable->find()->where(['status' => 'pending'])->count(),
                'verified' => $sportsurbainsTable->find()->where(['status' => 'verified'])->count(),
                'rejected' => $sportsurbainsTable->find()->where(['status' => 'rejected'])->count()
            ];
            
            // Get categories for filter
            $categoriesTable = $this->fetchTable('SportsurbainsCategories');
            $categories = $categoriesTable->find('list', [
                'keyField' => 'id',
                'valueField' => function ($category) {
                    return $category->gender . ' - ' . $category->age_category;
                }
            ])->where(['active' => true])->toArray();
            
            // Get sport types from model
            $sportTypes = \App\Model\Table\SportsurbainsParticipantsTable::getSportTypes();
            
            $this->set(compact('participants', 'stats', 'categories', 'sportTypes'));
        } catch (\Exception $e) {
            $this->Flash->error('Erreur lors du chargement des participants Sports Urbains');
            $this->set('participants', []);
            $this->set('stats', ['total' => 0, 'pending' => 0, 'verified' => 0, 'rejected' => 0]);
            $this->set('categories', []);
            $this->set('sportTypes', []);
        }
    }
    
    /**
     * Concours management
     */
    public function concours()
    {
        try {
            $concoursTable = $this->fetchTable('ConcoursParticipants');
            
            // Get filter parameters
            $search = $this->request->getQuery('search');
            $statusFilter = $this->request->getQuery('status');
            $typeFilter = $this->request->getQuery('type');
            $categoryFilter = $this->request->getQuery('category');
            
            // Build query
            $query = $concoursTable->find()
                ->contain(['Users', 'ConcoursCategories'])
                ->order(['ConcoursParticipants.created' => 'DESC']);
            
            // Apply filters
            if ($search) {
                $query->where([
                    'OR' => [
                        'ConcoursParticipants.nom_complet LIKE' => '%' . $search . '%',
                        'ConcoursParticipants.cin LIKE' => '%' . $search . '%',
                        'ConcoursParticipants.reference_inscription LIKE' => '%' . $search . '%',
                        'Users.email LIKE' => '%' . $search . '%'
                    ]
                ]);
            }
            
            if ($statusFilter) {
                $query->where(['ConcoursParticipants.status' => $statusFilter]);
            }
            
            if ($typeFilter) {
                $query->where(['ConcoursParticipants.type_concours' => $typeFilter]);
            }
            
            if ($categoryFilter) {
                $query->where(['ConcoursParticipants.category_id' => $categoryFilter]);
            }
            
            $participants = $this->paginate($query, ['limit' => 20]);
            
            // Get stats
            $stats = [
                'total' => $concoursTable->find()->count(),
                'pending' => $concoursTable->find()->where(['status' => 'pending'])->count(),
                'verified' => $concoursTable->find()->where(['status' => 'verified'])->count(),
                'rejected' => $concoursTable->find()->where(['status' => 'rejected'])->count(),
                'types' => [
                    'Dessin' => $concoursTable->find()->where(['type_concours' => 'Dessin'])->count(),
                    'Chanson' => $concoursTable->find()->where(['type_concours' => 'Chanson'])->count(),
                    'Commentateur sportif' => $concoursTable->find()->where(['type_concours' => 'Commentateur sportif'])->count(),
                    'Film documentaire' => $concoursTable->find()->where(['type_concours' => 'Film documentaire'])->count()
                ]
            ];
            
            // Get categories for filter
            $categoriesTable = $this->fetchTable('ConcoursCategories');
            $categories = $categoriesTable->find('list', [
                'keyField' => 'id',
                'valueField' => function ($category) {
                    return $category->gender . ' - ' . $category->age_category;
                }
            ])->where(['active' => true])->toArray();
            
            // Get contest types from model
            $concoursTypes = \App\Model\Table\ConcoursParticipantsTable::getConcoursTypes();
            
            $this->set(compact('participants', 'stats', 'categories', 'concoursTypes'));
        } catch (\Exception $e) {
            $this->Flash->error('Erreur lors du chargement des participants Concours');
            $this->set('participants', []);
            $this->set('stats', ['total' => 0, 'pending' => 0, 'verified' => 0, 'rejected' => 0, 'types' => []]);
            $this->set('categories', []);
            $this->set('concoursTypes', []);
        }
    }
    
    /**
     * Update Sports Urbains participant status
     */
    public function updateSportsurbainsStatus()
    {
        $this->request->allowMethod(['post', 'ajax']);
        
        $id = $this->request->getData('id');
        $status = $this->request->getData('status');
        
        try {
            $participantsTable = $this->fetchTable('SportsurbainsParticipants');
            $participant = $participantsTable->get($id);
            
            $participant->status = $status;
            if ($status === 'verified') {
                $participant->verified_at = new \DateTime();
            }
            
            if ($participantsTable->save($participant)) {
                $this->set(['success' => true, 'message' => 'Statut mis à jour']);
            } else {
                $this->set(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
            }
        } catch (\Exception $e) {
            $this->set(['success' => false, 'message' => $e->getMessage()]);
        }
        
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }
    
    /**
     * View Sports Urbains participant details
     */
    public function viewSportsurbainsParticipant($id = null)
    {
        try {
            $participantsTable = $this->fetchTable('SportsurbainsParticipants');
            $participant = $participantsTable->get($id, [
                'contain' => ['Users', 'SportsurbainsCategories']
            ]);
            
            $this->set(compact('participant'));
            $this->viewBuilder()->setLayout('ajax');
        } catch (\Exception $e) {
            $this->Flash->error('Participant introuvable');
            return $this->redirect(['action' => 'sportsurbains']);
        }
    }
    
    /**
     * Update Concours participant status
     */
    public function updateConcoursStatus()
    {
        $this->request->allowMethod(['post', 'ajax']);
        
        $id = $this->request->getData('id');
        $status = $this->request->getData('status');
        
        try {
            $participantsTable = $this->fetchTable('ConcoursParticipants');
            $participant = $participantsTable->get($id);
            
            $participant->status = $status;
            if ($status === 'verified') {
                $participant->verified_at = new \DateTime();
            }
            
            if ($participantsTable->save($participant)) {
                $this->set(['success' => true, 'message' => 'Statut mis à jour']);
            } else {
                $this->set(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
            }
        } catch (\Exception $e) {
            $this->set(['success' => false, 'message' => $e->getMessage()]);
        }
        
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }
    
    /**
     * View Concours participant details
     */
    public function viewConcoursParticipant($id = null)
    {
        try {
            $participantsTable = $this->fetchTable('ConcoursParticipants');
            $participant = $participantsTable->get($id, [
                'contain' => ['Users', 'ConcoursCategories']
            ]);
            
            $this->set(compact('participant'));
            $this->viewBuilder()->setLayout('ajax');
        } catch (\Exception $e) {
            $this->Flash->error('Participant introuvable');
            return $this->redirect(['action' => 'concours']);
        }
    }
    
    /**
     * View Sports Urbains participant full details
     */
    public function viewSportsurbainsFullParticipant($id = null)
    {
        try {
            $participantsTable = $this->fetchTable('SportsurbainsParticipants');
            $participant = $participantsTable->get($id, [
                'contain' => ['Users', 'SportsurbainsCategories']
            ]);
            
            $this->set(compact('participant'));
            $this->render('view_sportsurbains_full');
        } catch (\Exception $e) {
            $this->Flash->error('Participant introuvable');
            return $this->redirect(['action' => 'sportsurbains']);
        }
    }
    
    /**
     * View Concours participant full details
     */
    public function viewConcoursFullParticipant($id = null)
    {
        try {
            $participantsTable = $this->fetchTable('ConcoursParticipants');
            $participant = $participantsTable->get($id, [
                'contain' => ['Users', 'ConcoursCategories']
            ]);
            
            $this->set(compact('participant'));
            $this->render('view_concours_full');
        } catch (\Exception $e) {
            $this->Flash->error('Participant introuvable');
            return $this->redirect(['action' => 'concours']);
        }
    }
    
    /**
     * Verify Sports Urbains participant
     */
    public function verifySportsurbains($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        
        $participant = $this->fetchTable('SportsurbainsParticipants')->get($id);
        
        $data = [
            'status' => 'verified',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $this->Authentication->getIdentity()->id,
            'verification_notes' => $this->request->getData('verification_notes')
        ];
        
        $participant = $this->fetchTable('SportsurbainsParticipants')->patchEntity($participant, $data);
        
        if ($this->fetchTable('SportsurbainsParticipants')->save($participant)) {
            $this->Flash->success(__('Le participant a été vérifié avec succès.'));
        } else {
            $this->Flash->error(__('Impossible de vérifier le participant.'));
        }
        
        return $this->redirect(['action' => 'viewSportsurbainsFullParticipant', $id]);
    }
    
    /**
     * Reject Sports Urbains participant
     */
    public function rejectSportsurbains($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        
        $participant = $this->fetchTable('SportsurbainsParticipants')->get($id);
        
        $data = [
            'status' => 'rejected',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $this->Authentication->getIdentity()->id,
            'verification_notes' => $this->request->getData('verification_notes')
        ];
        
        $participant = $this->fetchTable('SportsurbainsParticipants')->patchEntity($participant, $data);
        
        if ($this->fetchTable('SportsurbainsParticipants')->save($participant)) {
            $this->Flash->error(__('Le participant a été rejeté.'));
        } else {
            $this->Flash->error(__('Impossible de rejeter le participant.'));
        }
        
        return $this->redirect(['action' => 'viewSportsurbainsFullParticipant', $id]);
    }
    
    /**
     * Verify Concours participant
     */
    public function verifyConcours($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        
        $participant = $this->fetchTable('ConcoursParticipants')->get($id);
        
        $data = [
            'status' => 'verified',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $this->Authentication->getIdentity()->id,
            'verification_notes' => $this->request->getData('verification_notes')
        ];
        
        $participant = $this->fetchTable('ConcoursParticipants')->patchEntity($participant, $data);
        
        if ($this->fetchTable('ConcoursParticipants')->save($participant)) {
            $this->Flash->success(__('Le participant a été vérifié avec succès.'));
        } else {
            $this->Flash->error(__('Impossible de vérifier le participant.'));
        }
        
        return $this->redirect(['action' => 'viewConcoursFullParticipant', $id]);
    }
    
    /**
     * Reject Concours participant
     */
    public function rejectConcours($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        
        $participant = $this->fetchTable('ConcoursParticipants')->get($id);
        
        $data = [
            'status' => 'rejected',
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $this->Authentication->getIdentity()->id,
            'verification_notes' => $this->request->getData('verification_notes')
        ];
        
        $participant = $this->fetchTable('ConcoursParticipants')->patchEntity($participant, $data);
        
        if ($this->fetchTable('ConcoursParticipants')->save($participant)) {
            $this->Flash->error(__('Le participant a été rejeté.'));
        } else {
            $this->Flash->error(__('Impossible de rejeter le participant.'));
        }
        
        return $this->redirect(['action' => 'viewConcoursFullParticipant', $id]);
    }
    
    /**
     * Export section - Display export options
     */
    public function export()
    {
        // Get counts for each sport/discipline
        $counts = [
            'football' => $this->fetchTable('Teams')->find()->count(),
            'basketball' => $this->fetchTable('BasketballTeams')->find()->count(),
            'handball' => $this->fetchTable('HandballTeams')->find()->count(),
            'volleyball' => $this->fetchTable('VolleyballTeams')->find()->count(),
            'beachvolley' => $this->fetchTable('BeachvolleyTeams')->find()->count(),
            'crosstraining' => $this->fetchTable('CrosstrainingParticipants')->find()->count(),
            'sportsurbains' => $this->fetchTable('SportsurbainsParticipants')->find()->count(),
            'concours' => $this->fetchTable('ConcoursParticipants')->find()->count()
        ];
        
        $this->set(compact('counts'));
    }
    
    /**
     * Export Football teams to Excel
     */
    public function exportFootball()
    {
        $teams = $this->fetchTable('Teams')->find()
            ->contain(['Users', 'Joueurs'])
            ->all();
        
        // Enhanced export with team and player details
        return $this->exportTeamsWithPlayersToExcel($teams, 'football', 'football_teams_detailed');
    }
    
    /**
     * Export Basketball teams to Excel
     */
    public function exportBasketball()
    {
        $teams = $this->fetchTable('BasketballTeams')->find()
            ->contain(['Users', 'BasketballTeamsJoueurs'])
            ->all();
        
        return $this->exportTeamsWithPlayersToExcel($teams, 'basketball', 'basketball_teams_detailed');
    }
    
    /**
     * Export Handball teams to Excel
     */
    public function exportHandball()
    {
        $teams = $this->fetchTable('HandballTeams')->find()
            ->contain(['Users', 'HandballTeamsJoueurs'])
            ->all();
        
        return $this->exportTeamsWithPlayersToExcel($teams, 'handball', 'handball_teams_detailed');
    }
    
    /**
     * Export Volleyball teams to Excel
     */
    public function exportVolleyball()
    {
        $teams = $this->fetchTable('VolleyballTeams')->find()
            ->contain(['Users', 'VolleyballTeamsJoueurs'])
            ->all();
        
        return $this->exportTeamsWithPlayersToExcel($teams, 'volleyball', 'volleyball_teams_detailed');
    }
    
    /**
     * Export Beachvolley teams to Excel
     */
    public function exportBeachvolley()
    {
        $teams = $this->fetchTable('BeachvolleyTeams')->find()
            ->contain(['Users', 'BeachvolleyTeamsJoueurs'])
            ->all();
        
        return $this->exportTeamsWithPlayersToExcel($teams, 'beachvolley', 'beachvolley_teams_detailed');
    }
    
    /**
     * Export CrossTraining participants to Excel
     */
    public function exportCrosstraining()
    {
        $participants = $this->fetchTable('CrosstrainingParticipants')->find()
            ->contain(['Users', 'CrosstrainingCategories'])
            ->all();
        
        return $this->exportToExcel($participants, 'crosstraining_participants', [
            'reference_inscription' => 'Référence',
            'nom_complet' => 'Nom complet',
            'date_naissance' => 'Date de naissance',
            'gender' => 'Genre',
            'cin' => 'CIN',
            'telephone' => 'Téléphone',
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
            'category_name' => 'Catégorie',
            'taille_tshirt' => 'Taille T-shirt',
            'status' => 'Statut',
            'created' => 'Date d\'inscription'
        ]);
    }
    
    /**
     * Export Sports Urbains participants to Excel
     */
    public function exportSportsurbains()
    {
        $participants = $this->fetchTable('SportsurbainsParticipants')->find()
            ->contain(['Users', 'SportsurbainsCategories'])
            ->all();
        
        return $this->exportToExcel($participants, 'sportsurbains_participants', [
            'reference_inscription' => 'Référence',
            'nom_complet' => 'Nom complet',
            'type_sport' => 'Type de sport',
            'date_naissance' => 'Date de naissance',
            'gender' => 'Genre',
            'cin' => 'CIN',
            'telephone' => 'Téléphone',
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
            'category_name' => 'Catégorie',
            'taille_tshirt' => 'Taille T-shirt',
            'status' => 'Statut',
            'created' => 'Date d\'inscription'
        ]);
    }
    
    /**
     * Export Concours participants to Excel
     */
    public function exportConcours()
    {
        $participants = $this->fetchTable('ConcoursParticipants')->find()
            ->contain(['Users', 'ConcoursCategories'])
            ->all();
        
        return $this->exportToExcel($participants, 'concours_participants', [
            'reference_inscription' => 'Référence',
            'nom_complet' => 'Nom complet',
            'type_concours' => 'Type de concours',
            'date_naissance' => 'Date de naissance',
            'gender' => 'Genre',
            'cin' => 'CIN',
            'telephone' => 'Téléphone',
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
            'category_name' => 'Catégorie',
            'taille_tshirt' => 'Taille T-shirt',
            'status' => 'Statut',
            'created' => 'Date d\'inscription',
            'description_projet' => 'Description du projet'
        ]);
    }
    
    /**
     * Helper method to export data to Excel
     */
    private function exportToExcel($data, $filename, $columns)
    {
        // Disable view rendering
        $this->autoRender = false;
        
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Start output
        echo '<html>';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body>';
        echo '<table border="1">';
        
        // Header row
        echo '<tr>';
        foreach ($columns as $header) {
            echo '<th>' . h($header) . '</th>';
        }
        echo '</tr>';
        
        // Data rows
        foreach ($data as $row) {
            echo '<tr>';
            foreach (array_keys($columns) as $field) {
                $value = '';
                
                // Special handling for different fields
                switch ($field) {
                    case 'created':
                    case 'date_naissance':
                        if (isset($row->$field)) {
                            $value = $row->$field->format('d/m/Y');
                        }
                        break;
                    case 'joueurs_count':
                        if (isset($row->joueurs)) {
                            $value = count($row->joueurs);
                        } elseif (isset($row->basketball_teams_joueurs)) {
                            $value = count($row->basketball_teams_joueurs);
                        } elseif (isset($row->handball_teams_joueurs)) {
                            $value = count($row->handball_teams_joueurs);
                        } elseif (isset($row->volleyball_teams_joueurs)) {
                            $value = count($row->volleyball_teams_joueurs);
                        } elseif (isset($row->beachvolley_teams_joueurs)) {
                            $value = count($row->beachvolley_teams_joueurs);
                        }
                        break;
                    case 'category_name':
                        if (isset($row->crosstraining_category)) {
                            $value = $row->crosstraining_category->gender . ' - ' . $row->crosstraining_category->age_category;
                        } elseif (isset($row->sportsurbains_category)) {
                            $value = $row->sportsurbains_category->gender . ' - ' . $row->sportsurbains_category->age_category;
                        } elseif (isset($row->concours_category)) {
                            $value = $row->concours_category->gender . ' - ' . $row->concours_category->age_category;
                        }
                        break;
                    case 'status':
                        $value = $row->$field ?? 'pending';
                        switch ($value) {
                            case 'verified':
                                $value = 'Vérifié';
                                break;
                            case 'rejected':
                                $value = 'Rejeté';
                                break;
                            case 'pending':
                                $value = 'En attente';
                                break;
                        }
                        break;
                    default:
                        if (isset($row->$field)) {
                            $value = $row->$field;
                        }
                }
                
                echo '<td>' . h($value) . '</td>';
            }
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</body>';
        echo '</html>';
        
        exit;
    }
    
    /**
     * Helper method to export teams with players details to Excel
     */
    private function exportTeamsWithPlayersToExcel($teams, $sport, $filename)
    {
        // Disable view rendering
        $this->autoRender = false;
        
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Start output
        echo '<html>';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body>';
        
        // Teams summary
        echo '<h2>Résumé des équipes ' . ucfirst($sport) . '</h2>';
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>Référence</th>';
        echo '<th>Nom de l\'équipe</th>';
        echo '<th>Type</th>';
        echo '<th>Catégorie</th>';
        echo '<th>District</th>';
        echo '<th>Organisation</th>';
        echo '<th>Responsable</th>';
        echo '<th>Téléphone</th>';
        echo '<th>Email</th>';
        echo '<th>Statut</th>';
        echo '<th>Date d\'inscription</th>';
        echo '<th>Nombre de joueurs</th>';
        echo '</tr>';
        
        foreach ($teams as $team) {
            echo '<tr>';
            echo '<td>' . h($team->reference_inscription) . '</td>';
            echo '<td>' . h($team->nom_equipe) . '</td>';
            echo '<td>' . h($team->{'type_' . $sport} ?? '') . '</td>';
            echo '<td>' . h($team->categorie) . '</td>';
            echo '<td>' . h($team->district) . '</td>';
            echo '<td>' . h($team->organisation) . '</td>';
            echo '<td>' . h($team->nom_responsable) . '</td>';
            echo '<td>' . h($team->telephone) . '</td>';
            echo '<td>' . h($team->email) . '</td>';
            
            $status = $team->status ?? 'pending';
            switch ($status) {
                case 'verified':
                    $statusText = 'Vérifié';
                    break;
                case 'rejected':
                    $statusText = 'Rejeté';
                    break;
                default:
                    $statusText = 'En attente';
            }
            echo '<td>' . $statusText . '</td>';
            echo '<td>' . $team->created->format('d/m/Y') . '</td>';
            
            // Count players
            $playersCount = 0;
            if ($sport === 'football' && isset($team->joueurs)) {
                $playersCount = count($team->joueurs);
            } else {
                $playersField = $sport . '_teams_joueurs';
                if (isset($team->$playersField)) {
                    $playersCount = count($team->$playersField);
                }
            }
            echo '<td>' . $playersCount . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        
        // Players details for each team
        echo '<br><br>';
        echo '<h2>Détails des joueurs par équipe</h2>';
        
        foreach ($teams as $team) {
            echo '<br>';
            echo '<h3>Équipe : ' . h($team->nom_equipe) . ' (Ref: ' . h($team->reference_inscription) . ')</h3>';
            echo '<table border="1">';
            echo '<tr>';
            echo '<th>N°</th>';
            echo '<th>Nom complet</th>';
            echo '<th>Date de naissance</th>';
            echo '<th>CIN</th>';
            echo '<th>Taille maillot</th>';
            echo '</tr>';
            
            // Get players based on sport
            $players = [];
            if ($sport === 'football' && isset($team->joueurs)) {
                $players = $team->joueurs;
            } else {
                $playersField = $sport . '_teams_joueurs';
                if (isset($team->$playersField)) {
                    $players = $team->$playersField;
                }
            }
            
            $num = 1;
            foreach ($players as $player) {
                echo '<tr>';
                echo '<td>' . $num++ . '</td>';
                echo '<td>' . h($player->nom_complet) . '</td>';
                echo '<td>' . ($player->date_naissance ? $player->date_naissance->format('d/m/Y') : '') . '</td>';
                echo '<td>' . h($player->cin) . '</td>';
                echo '<td>' . h($player->taille_maillot) . '</td>';
                echo '</tr>';
            }
            
            if (empty($players)) {
                echo '<tr><td colspan="5">Aucun joueur enregistré</td></tr>';
            }
            
            echo '</table>';
        }
        
        echo '</body>';
        echo '</html>';
        
        exit;
    }
    
}
?>