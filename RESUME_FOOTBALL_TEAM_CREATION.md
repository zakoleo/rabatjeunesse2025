# Résumé - Logique de Création d'Équipes de Football

## Vue d'ensemble du système
Le système Rabat Jeunesse 2025 permet la création d'équipes de football via un formulaire multi-étapes avec validation dynamique. L'application utilise CakePHP 4 avec une interface JavaScript interactive.

## Architecture de la base de données

### Tables principales

#### 1. **teams** (Table principale)
```sql
- id (PK)
- nom_equipe VARCHAR(255)
- categorie VARCHAR(50) -- Stockage texte de la catégorie
- genre ENUM('Homme', 'Femme')
- type_football ENUM('5x5', '6x6', '11x11')
- district VARCHAR(100) -- Stockage texte du district
- organisation VARCHAR(100) -- Stockage texte de l'organisation
- adresse TEXT
- user_id INT (FK -> users.id)
- reference_inscription VARCHAR(255) -- Référence unique générée automatiquement
- status VARCHAR(20) -- Statut de l'équipe
- verified_at DATETIME
- verified_by INT
- verification_notes TEXT
- created/modified DATETIME

-- Champs responsable
- responsable_nom_complet VARCHAR(255)
- responsable_date_naissance DATE
- responsable_tel VARCHAR(20)
- responsable_whatsapp VARCHAR(20)
- responsable_cin_recto VARCHAR(255) -- Chemin vers fichier uploadé
- responsable_cin_verso VARCHAR(255)

-- Champs entraîneur
- entraineur_nom_complet VARCHAR(255)
- entraineur_date_naissance DATE
- entraineur_tel VARCHAR(20)
- entraineur_whatsapp VARCHAR(20)
- entraineur_cin_recto VARCHAR(255)
- entraineur_cin_verso VARCHAR(255)
- entraineur_same_as_responsable BOOLEAN

-- Relations avec les tables de référence
- football_category_id INT (FK -> football_categories.id)
- football_district_id INT (FK -> football_districts.id)
- football_organisation_id INT (FK -> football_organisations.id)
```

#### 2. **football_categories** (Table de référence)
```sql
- id (PK)
- name VARCHAR(50) -- Ex: 'U12', 'U15', 'U18', '18+'
- age_range VARCHAR(100) -- Ex: 'Moins de 12 ans'
- min_date DATE -- Date de naissance minimum
- max_date DATE -- Date de naissance maximum
- active BOOLEAN
- created/modified DATETIME
```

#### 3. **football_districts** (Table de référence)
```sql
- id (PK)
- name VARCHAR(50)
- active BOOLEAN
- created/modified DATETIME
```

#### 4. **football_organisations** (Table de référence)
```sql
- id (PK)
- name VARCHAR(50)
- active BOOLEAN
- created/modified DATETIME
```

#### 5. **joueurs** (Joueurs de l'équipe)
```sql
- id (PK)
- team_id INT (FK -> teams.id) -- CASCADE DELETE
- nom_complet VARCHAR(255)
- date_naissance DATE
- identifiant VARCHAR(50) -- CIN ou Code Massar
- taille_vestimentaire ENUM('XS','S','M','L','XL','XXL')
- created/modified DATETIME
```

## Logique de création d'équipe

### 1. Interface utilisateur (Templates/JavaScript)

#### Fichiers principaux :
- **Template** : `templates/Teams/add.php`
- **JavaScript** : `webroot/js/inscription-form.js`
- **Page sport** : `templates/Sports/football.php`

#### Formulaire multi-étapes :
1. **Étape 1 - Équipe** :
   - Nom de l'équipe
   - Catégorie d'âge (dynamique depuis BD)
   - Genre (Homme/Femme)
   - Type de football (5x5, 6x6, 11x11) - Filtré selon catégorie
   - District/Quartier (dynamique depuis BD)
   - Organisation (dynamique depuis BD)
   - Adresse

2. **Étape 2 - Responsable & Entraîneur** :
   - Responsable : nom, date naissance, téléphone, WhatsApp, CIN (recto/verso)
   - Entraîneur : mêmes champs + option "identique au responsable"
   - Upload de fichiers CIN

3. **Étape 3 - Joueurs** :
   - Ajout dynamique de joueurs selon type de football
   - Validation des dates de naissance selon catégorie
   - Champs : nom, date naissance, CIN/Code Massar, taille vestimentaire

### 2. Logique JavaScript (inscription-form.js)

#### Fonctionnalités clés :

```javascript
// Contraintes par type de football
const joueursMin = { '5x5': 5, '6x6': 6, '11x11': 11 };
const joueursMax = { '5x5': 8, '6x6': 10, '11x11': 18 };

// Chargement dynamique des plages de dates par catégorie
async function loadDateRanges() {
    const response = await fetch('/api/football-date-ranges');
    categoriesDateRanges = data.dateRanges;
}

// Filtrage type de football selon catégorie
categorieSelect.addEventListener('change', function() {
    if (categorieName === 'U12' || categorieName === 'U15') {
        // Seulement 6x6 disponible
    } else if (categorieName === 'U18' || categorieName === '18+') {
        // 5x5 et 11x11 disponibles
    }
});

// Validation des dates de naissance
function validateJoueurDateNaissance(dateNaissance, categorie) {
    const dateRange = categoriesDateRanges[categorie];
    const birthDate = new Date(dateNaissance);
    const minDate = new Date(dateRange.min);
    const maxDate = new Date(dateRange.max);
    
    return birthDate >= minDate && birthDate <= maxDate;
}
```

