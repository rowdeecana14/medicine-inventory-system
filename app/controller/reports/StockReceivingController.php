<?php

namespace App\Controller\Reports;
use App\Model\Medicines\StockinMedicineModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class StockReceivingController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new StockinMedicineModel;
        $show_fields = [ 
            'st.transaction_no', 'm.name', 'm.description', 'categories.name as category', 'types.name as type',
            'stockin_medicines.quantity', 'sit.received_at',
            "CONCAT(ho.first_name,' ',  SUBSTRING(ho.middle_name, 1, 1), '. ', ho.last_name) as receiver",
        ];
        $join_tables = [
            [ "LEFT", "medicines as m", "m.id", "stockin_medicines.medicine_id"],
            [ "LEFT", "categories", "m.category_id", "categories.id"],
            [ "LEFT", "types", "m.type_id", "types.id"],
            [ "LEFT", "stockin_transactions as sit", "sit.id", "stockin_medicines.stockin_transaction_id"],
            [ "LEFT", "stock_transactions as st", "st.stock_transaction_id", "sit.id"],
            [ "LEFT", "health_officials as ho", "sit.health_official_id", "ho.id"],
        ];

        $where_fields = [[ 'table' => 'st', 'key' => 'type', 'value' => 'stockin' ]];
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
                'receiver' => $medicine['receiver'],
                'received_at' =>  Helper::humanDate('M d, Y', $medicine['received_at']), 
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function filter($request) {
        $model = new StockinMedicineModel;
        $show_fields = [ 
            'st.transaction_no', 'm.name', 'm.description', 'categories.name as category', 'types.name as type',
            'stockin_medicines.quantity', 'sit.received_at',
            "CONCAT(ho.first_name,' ',  SUBSTRING(ho.middle_name, 1, 1), '. ', ho.last_name) as receiver",
        ];
        $join_tables = [
            [ "LEFT", "medicines as m", "m.id", "stockin_medicines.medicine_id"],
            [ "LEFT", "categories", "m.category_id", "categories.id"],
            [ "LEFT", "types", "m.type_id", "types.id"],
            [ "LEFT", "stockin_transactions as sit", "sit.id", "stockin_medicines.stockin_transaction_id"],
            [ "LEFT", "stock_transactions as st", "st.stock_transaction_id", "sit.id"],
            [ "LEFT", "health_officials as ho", "sit.health_official_id", "ho.id"],
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
            array_push($where_fields, [ 'table' => 'sit', 'key' => 'health_official_id', 'value' => $request->health_official_id ]);
        }
        if($request->received_at != '' && $request->received_at != null) {
            array_push($where_fields, [ 'table' => 'sit', 'key' => 'received_at', 'value' =>  Helper::dateParser($request->received_at) ]);
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
                'receiver' => $medicine['receiver'],
                'received_at' =>  Helper::humanDate('M d, Y', $medicine['received_at']), 
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