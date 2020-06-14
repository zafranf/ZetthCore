<?php

namespace ZetthCore\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ZetthCore\Traits\MainTrait;

class Main extends Mailable
{
    use Queueable, SerializesModels, MainTrait;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /* set variable */
        $from = $this->data['from'] ?? config('mail.username');

        /* set view file */
        $view = getEmailFile($this->data['view']);

        return $this->from($from)
            ->subject($this->data['subject'])
            ->view($view)
            ->with($this->data);
    }
}
