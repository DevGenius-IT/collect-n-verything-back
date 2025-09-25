<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\ContactService;
use App\Validators\ContactValidator;

class ContactController extends CrudController
{
    protected string $modelClass = Contact::class;
    protected string $modelName = 'Contact';

    protected $service;

    public function __construct(ContactService $service, ContactValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
