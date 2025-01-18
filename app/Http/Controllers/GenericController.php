<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenericController extends Controller
{
    //
    public const fileModel = "file";
    public const modelPrefix = "App\Models";

    public function getModel($modelName){
        $modelClass = 'App\\Models\\' . Str::studly($modelName);

        if (!class_exists($modelClass)) {
            abort(404, "Model $modelName not found.");
        }

        return new $modelClass;
    }

    public function index($model, Request $request)
    {
        $currentPage = max((int)($request->page ?? 1), 1); // Ensure page is at least 1
        $limit = max((int)($request->limit ?? 10), 1); // Ensure limit is at least 1
        $skip = ($currentPage - 1) * $limit;

        $modelInstance = $this->getModel($model);
        $totalItems = $modelInstance::count();
        $data = $modelInstance::skip($skip)->take($limit)->get();
        return $this->returnResponse($data, [
            'totalItems' => $totalItems,
            'currentPage' => $currentPage,
            'totalPages' => ceil($totalItems / $limit),
            'limit' => $limit,
        ], 200);
    }

    public function show($model, $id)
    {
        $modelInstance = $this->getModel($model);
        $data = $modelInstance::findOrFail($id);
        return $this->returnResponse($data, [], 200);
    }

    public function store(Request $request, $model)
    {
        $modelInstance = $this->getModel($model);
        $data = $request->all();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $created = $modelInstance::create($data);
        return $this->returnResponse($created, [], 201);
    }

    public function update(Request $request, $model, $id)
    {
        $modelInstance = $this->getModel($model);
        $item = $modelInstance::findOrFail($id);
        $data = $request->all();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $item->update($data);

        return $this->returnResponse($data, [], 200);
    }

    public function destroy($model, $id)
    {
        $modelInstance = $this->getModel($model);
        $item = $modelInstance::findOrFail($id);
        $item->delete();

        return $this->returnResponse([], [], 200);;
    }

    public function fileUpload($model, Request $request){
        $modelInstance = $this->getModel($model);
        $modelName = self::modelPrefix."\\".ucfirst($model);
        $data = $request->all();
        
        // check modelId present or not
        $checkModelPresent = $this->checkModelId($modelInstance, $data['model_id']);
        if($checkModelPresent){
            return $this->returnResponse([], ["msg" => "No Model Data Found"], 404);
        }

        $data['model'] = $modelName;
        $fileName = isset($data['file']) ?  $this->fileStore($data['file']) : "";
        $data['file'] = $fileName;
        
        $storeData = $this->store(new Request($data), self::fileModel);
        return $this->returnResponse($storeData, [], 200);
    }

    public function fileStore($file){
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName, 'public');
        return $filePath;
    }

    public function checkModelId($modelInstance, $model_id){
           $check = $modelInstance->whereId($model_id)->first();
           return !$check;
    }

    public function returnResponse($data, $meta = [], $status = ""){
            return response()->json([
                'data' => $data, 
                'meta' => $meta,
                'sattus' => $status
            ]);
    }
}