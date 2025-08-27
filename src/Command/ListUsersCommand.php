<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

/**
 * ListUsers command.
 */
class ListUsersCommand extends Command
{
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('List all users in database.');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Listing all users...');

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        
        $users = $usersTable->find()->all();
        
        if ($users->count() > 0) {
            $io->out('Found ' . $users->count() . ' users:');
            $io->out('');
            
            foreach ($users as $user) {
                $io->out("ID: {$user->id}");
                $io->out("Username: {$user->username}");
                $io->out("Email: {$user->email}");
                
                // Check is_admin in different ways
                $adminStatus = 'Unknown';
                if (property_exists($user, 'is_admin')) {
                    $adminStatus = $user->is_admin ? 'Yes' : 'No';
                } elseif (isset($user['is_admin'])) {
                    $adminStatus = $user['is_admin'] ? 'Yes' : 'No';
                }
                
                $io->out("Is Admin: {$adminStatus}");
                $io->out("Created: {$user->created}");
                $io->out('---');
            }
        } else {
            $io->out('No users found in database!');
        }

        return static::CODE_SUCCESS;
    }
}