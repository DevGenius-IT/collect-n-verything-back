<?php

namespace App\Http\Modules\Admin\Users;

use App\Components\ExceptionHandler;
use App\Components\Repository;
use App\Components\Ressource;
use App\Enums\RolesEnum;
use App\Helpers\SchoolsHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\ShopsHelper;
use App\Helpers\UsersHelper;
use App\Helpers\UserTypesHelper;
use App\Http\Modules\Admin\Users\Exceptions\UserRepositoryException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * The user repository class.
 *
 * @package App\Http\Modules\Admin\Users
 * @extends Repository
 *
 * *****Traits*****
 * @use SchoolsHelper
 * @use SectionsHelper
 * @use ShopsHelper
 * @use UsersHelper
 * @use UserTypesHelper
 *
 ******Methods*******
 * @method public __construct(User $model, UserRessource $ressource)
 * @method public store(array $data): User|array
 * @method public update(int $id, array $data): User|array
 * @method public destroy(array $ids, bool $force = false): JsonResponse
 */
class UserRepository extends Repository
{

  public function __construct(User $model, Ressource $ressource)
  {
    parent::__construct($model, $ressource);
  }

  /**
   * Create a new user
   *
   * @param array $data
   * @return \App\Models\User
   */
  public function create(array $data)
  {
    // Crée un utilisateur avec les données fournies et retourne l'utilisateur créé
    $user = User::create([
      'username' => $data['username'],
      'lastname' => $data['lastname'],
      'firstname' => $data['firstname'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),  // Hachage du mot de passe
      'phone_number' => $data['phone_number'] ?? null,
      'type' => $data['type'] ?? 'USER',
      'stripe_id' => $data['stripe_id'] ?? null,
    ]);

    return $user;
  }

  /**
   * Get all users.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAll()
  {
    return User::all();  // Retourne tous les utilisateurs
  }

  /**
   * Find the user by his username or email.
   * 
   */
  public function findByEmailOrUsername(string $identification)
  {
    return User::where('email', $identification)->orWhere('username', $identification)->first();
  }
}
