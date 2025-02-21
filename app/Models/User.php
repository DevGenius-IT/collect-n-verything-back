<?php

namespace App\Models;

use App\Helpers\UsersHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Role;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 ******Fields*******
 * @property int $id
 * @property string $lastname
 * @property string $firstname
 * @property string $username
 * @property string $email
 * @property bool $enabled
 * @property string $password
 * @property \Carbon\Carbon $password_requested_at
 * @property string $phone_number
 * @property bool $has_newsletter
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 ******Relationships*******
 * @property-read Role $roles
 *
 ******Methods*******
 * @method public string fullName()
 * @method public int getTotalExpenses()
 * @method public int getExpensesCount()
 * @method public int getDownloadsCount()
 * @method public int getViewsCount()
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes, CanResetPassword, HasRoles;

  // Properties =====================================

  /**
   * The guard name of the model.
   *
   * @var array<string>
   */
  protected $guard_name = ["web", "admin"];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<string>
   */
  protected $fillable = [
    "lastname",
    "firstname",
    "username",
    "email",
    "password",
    "phone_number",
    "type",
    "stripe_id",
    "created_at",
    "updated_at",
    "deleted_at",
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array<string>
   */
  protected $hidden = [
    "password",
    'remember_token'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    "created_at" => "datetime",
    "updated_at" => "datetime",
    "deleted_at" => "datetime",
  ];

  // Methods =====================================

  /**
   * Get the user's full name.
   *
   * @return string
   */
  public function fullName(): string
  {
    return "{$this->firstname} {$this->lastname}";
  }

  // Relationships  =====================================

}
