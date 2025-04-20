<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Services\WebsiteService;
use App\Validators\WebsiteValidator;

class WebsiteController extends CrudController
{
    protected string $modelClass = Website::class;
    protected string $modelName = 'Site web';

    protected $service;

    public function __construct(WebsiteService $service, WebsiteValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
