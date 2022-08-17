<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->to($this->mailData['to'])
            ->subject($this->mailData['subject'])
            ->view($this->mailData['view'])
            ->with($this->mailData['params']);

        if (isset($this->mailData['fromAddress']) && isset($this->mailData['fromName'])) {
            $mail->from($this->mailData['fromAddress'], $this->mailData['fromName']);
        }

        if (isset($this->mailData['attachment'])) {
            $mail->attach($this->mailData['attachment'], [
                'mime' => $this->mailData['fileType']]
            );
        }

        return $mail;
    }
}
