<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\ContactRepository;

class ContactService extends CrudService
{
    protected $repo;

    public function __construct(ContactRepository $repo, PaginationService $pagination)
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

    public function update(Contact $contact, array $data)
    {
        return $this->repo->update($contact, $data);
    }

    public function delete(Contact $contact)
    {
        return $this->repo->delete($contact);
    }

}
