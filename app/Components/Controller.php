<?php

namespace App\Components;

// use App\Services\PaginationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * The base controller class.
 *
 * @package App\Components
 * @extends BaseController
 *
 * *****Traits*****
 * @use AuthorizesRequests
 * @use DispatchesJobs
 * @use ValidatesRequests
 *
 * *****Properties*****
 * @property PaginationService $pagination
 * @property Repository $repository
 * @property Ressource $ressource
 *
 * *****Methods*******
 * @method void __construct(Repository $repository, Ressource $ressource)
 */
class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  /**
   * The pagination instance.
   *
   * @var PaginationService
   */
  // protected PaginationService $pagination;

  /**
   * The service instance.
   *
   * @var \App\Components\Repository
   */
  protected Repository $repository;

  /**
   * The ressource instance.
   *
   * @var Ressource
   */
  protected Ressource $ressource;

  /**
   * Create a new controller instance.
   *
   * @param  Repository  $repository
   * @param  Ressource  $ressource
   * @return void
   */
  public function __construct(Repository $repository, Ressource $ressource)
  {
    // $this->pagination = new PaginationService($ressource);
    $this->repository = $repository;
    $this->ressource = $ressource;
  }
}
