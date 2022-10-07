<?php

namespace App\Controller\Settings;
use App\Model\Settings\BaranggayModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class BaranggayController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new BaranggayModel;
        $barrangays = $model->all();
        $result = [];

        foreach($barrangays as $index => $barrangay) {
            $badge =  $barrangay['status'] == "Active" ? "secondary" : "default";
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $barrangay['name'],
                'code' => $barrangay['code'],
                'created_at' => date('M d, Y h:i A', strtotime($barrangay['created_at'])),
                'updated_at' => $barrangay['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($barrangay['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($barrangay['status']).'</span>',
                'action' => ' 
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$barrangay['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$barrangay['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
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
        $model = new BaranggayModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $barrangays = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $barrangays
        ];
    }
    
    public function store($request) {
        $barrangay_model = new BaranggayModel;
        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'created_by' => $this->auth->id
        ];
        $barrangay_id =  $barrangay_model->lastInsertId($data);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $barrangay_model->module,
            'action_id' => $barrangay_model->action_add,
            'record_id' => $barrangay_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $barrangay_model = new BaranggayModel;
        $barrangay = $barrangay_model->show(['id' => $request->id]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $barrangay_model->module,
            'action_id' => $barrangay_model->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $barrangay
        ];
    }

    public function update($request) {
        $barrangay_model = new BaranggayModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($barrangay_model->update($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $barrangay_model->module,
                'action_id' => $barrangay_model->action_update,
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
        $barrangay_model = new BaranggayModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($barrangay_model->remove($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $barrangay_model->module,
                'action_id' => $barrangay_model->action_delete,
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