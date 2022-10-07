<?php

namespace App\Controller\Settings;
use App\Model\Settings\CivilStatusModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class CivilStatusController extends BaseController {
    
    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new CivilStatusModel;
        $civil_statuses = $model->all();
        $result = [];

        foreach($civil_statuses as $index => $civil_status) {
            $badge =  $civil_status['status'] == "Active" ? "secondary" : "default";
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $civil_status['name'],
                'created_at' => date('M d, Y h:i A', strtotime($civil_status['created_at'])),
                'updated_at' => $civil_status['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($civil_status['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($civil_status['status']).'</span>',
                'action' => ' 
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$civil_status['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$civil_status['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
                        <i class="fas fa-trash-alt"></i>
                    </button>'
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function select2($request) {
        $model = new CivilStatusModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $civil_statuses = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $civil_statuses
        ];
    }
    
    public function store($request) {
        $civilStatusModel = new CivilStatusModel;
        $data = [
            'name' => $request->name,
            'created_by' => $this->auth->id
        ];

        $log = new LogModel;
        $civil_status_id =  $civilStatusModel->lastInsertId($data);

        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $civilStatusModel->module,
            'action_id' => $civilStatusModel->action_add,
            'record_id' => $civil_status_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $civilStatusModel = new CivilStatusModel;
        $civil_status = $civilStatusModel->show(['id' => $request->id]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $civilStatusModel->module,
            'action_id' => $civilStatusModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $civil_status
        ];
    }

    public function update($request) {
        $civilStatusModel = new CivilStatusModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' =>  $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($civilStatusModel->update($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $civilStatusModel->module,
                'action_id' => $civilStatusModel->action_update,
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
        $civilStatusModel = new CivilStatusModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($civilStatusModel->remove($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $civilStatusModel->module,
                'action_id' => $civilStatusModel->action_delete,
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