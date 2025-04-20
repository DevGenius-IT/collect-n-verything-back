<?php

namespace App\Repositories;

use App\Models\Pack;

class PackRepository
{
    public function all()
    {
        return Pack::all();
    }

    public function find($id)
    {
        return Pack::findOrFail($id);
    }

    public function create(array $data)
    {
        return Pack::create($data);
    }

    public function update(Pack $pack, array $data)
    {
        $pack->update($data);
        return $pack;
    }

    public function delete(Pack $pack)
    {
        return $pack->delete();
    }
}
