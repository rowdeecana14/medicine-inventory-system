<?php

namespace App\Controller\Settings;
use App\Model\Settings\GenderModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class GenderController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new GenderModel;
        $genders = $model->all();
        $result = [];

        foreach($genders as $index => $gender) {
            $badge =  "";
            $action = '
                <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$gender['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$gender['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
                    <i class="fas fa-trash-alt"></i>
                </button>
            ';

            if( $gender['status'] == "Default") {
                $action = '';
                $badge =  "info";
            }
            else if( $gender['status'] == "Active") {
                $badge = "secondary";
            }
            else {
                $badge = "default";
            }
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $gender['name'],
                'created_at' => date('M d, Y h:i A', strtotime($gender['created_at'])),
                'updated_at' => $gender['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($gender['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($gender['status']).'</span>',
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
        $model = new GenderModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $genders = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $genders
        ];
    }
    
    public function store($request) {
        $data = [
            'name' => $request->name,
            'created_by' => $this->auth->id
        ];
        $gender_model = new GenderModel;
        $gender_id =  $gender_model->lastInsertId($data);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $gender_model->module,
            'action_id' => $gender_model->action_add,
            'record_id' => $gender_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $gender_model = new GenderModel;
        $gender = $gender_model->show(['id' => $request->id]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $gender_model->module,
            'action_id' => $gender_model->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $gender
        ];
    }

    public function update($request) {
        $gender_model = new GenderModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($gender_model->update($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $gender_model->module,
                'action_id' => $gender_model->action_update,
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
        $gender_model = new GenderModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($gender_model->remove($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $gender_model->module,
                'action_id' => $gender_model->action_delete,
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