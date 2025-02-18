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
 * @property int $address_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 ******Relationships*******
 * @property-read Role $roles
 * @property-read Address $address
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
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes, CanResetPassword, HasRoles, UsersHelper;

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
    "enabled",
    "password",
    "password_requested_at",
    "phone_number",
    "has_newsletter",
    "address_id",
    "google_id",
    "created_at",
    "updated_at",
    "deleted_at",
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array<string>
   */
  protected $hidden = ["password", "reset_password_token"];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    "enabled" => "boolean",
    "password_requested_at" => "datetime",
    "has_newsletter" => "boolean",
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

  /**
   * Get the address for the user.
   *
   * @return BelongsTo
   */
  public function address(): BelongsTo
  {
    return $this->belongsTo(Address::class)->withTrashed();
  }
}
