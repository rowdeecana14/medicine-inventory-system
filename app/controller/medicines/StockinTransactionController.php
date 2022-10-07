<?php

namespace App\Controller\Medicines;
use App\Model\Medicines\StockTransactionModel;
use App\Model\Medicines\StockinTransactionModel;
use App\Model\Medicines\StockinMedicineModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class StockinTransactionController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $stockinTransactionModel = new StockinTransactionModel;
        $show_fields = [ 
            'delivery_person', 'received_at', 'health_official_id', 
        ];
        $show_fields = Helper::appendTable('stockin_transactions', $show_fields);
        $show_fields = array_merge($show_fields, [
            "stockin_transactions.id as stockin_id",
            "st.transaction_no as transaction_no",
            "CONCAT(ho.first_name,' ',  SUBSTRING(ho.middle_name, 1, 1), '. ', ho.last_name) as receiver",
            "( SELECT COUNT(id) FROM stockin_medicines AS sim WHERE sim.stockin_transaction_id=stockin_transactions.id ) as total"
        ]);
        $join_tables = [
            [ "LEFT", "stock_transactions as st", "stockin_transactions.id", "st.stock_transaction_id"],
            [ "LEFT", "health_officials as ho", "stockin_transactions.health_official_id", "ho.id"],
        ];
        $wheres = [[ 'table' => 'st', 'key' => 'type', 'value' => 'stockin' ]];
        $stockin_transactions = $stockinTransactionModel->selects($show_fields, $join_tables, $wheres);
        $result = [];

        foreach($stockin_transactions as $index => $transaction) {
            array_push($result, [
                'index' => $index + 1,
                'transaction_no' => app_code().'-'.str_pad($transaction['transaction_no'], 6, "0", STR_PAD_LEFT),
                'total' => $transaction['total'],
                'receiver' => $transaction['receiver'],
                'delivery_person' => $transaction['delivery_person'],
                'received_at' => Helper::humanDate('M d, Y', $transaction['received_at']),
                'action' => '
                    <button type="button" class="btn btn-icon btn-round btn-info btn-show"  data-id="'.$transaction['stockin_id'].'" data-toggle="tooltip" data-placement="top" title="View record">
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

        // SAVE STOCKIN TRANSACTION DATA
        $stockinTransactionModel = new StockinTransactionModel;
        $sit_data = Helper::allowOnly((array) $request, ['contact_no', 'delivery_person', 'received_at', 'remarks', 'health_official_id']);
        $sit_data = array_merge($sit_data, [
            "received_at" => Helper::dateParser($sit_data['received_at']),
            "created_by" => $this->auth->id 
        ]);
        $sit_id =  $stockinTransactionModel->lastInsertId($sit_data);

        // SAVE STOCK TRANSACTION DATA 
        $st_model = new StockTransactionModel;
        $last_row = $st_model->getLastRow();
        $transaction_no = isset($last_row['transaction_no']) ? (int) $last_row['transaction_no'] + 1  : 1;
        $transaction_code =  app_code().'-'.str_pad($transaction_no, 6, "0", STR_PAD_LEFT);

        $st_data = [
            "transaction_no" => $transaction_no,
            "transaction_code" => $transaction_code,
            "type" => "stockin",
            "stock_transaction_id" => $sit_id,
            "created_by" => $this->auth->id
        ];
        $st_id =  $st_model->lastInsertId($st_data);

        // SAVE STOCKIN MEDICINES 
        $medicines =  (array) $request->medicines;
        $sim_model = new StockinMedicineModel;
        
        foreach($medicines as $index => $medicine) {
            $medicine_data =[
                "medicine_id" => $medicine->medicine_id,
                "quantity" => $medicine->quantity,
                "stockin_transaction_id" => $sit_id,
                "created_by" => $this->auth->id 
            ];
            $sim_model->lastInsertId($medicine_data);
        }

        // SAVE LOG DATA
        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $stockinTransactionModel->module,
            'action_id' => $stockinTransactionModel->action_add,
            'record_id' => $sit_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function profile($request) {
        // GET ONE STOCKIN TRANSACTION BY ID
        $stockinTransactionModel = new StockinTransactionModel;
        $show_fields = [ 'delivery_person', 'received_at', 'contact_no', 'remarks'  ];
        $show_fields = Helper::appendTable('stockin_transactions', $show_fields);
        $show_fields = array_merge($show_fields, [
            "ho.image", "CONCAT(ho.first_name,' ',  SUBSTRING(ho.middle_name, 1, 1), '. ', ho.last_name) as receiver",
        ]);
        $join_tables = [
            [ "LEFT", "stock_transactions as st", "stockin_transactions.id", "st.stock_transaction_id"],
            [ "LEFT", "health_officials as ho", "stockin_transactions.health_official_id", "ho.id"],
        ];
        $wheres = [[ 'table' => 'stockin_transactions', 'key' => 'id', 'value' => $request->id ]];
        $stockin_transaction = $stockinTransactionModel->select($show_fields, $join_tables,  $wheres);

        $stockin_transaction = array_merge($stockin_transaction, [
           'image_profile' => Helper::uploadedHealthOfficialImage($stockin_transaction['image']),
           'received_at' => Helper::humanDate('F d, Y', Helper::humanDate('m/d/Y', $stockin_transaction['received_at'])),
        ]);

        // GET LIST STOCKIN MEDICINE BY STOCKIN TRANSACTION ID
        $stockin_medicine_model = new StockinMedicineModel;
        $show_fields = [ 'quantity'];
        $show_fields = Helper::appendTable('stockin_medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            "medicines.image", "medicines.name", "medicines.description"
        ]);
        $join_tables = [
            [ "LEFT", "medicines", "stockin_medicines.medicine_id", "medicines.id"],
        ];
        $wheres = [[ 'table' => 'stockin_medicines', 'key' => 'stockin_transaction_id', 'value' => $request->id ]];
        $stockin_medicine = $stockin_medicine_model->selects($show_fields, $join_tables,  $wheres);

        foreach($stockin_medicine as $index => $medicine) {
            $url = Helper::uploadedMedicineImage($medicine['image']);
            $stockin_medicine[$index]['index'] = $index + 1;
            $stockin_medicine[$index]['image'] =   '
                <div class="avatar">
                    <img src="'.$url.'" alt="'.$medicine['name'].'" class="avatar-img">
                </div>
            ';
        };

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $stockinTransactionModel->module,
            'action_id' => $stockinTransactionModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "profile" => $stockin_transaction,
                "medicines" => $stockin_medicine
            ]
        ];
    }
}
?>