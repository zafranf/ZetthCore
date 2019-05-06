<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use ZetthCore\Models\Permission;
use ZetthCore\Models\Role;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install ZetthCMS Core Admin Panel';

    /**
     * Clear timeout while executing command
     *
     * @var integer
     */
    protected $timeout = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        set_time_limit($this->timeout);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->migratingTable();
        $this->line('');
        $this->seedingTable();
        // $this->line('');
        // $this->createRoles();
    }

    public function migratingTable()
    {
        $this->info('Migrating tables');
        $process = new Process('php artisan migrate');
        $process->setTimeout($this->timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        $this->info('Migration finished!');
    }

    public function seedingTable()
    {
        $this->info('Seeding tables');
        $process = new Process('php artisan db:seed');
        $process->setTimeout($this->timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        $this->info('Seeding finished!');
    }

    public function createRoles()
    {
        $this->info('Creating \'super\' role');
        $super = new Role();
        $super->name = 'super';
        $super->display_name = 'Super User';
        $super->description = 'This user has super power';
        $super->save();
        $this->info('Role \'super\' has been created');

        $this->info('Creating \'admin\' role');
        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'Administrator';
        $admin->description = 'This user is allowed to manage application';
        $admin->save();
        $this->info('Role \'admin\' has been created');
        /* }

        public function createPermissions()
        { */
        $readHome = new Permission();
        $readHome->name = 'read-home';
        $readHome->display_name = 'View Home';
        $readHome->description = 'view home page';
        $readHome->save();

        $editSetApp = new Permission();
        $editSetApp->name = 'read-application';
        $editSetApp->display_name = 'read Application Setting';
        $editSetApp->description = 'read application setting';
        $editSetApp->save();

        $editSetApp = new Permission();
        $editSetApp->name = 'update-application';
        $editSetApp->display_name = 'update Application Setting';
        $editSetApp->description = 'update application setting';
        $editSetApp->save();
    }
}
