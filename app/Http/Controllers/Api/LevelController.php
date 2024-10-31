<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\levelmodel;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(){
        return levelmodel::all();
    }

    public function store(Request $request){
        $level = levelmodel::create($request->all());
        return response()->json($level, 201);
    }

    public function show(levelmodel $level){
        return levelmodel::find($level);
    }

    public function update(Request $request, levelmodel $level){
        $level->update($request->all());
        return levelmodel::find($level);
    }

    public function destroy(levelmodel $level){
        $level->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Data terhapus'
        ]);
    }
}
