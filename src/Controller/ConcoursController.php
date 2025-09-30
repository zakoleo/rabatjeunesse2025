<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

class ConcoursController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        
        $this->ConcoursParticipants = $this->fetchTable('ConcoursParticipants');
        $this->ConcoursCategories = $this->fetchTable('ConcoursCategories');
    }
    
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Allow non-authenticated users to access contest landing pages
        $this->Authentication->addUnauthenticatedActions(['dessin', 'chanson', 'commentateur', 'film']);
    }

    public function index()
    {
        $user = $this->Authentication->getIdentity();
        
        $participants = $this->ConcoursParticipants->find()
            ->where(['ConcoursParticipants.user_id' => $user->id])
            ->contain(['ConcoursCategories'])
            ->order(['ConcoursParticipants.created' => 'DESC'])
            ->all();

        $this->set(compact('participants'));
    }

    public function add()
    {
        $user = $this->Authentication->getIdentity();
        
        // Check if a contest type was passed via URL parameter
        $selectedType = $this->request->getQuery('cat');
        
        // If no type is selected, show the visual selector
        if (!$selectedType && !$this->request->is('post')) {
            $this->render('select_type');
            return;
        }
        
        $participant = $this->ConcoursParticipants->newEmptyEntity();
        
        // Pre-fill the contest type if passed via URL
        if ($selectedType) {
            $participant->type_concours = $selectedType;
        }
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = $user->id;
            $data['status'] = 'pending';
            
            // Handle file uploads
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'concours' . DS;
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
            
            $participant = $this->ConcoursParticipants->patchEntity($participant, $data);
            
            if ($this->ConcoursParticipants->save($participant)) {
                $this->Flash->success(__('Votre inscription au concours a été enregistrée avec succès.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'enregistrer votre inscription. Veuillez réessayer.'));
        }
        
        $categories = $this->ConcoursCategories->find('list', [
            'keyField' => 'id',
            'valueField' => function ($category) {
                return $category->gender . ' - ' . $category->age_category;
            }
        ])->where(['active' => true])->toArray();
        
        // Get categories with date ranges for validation
        $categoriesData = $this->ConcoursCategories->find()
            ->where(['active' => true])
            ->toArray();

        // Get concours types from the model
        $concoursTypes = \App\Model\Table\ConcoursParticipantsTable::getConcoursTypes();

        $this->set(compact('participant', 'categories', 'categoriesData', 'concoursTypes'));
    }

    public function view($id = null)
    {
        $user = $this->Authentication->getIdentity();
        
        $participant = $this->ConcoursParticipants->get($id, [
            'contain' => ['ConcoursCategories', 'Users']
        ]);

        if ($participant->user_id !== $user->id && !$user->is_admin) {
            throw new NotFoundException(__('Participant non trouvé.'));
        }

        $this->set('participant', $participant);
    }

    public function edit($id = null)
    {
        $user = $this->Authentication->getIdentity();
        
        $participant = $this->ConcoursParticipants->get($id, [
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
            $uploadPath = WWW_ROOT . 'uploads' . DS . 'concours' . DS;
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
            
            $participant = $this->ConcoursParticipants->patchEntity($participant, $data);
            if ($this->ConcoursParticipants->save($participant)) {
                $this->Flash->success(__('Les informations ont été mises à jour.'));
                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error(__('Impossible de mettre à jour les informations.'));
        }
        
        $categories = $this->ConcoursCategories->find('list', [
            'keyField' => 'id',
            'valueField' => function ($category) {
                return $category->gender . ' - ' . $category->age_category;
            }
        ])->where(['active' => true])->toArray();
        
        // Get categories with date ranges for validation
        $categoriesData = $this->ConcoursCategories->find()
            ->where(['active' => true])
            ->toArray();

        // Get concours types from the model
        $concoursTypes = \App\Model\Table\ConcoursParticipantsTable::getConcoursTypes();

        $this->set(compact('participant', 'categories', 'categoriesData', 'concoursTypes'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $user = $this->Authentication->getIdentity();
        $participant = $this->ConcoursParticipants->get($id);
        
        if ($participant->user_id !== $user->id) {
            throw new NotFoundException(__('Participant non trouvé.'));
        }
        
        if ($participant->status === 'verified') {
            $this->Flash->error(__('Vous ne pouvez pas supprimer une inscription déjà vérifiée.'));
            return $this->redirect(['action' => 'index']);
        }
        
        if ($this->ConcoursParticipants->delete($participant)) {
            $this->Flash->success(__('L\'inscription a été supprimée.'));
        } else {
            $this->Flash->error(__('Impossible de supprimer l\'inscription.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Dessin contest landing page
     */
    public function dessin()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
        $this->render('contest_landing');
    }

    /**
     * Chanson contest landing page
     */
    public function chanson()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
        $this->render('contest_landing');
    }

    /**
     * Commentateur contest landing page
     */
    public function commentateur()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
        $this->render('contest_landing');
    }

    /**
     * Film contest landing page
     */
    public function film()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
        $this->render('contest_landing');
    }

    /**
     * Download Concours PDF method
     *
     * @param string|null $id Concours Participant id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function downloadPdf($id = null)
    {
        $participant = $this->ConcoursParticipants->get($id, [
            'contain' => ['ConcoursCategories', 'Users']
        ]);
        
        // Check if user has permission to download this PDF
        $user = $this->Authentication->getIdentity();
        if ($participant->user_id !== $user->id && !$user->is_admin) {
            throw new NotFoundException(__('Participant non trouvé.'));
        }
        
        // Generate PDF with DomPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new \Dompdf\Dompdf($options);
        
        // Create HTML for PDF
        $html = $this->generateConcoursPdfHtml($participant);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Filename
        $filename = sprintf('inscription_concours_%s.pdf', 
            $participant->reference_inscription ?? 'CO' . $participant->id
        );
        
        // Send PDF to browser
        $this->response = $this->response->withType('pdf');
        $this->response = $this->response->withDownload($filename);
        $this->response = $this->response->withStringBody($dompdf->output());
        
        return $this->response;
    }

    /**
     * Generate HTML for Concours participant PDF
     */
    private function generateConcoursPdfHtml($participant)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fiche d\'inscription - Concours</title>
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
            border-bottom: 2px solid #9B59B6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 18pt;
            font-weight: bold;
            color: #9B59B6;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14pt;
            color: #666;
            margin-bottom: 5px;
        }
        .reference {
            background-color: #F5E6FF;
            color: #9B59B6;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #9B59B6;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 15px;
        }
        .info-row {
            margin-bottom: 8px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 5px;
        }
        .label {
            font-weight: bold;
            width: 35%;
            display: inline-block;
            color: #555;
        }
        .value {
            color: #333;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">RABAT JEUNESSE 2025</div>
        <div class="subtitle">Fiche d\'inscription - Concours</div>
    </div>

    <div class="reference">
        Référence: ' . h($participant->reference_inscription) . '
    </div>

    <div class="section">
        <div class="section-title">Informations personnelles</div>
        <div class="info-row">
            <span class="label">Nom complet:</span>
            <span class="value">' . h($participant->nom_complet) . '</span>
        </div>
        <div class="info-row">
            <span class="label">Date de naissance:</span>
            <span class="value">' . h($participant->date_naissance ? $participant->date_naissance->format('d/m/Y') : '') . '</span>
        </div>
        <div class="info-row">
            <span class="label">Sexe:</span>
            <span class="value">' . h($participant->gender) . '</span>
        </div>
        <div class="info-row">
            <span class="label">CIN:</span>
            <span class="value">' . h($participant->cin) . '</span>
        </div>
        <div class="info-row">
            <span class="label">Téléphone:</span>
            <span class="value">' . h($participant->telephone) . '</span>
        </div>
        <div class="info-row">
            <span class="label">WhatsApp:</span>
            <span class="value">' . h($participant->whatsapp) . '</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">' . h($participant->email) . '</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Informations concours</div>
        <div class="info-row">
            <span class="label">Type de concours:</span>
            <span class="value">' . h($participant->type_concours) . '</span>
        </div>
        <div class="info-row">
            <span class="label">Catégorie:</span>
            <span class="value">' . h($participant->concours_category ? $participant->concours_category->gender . ' - ' . $participant->concours_category->age_category : '') . '</span>
        </div>
        <div class="info-row">
            <span class="label">Taille T-shirt:</span>
            <span class="value">' . h($participant->taille_tshirt) . '</span>
        </div>
        <div class="info-row">
            <span class="label">Statut:</span>
            <span class="value">' . h($participant->status) . '</span>
        </div>
        <div class="info-row">
            <span class="label">Date d\'inscription:</span>
            <span class="value">' . h($participant->created ? $participant->created->format('d/m/Y H:i') : '') . '</span>
        </div>
    </div>

    <div class="footer">
        Document généré le ' . date('d/m/Y à H:i') . '<br>
        Rabat Jeunesse 2025 - Concours
    </div>
</body>
</html>';

        return $html;
    }
}