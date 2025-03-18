<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Address extends Model
{
  use HasFactory, SoftDeletes;

  // Properties =====================================

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    "country",
    "city",
    "postal_code",
    "streetname",
    "number",
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    "created_at" => "datetime",
    "updated_at" => "datetime",
    "deleted_at" => "datetime",
  ];

  // Relationships  =====================================

  public function users()
    {
        return $this->hasMany(User::class);
    }
}
