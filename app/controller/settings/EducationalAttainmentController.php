<?php

namespace App\Controller\Settings;
use App\Model\Settings\EducationalAttainmentModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class EducationalAttainmentController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new EducationalAttainmentModel;
        $educational_attainments = $model->all();
        $result = [];

        foreach($educational_attainments as $index => $educational_attainment) {
            $badge =  $educational_attainment['status'] == "Active" ? "secondary" : "default";
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $educational_attainment['name'],
                'created_at' => date('M d, Y h:i A', strtotime($educational_attainment['created_at'])),
                'updated_at' => $educational_attainment['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($educational_attainment['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($educational_attainment['status']).'</span>',
                'action' => ' 
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$educational_attainment['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$educational_attainment['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
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
        $model = new EducationalAttainmentModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $educational_attainments = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $educational_attainments
        ];
    }
    
    public function store($request) {
        $data = [
            'name' => $request->name,
            'created_by' => $this->auth->id
        ];
        $educationalAttainmentModel = new EducationalAttainmentModel;
        $educational_attainment_id =  $educationalAttainmentModel->lastInsertId($data);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $educationalAttainmentModel->module,
            'action_id' => $educationalAttainmentModel->action_add,
            'record_id' => $educational_attainment_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $educationalAttainmentModel = new EducationalAttainmentModel;
        $educational_attainment = $educationalAttainmentModel->show(['id' => $request->id]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $educationalAttainmentModel->module,
            'action_id' => $educationalAttainmentModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);


        return [
            "success" => true,
            "message" => "success",
            "data" => $educational_attainment
        ];
    }

    public function update($request) {
        $educationalAttainmentModel = new EducationalAttainmentModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($educationalAttainmentModel->update($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $educationalAttainmentModel->module,
                'action_id' => $educationalAttainmentModel->action_update,
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
        $educationalAttainmentModel = new EducationalAttainmentModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($educationalAttainmentModel->remove($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $educationalAttainmentModel->module,
                'action_id' => $educationalAttainmentModel->action_delete,
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