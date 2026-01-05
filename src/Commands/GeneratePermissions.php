<?php

namespace ZakariaJawas\PermissionsGenerator\Commands;

use Illuminate\Console\Command;
use ZakariaJawas\PermissionsGenerator\PermissionsGenerator;

class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions rows';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Generate permissions and display them as a table
        $rows = (new PermissionsGenerator())->generate();

        if (is_array($rows) && count($rows) > 0) {
            $this->table(['name', 'guard_name'], $rows);
            $this->info('Permissions are generated, check your table.');
        } else {
            $this->info('No permissions were generated.');
        } //end if

        return Command::SUCCESS;
    }
}
