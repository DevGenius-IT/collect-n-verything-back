<?php

namespace App\Services;

use App\Models\Answer;
use App\Repositories\AnswerRepository;

class AnswerService extends CrudService
{
    protected $repo;

    public function __construct(AnswerRepository $repo, PaginationService $pagination)
    {
        parent::__construct($repo, $pagination);
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->query();
    }

    public function getById($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(Answer $answer, array $data)
    {
        return $this->repo->update($answer, $data);
    }

    public function delete(Answer $answer)
    {
        return $this->repo->delete($answer);
    }
}
