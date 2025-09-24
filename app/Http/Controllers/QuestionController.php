<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Services\QuestionService;
use App\Validators\QuestionValidator;

class QuestionController extends CrudController
{
    protected string $modelClass = Question::class;
    protected string $modelName = 'Question';

    protected $service;

    public function __construct(QuestionService $service, QuestionValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
