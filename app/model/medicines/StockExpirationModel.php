<?php
namespace App\Model\Medicines;

use App\Helper\Helper;
use App\Model\BaseModel;

class StockExpirationModel extends BaseModel {
    private static $table = 'stock_expiries';
    private static $order_by = [ "stock_expiries.expired_at", "asc"];
    private static $fillable = [];

    public $module = 18;
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

    public function getAvailableStock($id) {
        $this->sql = "
            SELECT 
            (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id ) as stockin,
            (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id ) as stockout,
            (SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL) as expired
            FROM medicines 
            WHERE medicines.id =:id
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':id', $id);
        $this->statement->execute();

        return $this->statement->fetch();
    }

    public function getCountExpiredDate($expired_at, $medicine_id) {
        $this->sql = "
                SELECT count(id) as total
                FROM stock_expiries AS ss
                WHERE ss.expired_at =:expired_at AND ss.medicine_id =:medicine_id AND ss.deleted_at IS NULL
            ";
            $this->statement  = $this->connection()->prepare($this->sql);
            $this->statement->bindValue(':expired_at', $expired_at);
            $this->statement->bindValue(':medicine_id', $medicine_id);

            $this->statement->execute();

            return $this->statement->fetch();

        // if($medicine_id == '') {
        //     $this->sql = "
        //         SELECT count(id) as total
        //         FROM stock_expiries AS ss
        //         WHERE ss.expired_at =:expired_at AND ss.deleted_at IS NULL
        //     ";
        //     $this->statement  = $this->connection()->prepare($this->sql);
        //     $this->statement->bindValue(':expired_at', $medicine_id);
        //     $this->statement->execute();

        //     return $this->statement->fetch();
        // }
        // else {
        //     $this->sql = "
        //         SELECT count(id) as total
        //         FROM stock_expiries AS ss
        //         WHERE ss.expired_at =:expired_at AND ss.medicine_id =:id AND ss.deleted_at IS NULL
        //     ";
        //     $this->statement  = $this->connection()->prepare($this->sql);
        //     $this->statement->bindValue(':expired_at', $expired_at);
        //     $this->statement->bindValue(':id', $medicine_id);

        //     $this->statement->execute();

        //     return $this->statement->fetch();
        // }
    }

    public function getCountExpiredDateUpdate($expired_at, $medicine_id, $id) {
        $this->sql = "
            SELECT count(id) as total
            FROM stock_expiries AS ss
            WHERE ss.expired_at =:expired_at AND ss.medicine_id =:medicine_id AND ss.id !=:id AND ss.deleted_at IS NULL
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':expired_at', $expired_at);
        $this->statement->bindValue(':medicine_id', $medicine_id);
        $this->statement->bindValue(':id', $id);

        $this->statement->execute();

        return $this->statement->fetch();
    }

    public function getOneExpiration($id) {
        $this->sql = "
            SELECT ss.quantity
            FROM stock_expiries AS ss
            WHERE ss.id =:id AND ss.deleted_at IS NULL
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':id', (int) $id);
        $this->statement->execute();

        return $this->statement->fetch();
    }

    public function filterExpiration($request) {
        $this->sql = "
            SELECT 
                m.name, m.description, c.name as category, t.name as type, ss.quantity,
                ss.expired_at, DATEDIFF(ss.expired_at, CURRENT_DATE) as days
            FROM stock_expiries AS ss
            LEFT JOIN medicines AS m ON m.id=ss.medicine_id
            LEFT JOIN categories AS c ON c.id=m.category_id
            LEFT JOIN types AS t ON t.id=m.type_id
            WHERE ss.deleted_at IS NULL
        ";

        if($request->medicine_id != 'All' && $request->medicine_id != null) {
            $this->sql = $this->sql . " AND m.id =:medicine_id";
        }
        if($request->category_id != 'All' && $request->category_id != null) {
            $this->sql = $this->sql . " AND m.category_id =:category_id";
        }
        if($request->type_id != 'All' && $request->type_id != null) {
            $this->sql = $this->sql . " AND m.type_id =:type_id";
        }
        if($request->expired_at != '' && $request->expired_at != null) {
            $this->sql = $this->sql . " AND ss.expired_at =:expired_at";
        }
        if($request->status != 'All') {
            $this->sql  = $request->status == "EXPIRED" 
                ? $this->sql . " AND DATEDIFF(ss.expired_at, CURRENT_DATE) < 1"
                : $this->sql . " AND DATEDIFF(ss.expired_at, CURRENT_DATE) > 0";
        }
        
        $this->statement  = $this->connection()->prepare($this->sql);

        if($request->medicine_id != 'All' && $request->medicine_id != null) {
            $this->statement->bindValue(':medicine_id', $request->medicine_id);
        }
        if($request->category_id != 'All' && $request->category_id != null) {
            $this->statement->bindValue(':category_id', $request->category_id);
        }
        if($request->type_id != 'All' && $request->type_id != null) {
            $this->statement->bindValue(':type_id', $request->type_id);
        }
        if($request->expired_at != '' && $request->expired_at != null) {
            $this->statement->bindValue(':expired_at',  Helper::dateParser($request->expired_at));
        }
        $this->statement->execute();

        return $this->statement->fetchAll();
    }
}

?>