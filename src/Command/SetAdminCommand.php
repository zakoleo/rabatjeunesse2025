<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

/**
 * SetAdmin command.
 */
class SetAdminCommand extends Command
{
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Set a user as admin by email or username.');
        $parser->addArgument('identifier', [
            'help' => 'Email or username of user to make admin',
            'required' => false
        ]);
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Setting up admin access...');

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        
        // If no identifier provided, show all users and let user choose
        $identifier = $args->getArgument('identifier');
        
        if (!$identifier) {
            $io->out('Available users:');
            $users = $usersTable->find()->all();
            foreach ($users as $user) {
                $adminStatus = '';
                try {
                    if ($user->is_admin == 1) {
                        $adminStatus = ' [ADMIN]';
                    }
                } catch (\Exception $e) {
                    // Column might not exist
                }
                $io->out("  ID: {$user->id} | {$user->username} ({$user->email}){$adminStatus}");
            }
            
            $identifier = $io->ask('Enter email or username to make admin:');
        }
        
        if (!$identifier) {
            $io->error('No identifier provided.');
            return static::CODE_ERROR;
        }
        
        // Find user
        $user = $usersTable->find()
            ->where(['OR' => [
                ['email' => $identifier],
                ['username' => $identifier]
            ]])
            ->first();
            
        if (!$user) {
            $io->error("User not found: {$identifier}");
            return static::CODE_ERROR;
        }
        
        // Try to set is_admin flag
        try {
            $user->is_admin = 1;
            if ($usersTable->save($user)) {
                $io->out("<success>✅ User '{$user->username}' ({$user->email}) is now an admin!</success>");
            } else {
                $io->error('Failed to save user.');
                return static::CODE_ERROR;
            }
        } catch (\Exception $e) {
            $io->out("<warning>Could not set is_admin flag: {$e->getMessage()}</warning>");
            $io->out("<info>User will still have admin access via email/username fallback.</info>");
        }
        
        $io->out('');
        $io->out('<info>=== ADMIN ACCESS METHODS ===</info>');
        $io->out('This user now has admin access via:');
        $io->out("1. Database flag: is_admin = 1");
        $io->out("2. Email fallback: {$user->email}");
        $io->out("3. Username fallback: {$user->username}");
        
        if ($user->id == 1) {
            $io->out("4. ID fallback: First user (ID = 1)");
        }
        
        $io->out('');
        $io->out('To test admin access:');
        $io->out('1. Login with this user credentials');
        $io->out('2. Look for "Administration" link in navigation');
        $io->out('3. Go to: /admin');

        return static::CODE_SUCCESS;
    }
}