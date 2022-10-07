<?php

namespace App\Controller\Settings;
use App\Model\Settings\TypeModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class TypeController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new TypeModel;
        $types = $model->all();
        $result = [];

        foreach($types as $index => $type) {
            $badge =  $type['status'] == "Active" ? "secondary" : "default";
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $type['name'],
                'created_at' => date('M d, Y h:i A', strtotime($type['created_at'])),
                'updated_at' => $type['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($type['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($type['status']).'</span>',
                'action' => ' 
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$type['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$type['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
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

    public function reportFilter($request) {
        $model = new TypeModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $types = $model->search($fields, [], $data);

        array_unshift($types, ['id' => 'All' , 'text' => 'All']);

        return [
            "success" => true,
            "message" => "success",
            "data" => $types
        ];
    }

    public function select2($request) {
        $model = new TypeModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $types = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $types
        ];
    }
    
    public function store($request) {
        $type_model = new TypeModel;
        $data = [
            'name' => $request->name,
            'created_by' => $this->auth->id
        ];
        $type_id =  $type_model->lastInsertId($data);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $type_model->module,
            'action_id' => $type_model->action_add,
            'record_id' => $type_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $type_model = new TypeModel;
        $type = $type_model->show(['id' => $request->id]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $type_model->module,
            'action_id' => $type_model->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $type
        ];
    }

    public function update($request) {
        $type_model = new TypeModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($type_model->update($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $type_model->module,
                'action_id' => $type_model->action_update,
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
        $type_model = new TypeModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($type_model->remove($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $type_model->module,
                'action_id' => $type_model->action_delete,
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