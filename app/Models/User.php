<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model
{

  use SoftDeletes;

  // Properties =====================================

  protected $table = 'user';

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
    "type"
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
  protected $dates = [
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

  public function subscription()
  {
    return $this->belongsTo(Subscription::class);
  }

  public function websites()
  {
    return $this->hasMany(Website::class);
  }
}
