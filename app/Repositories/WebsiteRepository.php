<?php

namespace App\Repositories;

use App\Models\Website;

class WebsiteRepository
{
    public function all()
    {
        return Website::all();
    }

    public function find($id)
    {
        return Website::findOrFail($id);
    }

    public function create(array $data)
    {
        return Website::create($data);
    }

    public function update(Website $website, array $data)
    {
        $website->update($data);
        return $website;
    }

    public function delete(Website $website)
    {
        return $website->delete();
    }
}
