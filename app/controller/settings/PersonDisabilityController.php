<?php

namespace App\Controller\Settings;
use App\Model\Settings\PersonDisabilityModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class PersonDisabllityController extends BaseController {
    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new PersonDisabilityModel;
        $person_disabilities = $model->all();
        $result = [];

        foreach($person_disabilities as $index => $person_disability) {
            $badge =  "";
            $action = '
                <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$person_disability['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$person_disability['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
                    <i class="fas fa-trash-alt"></i>
                </button>
            ';

            if( $person_disability['status'] == "Default") {
                $action = '';
                $badge =  "info";
            }
            else if( $person_disability['status'] == "Active") {
                $badge = "secondary";
            }
            else {
                $badge = "default";
            }
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $person_disability['name'],
                'created_at' => date('M d, Y h:i A', strtotime($person_disability['created_at'])),
                'updated_at' => $person_disability['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($person_disability['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($person_disability['status']).'</span>',
                'action' => $action
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function select2($request) {
        $model = new PersonDisabilityModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $person_disabilities = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $person_disabilities
        ];
    }
    
    public function store($request) {
        $data = [
            'name' => $request->name,
            'created_by' => $this->auth->id
        ];
        $person_disability = new PersonDisabilityModel;
        $person_disability_id =  $person_disability->lastInsertId($data);
        
        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $person_disability->module,
            'action_id' => $person_disability->action_add,
            'record_id' => $person_disability_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $person_disability_model = new PersonDisabilityModel;
        $person_disability = $person_disability_model->show(['id' => $request->id]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $person_disability_model->module,
            'action_id' => $person_disability_model->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $person_disability
        ];
    }

    public function update($request) {
        $person_disability = new PersonDisabilityModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($person_disability->update($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $person_disability->module,
                'action_id' => $person_disability->action_update,
                'record_id' => $request->id,
                'user_id' => $this->auth->id
            ]);

            return [
                "success" => true,
                "message" => "success"
            ];
        }

        return [
            "success" => false,
            "message" => "error"
        ];
    }

    public function remove($request) {
        $person_disability = new PersonDisabilityModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($person_disability->remove($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $person_disability->module,
                'action_id' => $person_disability->action_delete,
                'record_id' => $request->id,
                'user_id' => $this->auth->id
            ]);

            return [
                "success" => true,
                "message" => "success"
            ];
        }

        return [
            "success" => false,
            "message" => "error"
        ];
    }
}
?>