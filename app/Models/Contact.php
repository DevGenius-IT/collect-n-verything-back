<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Contact extends Model
{
  use HasFactory, SoftDeletes;

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
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $dates = [
    "created_at" => "datetime",
    "updated_at" => "datetime",
    "deleted_at" => "datetime",
  ];

  const SUBJECT_GENERAL_INQUIRY = "general-inquiry";
  const SUBJECT_TECHNICAL_SUPPORT = "technical-support";
  const SUBJECT_ACCOUNT_ISSUE = "account-issue";
  const SUBJECT_BUG_REPORT = "bug-report";
  const SUBJECT_FEEDBACK = "feedback";
  const SUBJECT_JOB_APPLICATION = "job-application";
  const SUBJECT_OTHER = "other";

  public static function getSubjects(): array
  {
    return [
      self::SUBJECT_GENERAL_INQUIRY,
      self::SUBJECT_TECHNICAL_SUPPORT,
      self::SUBJECT_ACCOUNT_ISSUE,
      self::SUBJECT_BUG_REPORT,
      self::SUBJECT_FEEDBACK,
      self::SUBJECT_JOB_APPLICATION,
      self::SUBJECT_OTHER,
    ];
  }
}
