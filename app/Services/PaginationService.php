<?php

namespace App\Services;

use App\Components\Abstracts\Pagination;
use App\Components\ExceptionHandler;
use App\Components\Repository;
use App\Components\Ressource;
use App\Http\Modules\Admin\Search\SearchRepository;

class PaginationService extends Pagination
{
  /**
   * The ressource instance.
   *
   * @var \App\Components\Ressource
   */
  protected Ressource $ressource;

  /**
   * The pagination configurations.
   *
   * @var array
   */
  protected static array $configurations = [];

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
   * Define a new pagination configuration.
   *
   * @param  string  $name
   * @param  callable  $callback
   * @return void
   */
  public static function define(string $name, Ressource $ressource): void
  {
    self::$configurations[$name] = function () use ($ressource) {
      $paginationService = new PaginationService();
      $paginationService->setRessource($ressource);
      return $paginationService;
    };
  }

  /**
   * Get a pagination configuration by name.
   *
   * @param  string  $name
   * @return callable|null
   */
  public static function getConfiguration(string $name): ?PaginationService
  {
    return isset(self::$configurations[$name]) ? self::$configurations[$name]() : null;
  }

  /**
   * Paginate a list of items.
   *
   * @param array $params
   * @param Repository $repository
   * @param string $url
   * @return array
   * @throws ExceptionHandler
   */
  public function paginate(array $params, Repository $repository, string $url): array
  {
    $limit = isset($params["limit"]) ? (int) $params["limit"] : 50;
    $page = isset($params["page"]) ? (int) $params["page"] : 1;
    $orderBy = $params["orderBy"] ?? null;
    $order = $params["order"] ?? null;
    $trash = $params["trash"] ?? null;
    $fields = $params["fields"] ?? null;

    $params = $this->cleanParams($params);

    $count = $repository->count([
      "filters" => $params,
      "trash" => $trash,
      "limit" => $limit,
    ]);

    $pagesCount = $this->getPagesCount($count, $limit);
    $currentPage = $this->getCurrentPage($page, $pagesCount);

    $items = $this->ressource->toArrayCollection(
      $repository->index([
        "filters" => $params,
        "limit" => $limit,
        "offset" => $this->getOffset($currentPage, $limit),
        "orderBy" => $orderBy,
        "order" => $order,
        "trash" => $trash,
        "fields" => $fields,
      ]),
      $fields
    );

    $pages = $this->getPagesUrls(
      $pagesCount,
      $currentPage,
      $limit,
      $orderBy,
      $order,
      $trash,
      null,
      $url,
      $params
    );

    return [
      "items" => $items,
      "meta" => [
        "total" => $count,
        "pages_count" => $pagesCount,
        "current_page" => $currentPage,
        "limit" => $limit,
        "pages" => $pages,
        "selected_fields" => $fields,
      ],
    ];
  }

  public function paginateSearch(
    array $params,
    SearchRepository $repository,
    Ressource $ressource,
    string $url
  ): array {
    $this->setRessource($ressource);

    $limit = isset($params["limit"]) ? (int) $params["limit"] : 50;
    $page = isset($params["page"]) ? (int) $params["page"] : 1;
    $orderBy = $params["orderBy"] ?? null;
    $order = $params["order"] ?? null;
    $trash = $params["trash"] ?? null;
    $fields = $params["fields"] ?? null;
    $q = isset($params["q"]) ? $params["q"] : null;

    $params = $this->cleanParams($params);

    $count = $repository->count([
      "q" => $q,
      "trash" => $trash,
      "limit" => $limit,
    ]);

    $pagesCount = $this->getPagesCount($count, $limit);
    $currentPage = $this->getCurrentPage($page, $pagesCount);

    $items = $this->ressource->toArrayCollection(
      $repository->search([
        "q" => $q,
        "page" => $page,
        "limit" => $limit,
        "offset" => $this->getOffset($currentPage, $limit),
        "orderBy" => $orderBy,
        "order" => $order,
        "trash" => $trash,
        "fields" => $fields,
      ]),
      $fields
    );

    $pages = $this->getPagesUrls(
      $pagesCount,
      $currentPage,
      $limit,
      $orderBy,
      $order,
      $trash,
      $q,
      $url,
      $params
    );

    return [
      "items" => $items,
      "meta" => [
        "total" => $count,
        "pages_count" => $pagesCount,
        "current_page" => $currentPage,
        "limit" => $limit,
        "pages" => $pages,
        "selected_fields" => $fields ?? null,
      ],
    ];
  }

  /**
   * Clean the params array.
   *
   * @param array  $params
   * @return array
   */
  private function cleanParams(array $params): array
  {
    unset(
      $params["limit"],
      $params["page"],
      $params["orderBy"],
      $params["order"],
      $params["trash"],
      $params["fields"],
      $params["q"]
    );

    return $params;
  }

  /**
   * Get the total pages count.
   *
   * @param int  $total
   * @param int  $limit
   * @return int
   */
  private function getPagesCount(int $total, int $limit): int
  {
    return max(1, (int) ceil($total / $limit));
  }

  /**
   * Get the pages urls.
   *
   * @param int  $pagesCount
   * @param int  $currentPage
   * @param int  $limit
   * @param string  $orderBy
   * @param string  $order
   * @param string  $trash
   * @param string  $baseUrl
   * @param array  $params
   * @return array
   */
  private function getPagesUrls(
    int $pagesCount,
    int $currentPage,
    int $limit,
    string|null $orderBy,
    string|null $order,
    string|null $trash,
    string|null $q,
    string $baseUrl,
    array $params
  ): array {
    $pages = [];
    $baseUrl = rtrim(preg_replace("/[?&]page=\d+/", "", $baseUrl), "/");

    for ($i = 1; $i <= $pagesCount; $i++) {
      if ($i === $currentPage) {
        continue;
      }

      if (!empty($q)) {
        $params["q"] = $q;
      }

      $params["page"] = $i;
      $params["limit"] = $limit;

      if (!empty($orderBy)) {
        $params["orderBy"] = $orderBy;
      }

      if (!empty($order)) {
        $params["order"] = $order;
      }

      if (!empty($trash)) {
        $params["trash"] = $trash;
      }

      $queryString = http_build_query($params);
      $separator = str_contains($baseUrl, "?") ? "&" : "?";
      $pages[] = "{$baseUrl}{$separator}{$queryString}";
    }
    return $pages;
  }

  /**
   * Get the offset.
   *
   * @param int  $page
   * @param int  $limit
   * @return int
   */
  private function getOffset(int $page, int $limit): int
  {
    return max(0, ($page - 1) * $limit);
  }

  /**
   * Get the current page.
   *
   * @param int  $page
   * @param int  $pagesCount
   * @return int
   */
  private function getCurrentPage(int $page, int $pagesCount): int
  {
    return min(max(1, $page), $pagesCount);
  }
}
