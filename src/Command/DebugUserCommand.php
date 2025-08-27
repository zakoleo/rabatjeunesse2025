<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

/**
 * DebugUser command.
 */
class DebugUserCommand extends Command
{
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Debug user data structure.');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Debugging user data structure...');

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        
        // Get the admin user
        $adminUser = $usersTable->find()
            ->where(['email' => 'admin@rabatjeunesse.ma'])
            ->first();

        if ($adminUser) {
            $io->out('Admin user found:');
            $io->out('ID: ' . $adminUser->id);
            $io->out('Username: ' . $adminUser->username);
            $io->out('Email: ' . $adminUser->email);
            
            // Debug all properties
            $io->out('All properties:');
            foreach ($adminUser->toArray() as $key => $value) {
                if ($key !== 'password') {
                    $io->out("  $key: " . ($value === true ? 'true' : ($value === false ? 'false' : $value)));
                }
            }
            
            // Check specific properties
            if (property_exists($adminUser, 'is_admin')) {
                $io->out('is_admin property exists: ' . ($adminUser->is_admin ? 'true' : 'false'));
            } else {
                $io->out('is_admin property does NOT exist');
            }
            
            if (isset($adminUser['is_admin'])) {
                $io->out('is_admin array key exists: ' . ($adminUser['is_admin'] ? 'true' : 'false'));
            } else {
                $io->out('is_admin array key does NOT exist');
            }
            
        } else {
            $io->out('Admin user not found!');
        }

        return static::CODE_SUCCESS;
    }
}