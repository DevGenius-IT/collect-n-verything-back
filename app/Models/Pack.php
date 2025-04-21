<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pack extends Model
{

  use SoftDeletes;

  // Properties =====================================
  protected $table = 'pack';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    "name",
    "price",
    "features",
    "created_at",
    "updated_at",
    "deleted_at",
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

  public function subcriptions()
  {
    return $this->hasMany(Subscription::class);
  }
}
