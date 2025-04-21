<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\AddressRepository;

class AddressService
{
    protected $repo;

    public function __construct(AddressRepository $repo)
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

    public function update(Address $address, array $data)
    {
        return $this->repo->update($address, $data);
    }

    public function delete(Address $address)
    {
        return $this->repo->delete($address);
    }
}
