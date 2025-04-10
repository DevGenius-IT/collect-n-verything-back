<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model{

  // Properties =====================================

  protected $table = 'user_us';

  /**
   * The guard name of the model.
   *
   * @var array<string>
   */
  protected $guard_name = ["api","web", "admin"];

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
    "created_at",
    "updated_at",
    "deleted_at",
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array<string>
   */
  protected $hidden = ["password"];

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

  public function address()
  {
    return $this->belongsTo(Address::class);
  }

  public function pack()
  {
    return $this->belongsTo(Pack::class);
  }

  public function websites()
  {
    return $this->hasMany(Website::class);
  }
}
