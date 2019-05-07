<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install {--fresh}';

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
        $this->process('composer dump-autoload');

        $this->publishConfig();
        $this->line('');
        $this->migratingTable();
        $this->line('');
        $this->seedingTable();
        // $this->line('');
        // $this->createRoles();
    }

    public function publishConfig()
    {
        $this->info('Publishing package configuration files');
        $this->process('php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5" --force');
        $this->process('php artisan vendor:publish --provider="RenatoMarinho\LaravelPageSpeed\ServiceProvider" --force');
        $this->process('php artisan vendor:publish --tag=datatables --force');
        // $this->process('php artisan vendor:publish --tag="laratrust" --force');
        $this->process('php artisan vendor:publish --tag=zetthtrust --force');
        // $this->process('php artisan vendor:publish --tag=zetthmigrate --force');
        $this->info('Publish config finished!');
    }

    public function migratingTable()
    {
        if ($this->option('fresh')) {
            $this->info('Freshing migration tables');
            $process = new Process('php artisan migrate:fresh');
        } else {
            $this->info('Migrating tables');
            $process = new Process('php artisan migrate');
        }
        $process->setTimeout($this->timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        $this->info('Migration finished!');
    }

    public function seedingTable()
    {
        $this->info('Seeding tables');
        $process = new Process('php artisan db:seed --class=ZetthSeeder');
        $process->setTimeout($this->timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        $this->info('Seeding finished!');
    }

    public function process($command)
    {
        $process = new Process($command);
        $process->setTimeout($this->timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
