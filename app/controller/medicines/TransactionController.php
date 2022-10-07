<?php

namespace App\Controller\Medicines;
use App\Model\Medicines\StockTransactionModel;
use App\Controller\BaseController;

class TransactionController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function stockReceivingFilter($request) {
        $search = isset($request->q) ? $request->q : '';
        $type = "stockin";

        $transaction_model = new StockTransactionModel;
        $transactions = $transaction_model->getTransactionNos($search, $type);+

        array_unshift($transactions, ['id' => 'All' , 'text' => 'All']);

        return [
            "success" => true,
            "message" => "success",
            "data" => $transactions
        ];
    }
    public function stockDispencingFilter($request) {
        $search = isset($request->q) ? $request->q : '';
        $type = "stockout";

        $transaction_model = new StockTransactionModel;
        $transactions = $transaction_model->getTransactionNos($search, $type);+

        array_unshift($transactions, ['id' => 'All' , 'text' => 'All']);

        return [
            "success" => true,
            "message" => "success",
            "data" => $transactions
        ];
    }
}
?>