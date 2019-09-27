<?php

namespace App\Http\Controllers;
use App\ModelTemplates;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index(Request $request){
        

        $data = ModelTemplates::all();


        return response($data);
    }
    public function show($id){
        $data = ModelTemplates::where('id',$id)->get();
        return response ($data);
    }
    public function store (Request $request){
        if ($request->isJson()) {
            $data = $request->json()->all();
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
        //$data = new ModelTemplates ();
        //$data->activity = $request->input('name');
        //$data->description = $request->input('description');
        //$data->save();
    
        return response($data);
    }
    //
}