<?php

namespace App\Http\Controllers;
use App\ModelItem;
use App\ModelChecklist;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    private $atributes = array();
    public function __construct() {
        $this->atributes = array(
            'due'=>'due',
            'urgency'=>'urgency',
            'task_id'=>'task_id',
            'description'=>'description',
            'completed_at'=>'completed_at',
            'is_completed'=>'is_completed',
            'update_by'=>'update_by',
            'created_at'=>'created_at',
            'last_update_by'=>'last_update_by',
            'assignee_id'=>'assignee_id'
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
                  'self' => url('/').'/Items/'.$item['id'],
                ),
        );
    }

    private function format_atributes($object,$data){
        foreach($this->atributes as $atribute=>$value) {
            if(array_key_exists($atribute,$data)) {
                $object->$atribute = $data[$atribute];
            }
        }
        return $object;
    }

    private function get_complete($is_completed = 0) {
        $object = ModelItem::where('is_completed',$is_completed)->get();
        $items = array();
        foreach($object as $item) {
            array_push($items,array(
                "id"=>$item->id,
                "item_id"=>$item->id,
                "is_completed"=>(bool) $item->is_completed,
                "checklist_id"=>$item->checklist_id,
            ));
        }
        return $items;
    }

    public function complete(Request $request){
        if ($request->isJson()) {
            $input  = $request->json()->all();
            $items = $this->get_complete(1);
            $data = array("data"=>$items);
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
        return response($data);
    }

    public function incomplete(Request $request){
        if ($request->isJson()) {
            $input  = $request->json()->all();
            $items = $this->get_complete(0);
            $data = array("data"=>$items);
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
        return response($data);
    }

    public function index(Request $request,$id){
        $filter = $request->input('filter');
        $sort   = $request->input('sort');
        $fields = $request->input('fields');
        $include    = $request->input('include');
        $page_limit = $request->input('page_limit');
        $page_offset= $request->input('page_offset');
        $datas = ModelItem::where([['description','LIKE',"%".$filter."%"]])
                ->where('checklist_id', '=', $id)
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

    public function delete($checklist_id,$id) {
        ModelItem::where('id', $id)->where('checklist_id', $checklist_id)->delete();
        return response(array(
            "status" => "success"
        ));
    }
    public function show($checklist_id,$id){
        $item = ModelItem::where('id',$id)->first();
        if(!empty($item)) {
            return response(
                array(
                    "data"=>$this->format_output($item)
                )
            );
        } else {
            return response(array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            ),404);
        }
    }

    public function update(Request $request,$checklist_id,$id){
        if ($request->isJson()) {
            $input  = $request->json()->all();
            $object = ModelItem::where('checklist_id',$checklist_id)->where('id',$id)->first();
            if(!empty($object)) {
                $data = $this->format_atributes($object,$input['data']['attribute']);
                $data->save();
                $data = array("data"=>$this->format_output($data));
            } else {
                $data = array(
                    "status"    => "failed",
                    "message"   => "Input is invalid"
                );
            }
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
        return response($data);
    }

    public function bulk_update(Request $request,$checklist_id){
        if ($request->isJson()) {
            $input  = $request->json()->all();
            $output = array();
            foreach($input['data'] as $item) {
                $object = ModelItem::where('checklist_id',$checklist_id)->where('id',$item['id'])->first();
                if(!empty($object)) {
                    $data = $this->format_atributes($object,$item['attributes']);
                    $data->save();
                    array_push($data);
                }
            }
            $data = array("data"=>$data);
            
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
        return response($data);
    }

    public function store (Request $request,$id){
        if ($request->isJson()) {
            $checklist  = ModelChecklist::where('id',$id)->first();
            if(!empty($checklist)) {
                $input  = $request->json()->all();
                $object = new ModelItem ();
                $data   = $this->format_atributes($object,$input['data']['attribute']);
                $data->type = "items";
                $data->task_id = $checklist->task_id;
                $data->checklist_id = $checklist->id;
            } else {
                $data = array(
                    "status"    => "failed",
                    "message"   => "Input is invalid"
                );
            }
            $data->save();
            return response($data);
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
        return response($data);
    }
}