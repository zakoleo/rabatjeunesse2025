<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

/**
 * TestAdmin command.
 */
class TestAdminCommand extends Command
{
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Test admin functionality.');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Testing admin system...');
        $io->out('');

        // Test 1: Check admin users
        $io->out('1. Checking admin users:');
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $users = $usersTable->find()->all();
        
        $adminCount = 0;
        foreach ($users as $user) {
            $isAdmin = false;
            
            // Test admin detection logic
            try {
                if ($user->is_admin == 1 || $user->is_admin === true) {
                    $isAdmin = true;
                }
            } catch (\Exception $e) {
                // Field might not exist
            }
            
            if (!$isAdmin) {
                $adminEmails = ['admin@lifemoz.com', 'zouhair@gmail.com', 'admin@rabatjeunesse.ma', 'zouhair@lifemoz.com'];
                $adminUsernames = ['admin', 'administrator', 'admin1', 'Zouhair'];
                
                if (in_array($user->email, $adminEmails) || 
                    in_array($user->username, $adminUsernames) || 
                    $user->id == 1) {
                    $isAdmin = true;
                }
            }
            
            if ($isAdmin) {
                $adminCount++;
                $io->out("   ✓ {$user->username} ({$user->email}) - Admin access");
            }
        }
        
        if ($adminCount > 0) {
            $io->out("<success>   Found {$adminCount} admin user(s)</success>");
        } else {
            $io->out("<error>   No admin users found!</error>");
        }
        
        $io->out('');
        
        // Test 2: Check tables
        $io->out('2. Checking database tables:');
        $tables = [
            'Teams' => 'football',
            'BasketballTeams' => 'basketball', 
            'HandballTeams' => 'handball',
            'VolleyballTeams' => 'volleyball',
            'BeachvolleyTeams' => 'beachvolley'
        ];
        
        $workingTables = 0;
        foreach ($tables as $tableName => $sportType) {
            try {
                $table = TableRegistry::getTableLocator()->get($tableName);
                $count = $table->find()->count();
                $io->out("   ✓ {$tableName}: {$count} records");
                $workingTables++;
            } catch (\Exception $e) {
                $io->out("   ✗ {$tableName}: Error - " . $e->getMessage());
            }
        }
        
        $io->out("<success>   {$workingTables}/{count($tables)} tables working</success>");
        $io->out('');
        
        // Test 3: URLs to test
        $io->out('3. URLs to test manually:');
        $io->out('   Login: http://localhost/rabatjeunesse2025/users/login');
        $io->out('   Admin Dashboard: http://localhost/rabatjeunesse2025/admin');
        $io->out('   Team Management: http://localhost/rabatjeunesse2025/admin/teams');
        $io->out('   User Management: http://localhost/rabatjeunesse2025/admin/users');
        
        $io->out('');
        $io->out('<info>=== TESTING CHECKLIST ===</info>');
        $io->out('□ 1. Login with admin credentials');
        $io->out('□ 2. Check if "Administration" link appears in navigation');
        $io->out('□ 3. Access /admin dashboard');
        $io->out('□ 4. View team management page');
        $io->out('□ 5. View user management page');
        $io->out('□ 6. Try to verify a team');
        
        $io->out('');
        $io->out('<success>✅ Admin system test complete!</success>');

        return static::CODE_SUCCESS;
    }
}