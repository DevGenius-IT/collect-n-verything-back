<?php

namespace App\Utils;

trait S3Utils
{
  /**
   * Get the path from a URL.
   *
   * @param string $url
   * @return string
   */
  private function getPathFromUrl(string $url): string
  {
    $bucket = Env("AWS_BUCKET");
    return preg_replace("/.*\/$bucket\//", "", $url);
  }
}