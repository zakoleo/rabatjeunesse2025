<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
 * So you can use `$this` to reference the application class instance
 * if required.
 */
return function (RouteBuilder $routes): void {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        $builder->connect('/', ['controller' => 'Sports', 'action' => 'index']);

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');
        
        /*
         * Sports routes
         */
        $builder->connect('/sports', ['controller' => 'Sports', 'action' => 'index']);
        $builder->connect('/sports/football', ['controller' => 'Sports', 'action' => 'football']);
        $builder->connect('/sports/basketball', ['controller' => 'Sports', 'action' => 'basketball']);
        $builder->connect('/sports/handball', ['controller' => 'Sports', 'action' => 'handball']);
        $builder->connect('/sports/volleyball', ['controller' => 'Sports', 'action' => 'volleyball']);
        $builder->connect('/sports/beachvolley', ['controller' => 'Sports', 'action' => 'beachvolley']);

        /*
         * Admin routes - Must be defined before fallbacks
         */
        $builder->connect('/admin/team-details/*', ['controller' => 'Admin', 'action' => 'teamDetails']);
        $builder->connect('/admin/view-team/{sport}/{id}', ['controller' => 'Admin', 'action' => 'viewTeam'])
            ->setPatterns(['id' => '\d+', 'sport' => 'football|basketball|handball|volleyball|beachvolley'])
            ->setPass(['sport', 'id']);
        
        // Generic team status update (existing)
        $builder->connect('/admin/update-team-status', ['controller' => 'Admin', 'action' => 'updateTeamStatus']);
        
        // Sport-specific team status updates
        $builder->connect('/admin/update-football-team-status', ['controller' => 'Admin', 'action' => 'updateFootballTeamStatus']);
        $builder->connect('/admin/update-basketball-team-status', ['controller' => 'Admin', 'action' => 'updateBasketballTeamStatus']);
        $builder->connect('/admin/update-handball-team-status', ['controller' => 'Admin', 'action' => 'updateHandballTeamStatus']);
        $builder->connect('/admin/update-volleyball-team-status', ['controller' => 'Admin', 'action' => 'updateVolleyballTeamStatus']);
        $builder->connect('/admin/update-beachvolley-team-status', ['controller' => 'Admin', 'action' => 'updateBeachvolleyTeamStatus']);
        
        // Sport-specific verification notes
        $builder->connect('/admin/save-football-verification-notes', ['controller' => 'Admin', 'action' => 'saveFootballVerificationNotes']);
        $builder->connect('/admin/save-basketball-verification-notes', ['controller' => 'Admin', 'action' => 'saveBasketballVerificationNotes']);
        $builder->connect('/admin/save-handball-verification-notes', ['controller' => 'Admin', 'action' => 'saveHandballVerificationNotes']);
        $builder->connect('/admin/save-volleyball-verification-notes', ['controller' => 'Admin', 'action' => 'saveVolleyballVerificationNotes']);
        $builder->connect('/admin/save-beachvolley-verification-notes', ['controller' => 'Admin', 'action' => 'saveBeachvolleyVerificationNotes']);
        
        // Generic verification notes (existing)
        $builder->connect('/admin/save-verification-notes', ['controller' => 'Admin', 'action' => 'saveVerificationNotes']);
        
        /*
         * API routes for dynamic data
         */
        $builder->connect('/api/football-date-ranges', ['controller' => 'Teams', 'action' => 'getFootballDateRanges']);
        $builder->connect('/api/basketball-date-ranges', ['controller' => 'Teams', 'action' => 'getBasketballDateRanges']);
        $builder->connect('/api/handball-date-ranges', ['controller' => 'Teams', 'action' => 'getHandballDateRanges']);
        $builder->connect('/api/volleyball-date-ranges', ['controller' => 'Teams', 'action' => 'getVolleyballDateRanges']);
        $builder->connect('/api/beachvolley-date-ranges', ['controller' => 'Teams', 'action' => 'getBeachvolleyDateRanges']);
        $builder->connect('/teams/getCategories', ['controller' => 'Teams', 'action' => 'getCategories']);
        $builder->connect('/teams/getSports', ['controller' => 'Teams', 'action' => 'getSports']);

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * It is NOT recommended to use fallback routes after your initial prototyping phase!
         * See https://book.cakephp.org/5/en/development/routing.html#fallbacks-method for more information
         */
        $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
