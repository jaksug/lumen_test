<?php

namespace App\Http\Controllers;
use App\ModelChecklist;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChecklistsController extends Controller
{
    private $atributes = array();
    public function __construct() {
        $this->atributes = array(
            'object_id'=>'object_id',
            'object_domain'=>'object_domain',
            'due'=>'due',
            'is_completed'=>'is_completed',
            'urgency'=>'urgency',
            'task_id'=>'task_id',
            'description'=>'description',
            'completed_at'=>'completed_at',
            'update_by'=>'update_by',
            'created_at'=>'created_at',
            'last_update_by'=>'last_update_by'
        );
    }

    private function format_output($item) {
        $atributes = array();
        foreach($this->atributes as $atribute=>$value) {
            $atributes[$atribute] = $item[$atribute];
        }
        return array(
            'type' => $item['type'],
            'id' => $item['id'],
            'attributes' => $atributes,
            'links' => 
                array (
                  'self' => url('/').'/checklists/'.$item['id'],
                ),
        );
    }

    private function format_atributes($object,$data){
        foreach($this->atributes as $atribute=>$value) {
            if(array_key_exists($atribute,$data)) {
                if($atribute== 'due') {
                    $object->due = Carbon::parse($data['due']);
                } else {
                    $object->$atribute = $data[$atribute];
                }
            }
        }
        return $object;
    }

    public function index(Request $request){
        $include = $request->input('include');
        $filter = $request->input('filter');
        $sort = $request->input('sort');
        $fields = $request->input('fields');
        $page_limit = $request->input('page_limit');
        $page_offset = $request->input('page_offset');
        $datas = ModelChecklist::where([['description','LIKE',"%".$filter."%"]])
                ->orderBy('id','DESC')
                //->skip($page_offset)
                //->take($page_limit)
                ->get();
        $list = array();
        foreach($datas as $item) {
            array_push($list,$this->format_output($item));
        }
        $data = array("data"=>$list);
        return response($data);
    }

    public function delete($id) {
        $checklist = ModelChecklist::find($id);
        if($checklist == null){
            return response(array("status"=>404,"error"=>"Not Found"),404);
        } else {
            $checklist ->delete();
            return response(array(
                "status" => "success"
            ));
        }
        
    }
    public function show($id){
        $item = ModelChecklist::where('id',$id)->first();
        if($checklist == null){
            return response(array("status"=>404,"error"=>"Not Found"),404);
        } else{
            return response(
                array(
                    "data"=>$this->format_output($item)
                )
            );
        }
    }

    public function update(Request $request,$id){
        if ($request->isJson()) {
            $input = $request->json()->all();
            $object = ModelChecklist::where('id',$id)->first();
            $data = $this->format_atributes($object,$input['data']['attributes']);
            $data->save();
            return response(
                array(
                    "data"=>$this->format_output($data)
                )
            );
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
    }

    public function store (Request $request){
        if ($request->isJson()) {
            $input = $request->json()->all();
            $object = new ModelChecklist ();
            $data = $this->format_atributes($object,$input['data']['attributes']);
            $data->type = 'checklists';
            $data->is_completed = 0;
            $data->save();

            return response(
                array(
                    "data"=>$this->format_output($data)
                )
            );
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
        return response($data);
    }
}