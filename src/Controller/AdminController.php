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
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Football';
                            return $row;
                        });
                    });
                
                $basketballTeams = $this->fetchTable('BasketballTeams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Basketball';
                            return $row;
                        });
                    });
                
                $handballTeams = $this->fetchTable('HandballTeams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Handball';
                            return $row;
                        });
                    });
                
                $volleyballTeams = $this->fetchTable('VolleyballTeams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Volleyball';
                            return $row;
                        });
                    });
                
                $beachvolleyTeams = $this->fetchTable('BeachvolleyTeams')->find()
                    ->contain(['Users'])
                    ->select(['id', 'nom_equipe', 'status', 'created', 'user_id', 'Users.username'])
                    ->formatResults(function ($results) {
                        return $results->map(function ($row) {
                            $row['sport'] = 'Beach Volleyball';
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
                // If category/district lookup fails, continue with empty names
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
    
}
?>