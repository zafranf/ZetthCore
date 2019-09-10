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
    protected $signature = 'zetth:install {--fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install ZetthCore';

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
        if (!file_exists(base_path('.env'))) {
            throw new \Exception("Salin berkas .env.example sebagai .env kemudian atur semua nilainya", 1);
        } else if (env('APP_DOMAIN') === null) {
            throw new \Exception("Harap atur APP_DOMAIN di dalam berkas .env", 1);
        }

        $this->publishConfig();
        $this->line('');
        $this->migratingTable();
        $this->line('');
        $this->seedingTable();
        $this->line('');
        $this->linkFolders();
    }

    public function publishConfig()
    {
        $this->info('Publishing package files');
        // $this->process('php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5" --force');
        $this->process('php artisan vendor:publish --provider="RenatoMarinho\LaravelPageSpeed\ServiceProvider" --force');
        $this->process('php artisan vendor:publish --tag=datatables --force');
        // $this->process('php artisan vendor:publish --tag="laratrust" --force');
        $this->process('php artisan vendor:publish --tag=zetthconfig --force');
        // $this->process('php artisan vendor:publish --tag=zetthauth --force');
        $this->process('php artisan vendor:publish --tag=zetthhandler --force');
        $this->process('php artisan vendor:publish --tag=zetthmiddleware --force');
        $this->process('php artisan vendor:publish --tag=zetthroutes --force');
        // $this->process('php artisan vendor:publish --tag=zetthmigrate --force');
        $this->info('Publish files finished!');
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

    public function linkFolders()
    {
        $this->info('Link folders');
        $this->info('');
        $this->info('Linking storage folder');
        $process = new Process('php artisan storage:link');
        $process->setTimeout($this->timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        $this->info('');
        $this->info('Linking filemanager folder');
        $filemanager_path = base_path('vendor/zafranf/zetthcore/src/resources/themes/AdminSC/plugins');
        $process = new Process('cd ' . public_path() . ' && ln -s ' . $filemanager_path . ' && cd ' . base_path());
        $process->setTimeout($this->timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        $this->info('Link folders finished!');
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
