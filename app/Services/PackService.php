<?php

namespace App\Services;

use App\Models\Pack;
use App\Repositories\PackRepository;

class PackService
{
    protected $repo;

    public function __construct(PackRepository $repo)
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

    public function update(Pack $pack, array $data)
    {
        return $this->repo->update($pack, $data);
    }

    public function delete(Pack $pack)
    {
        return $this->repo->delete($pack);
    }
}
