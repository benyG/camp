<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Smail;
use Illuminate\Mail\Mailables\Headers;

class Imail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected Smail $order, protected $para, protected $opt)
    {
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'MIME-Version' => '1.0',
              //  'From' => env('MAIL_FROM_ADDRESS', 'contact@cisspbootcamp.online'),
             //   'Reply-To' => env('MAIL_FROM_ADDRESS', 'contact@cisspbootcamp.online'),
                'X-Mailer' => 'PHP '.phpversion(),
            ],
        );
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->order->sub,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.mail'.$this->opt,
            text: 'emails.mail'.$this->opt.'-t',
            with: [
                'content' => $this->order->content,
                'para' => $this->para,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

}
