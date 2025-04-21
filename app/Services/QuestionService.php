<?php

namespace App\Services;

use App\Models\Question;
use App\Repositories\QuestionRepository;

class QuestionService
{
    protected $repo;

    public function __construct(QuestionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->all();
    }

    public function getById($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(Question $question, array $data)
    {
        return $this->repo->update($question, $data);
    }

    public function delete(Question $question)
    {
        return $this->repo->delete($question);
    }
}
