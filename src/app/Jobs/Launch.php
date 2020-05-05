<?php

namespace ZetthCore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Launch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* get subscribers */
        $subscribers = \ZetthCore\Models\Subscriber::select('email')->active()->get();

        /* check subscribers */
        if (count($subscribers)) {
            foreach ($subscribers as $subscriber) {
                /* send mail */
                \Mail::to($subscriber->email)->queue(new \App\Mail\Launch());

                /* delay */
                sleep(1 / 60);
            }
        }
    }
}
