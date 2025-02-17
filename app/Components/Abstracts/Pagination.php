<?php

namespace App\Components\Abstracts;

use App\Components\Repository;
use App\Components\Ressource;

/**
 * The pagination abstract class.
 *
 * @package App\Components\Abstracts
 *
 * *****Properties*****
 * @property Ressource $ressource
 *
 * *****Methods*******
 * @method void setRessource(Ressource $ressource)
 * @method array paginate(array $params, Repository $repository, string $url)
 */
abstract class Pagination
{
  /**
   * The ressource instance.
   *
   * @var \App\Components\Abstracts\Ressource
   */
  protected Ressource $ressource;

  /**
   * Set the ressource instance.
   *
   * @param  Ressource  $ressource
   * @return void
   */
  public function setRessource(Ressource $ressource): void
  {
    $this->ressource = $ressource;
  }

  /**
   * The paginate method.
   *
   * @param array  $params
   * @param Repository  $repository
   * @param string  $url
   * @return array
   */
  abstract public function paginate(array $params, Repository $repository, string $url): array;
}
