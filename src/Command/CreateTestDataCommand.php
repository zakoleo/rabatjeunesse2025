<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

/**
 * CreateTestData command.
 */
class CreateTestDataCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Create test data for admin panel testing.');

        return $parser;
    }

    /**
     * Execute the command
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Creating test data for admin panel...');

        // Create test users first
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        
        $testUsers = [
            ['username' => 'testuser1', 'email' => 'user1@test.com', 'password' => 'test123'],
            ['username' => 'testuser2', 'email' => 'user2@test.com', 'password' => 'test123'],
            ['username' => 'testuser3', 'email' => 'user3@test.com', 'password' => 'test123']
        ];

        $userIds = [];
        foreach ($testUsers as $userData) {
            $existingUser = $usersTable->find()
                ->where(['email' => $userData['email']])
                ->first();

            if (!$existingUser) {
                $user = $usersTable->newEmptyEntity();
                $user = $usersTable->patchEntity($user, $userData);
                if ($usersTable->save($user)) {
                    $userIds[] = $user->id;
                    $io->out("Created user: {$userData['username']}");
                }
            } else {
                $userIds[] = $existingUser->id;
                $io->out("User already exists: {$userData['username']}");
            }
        }

        // Create test basketball teams
        if (!empty($userIds)) {
            $basketballTable = TableRegistry::getTableLocator()->get('BasketballTeams');
            
            $testTeams = [
                [
                    'nom_equipe' => 'Test Basketball Team 1',
                    'categorie' => 'Senior',
                    'genre' => 'Homme',
                    'type_basketball' => '5x5',
                    'district' => 'Rabat',
                    'organisation' => 'Club Test 1',
                    'adresse' => 'Adresse Test 1',
                    'user_id' => $userIds[0],
                    'responsable_nom_complet' => 'Responsable Test 1',
                    'responsable_tel' => '0612345678'
                ],
                [
                    'nom_equipe' => 'Test Basketball Team 2',
                    'categorie' => 'Junior',
                    'genre' => 'Femme',
                    'type_basketball' => '3x3',
                    'district' => 'Casablanca',
                    'organisation' => 'Club Test 2',
                    'adresse' => 'Adresse Test 2',
                    'user_id' => $userIds[1] ?? $userIds[0],
                    'responsable_nom_complet' => 'Responsable Test 2',
                    'responsable_tel' => '0612345679'
                ]
            ];

            foreach ($testTeams as $teamData) {
                $existingTeam = $basketballTable->find()
                    ->where(['nom_equipe' => $teamData['nom_equipe']])
                    ->first();

                if (!$existingTeam) {
                    $team = $basketballTable->newEmptyEntity();
                    $team = $basketballTable->patchEntity($team, $teamData);
                    if ($basketballTable->save($team)) {
                        $io->out("Created basketball team: {$teamData['nom_equipe']}");
                    }
                } else {
                    $io->out("Basketball team already exists: {$teamData['nom_equipe']}");
                }
            }
        }

        // Create test volleyball teams
        if (!empty($userIds)) {
            $volleyballTable = TableRegistry::getTableLocator()->get('VolleyballTeams');
            
            $testVolleyballTeams = [
                [
                    'nom_equipe' => 'Test Volleyball Team 1',
                    'categorie' => 'Senior',
                    'genre' => 'Mixte',
                    'type_volleyball' => '6x6',
                    'district' => 'Rabat',
                    'organisation' => 'Club Volleyball Test',
                    'adresse' => 'Adresse Volleyball Test',
                    'user_id' => $userIds[0],
                    'responsable_nom_complet' => 'Responsable Volleyball',
                    'responsable_tel' => '0612345680'
                ]
            ];

            foreach ($testVolleyballTeams as $teamData) {
                $existingTeam = $volleyballTable->find()
                    ->where(['nom_equipe' => $teamData['nom_equipe']])
                    ->first();

                if (!$existingTeam) {
                    $team = $volleyballTable->newEmptyEntity();
                    $team = $volleyballTable->patchEntity($team, $teamData);
                    if ($volleyballTable->save($team)) {
                        $io->out("Created volleyball team: {$teamData['nom_equipe']}");
                    }
                } else {
                    $io->out("Volleyball team already exists: {$teamData['nom_equipe']}");
                }
            }
        }

        // Create test beach volleyball teams
        if (!empty($userIds)) {
            $beachvolleyTable = TableRegistry::getTableLocator()->get('BeachvolleyTeams');
            
            $testBeachvolleyTeams = [
                [
                    'nom_equipe' => 'Test Beach Volleyball Duo',
                    'categorie' => 'Senior',
                    'genre' => 'Homme',
                    'type_beachvolley' => '2x2',
                    'district' => 'Rabat',
                    'organisation' => 'Beach Club Test',
                    'adresse' => 'Plage Test',
                    'user_id' => $userIds[2] ?? $userIds[0],
                    'responsable_nom_complet' => 'Responsable Beach',
                    'responsable_tel' => '0612345681'
                ]
            ];

            foreach ($testBeachvolleyTeams as $teamData) {
                $existingTeam = $beachvolleyTable->find()
                    ->where(['nom_equipe' => $teamData['nom_equipe']])
                    ->first();

                if (!$existingTeam) {
                    $team = $beachvolleyTable->newEmptyEntity();
                    $team = $beachvolleyTable->patchEntity($team, $teamData);
                    if ($beachvolleyTable->save($team)) {
                        $io->out("Created beach volleyball team: {$teamData['nom_equipe']}");
                    }
                } else {
                    $io->out("Beach volleyball team already exists: {$teamData['nom_equipe']}");
                }
            }
        }

        $io->out('');
        $io->out('<success>✅ Test data created successfully!</success>');
        $io->out('');
        $io->out('<info>You can now test the admin panel with sample data:</info>');
        $io->out('1. Login as admin (admin@rabatjeunesse.ma / admin123)');
        $io->out('2. Go to /admin to see the dashboard');
        $io->out('3. Go to /admin/teams to manage teams');
        $io->out('4. Click on team details to verify teams');

        return static::CODE_SUCCESS;
    }
}