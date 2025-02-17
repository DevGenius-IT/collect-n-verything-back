<?php

namespace App\Utils;

use Illuminate\Support\Str;

trait StringUtils
{
  /**
   * Make the username from the given name.
   * Lowercase and trim.
   *
   * @param  string  $name
   * @return string
   */
  private function makeUsername(string $name): string
  {
    return strtolower(str_replace(" ", "", $name));
  }

  /**
   * Make timer string from the given time in minutes.
   *
   * @param  int  $time
   * @return string
   */
  private function makeTimerInMinutes(int $time): string
  {
    return "$time minutes";
  }

  /**
   * Make timer string from the given time in days.
   *
   * @param  int  $time
   * @return string
   */
  private function makeTimerInDays(int $time): string
  {
    $days = [
      "en" => "days",
      "fr" => "jours",
    ];
    $lang = app()->getLocale();
    return "$time {$days[$lang]}";
  }

  /**
   * Convert camelCase module name to snake_case.
   *
   * @param  string  $module
   * @return string
   */
  private function convertToSnakeCase(string $module): string
  {
    return strtolower(preg_replace("/([a-z])([A-Z])/", '$1_$2', $module));
  }

  /**
   * Replace spaces with underscores in the given string.
   *
   * @param  string  $string
   * @return string
   */
  private function replaceSpacesWithUnderscores(string $string): string
  {
    return str_replace(" ", "_", $string);
  }

  /**
   * Get the plural form of the given string.
   *
   * @param  string  $string
   * @return string
   */
  private function pluralize(string $string): string
  {
    return Str::plural($string);
  }
  
  /**
   * Get the slug of the given string.
   *
   * @param  string  $string
   * @return string
   */
  private function slugify(string $string): string
  {
    return Str::slug($string);
  }
}
