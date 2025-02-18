<?php

namespace App\Helpers;

use App\Models\User;

/**
 * The OAuth helper trait.
 *
 * @package App\Helpers
 *
 * *****Methods*****
 * @method array getOAuth(User $user)
 */
trait OAuthHelper
{
  /**
   * Get the OAuth data of the user.
   *
   * @param User $user
   * @return array
   */
  private function getOAuth(User $user): array
  {
    return [
      "google" => !!$user->google_id,
    ];
  }
}