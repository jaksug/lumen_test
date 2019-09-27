<?php

namespace App\Http\Controllers;
use App\ModelChecklist;
use Illuminate\Http\Request;

class ChecklistsController extends Controller
{
    private function format_output($item) {
        return array(
            'type' => $item['type'],
            'id' => $item['id'],
            'attributes' => array (
                  'object_domain' => $item['object_domain'],
                  'object_id' => $item['object_id'],
                  'description' => $item['description'],
                  'is_completed' => $item['is_completed'],
                  'due' => $item['due'],
                  'urgency' => $item['urgency'],
                  'completed_at' => $item['completed_at'],
                  'last_update_by' => $item['update_by'],
                  'update_at' => $item['update_at'],
                  'created_at' => $item['created_at'],
                ),
            'links' => 
                array (
                  'self' => url('/').'/checklists/'.$item['id'],
                ),
        );
    }

    private function format_atributes($data){
        $object = new ModelChecklist ();
        $atributes = array('object_domain','object_id','due','urgency','urgency','task_id','description');
        foreach($atributes as $atribute) {
            $object->$atribute = $data[$atribute];
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
        ModelChecklist::find($id)->delete();
        return response(array(
            "status" => "success"
        ));
    }
    public function show($id){
        $item = ModelChecklist::where('id',$id)->first();
        return response(
            array(
                "data"=>$this->format_output($item)
            )
        );
    }
    public function store (Request $request){
        if ($request->isJson()) {
            $input = $request->json()->all();
            $data = new ModelChecklist ();
            $checklist_data = $input['data']['attributes'];
            /*
            $data->object_domain = $checklist_data['object_domain'];
            $data->object_id = $checklist_data['object_id'];
            $data->due = $checklist_data['due'];
            $data->urgency = $checklist_data['urgency'];
            $data->description = $checklist_data['description'];
            $data->task_id = $checklist_data['task_id'];
            */
            $data = $this->format_atributes($checklist_data);
            $data->type = 'checklists';
            $data->save();
        } else {
            $data = array(
                "status"    => "failed",
                "message"   => "Input is invalid"
            );
        }
        return response($data);
    }
}