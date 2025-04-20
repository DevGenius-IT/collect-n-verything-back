<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

abstract class CrudController extends Controller
{
    protected $service;
    protected $validator;
    protected string $modelClass;
    protected string $modelName = 'Ressource';

    public function __construct($service, $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    protected function findOrJson404($id): Model
    {
        $model = $this->modelClass::find($id);

        if (!$model) {
            abort(response()->json([
                'message' => "{$this->modelName} avec l'identifiant {$id} introuvable."
            ], 404));
        }

        return $model;
    }

    public function show($id)
    {
        $model = $this->findOrJson404($id);
        return response()->json($model);
    }

    public function destroy($id)
    {
        $model = $this->findOrJson404($id);
        $model->delete();

        return response()->json(['message' => "{$this->modelName} supprimée avec succès."]);
    }

    public function update(Request $request, $id)
    {
        $model = $this->findOrJson404($id);
        $model->update($request->all());

        return response()->json($model);
    }

    public function index()
    {
        return response()->json($this->modelClass::all());
    }

    public function store(Request $request)
    {
       
    $rules = call_user_func([$this->validator, 'rules']);
    $messages = call_user_func([$this->validator, 'messages']);

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return response()->json([
            'status'  => 'error',
            'message' => 'La validation a échoué.',
            'errors'  => $validator->errors()
        ], 422);
    }

    $data = $validator->validated();

    if (isset($data['us_password'])) {
        $data['us_password'] = Hash::make($data['us_password']);
    }

    $model = $this->modelClass;
    $record = $model::create($data);

    return response()->json([
        'status'  => 'success',
        'message' => class_basename($model) . ' créé avec succès.',
        'data'    => $record
    ], 201);
    }
}
