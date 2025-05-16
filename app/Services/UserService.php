<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService extends CrudService
{
    protected $repo;

    public function __construct(UserRepository $repo, PaginationService $pagination)
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

    public function update(User $user, array $data)
    {
        return $this->repo->update($user, $data);
    }

    public function delete(User $user)
    {
        return $this->repo->delete($user);
    }

}
