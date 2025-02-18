<?php

namespace App\Http\Modules\Authentication\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * The reset password mail class.
 *
 * @package App\Http\Modules\Authentication\Mail
 * @extends Mailable
 * @implements ShouldQueue
 *
 * *****Traits*****
 * @use Queueable
 * @use SerializesModels
 *
 * *****Properties*****
 * @property User $user
 * @property string $resetPasswordUrl
 * @property string $locale
 *
 * *****Methods*****
 * @method void __construct(User $user, string $resetPasswordUrl)
 * @method string preferredLocale()
 * @method Envelope envelope()
 * @method Content content()
 * @method array attachments()
 */
class ResetPasswordMail extends Mailable implements ShouldQueue
{
  use Queueable, SerializesModels;

  /**
   * The user instance.
   *
   * @var User
   */
  public User $user;

  /**
   * The token instance.
   *
   * @var string
   */
  public string $resetPasswordUrl;

  /**
   * The locale instance.
   *
   * @var string
   */
  public $locale;

  /**
   * Create a new message instance.
   */
  public function __construct(User $user, string $resetPasswordUrl)
  {
    $this->user = $user;
    $this->resetPasswordUrl = $resetPasswordUrl;
  }

  /**
   * Get the user's preferred locale.
   *
   * @return string
   */
  public function preferredLocale(): string
  {
    return $this->locale;
  }

  /**
   * Get the message envelope.
   *
   * @return Envelope
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      to: $this->user->email,
      replyTo: [Env("MAIL_REPLY_TO_ADDRESS")],
      subject: __("authentication.emails.reset_password.subject"),
      tags: ["reset-password", "forgot-password", "reset", "forgot", "password"]
    );
  }

  /**
   * Get the message content definition.
   *
   * @return Content
   */
  public function content(): Content
  {
    return new Content(view: "emails.reset-password");
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, Attachment>
   */
  public function attachments(): array
  {
    return [];
  }
}
