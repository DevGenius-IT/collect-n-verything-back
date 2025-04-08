<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class CrudController extends Controller
{
    protected $service;
    protected $validator;

    public function __construct($service, $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show($id)
    {
        return response()->json($this->service->getById($id));
    }

    public function store(Request $request)
    {
        $data = $this->validator->validate($request);
        return response()->json($this->service->create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $this->validator->validate($request, true);
        $model = $this->service->getById($id);
        return response()->json($this->service->update($model, $data));
    }

    public function destroy($id)
    {
        $model = $this->service->getById($id);
        $this->service->delete($model);
        return response()->json(null, 204);
    }
}
