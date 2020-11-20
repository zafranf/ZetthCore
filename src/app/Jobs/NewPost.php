<?php

namespace ZetthCore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZetthCore\Models\Post;
use ZetthCore\Traits\MainTrait;

class NewPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MainTrait;

    private $post;
    private $to_subscribers;
    private $to_users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, $to_subscribers = true, $to_users = true)
    {
        $this->post = $post;
        $this->to_subscribers = $to_subscribers;
        $this->to_users = $to_users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sent = [];

        /* get subscribers */
        if ($this->to_subscribers) {
            $subscribers = \ZetthCore\Models\Subscriber::active()->where('is_registered', 'no')->get();

            /* check subscribers */
            if (count($subscribers)) {
                foreach ($subscribers as $subscriber) {
                    if (!in_array($subscriber->email, $sent)) {
                        /* send mail */
                        \Mail::to($subscriber->email)->queue(new \App\Mail\NewPost($this->post));
                        $sent[] = $subscriber->email;

                        /* delay */
                        sleep(1 / 60);
                    }
                }
            }
        }

        /* get usets */
        if ($this->to_users) {
            $users = \ZetthCore\Models\User::active()->get();

            /* check users */
            if (count($users)) {
                foreach ($users as $user) {
                    if (!in_array($user->email, $sent) && !$user->is_admin) {
                        /* send mail */
                        \Mail::to($user->email)->queue(new \App\Mail\NewPost($this->post));
                        $sent[] = $user->email;

                        /* delay */
                        sleep(1 / 60);
                    }
                }
            }
        }
    }
}
