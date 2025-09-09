# Rabat Jeunesse 2025 - Project Documentation

## Project Overview
**Rabat Jeunesse 2025** is a sports team management and registration system built with CakePHP 5.2. The application manages multiple sports tournaments and team registrations for youth sports events in Rabat, Morocco.

## Technology Stack
- **Framework**: CakePHP 5.2
- **PHP Version**: 8.1+
- **Database**: MySQL (via XAMPP)
- **Frontend**: HTML, CSS, JavaScript with form validation
- **Authentication**: CakePHP Authentication plugin
- **PDF Generation**: DomPDF
- **Mobile Detection**: MobileDetect library

## Core Business Logic

### Sports Management System
The application manages 5 different sports with specific rules and requirements:

| Sport | Type/Format | Min Players | Max Players | Description |
|-------|-------------|-------------|-------------|-------------|
| **Football** | 5x5 | 5 | 10 | Football à 5 (5x5) |
| **Football** | 6x6 | 6 | 12 | Football à 6 (6x6) |
| **Football** | 11x11 | 11 | 18 | Football à 11 (11x11) |
| **Basketball** | 3x3 | 3 | 6 | Basketball 3x3 |
| **Basketball** | 5x5 | 5 | 10 | Basketball 5x5 |
| **Handball** | 7v7 | 7 | 10 | Handball (7-10 joueurs) |
| **Volleyball** | 6v6 | 6 | 10 | Volleyball (6-10 joueurs) |
| **Beach Volleyball** | 2v2 | 2 | 4 | Beach-volley en duo |

### User Management
- **User Registration/Login**: Standard authentication with username, email, password
- **User Roles**: Regular users and administrators (is_admin flag)
- **User Relationships**: Users can manage multiple teams across different sports

### Team Management Architecture

#### Core Team Structure
Each sport has its own dedicated team table and management system:
- `teams` - Football teams (original table)
- `basketball_teams` - Basketball specific teams
- `handball_teams` - Handball specific teams  
- `volleyball_teams` - Volleyball specific teams
- `beachvolley_teams` - Beach volleyball specific teams

#### Team-Player Relationships
Each sport maintains its own player roster system:
- `joueurs` - Players for football teams
- `basketball_teams_joueurs` - Basketball team players
- `handball_teams_joueurs` - Handball team players
- `volleyball_teams_joueurs` - Volleyball team players
- `beachvolley_teams_joueurs` - Beach volleyball team players

#### Category and Classification System
Each sport has its own category system:
- `football_categories` - Age groups and team sizes for football
- `basketball_categories` - 3x3 and 5x5 categories
- `handball_categories` - Age and skill categories
- `volleyball_categories` - Competition categories
- `beachvolley_categories` - Tournament categories

### Key Features

#### Multi-Sport Registration
- Unified registration system through `SportsController`
- Sport-specific landing pages and registration flows
- Separate controllers for each sport team management

#### Team Status Management
All team tables include status tracking:
- `pending` - Newly registered teams awaiting verification
- `approved` - Verified and accepted teams
- `rejected` - Teams that didn't meet requirements
- `verification_notes` - Admin notes for team verification

#### Date Range Management
Categories include date ranges for registration periods:
- `date_debut` - Registration start date
- `date_fin` - Registration end date
- Dynamic availability checking based on current date

#### Football-Specific Features
Football has additional complexity with:
- **Districts**: Geographic organization (`football_districts`)
- **Organizations**: Club/organization management (`football_organisations`)
- **Multiple Team Sizes**: 5v5, 6v6, 11v11 configurations

### Database Architecture

#### Migration History
The project has extensive migration history showing evolution:
- Initial basic tables (Aug 9, 2025)
- Complex foreign key relationships and constraints
- Category system implementation
- Status and verification system
- Collation updates for proper text handling
- Emergency fixes for constraint issues

#### Key Relationships
- Users → Multiple sports teams (1:many)
- Teams → Players (1:many per sport)
- Teams → Categories (many:1 per sport)
- Football Teams → Districts, Organizations (many:1)

### Controller Logic

#### Authentication Flow
- Public access to sports information and landing pages
- Required authentication for team management
- Role-based access for admin functions

