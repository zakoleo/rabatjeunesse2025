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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $user = $this->Authentication->getIdentity();
        $query = $this->Teams->find()
            ->where(['Teams.user_id' => $user->id])
            ->contain(['Users', 'FootballCategories', 'FootballDistricts', 'FootballOrganisations']);
        $teams = $this->paginate($query);

        $this->set(compact('teams'));
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
                
                // Valider les dates de naissance des joueurs
                $categorie = $data['categorie'] ?? '';
                $dateRanges = [
                    'U12' => ['min' => '2014-01-01', 'max' => '2015-12-31'],
                    'U15' => ['min' => '2012-01-01', 'max' => '2013-12-31'],
                    'U18' => ['min' => '2008-01-01', 'max' => '2010-12-31'],
                    '18+' => ['min' => '1970-01-01', 'max' => '2007-12-31'],
                    'U21' => ['min' => '2005-01-01', 'max' => '2007-12-31']
                ];
                
                if (isset($dateRanges[$categorie])) {
                    $range = $dateRanges[$categorie];
                    $minDate = new \DateTime($range['min']);
                    $maxDate = new \DateTime($range['max']);
                    
                    foreach ($data['joueurs'] as $index => $joueur) {
                        if (!empty($joueur['date_naissance'])) {
                            $birthDate = new \DateTime($joueur['date_naissance']);
                            if ($birthDate < $minDate || $birthDate > $maxDate) {
                                $this->Flash->error(sprintf(
                                    'Le joueur %s doit être né entre le %s et le %s pour la catégorie %s',
                                    $joueur['nom_complet'] ?? 'n°' . ($index + 1),
                                    $minDate->format('d/m/Y'),
                                    $maxDate->format('d/m/Y'),
                                    $categorie
                                ));
                                return $this->redirect(['action' => 'add']);
                            }
                        }
                    }
                }
            }
            
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
                foreach ($fieldErrors as $error) {
                    $errorMessages[] = $field . ': ' . $error;
                }
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(__('Erreurs de validation: ') . implode(', ', $errorMessages));
            } else {
                $this->Flash->error(__('L\'inscription n\'a pas pu être enregistrée. Veuillez réessayer.'));
            }
        }
        
        // Charger les listes pour les dropdowns
        $footballCategories = $this->Teams->FootballCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballDistricts = $this->Teams->FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballOrganisations = $this->Teams->FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $this->set(compact('team', 'footballCategories', 'footballDistricts', 'footballOrganisations'));
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
                
                // Valider les dates de naissance des joueurs
                $categorie = $data['categorie'] ?? $team->categorie ?? '';
                $dateRanges = [
                    'U12' => ['min' => '2014-01-01', 'max' => '2015-12-31'],
                    'U15' => ['min' => '2012-01-01', 'max' => '2013-12-31'],
                    'U18' => ['min' => '2008-01-01', 'max' => '2010-12-31'],
                    '18+' => ['min' => '1970-01-01', 'max' => '2007-12-31'],
                    'U21' => ['min' => '2005-01-01', 'max' => '2007-12-31']
                ];
                
                if (isset($dateRanges[$categorie])) {
                    $range = $dateRanges[$categorie];
                    $minDate = new \DateTime($range['min']);
                    $maxDate = new \DateTime($range['max']);
                    
                    foreach ($data['joueurs'] as $index => $joueur) {
                        if (!empty($joueur['date_naissance'])) {
                            $birthDate = new \DateTime($joueur['date_naissance']);
                            if ($birthDate < $minDate || $birthDate > $maxDate) {
                                $this->Flash->error(sprintf(
                                    'Le joueur %s doit être né entre le %s et le %s pour la catégorie %s',
                                    $joueur['nom_complet'] ?? 'n°' . ($index + 1),
                                    $minDate->format('d/m/Y'),
                                    $maxDate->format('d/m/Y'),
                                    $categorie
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
                foreach ($fieldErrors as $error) {
                    $errorMessages[] = $field . ': ' . $error;
                }
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(__('Erreurs de validation: ') . implode(', ', $errorMessages));
            } else {
                $this->Flash->error(__('L\'équipe n\'a pas pu être mise à jour. Veuillez réessayer.'));
            }
        }
        
        // Charger les listes pour les dropdowns
        $footballCategories = $this->Teams->FootballCategories->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballDistricts = $this->Teams->FootballDistricts->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        $footballOrganisations = $this->Teams->FootballOrganisations->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['active' => true]);
        
        // Trouver les IDs correspondants aux valeurs texte stockées
        if (!empty($team->categorie)) {
            $category = $this->Teams->FootballCategories->find()
                ->where(['name' => $team->categorie])
                ->first();
            if ($category) {
                $team->football_category_id = $category->id;
            }
        }
        
        if (!empty($team->district)) {
            $district = $this->Teams->FootballDistricts->find()
                ->where(['name' => $team->district])
                ->first();
            if ($district) {
                $team->football_district_id = $district->id;
            }
        }
        
        if (!empty($team->organisation)) {
            $organisation = $this->Teams->FootballOrganisations->find()
                ->where(['name' => $team->organisation])
                ->first();
            if ($organisation) {
                $team->football_organisation_id = $organisation->id;
            }
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
        $logoPath = WWW_ROOT . 'img' . DS . 'logo.webp';
        $logoData = '';
        if (file_exists($logoPath)) {
            $logoData = 'data:image/webp;base64,' . base64_encode(file_get_contents($logoPath));
        }
        
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
}
