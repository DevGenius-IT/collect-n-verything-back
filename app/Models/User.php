<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{

  // Properties =====================================

  protected $table = 'user_us';

  protected $primaryKey = 'us_id'; // clé primaire personnalisée


  public $timestamps = true;
  const CREATED_AT = 'us_created_at';
  const UPDATED_AT = 'us_updated_at';
  const DELETED_AT = 'us_deleted_at';

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

  public function setUsPasswordAttribute($value)
  {
    $this->attributes['us_password'] = bcrypt($value);
  }

   // Mutateur pour le mot de passe (enregistrement)
   public function setPasswordAttribute($value)
   {
       $this->attributes['us_password'] = bcrypt($value); // Hachage du mot de passe
   }

   // Acesseur pour récupérer le mot de passe
   public function getPasswordAttribute()
   {
       return $this->attributes['us_password'];
   }

   // Accesseur pour "username" (au lieu de "us_username")
   public function getUsernameAttribute()
   {
       return $this->attributes['us_username'];
   }

   // Mutateur pour "username" (lors de l'enregistrement dans la base)
   public function setUsernameAttribute($value)
   {
       $this->attributes['us_username'] = $value;
   }

   // Accesseur pour "lastname" (au lieu de "us_lastname")
   public function getLastnameAttribute()
   {
       return $this->attributes['us_lastname'];
   }

   // Mutateur pour "lastname" (lors de l'enregistrement dans la base)
   public function setLastnameAttribute($value)
   {
       $this->attributes['us_lastname'] = $value;
   }

   // Accesseur pour "firstname" (au lieu de "us_firstname")
   public function getFirstnameAttribute()
   {
       return $this->attributes['us_firstname'];
   }

   // Mutateur pour "firstname" (lors de l'enregistrement dans la base)
   public function setFirstnameAttribute($value)
   {
       $this->attributes['us_firstname'] = $value;
   }

   // Accesseur pour "email" (au lieu de "us_email")
   public function getEmailAttribute()
   {
       return $this->attributes['us_email'];
   }

   // Mutateur pour "email" (lors de l'enregistrement dans la base)
   public function setEmailAttribute($value)
   {
       $this->attributes['us_email'] = $value;
   }

   // Accesseur pour "phone_number" (au lieu de "us_phone_number")
   public function getPhoneNumberAttribute()
   {
       return $this->attributes['us_phone_number'];
   }

   // Mutateur pour "phone_number" (lors de l'enregistrement dans la base)
   public function setPhoneNumberAttribute($value)
   {
       $this->attributes['us_phone_number'] = $value;
   }

   // Accesseur pour "type" (au lieu de "us_type")
   public function getTypeAttribute()
   {
       return $this->attributes['us_type'];
   }

   // Mutateur pour "type" (lors de l'enregistrement dans la base)
   public function setTypeAttribute($value)
   {
       $this->attributes['us_type'] = $value;
   }

   // Accesseur pour "id" (au lieu de "ad_id" - s'il s'agit de l'ID de l'adresse)
   public function getIdAttribute()
   {
       return $this->attributes['ad_id'];
   }

   // Mutateur pour "id" (lors de l'enregistrement dans la base - pour "ad_id")
   public function setIdAttribute($value)
   {
       $this->attributes['ad_id'] = $value;
   }
   
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
