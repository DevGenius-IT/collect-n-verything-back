<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Contact extends Model
{
  use SoftDeletes;

  // Properties =====================================

  protected $table = 'contact';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    "subject",
    "email",
    "body",
    "created_at",
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
}
