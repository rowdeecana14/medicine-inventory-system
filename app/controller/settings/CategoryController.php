<?php

namespace App\Controller\Settings;
use App\Model\Settings\CategoryModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class CategoryController extends BaseController {
    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new CategoryModel;
        $categories = $model->all();
        $result = [];

        foreach($categories as $index => $category) {
            $badge =  $category['status'] == "Active" ? "secondary" : "default";
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $category['name'],
                'created_at' => date('M d, Y h:i A', strtotime($category['created_at'])),
                'updated_at' => $category['updated_at'] != NULL ? date('M d, Y h:i A', strtotime($category['updated_at'])) : '',
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($category['status']).'</span>',
                'action' => ' 
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$category['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$category['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
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
        $model = new CategoryModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $categories = $model->search($fields, [], $data);

        array_unshift($categories, ['id' => 'All' , 'text' => 'All']);

        return [
            "success" => true,
            "message" => "success",
            "data" => $categories
        ];
    }

    public function select2($request) {
        $model = new CategoryModel;
        $data = isset($request->q) ? [ 'name' => $request->q ] : [];
        $fields = ['id', 'name as text'];
        $categories = $model->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $categories
        ];
    }
    
    public function store($request) {
        $category_model = new CategoryModel;
        $data = [
            'name' => $request->name,
            'created_by' => $this->auth->id
        ];
        $category_id =  $category_model->lastInsertId($data);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $category_model->module,
            'action_id' => $category_model->action_add,
            'record_id' => $category_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $category_model = new CategoryModel;
        $category = $category_model->show(['id' => $request->id]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $category_model->module,
            'action_id' => $category_model->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $category
        ];
    }

    public function update($request) {
        $category_model = new CategoryModel;

        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($category_model->update($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $category_model->module,
                'action_id' => $category_model->action_update,
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
        $category_model = new CategoryModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($category_model->remove($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $category_model->module,
                'action_id' => $category_model->action_delete,
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