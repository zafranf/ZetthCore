<?php

namespace ZetthCore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZetthCore\Traits\MainTrait;

class CommentReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MainTrait;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parent = $this->data['parent'];
        $post = $this->data['post'];
        $comment = $this->data['comment'];

        if (bool($parent->notify) && !bool($parent->is_owner)) {
            /* send mail */
            \Mail::to($parent->email)->queue(new \App\Mail\CommentReply($post, $comment));

            /* log sent mail */
            $sent_mails[] = $parent->email;

            /* send notif to subcomments */
            $subcomments = $parent->subcomments_all;
            foreach ($subcomments as $subcomment) {
                if (bool($subcomment->notify) && !bool($subcomment->is_owner) && $subcomment->created_by != $comment->created_by && !in_array($subcomment->email, $sent_mails)) {
                    /* send mail */
                    \Mail::to($subcomment->email)->queue(new \App\Mail\CommentReply($post, $comment));

                    /* log sent mail */
                    $sent_mails[] = $subcomment->email;
                }
            }
        }
    }
}
