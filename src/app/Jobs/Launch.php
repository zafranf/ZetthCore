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

    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($status = 'comingsoon')
    {
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* get subscribers */
        $subscribers = \ZetthCore\Models\Subscriber::select('email')->active()->where('is_registered', 'no')->get();

        /* check subscribers */
        if (count($subscribers)) {
            foreach ($subscribers as $subscriber) {
                /* send mail */
                \Mail::to($subscriber->email)->queue(new \App\Mail\Launch($subscriber->email, $this->status));

                /* delay */
                $mps = 1 / config('mail.mps', 1) * 1000000;
                usleep($mps);
            }
        }
    }
}
