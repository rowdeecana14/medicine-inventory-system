<?php

namespace App\Controller\Reports;
use App\Model\Medicines\StockoutMedicineModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class StockDepensingController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new StockoutMedicineModel;
        $show_fields = [ 
            'st.transaction_no', 'm.name', 'm.description', 'categories.name as category', 'types.name as type',
            'stockout_medicines.quantity', 'sot.dispenced_at',
            "CONCAT(ho.first_name,' ',  SUBSTRING(ho.middle_name, 1, 1), '. ', ho.last_name) as health_official",
            "CONCAT(p.first_name,' ',  SUBSTRING(p.middle_name, 1, 1), '. ', p.last_name) as patient",
        ];
        $join_tables = [
            [ "LEFT", "medicines as m", "m.id", "stockout_medicines.medicine_id"],
            [ "LEFT", "categories", "m.category_id", "categories.id"],
            [ "LEFT", "types", "m.type_id", "types.id"],
            [ "LEFT", "stockout_transactions as sot", "sot.id", "stockout_medicines.stockout_transaction_id"],
            [ "LEFT", "stock_transactions as st", "st.stock_transaction_id", "sot.id"],
            [ "LEFT", "health_officials as ho", "sot.health_official_id", "ho.id"],
            [ "LEFT", "patients as p", "sot.patient_id", "p.id"],
        ];

        $where_fields = [[ 'table' => 'st', 'key' => 'type', 'value' => 'stockout' ]];
        $order_fields = [[ 'table' => 'st', 'key' => 'transaction_code', 'value' => 'asc']];

        $medicines = $model->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $result = [];

        foreach($medicines as $index => $medicine) {

            array_push($result, [
                'index' => $index + 1,
                'transaction_no' => app_code().'-'.str_pad($medicine['transaction_no'], 6, "0", STR_PAD_LEFT), 
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'category' => $medicine['category'],
                'type' => $medicine['type'],
                'quantity' => $medicine['quantity'],
                'health_official' => $medicine['health_official'],
                'patient' => $medicine['patient'],
                'dispenced_at' =>  Helper::humanDate('M d, Y', $medicine['dispenced_at']), 
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function filter($request) {
        $model = new StockoutMedicineModel;
        $show_fields = [ 
            'st.transaction_no', 'm.name', 'm.description', 'categories.name as category', 'types.name as type',
            'stockout_medicines.quantity', 'sot.dispenced_at',
            "CONCAT(ho.first_name,' ',  SUBSTRING(ho.middle_name, 1, 1), '. ', ho.last_name) as health_official",
            "CONCAT(p.first_name,' ',  SUBSTRING(p.middle_name, 1, 1), '. ', p.last_name) as patient",
        ];
        $join_tables = [
            [ "LEFT", "medicines as m", "m.id", "stockout_medicines.medicine_id"],
            [ "LEFT", "categories", "m.category_id", "categories.id"],
            [ "LEFT", "types", "m.type_id", "types.id"],
            [ "LEFT", "stockout_transactions as sot", "sot.id", "stockout_medicines.stockout_transaction_id"],
            [ "LEFT", "stock_transactions as st", "st.stock_transaction_id", "sot.id"],
            [ "LEFT", "health_officials as ho", "sot.health_official_id", "ho.id"],
            [ "LEFT", "patients as p", "sot.patient_id", "p.id"],
        ];

        $where_fields = [];
        $order_fields = [[ 'table' => 'm', 'key' => 'name', 'value' => 'asc']];

        if($request->transaction_id != 'All' && $request->transaction_id != null) {
            array_push($where_fields,  [ 'table' => 'st', 'key' => 'id', 'value' => $request->transaction_id ]);
        }

        if($request->category_id != 'All' && $request->category_id != null) {
            array_push($where_fields,  [ 'table' => 'm', 'key' => 'category_id', 'value' => $request->category_id ]);
        }
        if($request->type_id != 'All' && $request->type_id != null) {
            array_push($where_fields, [ 'table' => 'm', 'key' => 'type_id', 'value' => $request->type_id ]);
        }
        if($request->health_official_id != 'All' && $request->health_official_id != null) {
            array_push($where_fields, [ 'table' => 'sot', 'key' => 'health_official_id', 'value' => $request->health_official_id ]);
        }
        if($request->patient_id != 'All' && $request->patient_id != null) {
            array_push($where_fields, [ 'table' => 'sot', 'key' => 'patient_id', 'value' => $request->patient_id ]);
        }
        if($request->dispenced_at != '' && $request->dispenced_at != null) {
            array_push($where_fields, [ 'table' => 'sot', 'key' => 'dispenced_at', 'value' =>  Helper::dateParser($request->dispenced_at) ]);
        }

        $medicines = $model->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $result = [];

        foreach($medicines as $index => $medicine) {

            array_push($result, [
                'index' => $index + 1,
                'transaction_no' => app_code().'-'.str_pad($medicine['transaction_no'], 6, "0", STR_PAD_LEFT), 
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'category' => $medicine['category'],
                'type' => $medicine['type'],
                'quantity' => $medicine['quantity'],
                'health_official' => $medicine['health_official'],
                'patient' => $medicine['patient'],
                'dispenced_at' =>  Helper::humanDate('M d, Y', $medicine['dispenced_at']), 
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