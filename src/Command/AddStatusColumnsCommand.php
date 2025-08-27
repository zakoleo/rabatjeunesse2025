<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

/**
 * AddStatusColumns command.
 */
class AddStatusColumnsCommand extends Command
{
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Add status columns to all team tables.');
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Adding status columns to team tables...');

        $connection = ConnectionManager::get('default');
        $tables = ['teams', 'basketball_teams', 'handball_teams', 'volleyball_teams', 'beachvolley_teams'];
        
        foreach ($tables as $table) {
            $io->out("Processing table: {$table}");
            
            try {
                // Check if status column exists
                $query = $connection->execute("SHOW COLUMNS FROM {$table} LIKE 'status'");
                $exists = $query->fetch();
                
                if (!$exists) {
                    // Add status columns
                    $queries = [
                        "ALTER TABLE {$table} ADD COLUMN status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending'",
                        "ALTER TABLE {$table} ADD COLUMN verified_at DATETIME NULL",
                        "ALTER TABLE {$table} ADD COLUMN verified_by INT NULL",
                        "ALTER TABLE {$table} ADD COLUMN verification_notes TEXT NULL"
                    ];
                    
                    foreach ($queries as $query) {
                        try {
                            $connection->execute($query);
                            $io->out("  ✓ Added column");
                        } catch (\Exception $e) {
                            if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                                $io->out("  - Column already exists");
                            } else {
                                $io->error("  ✗ Error: " . $e->getMessage());
                            }
                        }
                    }
                } else {
                    $io->out("  - Status columns already exist");
                }
                
            } catch (\Exception $e) {
                $io->error("Error processing table {$table}: " . $e->getMessage());
            }
        }
        
        $io->out('');
        $io->out('<success>✅ Status columns added successfully!</success>');
        $io->out('');
        $io->out('Columns added:');
        $io->out('- status: ENUM(\'pending\', \'verified\', \'rejected\') DEFAULT \'pending\'');
        $io->out('- verified_at: DATETIME NULL');
        $io->out('- verified_by: INT NULL');
        $io->out('- verification_notes: TEXT NULL');

        return static::CODE_SUCCESS;
    }
}