#### Team Management Flow
1. User browses available sports (`SportsController::index`)
2. User selects sport and views requirements
3. User registers/logs in if needed
4. User creates team through sport-specific controller
5. User adds players to team roster
6. Admin reviews and approves/rejects team
7. User can view team status in dashboard

### Frontend Components

#### Responsive Design
- Mobile-friendly interface using Milligram CSS framework
- Custom CSS for form validation and sports-specific styling
- JavaScript validation for each sport type

#### Football Validation Rules Table
| Field | Validation Type | Rules | Error Messages |
|-------|----------------|-------|----------------|
| **nom_equipe** | String | Required, 3-100 chars | "Le nom de l'équipe est requis" |
| **football_category_id** | ID | Required, valid DB ID | "La catégorie est requise" |
| **type_football** | Enum | `'5x5'`, `'6x6'`, `'11x11'` | "Type de football invalide" |
| **genre** | Enum | `'Homme'`, `'Femme'` | "Le genre est requis" |
| **football_district_id** | ID | Required, valid DB ID | "Le district est requis" |
| **football_organisation_id** | ID | Required, valid DB ID | "Le type d'organisation est requis" |
| **responsable_nom_complet** | String | Required, 2+ chars, letters only | "Le nom du responsable est requis" |
| **responsable_tel** | Phone | Required, Moroccan format | "Format de téléphone invalide" |
| **email** | Email | Required, valid email format | "Format d'email invalide" |
| **responsable_date_naissance** | Date | Required, age 18-70 | "L'âge doit être entre 18 et 70 ans" |
| **Players Count** | Number | Based on football type | "Vous devez ajouter au moins X joueurs" |
| **accepter_reglement** | Checkbox | Required, must be checked | "Vous devez accepter le règlement" |

#### Player Count Validation by Football Type
| Football Type | Form Value | Minimum Players | Maximum Players | Validation Message |
|---------------|------------|-----------------|-----------------|-------------------|
| Football à 5 | `'5x5'` | 5 | 10 | "Vous devez ajouter au moins 5 joueurs pour le 5x5" |
| Football à 6 | `'6x6'` | 6 | 12 | "Vous devez ajouter au moins 6 joueurs pour le 6x6" |
| Football à 11 | `'11x11'` | 11 | 18 | "Vous devez ajouter au moins 11 joueurs pour le 11x11" |

#### Form Validation
Sport-specific JavaScript validation files:
- `football-validation.js`
- `basketball-validation.js`
- `handball-validation.js`
- `volleyball-validation.js`
- `beachvolley-validation.js`

#### User Interface
- Clean, modern design with sport-themed imagery
- Dashboard showing all user's teams across sports
- Admin interface for team verification and management

### Security Features
- Password hashing and secure authentication
- CSRF protection on all forms
- Input validation and sanitization
- Role-based access control
- Secure file upload handling

### Admin Features
- Team verification workflow with status updates
- Ability to add verification notes
- User management capabilities
- System-wide oversight of registrations

## File Structure Overview
```
src/
├── Controller/
│   ├── Admin/           # Admin-specific controllers
│   ├── SportsController.php    # Main sports hub
│   ├── TeamsController.php     # Football team management
│   ├── UsersController.php     # User authentication
│   └── [Sport]TeamsController.php # Sport-specific controllers
├── Model/
│   ├── Table/          # Database table models
│   └── Entity/         # Data entities
templates/
├── Sports/             # Sports templates
├── Teams/              # Team management templates
├── Users/              # User authentication templates
└── [Sport]Teams/       # Sport-specific templates
webroot/
├── css/                # Stylesheets
├── js/                 # JavaScript validation
└── img/                # Sports imagery
```

## Development Notes
- Extensive migration history indicates iterative development with multiple refinements
- Strong focus on data integrity with comprehensive foreign key constraints
- Bilingual support (French/English) evident in validation messages
- Recent fixes suggest active development and maintenance
- Modular architecture allows easy addition of new sports

This system represents a comprehensive sports management platform designed to handle the complexity of multi-sport youth tournaments with proper user management, team verification, and administrative oversight.