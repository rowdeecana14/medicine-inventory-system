<?php

namespace App\Controller\Medicines;
use App\Model\Medicines\MedicineModel;
use App\Model\Settings\StockLevelModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class InventoryController extends BaseController {

    public $module = 16;
    public $action_add = 1;
    public $action_update = 2;
    public $action_delete = 3;
    public $action_read = 4;
    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new StockLevelModel;
        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 1]];
        $low_level = $model->select(['quantity'], [],  $wheres);
        $low_level = isset($low_level['quantity']) ? $low_level['quantity'] : 0;

        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 2]];
        $moderate_level = $model->select(['quantity'], [],  $wheres);
        $moderate_level = isset($moderate_level['quantity']) ? $moderate_level['quantity'] : 0;

        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 3]];
        $high_level = $model->select(['quantity'], [],  $wheres);
        $high_level = isset($high_level['quantity']) ? $high_level['quantity'] : 0;

        $model = new MedicineModel;
        $show_fields = [ 
            'image', 'name', 'description', 'status', 'category_id', 'type_id', 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type',
            "(SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id ) as stockin",
            "(SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id ) as stockout",
            "(SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL) as expired",
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],
        ];

        $medicines = $model->selects($show_fields, $join_tables);
        $result = [];

        foreach($medicines as $index => $medicine) {
            $level = "";
            $avatar_status = $medicine['status'] == "Active" ? "avatar-online" : "avatar-offline";
            $url =  Helper::uploadedMedicineImage($medicine['image']);

            $available = ((int) $medicine['stockin'] - (int)  $medicine['stockout']) - (int) $medicine['expired'];

            if($available <= $low_level ) {
                $level = '<span class="badge badge-danger">LOW</span>';
            }
            else if($available > $low_level && $available <= $moderate_level) {
                $level = '<span class="badge badge-warning">MODERATE</span>';
            }
            else if($available <= $high_level  && $available > $moderate_level) {
                $level = '<span class="badge badge-info">HIGH</span>';
            }
            else if($available > $high_level) {
                $level = '<span class="badge badge-primary">EXCELENT</span>';
            }
           
            array_push($result, [
                'index' => $index + 1,
                'image' => '
                    <div class="avatar '.$avatar_status.'">
                        <img src="'.$url.'" alt="'.$medicine['name'].'" class="avatar-img">
                    </div>
                ',
                'medicine' => $medicine['name'],
                'description' => $medicine['description'],
                'stockin' => (int)  $medicine['stockin'],
                'stockout' => (int) $medicine['stockout'],
                'expired' => (int) $medicine['expired'],
                'available' => $available,
                'level' => $level
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function show($request) {
        $model = new MedicineModel;
        $show_fields = [ 
            'image', 'name', 'description', 'expired_at', 'status', 'category_id', 'type_id', 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type'
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],
        ];
        $wheres = [[ 'table' => 'medicines', 'key' => 'id', 'value' => $request->id ]];
        $medicine = $model->select($show_fields, $join_tables,  $wheres);

        $medicine = array_merge($medicine, [
            'id' =>  $medicine['medicine_id'],
            'image_profile' => Helper::uploadedMedicineImage($medicine['image']),
            'expired_at' => Helper::dateParserShow($medicine['expired_at']),
            'category_id' => [
                "id" => $medicine['category_id'],
                "text" => $medicine['category'],
            ],
            'type_id' => [
                "id" => $medicine['type_id'],
                "text" => $medicine['type'],
            ],
         ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $medicine
        ];
    }
}
?>