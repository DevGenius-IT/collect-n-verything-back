<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Address
 *
 ******Fields*******
 * @property int $id
 * @property string $street
 * @property string|null $additional
 * @property string|null $locality
 * @property string $zip_code
 * @property string $city
 * @property string $department
 * @property string $country
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 ******Relationships*******
 * @property-read User $users
 *
 * @mixin \Eloquent
 */
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
    "street",
    "additional",
    "locality",
    "zip_code",
    "city",
    "department",
    "country",
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

  // Relationships =====================================

  /**
   * Get the users that owns the address.
   *
   * @return HasMany
   */
  public function users(): HasMany
  {
    return $this->hasMany(User::class);
  }
}
