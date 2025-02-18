<?php

namespace App\Components;

use App\Facades\Pagination;
use App\Helpers\RulesHelper;
use App\Services\PaginationService;
use App\Utils\StringUtils;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The CRUD controller class.
 *
 * @package App\Components
 * @extends Controller
 *
 * *****Traits*****
 * @use AuthorizesRequests
 * @use DispatchesJobs
 * @use ValidatesRequests
 * @use RulesHelper
 * @use StringUtils
 *
 * *****Properties*****
 * @property string $name
 * @property PaginationService $pagination
 * @property CRUDRules $rules
 * @property Repository $repository
 * @property Ressource $ressource
 *
 * *****Methods*******
 * @method void __construct(string $name, Repository $repository, Ressource $ressource)
 * @method JsonResponse index(Request $request, ExceptionHandler $exception)
 * @method array getParams(Request $request, bool $decodeJson = false)
 * @method array|null getFields(Request $request)
 * @method JsonResponse show(int $id, Request $request)
 * @method JsonResponse store(Request $request, ExceptionHandler $exception)
 * @method JsonResponse update(int $id, Request $request, ExceptionHandler $exception)
 * @method JsonResponse destroy(Request $request, ExceptionHandler $exception)
 * @method JsonResponse restore(Request $request, ExceptionHandler $exception)
 * @method JsonResponse duplicate(Request $request, ExceptionHandler $exception)
 */
class CRUDController extends Controller
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests, RulesHelper, StringUtils;

  /**
   * The name of the controller.
   *
   * @var string
   */
  protected string $name;

  /**
   * The pagination instance.
   *
   * @var PaginationService
   */
  protected PaginationService $pagination;

  /**
   * The rules instance.
   *
   * @var CRUDRules
   */
  protected CRUDRules $rules;

  /**
   * The service instance.
   *
   * @var Repository
   */
  protected Repository $repository;

  /**
   * The ressource instance.
   *
   * @var Ressource
   */
  protected Ressource $ressource;

  /**
   * The models with slug.
   *
   * @var array
   */
   private array $modelNameWithSlug = [
     "academic_subject",
     "author",
     "book",
     "cycle", 
     "editor",
     "item",
     "school_domain",
     "school_knowledge",
     "school_subject",
     "section",
     "shop",
     "sub_academic_subject",
     "tag",
     "theme"
   ];

  /**
   * Create a new controller instance.
   *
   * @param  string  $name
   * @param  Repository  $repository
   * @param  Ressource  $ressource
   * @param  PaginationService  $pagination
   * @return void
   */
  public function __construct(string $name, Repository $repository, Ressource $ressource)
  {
    $this->name = $name;
    $this->rules = new CRUDRules($name);
    $this->repository = $repository;
    $this->ressource = $ressource;
    $this->pagination = Pagination::getConfiguration($name);
  }

  /**
   * Index method.
   * List all records.
   *
   * @param  Request  $request
   * @param  ExceptionHandler $exception
   * @throws ExceptionHandler
   * @return JsonResponse
   */
  public function index(Request $request, ExceptionHandler $exception): JsonResponse
  {
    try {
      $params = $this->getParams($request, true);
      $validatedParams = $this->rules->index($params, $exception);

      $records = $this->pagination->paginate($validatedParams, $this->repository, $request->url());

      return response()->json($records);
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Get params from request.
   *
   * @param  Request  $request
   * @param  bool     $decodeJson
   * @return array
   */
  private function getParams(Request $request, bool $decodeJson = false): array
  {
    $fields = $this->getFields($request);
    $params = $request->all();

    if ($decodeJson) {
      foreach ($params as &$param) {
        if (preg_match('/^\[\d+(,\d+)*\]$/', $param)) {
          $param = json_decode($param, true);
        }
      }
    }

    if (
      !isset($params["slug"]) &&
      (isset($params["name"]) || isset($params["title"])) &&
      in_array($this->name, $this->modelNameWithSlug)
    ) {
      $params["slug"] = $this->slugify($params["name"] ?? $params["title"]);
    }

    return $fields ? array_merge($params, ["fields" => $fields]) : $params;
  }

  /**
   * Get fields from request.
   *
   * @param  Request  $request
   * @return array|null
   */
  private function getFields(Request $request): ?array
  {
    return $request->header("fields") ? explode(",", $request->header("fields")) : null;
  }

  /**
   * Show method.
   * Display a record.
   *
   * @param  int  $id
   * @param  Request  $request
   * @throws ExceptionHandler
   * @return JsonResponse
   */
  public function show(int $id, Request $request): JsonResponse
  {
    try {
      $fields = $this->getFields($request);
      $record = $this->repository->show($id);
      return response()->json($this->ressource->toArray($record, $fields));
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Store method.
   * Create a new record.
   *
   * @param  Request  $request
   * @param  ExceptionHandler  $exception
   * @throws ExceptionHandler
   * @return JsonResponse
   */
  public function store(Request $request, ExceptionHandler $exception): JsonResponse
  {
    try {
      $fields = $this->getFields($request);
      $params = $this->getParams($request);
      $validatedParams = $this->rules->store($params, $exception);

      $record = $this->repository->store($validatedParams);
      return response()->json($this->ressource->toArray($record, $fields), 201);
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Update method.
   * Update a record.
   *
   * @param  int  $id
   * @param  Request  $request
   * @param  ExceptionHandler  $exception
   * @throws ExceptionHandler
   * @return JsonResponse
   */
  public function update(int $id, Request $request, ExceptionHandler $exception): JsonResponse
  {
    try {
      $fields = $this->getFields($request);
      $params = $this->getParams($request);
      $validatedParams = $this->rules->update($params, $exception);

      $record = $this->repository->update($id, $validatedParams);
      return response()->json($this->ressource->toArray($record, $fields));
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Destroy method.
   * Delete one or more records.
   *
   * @param  Request  $request
   * @param  ExceptionHandler  $exception
   * @throws ExceptionHandler
   * @return JsonResponse
   */
  public function destroy(Request $request, ExceptionHandler $exception): JsonResponse
  {
    try {
      $params = $this->getParams($request);
      $validatedParams = $this->rules->destroy($params, $exception);
      $force = isset($validatedParams["force"]) ? $validatedParams["force"] : false;
      $ids = $validatedParams["ids"];

      return $this->repository->destroy($ids, $force);
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Restore method.
   * Restore a record.
   *
   * @param  Request  $request
   * @param  ExceptionHandler  $exception
   * @throws ExceptionHandler
   * @return JsonResponse
   */
  public function restore(Request $request, ExceptionHandler $exception): JsonResponse
  {
    try {
      $params = $this->getParams($request);
      $validatedParams = $this->rules->restore($params, $exception);

      return $this->repository->restore($validatedParams);
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Duplicate method.
   * Duplicate a record.
   *
   * @param  Request  $request
   * @param  ExceptionHandler  $exception
   * @throws ExceptionHandler
   * @return JsonResponse
   */
  public function duplicate(Request $request, ExceptionHandler $exception): JsonResponse
  {
    try {
      $fields = $this->getFields($request);
      $params = $this->getParams($request);
      $validatedParams = $this->rules->duplicate($params, $exception);

      $records = $this->repository->duplicate($validatedParams);
      return response()->json($records, 201);
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }
}
