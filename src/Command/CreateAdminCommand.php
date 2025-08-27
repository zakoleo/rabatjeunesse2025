<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * CreateAdmin command.
 */
class CreateAdminCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Create an admin user for testing the admin panel.');

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Creating admin user for testing...');

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        
        // Check if admin user already exists
        $existingUser = $usersTable->find()
            ->where(['OR' => [
                ['email' => 'admin@rabatjeunesse.ma'],
                ['username' => 'admin']
            ]])
            ->first();

        if ($existingUser) {
            $io->out('<warning>Admin user already exists. Updating...</warning>');
            
            // Update existing user
            $existingUser->password = 'admin123';
            if ($usersTable->getSchema()->hasColumn('is_admin')) {
                $existingUser->is_admin = true;
            }
            
            if ($usersTable->save($existingUser)) {
                $io->out('<success>✅ Admin user updated successfully!</success>');
            } else {
                $io->error('❌ Failed to update admin user.');
                return static::CODE_ERROR;
            }
        } else {
            // Create new admin user
            $adminUser = $usersTable->newEmptyEntity();
            $adminUser->username = 'admin';
            $adminUser->email = 'admin@rabatjeunesse.ma';
            $adminUser->password = 'admin123';
            
            if ($usersTable->getSchema()->hasColumn('is_admin')) {
                $adminUser->is_admin = true;
            }

            if ($usersTable->save($adminUser)) {
                $io->out('<success>✅ Admin user created successfully!</success>');
            } else {
                $io->error('❌ Failed to create admin user.');
                $errors = $adminUser->getErrors();
                foreach ($errors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $io->error("  - $field: $error");
                    }
                }
                return static::CODE_ERROR;
            }
        }

        $io->out('');
        $io->out('<info>=== ADMIN CREDENTIALS ===</info>');
        $io->out('Email: admin@rabatjeunesse.ma');
        $io->out('Username: admin');
        $io->out('Password: admin123');
        $io->out('<info>==========================</info>');
        $io->out('');
        $io->out('You can now:');
        $io->out('1. Go to: http://localhost/rabatjeunesse2025/users/login');
        $io->out('2. Login with the credentials above');
        $io->out('3. Access admin panel at: http://localhost/rabatjeunesse2025/admin');

        return static::CODE_SUCCESS;
    }
}