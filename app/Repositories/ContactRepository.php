<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository
{
    public function query()
    {
        return Contact::query();
    }

    public function find($id)
    {
        return Contact::findOrFail($id);
    }

    public function create(array $data)
    {
        return Contact::create($data);
    }

    public function update(Contact $contact, array $data)
    {
        $contact->update($data);
        return $contact;
    }

    public function delete(Contact $contact)
    {
        return $contact->delete();
    }
}
