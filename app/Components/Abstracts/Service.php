<?php

namespace App\Components\Abstracts;

use App\Components\Repository;

/**
 * The service abstract class.
 *
 * @package App\Components\Abstracts
 *
 * *****Properties*****
 * @property Repository $repository
 *
 * *****Methods*****
 * @method void __construct(Repository $repository)
 */
abstract class Service
{
  /**
   * The model instance.
   *
   * @var \App\Components\Repository
   */
  protected $repository;

  /**
   * Create a new service instance.
   *
   * @param  Repository  $repository
   * @return void
   */
  public function __construct(Repository $repository)
  {
    $this->repository = $repository;
  }
}
