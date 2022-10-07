<?php
namespace App\Model\Medicines;

use App\Model\BaseModel;

class StockTransactionModel extends BaseModel {
    private static $table = 'stock_transactions';
    private static $order_by = [ "stock_transactions.id", "asc"];
    private static $fillable = [];

    public $module = 16;
    public $action_add = 1;
    public $action_update = 2;
    public $action_delete = 3;
    public $action_read = 4;
    
    public function getFillable() {
       return self::$fillable;
    }

    public function getTable() {
        return self::$table;
    }

    public function getOrderBy() {
        return self::$order_by;
    }

    public function getLastRow() {
        $this->sql = "
            SELECT *
            FROM stock_transactions
            WHERE deleted_at IS NULL
            ORDER BY ID DESC LIMIT 1
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement ->execute();

        return $this->statement->fetch();
    }
    public function getTransactionNos($search, $type) {

        if($type == 'stockin') {
            $this->sql = "
                SELECT st.id, st.transaction_code as text
                FROM `stockin_transactions`  as sit
                LEFT JOIN stock_transactions AS st ON sit.id = st.stock_transaction_id
                WHERE  st.transaction_code LIKE :transaction_code
                AND st.type LIKE :type
            ";
        }
        else {
            $this->sql = "
                SELECT st.id, st.transaction_code as text
                FROM `stockout_transactions`  as sot
                LEFT JOIN stock_transactions AS st ON sot.id = st.stock_transaction_id
                WHERE  st.transaction_code LIKE :transaction_code
                AND st.type LIKE :type
            ";
        }
        
        $this->statement  = $this->connection()->prepare($this->sql);

        $this->statement->bindValue(':transaction_code',  '%'.$search.'%');
        $this->statement->bindValue(':type',  $type);
        $this->statement ->execute();

        return $this->statement->fetchAll();
    }
}

?>