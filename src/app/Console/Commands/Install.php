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
            throw new \Exception("Copy .env.example as .env and set the values first", 1);
        }
        if (config('app.domain') === null) {
            throw new \Exception("Please set APP_DOMAIN in .env file", 1);
        }

        if (!$this->option('fresh') || ($this->option('fresh') && $this->option('force'))) {
            $this->publishConfig();
        }
        $this->migratingTable();
        $this->seedingTable();
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
        $this->process('php artisan vendor:publish --tag=zetthroutes --force');
        // $this->process('php artisan vendor:publish --tag=zetthmigrate --force');
        $this->info('Publish files finished!');
        $this->line('');
    }

    public function migratingTable()
    {
        if ($this->option('fresh')) {

            $colname = 'Tables_in_' . getDatabaseName();
            $tables = \DB::select('SHOW TABLES');
            $droplist = [];
            foreach ($tables as $table) {
                $droplist[] = $table->{$colname};
            }
            $droplist = implode(',', $droplist);

            if (!empty($droplist)) {
                $this->info('Freshing migration tables');

                \DB::beginTransaction();
                //turn off referential integrity
                \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                \DB::statement("DROP TABLE $droplist");
                //turn referential integrity back on
                \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                \DB::commit();

                $this->info('Tables cleared');
                $this->info('');
            }
        }

        $defaultMigrationPath = dirname(__DIR__) . '/../../database/migrations';

        $this->info('Migrating default table');
        $this->process('php artisan migrate --realpath --path=' . $defaultMigrationPath . $this->force);
        $this->info('Migrating default table finished!');
        $this->line('');
        $this->info('Migrating additional table');
        $this->process('php artisan migrate' . $this->force);
        $this->info('Migrating additional table finished!');
        $this->line('');
    }

    public function seedingTable()
    {
        $this->info('Seeding default seeder');
        $this->process('php artisan db:seed --class="\ZetthCore\Seeder\ZetthSeeder"' . $this->force);
        $this->info('Seeding default seeder finished!');
        $this->line('');
        $this->info('Seeding additional seeder');
        $this->process('php artisan db:seed' . $this->force);
        $this->info('Seeding additional seeder finished!');
        $this->line('');
    }

    public function linkFolders()
    {
        $this->call('zetth:link');
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
