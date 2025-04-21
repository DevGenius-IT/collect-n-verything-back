<?php

namespace App\Services;

use App\Models\Website;
use App\Repositories\WebsiteRepository;

class WebsiteService
{
    protected $repo;

    public function __construct(WebsiteRepository $repo)
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

    public function update(Website $website, array $data)
    {
        return $this->repo->update($website, $data);
    }

    public function delete(Website $website)
    {
        return $this->repo->delete($website);
    }
}
