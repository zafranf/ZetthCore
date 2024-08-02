<?php

namespace ZetthCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class Link extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zetth:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Linking storage and filemanager folders';

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
        $this->info('Link folders');

        $this->info('Linking storage folder');
        if (is_link(public_path('storage'))) {
            $this->info('The [public/storage] directory already linked.');
        } else {
            $this->call('storage:link');
            $this->info('The [public/storage] directory has been linked.');
        }

        /* set filemanager root path */
        // $filemanager_path = dirname(__DIR__) . '/../../resources/assets/filemanager';

        /* linking public/files to filemanager */
        /* $this->info('Linking assets files filemanager folder');
        if (is_link($filemanager_path . '/upload')) {
            $this->info('The [files] directory already linked.');
        } else {
            $files_path = storage_path('app/public/assets/images/upload');
            $this->process('cd ' . $filemanager_path . ' && ln -s ' . $files_path . ' && cd ' . base_path());
            $this->info('The [files] directory has been linked.');
        } */

        /* linking public/thumbs to filemanager */
        /* $this->info('Linking assets thumbs filemanager folder');
        if (is_link($filemanager_path . '/thumbs')) {
            $this->info('The [thumbs] directory already linked.');
        } else {
            $thumbs_path = storage_path('app/public/assets/thumbs');
            $this->process('cd ' . $filemanager_path . ' && ln -s ' . $thumbs_path . ' && cd ' . base_path());
            $this->info('The [thumbs] directory has been linked.');
        } */

        $this->info('Link folders finished!');
    }

    public function process($command)
    {
        Process::timeout($this->timeout)->run($command);
    }
}
