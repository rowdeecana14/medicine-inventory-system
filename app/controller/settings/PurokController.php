<?php

namespace App\Controller\Settings;
use App\Model\Settings\PurokModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;


class PurokController extends BaseController {
    
    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new PurokModel;
        $show_fields = [
            'puroks.id as purok_id', 'puroks.name', 'puroks.baranggay_id', 'baranggays.id', 'baranggays.name as baranggay', 'puroks.status',
            'puroks.created_at', 'puroks.updated_at'
        ];
        $join_tables = [[ "LEFT", "baranggays", "puroks.baranggay_id", "baranggays.id"]];
        $puroks = $model->selects($show_fields, $join_tables);
        $result = [];

        foreach($puroks as $index => $purok) {
            $badge =  $purok['status'] == "Active" ? "secondary" : "default";
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $purok['name'],
                'baranggay' => $purok['baranggay'],
                'created_at' => date('M d, Y h:i A', strtotime($purok['created_at'])),
                'updated_at' => $purok['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($purok['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($purok['status']).'</span>',
                'action' => ' 
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$purok['purok_id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$purok['purok_id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
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
        $model = new PurokModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $puroks = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $puroks
        ];
    }
    
    public function store($request) {
        $purok_model = new PurokModel;
        $data = [
            'name' => $request->name,
            'baranggay_id' => $request->baranggay_id,
            'created_by' => $this->auth->id
        ];

        $purok_id =  $purok_model->lastInsertId($data);
        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $purok_model->module,
            'action_id' => $purok_model->action_add,
            'record_id' => $purok_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $purok_model = new PurokModel;
        $show_fields = [
            'puroks.id as purok_id', 'puroks.name', 'puroks.baranggay_id', 'baranggays.id', 'baranggays.name as baranggay', 'puroks.status',
        ];
        $join_tables = [[ "LEFT", "baranggays", "puroks.baranggay_id", "baranggays.id"]];
        $wheres = [[ 'table' => 'puroks', 'key' => 'id', 'value' => $request->id ]];
        $purok = $purok_model->select($show_fields, $join_tables,  $wheres);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $purok_model->module,
            'action_id' => $purok_model->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "id" => $purok['purok_id'],
                "name" => $purok['name'],
                "status" =>  $purok['status'],
                "edit_baranggay_id" => [
                    "id" => $purok['baranggay_id'],
                    "text" => $purok['baranggay'],
                ]
            ]
        ];
    }

    public function update($request) {
        $purok_model = new PurokModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'baranggay_id' => $request->edit_baranggay_id,
            'status' => $request->status,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($purok_model->update($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $purok_model->module,
                'action_id' => $purok_model->action_update,
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
        $purok_model = new PurokModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($purok_model->remove($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $purok_model->module,
                'action_id' => $purok_model->action_delete,
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