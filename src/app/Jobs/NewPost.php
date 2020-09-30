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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* get subscribers */
        $subscribers = \ZetthCore\Models\Subscriber::active()->where('is_registered', 'no')->get();

        /* check subscribers */
        if (count($subscribers)) {
            foreach ($subscribers as $subscriber) {
                /* send mail */
                \Mail::to($subscriber->email)->queue(new \App\Mail\NewPost($this->post));

                /* delay */
                sleep(1 / 60);
            }
        }
    }
}
