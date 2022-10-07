<?php

namespace App\Controller\Reports;
use App\Model\Medicines\StockExpirationModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class StockExpirationController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new StockExpirationModel;
        $show_fields = [ 
            'm.name', 'm.description', 'categories.name as category', 'types.name as type',
            'stock_expiries.quantity', 'stock_expiries.expired_at', 
            'DATEDIFF(stock_expiries.expired_at, CURRENT_DATE) as days'
        ];
        $join_tables = [
            [ "LEFT", "medicines as m", "m.id", "stock_expiries.medicine_id"],
            [ "LEFT", "categories", "m.category_id", "categories.id"],
            [ "LEFT", "types", "m.type_id", "types.id"],
        ];

        $where_fields = [];
        $order_fields = [[ 'table' => 'm', 'key' => 'name', 'value' => 'asc']];

        $medicines = $model->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $result = [];

        foreach($medicines as $index => $medicine) {
            $status = $medicine['days'] <= 0 ?  'EXPIRED': 'NOT EXPIRED';
            $days = $medicine['days'] <= 0 ?  '0 DAY(S)' :  $medicine['days'].' DAY(S)';
            
            array_push($result, [
                'index' => $index + 1,
                'name' => $medicine['name'],
                'quantity' => $medicine['quantity'],
                'description' => $medicine['description'],
                'category_id' => $medicine['category'],
                'type_id' => $medicine['type'],
                'expired_at' =>  Helper::humanDate('M d, Y', $medicine['expired_at']), 
                'days' => $days,
                'status' =>  $status,
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function filter($request) {
        $model = new StockExpirationModel;
        $medicines = $model->filterExpiration($request);
        $result = [];

        foreach($medicines as $index => $medicine) {
            $status = $medicine['days'] <= 0 ?  'EXPIRED': 'NOT EXPIRED';
            $days = $medicine['days'] <= 0 ?  '0 DAY(S)' :  $medicine['days'].' DAY(S)';


            array_push($result, [
                'index' => $index + 1,
                'name' => $medicine['name'],
                'quantity' => $medicine['quantity'],
                'description' => $medicine['description'],
                'category_id' => $medicine['category'],
                'type_id' => $medicine['type'],
                'expired_at' =>  Helper::humanDate('M d, Y', $medicine['expired_at']), 
                'days' => $days,
                'status' =>  $status,
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