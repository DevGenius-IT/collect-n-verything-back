<?php

namespace App\Providers\OAuth;

use App\Components\ExceptionHandler;
use App\Http\Modules\Authentication\Exceptions\OAuthException;
use App\Models\User;
use App\Utils\StringUtils;
use Illuminate\Support\Facades\Hash;

trait Google
{
  use StringUtils;
  
  /**
   * Handle the Google user.
   *
   * @param mixed $user
   * @param array $roles
   * @return User
   * @throws OAuthException
   */
  private function handleGoogleUser(mixed $user, array $roles): User
  {
    try {
      $userProvider = clone $user;
      $user = User::where("google_id", $user->id)->first();

      if ($user) {
        return $user;
      } else {
        return $this->createGoogleUser($userProvider, $roles);
      }
    } catch (ExceptionHandler $e) {
      throw new OAuthException(
        "Failed to handle the Google user. Please try again later.",
        null,
        400
      );
    }
  }

  /**
   * Create the user with the Google data.
   *
   * @param  mixed  $data
   * @param  array  $roles
   * @return User
   */
  private function createGoogleUser(mixed $data, array $roles): User
  {
    $user = User::updateOrCreate(
      [
        "google_id" => $data->id,
      ],
      [
        "lastname" => $data->user["family_name"],
        "firstname" => $data->user["given_name"],
        // "avatar" => $data->user['picture'],
        "username" => $this->makeUsername($data->user["name"]),
        "email" => $data->email,
        "password" => Hash::make(bin2hex(random_bytes(16))),
        "phone_number" => "",
        "has_newsletter" => false,
        "has_commercials" => false,
        "has_thematic_alerts" => false,
      ]
    );

    $this->syncRoles($user, $roles);
    return $user;
  }
}
