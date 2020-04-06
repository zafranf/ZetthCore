<?php

namespace ZetthCore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZetthCore\Models\Post;
use ZetthCore\Traits\MainTrait;

class NotifSubscriber implements ShouldQueue
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
        $subscribers = \ZetthCore\Models\Subscriber::active()->get();

        /* check subscribers */
        if (count($subscribers)) {
            foreach ($subscribers as $subscriber) {
                $this->sendMail([
                    'view' => env('NEW_POST_VIEW', 'zetthcore::AdminSC.emails.new_post'),
                    'data' => [
                        'site' => getSiteConfig(),
                        'post' => $this->post,
                    ],
                    'from' => env('MAIL_USERNAME', 'no-reply@' . env('APP_DOMAIN')),
                    'to' => $subscriber->email,
                    'subject' => '[' . env('APP_NAME') . '] Artikel baru "' . $this->post->title . '"',
                ]);

                /* delay */
                sleep(1 / 60);
            }
        }
    }
}
