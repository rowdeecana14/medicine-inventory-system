<?php

namespace App\Controller\Settings;
use App\Model\Settings\BloodTypeModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class BloodTypeController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new BloodTypeModel;
        $blood_types = $model->all();
        $result = [];

        foreach($blood_types as $index => $blood_type) {
            $badge =  "";
            $action = '
                <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$blood_type['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$blood_type['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
                    <i class="fas fa-trash-alt"></i>
                </button>
            ';

            if( $blood_type['status'] == "Default") {
                $action = '';
                $badge =  "info";
            }
            else if( $blood_type['status'] == "Active") {
                $badge = "secondary";
            }
            else {
                $badge = "default";
            }
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $blood_type['name'],
                'created_at' => date('M d, Y h:i A', strtotime($blood_type['created_at'])),
                'updated_at' => $blood_type['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($blood_type['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($blood_type['status']).'</span>',
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
        $model = new BloodTypeModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $blood_types = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $blood_types
        ];
    }
    
    public function store($request) {
        $data = [
            'name' => $request->name,
            'created_by' => $this->auth->id
        ];
        $blood_type_model = new BloodTypeModel;
        $blood_type_id =  $blood_type_model->lastInsertId($data);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $blood_type_model->module,
            'action_id' => $blood_type_model->action_add,
            'record_id' => $blood_type_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $blood_type_model = new BloodTypeModel;
        $blood_type = $blood_type_model->show(['id' => $request->id]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $blood_type_model->module,
            'action_id' => $blood_type_model->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $blood_type
        ];
    }

    public function update($request) {
        $blood_type_model = new BloodTypeModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($blood_type_model->update($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $blood_type_model->module,
                'action_id' => $blood_type_model->action_update,
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
        $blood_type_model = new BloodTypeModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($blood_type_model->remove($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $blood_type_model->module,
                'action_id' => $blood_type_model->action_delete,
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