<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Subscription extends Model
{

  use SoftDeletes;

  // Properties =====================================

  protected $table = 'subscription';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<string>
   */
  protected $fillable = [
    'user_id',
    'pack_id',
    "start_date",
    "free_trial_end_date",
  ];


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

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function pack()
  {
    return $this->belongsTo(Pack::class, 'pack_id');
  }
}
