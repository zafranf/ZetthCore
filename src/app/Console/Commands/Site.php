<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;

class Site extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Status Site';

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
        $this->info('Checking status site');

        $status = '-';
        $site = \ZetthCore\Models\Site::first();
        $update = !in_array($site->status, ['active', 'suspend']) && now()->greaterThanOrEqualTo($site->active_at);
        $this->info('Status: ' . $site->status);
        $this->info('Update status? ' . ($update ? 'yes' : 'no'));
        if ($update) {
            $status = $site->status;

            /* set active */
            $site->status = 'active';
            $site->save();

            /* clear cache */
            \Cache::flush();

            /* send notif to subscriber */
            \ZetthCore\Jobs\Launch::dispatch($status);

            $this->info('Status updated!');
        }

        $this->info('No status change');
    }

}
