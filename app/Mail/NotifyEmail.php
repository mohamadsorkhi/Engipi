<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $title;
    private $text;
    private $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($text, $title = null,$url = null)
    {
        $this->text = $text;
        $this->title = $title;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->title ?? Setting::getValue("name"),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $settingsObject = Setting::whereIn("key", [
            "name",
            "name2",
            "logo_png"
        ])->get();
        $settings = array();
        foreach ($settingsObject as $setting) {
            $settings[$setting->key] = $setting->value;
        }
        return new Content(
            markdown: 'emails.notifyEmail',
            with: [
                'subject' => $this->subject,
                'settings' => $settings,
                'text' => $this->text,
                'url' => $this->url,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
