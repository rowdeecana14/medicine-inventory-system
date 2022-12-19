<?php
namespace App\Model;
use App\Model\BaseModel;

class HealthOfficialModel extends BaseModel {
    private static $table = 'health_officials';
    private static $order_by = [ "health_officials.first_name", "asc"];
    private static $fillable = [];

    public $admin_id = 1;
    public $module = 14;
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

    public function transactions($id) {
        $this->sql = "
            SELECT stock_transactions.transaction_no, stock_transactions.type, stock_transactions.stock_transaction_id, sit.remarks as sit_remarks,
            sit.received_at as sit_received_at, sot.remarks as sot_remarks,  sot.dispenced_at as sot_dispenced_at,  sit.delivery_person as sit_delivery,
            CONCAT(p.first_name,' ', SUBSTRING(p.middle_name, 1,1), '. ', p.last_name)as sot_patient,
            (SELECT COUNT(sim.id)FROM stockin_medicines AS sim WHERE sim.stockin_transaction_id=sit.id) as stockin,
            (SELECT COUNT(som.id)FROM stockout_medicines AS som WHERE som.stockout_transaction_id=sot.id) as stockout
            FROM stock_transactions 
            LEFT JOIN stockin_transactions AS sit ON sit.id=stock_transactions.stock_transaction_id
            LEFT JOIN stockout_transactions AS sot ON sot.id=stock_transactions.stock_transaction_id 
            LEFT JOIN patients as p ON p.id=sot.patient_id 
            WHERE stock_transactions.deleted_at IS NULL 
            AND (sit.health_official_id= :sit_ho_id OR sot.health_official_id = :sot_ho_id)
            ORDER BY stock_transactions.transaction_no desc
            ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':sit_ho_id', $id);
        $this->statement->bindValue(':sot_ho_id', $id);
        $this->statement->execute();

        return $this->statement ->fetchAll();
    }

    public function isUniqueName($fullname = "", $id="") {
        if($id != '') {
            $sql = "SELECT *  FROM ".self::$table." 
            WHERE TRIM(CONCAT(LOWER(first_name),' ', LOWER(middle_name), ' ', LOWER(last_name)))=:fullname 
            AND id !=:id AND deleted_by IS NULL
            ";
            $statement = self::connection()->prepare($sql);
            $statement->bindValue(':fullname', $fullname);
            $statement->bindValue(':id', $id);
        }
        else {
            $sql = "SELECT *  FROM ".self::$table." WHERE TRIM(CONCAT(LOWER(first_name),' ', LOWER(middle_name), ' ', LOWER(last_name)))=:fullname  AND deleted_by IS NULL";
            $statement = self::connection()->prepare($sql);
            $statement->bindValue(':fullname', $fullname);
        }

        $statement->execute();

        return ($statement->rowCount() == 0) ? true : false;
    }
}

?>