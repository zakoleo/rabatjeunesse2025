<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Sports Controller
 *
 */
class SportsController extends AppController
{
    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
    }
    
    /**
     * beforeFilter callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Allow non-authenticated users to access sports list and landing pages
        $this->Authentication->addUnauthenticatedActions(['index', 'football', 'basketball', 'handball', 'volleyball', 'beachvolley', 'crosstraining']);
    }
    
    /**
     * Index method - Display all sports
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $sports = [
            [
                'id' => 'football',
                'name' => 'Football',
                'image' => 'img_sport_football.png',
                'description' => 'Participez aux tournois de football à 5, 6 ou 11 joueurs',
                'categories' => ['5x5', '6x6', '11x11']
            ],
            [
                'id' => 'basketball',
                'name' => 'Basketball',
                'image' => 'img_sport_basket.png',
                'description' => 'Rejoignez les compétitions de basketball 3x3 ou 5x5',
                'categories' => ['3x3', '5x5']
            ],
            [
                'id' => 'handball',
                'name' => 'Handball',
                'image' => 'img_sport_handball.png',
                'description' => 'Inscrivez votre équipe de handball (7-10 joueurs)',
                'categories' => ['7 joueurs']
            ],
            [
                'id' => 'volleyball',
                'name' => 'Volleyball',
                'image' => 'img_sport_volleyball-768x461.png',
                'description' => 'Participez aux tournois de volleyball (6-10 joueurs)',
                'categories' => ['6 joueurs']
            ],
            [
                'id' => 'beachvolley',
                'name' => 'Beach-volley',
                'image' => 'img_sport_volleyball-768x461.png',
                'description' => 'Tournois de beach-volley en duo',
                'categories' => ['2 joueurs']
            ],
            [
                'id' => 'crosstraining',
                'name' => 'Cross Training',
                'image' => 'img_sport_cross-768x461.png',
                'description' => 'Compétition individuelle de Cross Training',
                'categories' => ['Individuel']
            ]
        ];
        
        $this->set(compact('sports'));
    }
    
    /**
     * Football landing page
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function football()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
    }
    
    /**
     * Basketball landing page
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function basketball()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
    }
    
    /**
     * Handball landing page
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function handball()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
    }
    
    /**
     * Volleyball landing page
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function volleyball()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
    }
    
    /**
     * Beach-volley landing page
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function beachvolley()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
    }
    
    /**
     * Cross Training landing page
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function crosstraining()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));
    }
}