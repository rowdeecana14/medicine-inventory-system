<?php

namespace App\Controller\Medicines;
use App\Model\Medicines\StockTransactionModel;
use App\Model\Medicines\StockoutTransactionModel;
use App\Model\Medicines\StockoutMedicineModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;
use App\Model\Medicines\MedicineModel;

class StockoutTransactionController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $stockout_transaction_model = new StockoutTransactionModel;
        $show_fields = [ 
            'dispenced_at', 'health_official_id', 
        ];
        $show_fields = Helper::appendTable('stockout_transactions', $show_fields);
        $show_fields = array_merge($show_fields, [
            "stockout_transactions.id as stockout_id",
            "st.transaction_no as transaction_no",
            "CONCAT(ho.first_name,' ',  SUBSTRING(ho.middle_name, 1, 1), '. ', ho.last_name) as health_official",
            "CONCAT(p.first_name,' ',  SUBSTRING(p.middle_name, 1, 1), '. ', p.last_name) as patient",
            "( SELECT COUNT(id) FROM stockout_medicines AS som WHERE som.stockout_transaction_id=stockout_transactions.id ) as total"
        ]);
        $join_tables = [
            [ "LEFT", "stock_transactions as st", "stockout_transactions.id", "st.stock_transaction_id"],
            [ "LEFT", "health_officials as ho", "stockout_transactions.health_official_id", "ho.id"],
            [ "LEFT", "patients as p", "stockout_transactions.patient_id", "p.id"],
        ];
        $wheres = [[ 'table' => 'st', 'key' => 'type', 'value' => 'stockout' ]];
        $stockout_transactions = $stockout_transaction_model->selects($show_fields, $join_tables, $wheres);
        $result = [];

        foreach($stockout_transactions as $index => $transaction) {
            array_push($result, [
                'index' => $index + 1,
                'transaction_no' => app_code().'-'.str_pad($transaction['transaction_no'], 6, "0", STR_PAD_LEFT),
                'total' => $transaction['total'],
                'health_official' => $transaction['health_official'],
                'patient' => $transaction['patient'],
                'dispenced_at' => Helper::humanDate('M d, Y', $transaction['dispenced_at']),
                'action' => '
                    <button type="button" class="btn btn-icon btn-round btn-info btn-show"  data-id="'.$transaction['stockout_id'].'" data-toggle="tooltip" data-placement="top" title="View record">
                        <i class="fas fa-search"></i>
                    </button>'
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function store($request) {
        // CHECK AVAILABLE STOCKS
        $medicineModel = new MedicineModel;
        $medicines =  (array) $request->medicines;
        $errors = [];
        
        foreach($medicines as $index => $medicine) {
            $medicine_stock = $medicineModel->getAvailableStock($medicine->medicine_id);
            $available = ((int) $medicine_stock['stockin'] - (int)  $medicine_stock['stockout']) - (int) $medicine_stock['expired'];
            $quantity = (int) $medicine->quantity;

            if($available < $quantity) {
                array_push($errors, [
                    "medicine" => $medicine_stock['name'],
                    "available" => $available
                ]);
            }
        }

        if(count($errors) > 0) {
            return [
                "success" => false,
                "validation" => false,
                "message" => "Not enough stocks",
                "errors" => $errors
            ];
        }

        // SAVE STOCKIN TRANSACTION DATA
        $stockoutTransactionModel = new StockoutTransactionModel;
        $sot_data = Helper::allowOnly((array) $request, ['patient_id', 'dispenced_at', 'remarks', 'health_official_id']);
        $sot_data = array_merge($sot_data, [
            "dispenced_at" => Helper::dateParser($sot_data['dispenced_at']),
            "created_by" => $this->auth->id 
        ]);
        $sot_id =  $stockoutTransactionModel->lastInsertId($sot_data);

        // SAVE STOCK TRANSACTION DATA 
        $stockTransactionModel = new StockTransactionModel;
        $last_row = $stockTransactionModel->getLastRow();
        $transaction_no = isset($last_row['transaction_no']) ? (int) $last_row['transaction_no'] + 1  : 1;
        $transaction_code =  app_code().'-'.str_pad($transaction_no, 6, "0", STR_PAD_LEFT);

        $st_data = [
            "transaction_no" => $transaction_no,
            "transaction_code" => $transaction_code,
            "type" => "stockout",
            "stock_transaction_id" => $sot_id,
            "created_by" => $this->auth->id
        ];
        $st_id =  $stockTransactionModel->lastInsertId($st_data);

        // SAVE STOCKIN MEDICINES 
        $stockoutMedicineModel = new StockoutMedicineModel;
        
        foreach($medicines as $index => $medicine) {
            $medicine_data =[
                "medicine_id" => $medicine->medicine_id,
                "quantity" => $medicine->quantity,
                "dosage" => $medicine->dosage,
                "stockout_transaction_id" => $sot_id,
                "created_by" => $this->auth->id 
            ];
            $stockoutMedicineModel->lastInsertId($medicine_data);
        }

        // SAVE LOG DATA
        $logModel = new LogModel;
        $logModel->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $stockoutTransactionModel->module,
            'action_id' => $stockoutTransactionModel->action_add,
            'record_id' => $sot_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function profile($request) {
        // GET ONE STOCKIN TRANSACTION BY ID
        $stockoutTransactionModel = new StockoutTransactionModel;
        $show_fields = [ 'dispenced_at', 'remarks'  ];
        $show_fields = Helper::appendTable('stockout_transactions', $show_fields);
        $show_fields = array_merge($show_fields, [
            "p.image", "CONCAT(ho.first_name,' ',  SUBSTRING(ho.middle_name, 1, 1), '. ', ho.last_name) as health_official",
            "CONCAT(p.first_name,' ',  SUBSTRING(p.middle_name, 1, 1), '. ', p.last_name) as patient",
        ]);
        $join_tables = [
            [ "LEFT", "stock_transactions as st", "stockout_transactions.id", "st.stock_transaction_id"],
            [ "LEFT", "health_officials as ho", "stockout_transactions.health_official_id", "ho.id"],
            [ "LEFT", "patients as p", "stockout_transactions.patient_id", "p.id"],
        ];
        $wheres = [[ 'table' => 'stockout_transactions', 'key' => 'id', 'value' => $request->id ]];
        $stockout_transaction = $stockoutTransactionModel->select($show_fields, $join_tables,  $wheres);

        $stockout_transaction = array_merge($stockout_transaction, [
           'image_profile' => Helper::uploadedPatientImage($stockout_transaction['image']),
           'dispenced_at' => Helper::humanDate('F d, Y', Helper::humanDate('m/d/Y', $stockout_transaction['dispenced_at'])),
        ]);

        // GET LIST STOCKIN MEDICINE BY STOCKIN TRANSACTION ID
        $stockoutMedicineModel = new StockoutMedicineModel;
        $show_fields = [ 'quantity'];
        $show_fields = Helper::appendTable('stockout_medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            "medicines.image", "medicines.name", "medicines.description"
        ]);
        $join_tables = [
            [ "LEFT", "medicines", "stockout_medicines.medicine_id", "medicines.id"],
        ];
        $wheres = [[ 'table' => 'stockout_medicines', 'key' => 'stockout_transaction_id', 'value' => $request->id ]];
        $stockout_medicines = $stockoutMedicineModel->selects($show_fields, $join_tables,  $wheres);

        foreach($stockout_medicines as $index => $medicine) {
            $url = Helper::uploadedMedicineImage($medicine['image']);
            $stockout_medicines[$index]['index'] = $index + 1;
            $stockout_medicines[$index]['image'] =   '
                <div class="avatar">
                    <img src="'.$url.'" alt="'.$medicine['name'].'" class="avatar-img">
                </div>
            ';
        };

        // SAVE LOG DATA
        $logModel = new LogModel;
        $logModel->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $stockoutTransactionModel->module,
            'action_id' => $stockoutTransactionModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "profile" => $stockout_transaction,
                "medicines" => $stockout_medicines
            ]
        ];
    }
}
?>