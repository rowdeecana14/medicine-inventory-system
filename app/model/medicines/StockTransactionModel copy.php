<?php
namespace App\Model\Medicines;

use App\Model\BaseModel;

class TransactionModel extends BaseModel {
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

    public function getTransactionNos($search) {
        $this->sql = "
            SELECT  
                st.transaction_no as id,
                st.transaction_no as text
            FROM stock_transactions AS st
            WHERE sit.transaction_no=:transaction_no
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':transaction_no', $search);
        $this->statement ->execute();

        return $this->statement->fetchAll();
    }
}

?>