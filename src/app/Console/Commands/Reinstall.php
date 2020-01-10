<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Reinstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zetth:reinstall {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reinstall ZetthCore';

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
        $this->info('Reinstalling application');

        /* execute reinstall */
        $force = $this->option('force') ? ' --force' : '';
        $process = new Process('php artisan zetth:install --fresh' . $force);
        $process->setTimeout($this->timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        $this->info('Reinstall finished!');
    }

}
