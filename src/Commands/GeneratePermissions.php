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
        //do stuff
        
        echo (new PermissionsGenerator())->generate();
        return Command::SUCCESS;        
    }
}
