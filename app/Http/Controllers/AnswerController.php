<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Services\AnswerService;
use App\Validators\AnswerValidator;

class AnswerController extends CrudController
{
    protected string $modelClass = Answer::class;
    protected string $modelName = 'Réponse';

    protected $service;

    public function __construct(AnswerService $service, AnswerValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
