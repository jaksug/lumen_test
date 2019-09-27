<?php
use App\Checklists;
use App\User;

class ChecklistTest extends TestCase
{
    //token yang di simpan dalam tabel user
    public $token = "69c6d81e61779e6b16599e1c113c5ab5a50df186";
    protected $send_headers = [ 'CONTENT_TYPE' => 'application/json' ];
    protected $send_json = '{
        "data": {
            "attributes": {
                "object_domain": "contact",
                "object_id": "1",
                "due": "2019-01-25T07:50:14+00:00",
                "urgency": 1,
                "description": "Need to verify this guy house.",
                "items": [
                    "Visit his house",
                    "Capture a photo",
                    "Meet him on the house"
                ],
                "task_id": "123"
            }
        }
    }';
    protected $receive_json = null;
    protected $checklist_id = null;

    //Test authentikasi
    public function testAuth(){
        $user = factory('App\User')->create();
        $this->actingAs($user)
             ->get('/user');
        $test = $this->get('/checklists');
        $test->assertResponseStatus(200);
        $test->seeJsonStructure([
            'data','data'
        ]);
    }

    //Tes authentikasi dengan token
    public function testWithAuth(){
        $test = $this->get('/checklists?api_token='.$this->token);
        $test->assertResponseStatus(200);
        $test->seeJsonStructure([
            'data','data'
        ]);
    }

    //Test create list 
    public function testCreate(){
        $this->call(
            'POST',
            '/checklists?api_token='.$this->token,
            [],
            [],
            [],
            $this->send_headers,
            $this->send_json
        );

        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'data'
        ]);
        $response_data = json_decode($this->response->getContent(), true);
        $this->checklist_id = $response_data['data']['id'];
    }

    //Test create list 
    public function testUpdate(){
        $checklist = factory('App\Checklists')->create();
        $this->call(
            'PATCH',
            '/checklists/'.$this->checklist->id.'?api_token='.$this->token,
            [],
            [],
            [],
            $this->send_headers,
            $this->send_json
        );
        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data'
        ]);
    }

    //Test delete checklist
    public function testDelete(){
        if($this->checklist_id == null) {
            $test = $this->delete('/checklists/1000000000000?api_token='.$this->token);
            $test->assertResponseStatus(404);
            $test->seeJsonStructure([
                'status'
            ]);
        } else{
            $test = $this->delete('/checklists/'.$this->checklist_id.'?api_token='.$this->token);
            $test->assertResponseStatus(200);
            $test->seeJsonStructure([
                'status'
            ]);
        }
        

    }



}

