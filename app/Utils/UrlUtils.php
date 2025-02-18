<?php

namespace App\Utils;

trait UrlUtils
{
  /**
   * Build the reset password URL.
   *
   * @param  string  $token
   * @param  string  $email
   * @return string
   */
  private function resetPasswordUrl(string $token, string $email): string
  {
    return Env("APP_FRONTEND_URL") . "/reset-password?token=" . $token . "&email=" . $email;
  }
}
