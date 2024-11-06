<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\levelmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index(){
        return levelmodel::all();
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'level_kode'=>'required|string|min:3|unique:m_level,level_kode',
            'level_nama'=>'required|string|max:100'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $level = levelmodel::create([
            'level_id'=>$request->level_id,
            'level_kode'=>$request->level_kode,
            'level_nama'=>$request->level_nama
        ]);

        if($level){
            return response()->json([
                'success'=>true,
                'user'=>$level
            ],201);
        }

        return response()->json([
            'success'=>false,
        ],409);
    }

    public function show(levelmodel $level){
        return levelmodel::find($level);
    }

    public function update(Request $request, levelmodel $level){
        $level = levelmodel::find($level);
        if (!$level) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $rules = [
            'level_kode'=>'sometimes|string|min:3|unique:m_level,level_kode,'.$level,
            'level_nama'=>'sometimes|string|max:100'
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $level->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Kategori updated successfully',
            'data' => $level
        ]);
    }

    public function destroy(levelmodel $level){
        $level->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
