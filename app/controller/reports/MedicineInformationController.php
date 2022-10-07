<?php

namespace App\Controller\Reports;
use App\Model\Medicines\MedicineModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class MedicineInformationController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new MedicineModel;
        $show_fields = [ 
            'image', 'name', 'description', 'status', 'category_id', 'type_id', 'status' 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type'
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],
        ];

        $medicines = $model->selects($show_fields, $join_tables);
        $result = [];

        foreach($medicines as $index => $medicine) {

            array_push($result, [
                'index' => $index + 1,
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'category_id' => $medicine['category'],
                'type_id' => $medicine['type'],
                'status' =>  $medicine['status'],
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function filter($request) {
        $model = new MedicineModel;
        $show_fields = [ 
            'image', 'name', 'description', 'status', 'category_id', 'type_id', 'status' 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type'
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],
        ];

        $where_fields = [];
        $order_fields = [[ 'table' => 'medicines', 'key' => 'name', 'value' => 'asc']];

        if($request->category_id != 'All' && $request->category_id != null) {
            array_push($where_fields,  [ 'table' => 'medicines', 'key' => 'category_id', 'value' => $request->category_id ]);
        }
        if($request->type_id != 'All' && $request->type_id != null) {
            array_push($where_fields, [ 'table' => 'medicines', 'key' => 'type_id', 'value' => $request->type_id ]);
        }
        if($request->status != 'All') {
            array_push($where_fields, [ 'table' => 'medicines', 'key' => 'status', 'value' => $request->status ]);
        }

        $medicines = $model->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $result = [];

        foreach($medicines as $index => $medicine) {
            array_push($result, [
                'index' => $index + 1,
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'category_id' => $medicine['category'],
                'type_id' => $medicine['type'],
                'status' =>  $medicine['status'],
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }
}
?>