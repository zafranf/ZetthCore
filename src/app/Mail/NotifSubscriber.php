<?php

namespace ZetthCore\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentReply extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
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
        $from = env('MAIL_USERNAME', 'no-reply@' . env('APP_DOMAIN'));
        $subject = '[' . env('APP_NAME') . '] Artikel baru "' . $data['post']->title . '"';

        /* set view file */
        $file = env('NEW_POST_VIEW', 'zetthcore::AdminSC.emails.new_post');
        $view = getEmailFile($file);

        return $this->from($from)
            ->subject($subject)
            ->view($view)
            ->with($this->data);
    }
}
