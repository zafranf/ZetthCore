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
    protected $signature = 'zetth:install {--fresh} {--force}';

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

    protected $force = '';

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
        $this->force = $this->option('force') ? ' --force' : '';

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

        if ($this->option('fresh')) {
            $this->line('');
            $this->info('Clearing application cache');
            $this->process('php artisan cache:clear');
            $this->line('');
        }
    }

    public function publishConfig()
    {
        $this->info('Publishing package files');
        // $this->process('php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5" --force');
        // $this->process('php artisan vendor:publish --provider="RenatoMarinho\LaravelPageSpeed\ServiceProvider" --force');
        $this->process('php artisan vendor:publish --tag=datatables --force');
        // $this->process('php artisan vendor:publish --tag="laratrust" --force');
        $this->process('php artisan vendor:publish --tag=zetthkernel --force');
        $this->process('php artisan vendor:publish --tag=zetthconfig --force');
        // $this->process('php artisan vendor:publish --tag=zetthauth --force');
        $this->process('php artisan vendor:publish --tag=zetthhandler --force');
        $this->process('php artisan vendor:publish --tag=zetthmiddleware --force');
        // $this->process('php artisan vendor:publish --tag=zetthroutes --force');
        // $this->process('php artisan vendor:publish --tag=zetthmigrate --force');
        $this->info('Publish files finished!');
    }

    public function migratingTable()
    {
        if ($this->option('fresh')) {
            $this->info('Freshing migration tables');
            $this->process('php artisan migrate:fresh' . $this->force);
        } else {
            $this->info('Migrating tables');
            $this->process('php artisan migrate' . $this->force);
        }
        $this->info('Migration finished!');
    }

    public function seedingTable()
    {
        $this->info('Seeding default seeder');
        $this->process('php artisan db:seed --class="\ZetthCore\Seeder\ZetthSeeder"' . $this->force);
        $this->info('Seeding default seeder finished!');

        $this->info('Seeding additional seeder');
        $this->process('php artisan db:seed' . $this->force);
        $this->info('Seeding additional seeder finished!');
    }

    public function linkFolders()
    {
        $this->info('Link folders');

        $this->info('Linking storage folder');
        if ($this->option('fresh')) {
            if (is_link(public_path('storage'))) {
                $this->info('Removing "public/storage" folder');
                $this->process('cd ' . public_path() . ' && rm -rf storage && cd ' . base_path());
            }
        }
        $storage_path = storage_path('app/public');
        $this->process('cd ' . public_path() . ' && ln -s ' . $storage_path . ' storage && cd ' . base_path());
        $this->info('The [public/storage] directory has been linked.');

        /* link filemanager to public as larafile */
        $this->info('Linking filemanager folder');
        if ($this->option('fresh')) {
            if (is_link(public_path('larafile'))) {
                $this->info('Removing "public/larafile" folder');
                $this->process('cd ' . public_path() . ' && rm -rf larafile && cd ' . base_path());
            }
        }
        // $filemanager_path = storage_path('app/public/filemanager');
        $filemanager_path = base_path('vendor/zafranf/zetthcore/src/resources/assets/filemanager');
        // $this->process('cd ' . public_path() . ' && ln -s ' . $filemanager_path . ' larafile && cd ' . base_path());
        // $this->info('The [public/larafile] directory has been linked.');

        /* linking public/files to filemanager */
        $this->info('Linking assets filemanager folder');
        if ($this->option('fresh')) {
            if (is_link($filemanager_path . '/files')) {
                $this->info('Removing "filemanager/files" folder');
                $this->process('cd ' . $filemanager_path . ' && rm -rf files && cd ' . base_path());
            }
        }
        $files_path = storage_path('app/public/assets/files');
        $this->process('cd ' . $filemanager_path . ' && ln -s ' . $files_path . ' && cd ' . base_path());
        $this->info('The [files] directory has been linked.');

        /* linking public/thumbs to filemanager */
        $this->info('Linking assets thumbs filemanager folder');
        if ($this->option('fresh')) {
            if (is_link($filemanager_path . '/thumbs')) {
                $this->info('Removing "filemanager/thumbs" folder');
                $this->process('cd ' . $filemanager_path . ' && rm -rf thumbs && cd ' . base_path());
            }
        }
        $thumbs_path = storage_path('app/public/assets/thumbs');
        $this->process('cd ' . $filemanager_path . ' && ln -s ' . $thumbs_path . ' && cd ' . base_path());
        $this->info('The [thumbs] directory has been linked.');

        /* link filemanager-standalone to public as larafile-standalone */
        /* $this->info('Linking filemanager-standalone folder');
        if ($this->option('fresh')) {
        if (is_link(public_path('larafile-standalone'))) {
        $this->info('Removing "public/larafile-standalone" folder');
        $this->process('cd ' . public_path() . ' && rm -rf larafile-standalone && cd ' . base_path());
        }
        }
        $filemanager_standalone_path = base_path('vendor/zafranf/zetthcore/src/resources/themes/AdminSC/plugins/filemanager-standalone');
        $this->process('cd ' . public_path() . ' && ln -s ' . $filemanager_standalone_path . ' larafile-standalone && cd ' . base_path());
        $this->info('The [public/larafile-standalone] directory has been linked.'); */

        /* linking public/files to filemanager-standalone */
        /* $this->info('Linking assets filemanager-standalone folder');
        if ($this->option('fresh')) {
        if (is_link($filemanager_standalone_path . '/files')) {
        $this->info('Removing "filemanager/files" folder');
        $this->process('cd ' . $filemanager_standalone_path . ' && rm -rf files && cd ' . base_path());
        }
        }
        $files_path = storage_path('app/public/assets/files');
        $this->process('cd ' . $filemanager_standalone_path . ' && ln -s ' . $files_path . ' && cd ' . base_path());
        $this->info('The [files] directory has been linked.'); */

        /* linking public/thumbs to filemanager-standalone */
        /* $this->info('Linking assets thumbs filemanager-standalone folder');
        if ($this->option('fresh')) {
        if (is_link($filemanager_standalone_path . '/thumbs')) {
        $this->info('Removing "filemanager/thumbs" folder');
        $this->process('cd ' . $filemanager_standalone_path . ' && rm -rf thumbs && cd ' . base_path());
        }
        }
        $thumbs_path = storage_path('app/public/assets/thumbs');
        $this->process('cd ' . $filemanager_standalone_path . ' && ln -s ' . $thumbs_path . ' && cd ' . base_path());
        $this->info('The [thumbs] directory has been linked.'); */

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
