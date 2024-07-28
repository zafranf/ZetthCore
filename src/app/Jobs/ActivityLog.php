<?php

namespace ZetthCore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivityLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $description;
    protected $user;
    protected $req;
    protected $serv;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($description, $user, $req, $serv)
    {
        $this->description = $description;
        $this->user = $user;
        $this->req = $req;
        $this->serv = $serv;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $act = new \ZetthCore\Models\ActivityLog;
        $act->description = $this->description;
        $act->method = $this->req['method'];
        $act->path = $this->req['path'] ?? '-';
        $act->ip = getUserIP($this->serv);
        $act->headers = json_encode($this->req['header']);
        $act->get = json_encode($this->req['query']);
        $act->post = json_encode($this->req['post']);
        $act->files = json_encode($this->req['files']);
        $act->user_id = $this->user->id ?? (app('user')->id ?? null);
        $act->site_id = app('site')->id;
        $act->save();
    }
}