### 3. Logique serveur (Controller)

#### Fichier : `src/Controller/TeamsController.php`

#### Méthode `add()` - Traitement du formulaire :

```php
public function add() {
    if ($this->request->is('post')) {
        $data = $this->request->getData();
        $data['user_id'] = $this->Authentication->getIdentity()->get('id');
        
        // 1. Remplissage des champs texte depuis les clés étrangères
        if (!empty($data['football_category_id'])) {
            $category = $FootballCategories->get($data['football_category_id']);
            $data['categorie'] = $category->age_range;
        }
        
        // 2. Gestion upload fichiers CIN
        $uploadPath = WWW_ROOT . 'uploads' . DS . 'teams' . DS;
        
        // 3. Gestion entraîneur = responsable
        if ($data['entraineur_same_as_responsable']) {
            // Copie automatique des données
        }
        
        // 4. Validation dates joueurs
        foreach ($data['joueurs'] as $joueur) {
            $birthDate = new DateTime($joueur['date_naissance']);
            if ($birthDate < $minDate || $birthDate > $maxDate) {
                $this->Flash->error('Erreur date naissance');
                return;
            }
        }
        
        // 5. Sauvegarde avec joueurs associés
        $team = $this->Teams->patchEntity($team, $data, [
            'associated' => ['Joueurs']
        ]);
        
        if ($this->Teams->save($team)) {
            $this->Flash->success('Équipe inscrite avec succès');
        }
    }
}
```

### 4. Logique Model (Table/Entity)

#### Fichier : `src/Model/Table/TeamsTable.php`

#### Fonctionnalités clés :

```php
// Génération automatique de référence d'inscription
public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options) {
    if ($entity->isNew() && empty($entity->reference_inscription)) {
        $entity->reference_inscription = $this->generateReference();
    }
}

private function generateReference(): string {
    $year = date('Y');
    $month = date('m');
    $sequence = $this->getNextSequence($year, $month);
    
    // Format: FB + année + mois + séquence (FB202508001)
    return sprintf("FB%s%s%04d", $year, $month, $sequence);
}

// Validation des règles métier
public function validationDefault(Validator $validator): Validator {
    $validator
        ->inList('genre', ['Homme', 'Femme'])
        ->inList('type_football', ['5x5', '6x6', '11x11']);
    
    return $validator;
}

// Limites de joueurs par type
public function getPlayerLimits($type) {
    return [
        '5x5' => ['min' => 5, 'max' => 8],
        '6x6' => ['min' => 6, 'max' => 10],
        '11x11' => ['min' => 11, 'max' => 18]
    ][$type] ?? null;
}
```

## Règles de validation

### 1. Catégories et types de football
- **U12/U15** : Seulement football à 6 (6x6)
- **U18/18+** : Football à 5 (5x5) ou à 11 (11x11)

### 2. Nombre de joueurs
- **5x5** : Minimum 5, Maximum 8 joueurs
- **6x6** : Minimum 6, Maximum 10 joueurs
- **11x11** : Minimum 11, Maximum 18 joueurs

### 3. Validation des âges
- Dates de naissance validées contre les plages définies dans `football_categories`
- Validation côté client (JavaScript) et serveur (PHP)

### 4. Upload de fichiers
- CIN responsable/entraîneur stockés dans `webroot/uploads/teams/`
- Format : `timestamp_type_filename`

## Points techniques importants

### 1. Double stockage
Le système utilise un double stockage :
- **Clés étrangères** : `football_category_id`, `football_district_id`, `football_organisation_id`
- **Champs texte** : `categorie`, `district`, `organisation` (pour affichage/historique)

### 2. Référence unique
- Générée automatiquement au format `FB{YYYY}{MM}{SEQUENCE}`
- Versioning en cas de modification (`FB20250800001_v2`)

### 3. Gestion des fichiers
- Upload sécurisé dans dossier dédié
- Validation côté serveur des types de fichiers

### 4. Relations CASCADE
- Suppression d'une équipe supprime automatiquement ses joueurs
- Association `hasMany` avec `dependent => true`

## Migrations importantes
- `CreateTeams.php` : Structure de base
- `CreateFootballCategories/Districts/Organisations.php` : Tables de référence  
- `UpdateTeamsForFootballReferences.php` : Ajout des clés étrangères
- `AddResponsableEntraineurFieldsToTeams.php` : Champs responsable/entraîneur
- Migrations récentes pour les catégories avec date ranges

## Fichiers clés à examiner
1. **Migrations** : `config/Migrations/20250*_*Football*.php`
2. **Controller** : `src/Controller/TeamsController.php`
3. **Model** : `src/Model/Table/TeamsTable.php`, `src/Model/Entity/Team.php`
4. **Templates** : `templates/Teams/add.php`, `templates/Sports/football.php`
5. **JavaScript** : `webroot/js/inscription-form.js`
6. **CSS** : Styles intégrés dans les templates

Ce système offre une interface complète et validée pour la création d'équipes de football avec gestion des contraintes métier, upload de fichiers et référencement unique.