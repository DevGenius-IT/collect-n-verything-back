<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository
{
    public function all()
    {
        return Address::all();
    }

    public function find($id)
    {
        return Address::findOrFail($id);
    }

    public function create(array $data)
    {
        return Address::create($data);
    }

    public function update(Address $address, array $data)
    {
        $address->update($data);
        return $address;
    }

    public function delete(Address $address)
    {
        return $address->delete();
    }
}
