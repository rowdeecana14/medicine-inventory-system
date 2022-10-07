<?php
namespace App\Model\Medicines;

use App\Helper\Helper;
use App\Model\BaseModel;

class MedicineModel extends BaseModel {
    private static $table = 'medicines';
    private static $order_by = [ "medicines.name", "asc"];
    private static $fillable = [];

    public $module = 15;
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
            name,
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

    public function count() {
        $model = new MedicineModel;
        $show_fields = [
            "(SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id ) as stockin",
            "(SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id ) as stockout",
            "(SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL) as expired"
        ];

        $medicines = $model->selects($show_fields, []);
        $count = 0;

        foreach($medicines as $index => $medicine) {
            $count = $count + ((int) $medicine['stockin'] - (int)  $medicine['stockout']) - (int) $medicine['expired'];
        }

        return $count;
    }

    // TOP 10 MEDICINES
    public function lowStocks($quantity) {
        $this->sql = "
            SELECT
                medicines.image, medicines.name, medicines.description, c.name as  category,
                (
                    COALESCE(
                        (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id),
                        0
                    )
                    - 
                    COALESCE(
                        (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id),
                        0
                    )
                    - 
                    COALESCE(
                    (
                        SELECT SUM(ss.quantity) 
                        FROM stock_expiries AS ss 
                        WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                        AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL),
                        0
                    ) 
                ) as quantity
            FROM medicines
            LEFT JOIN categories AS c ON medicines.category_id=c.id
            WHERE 
                (
                    COALESCE(
                        (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id),
                        0
                    )
                    - 
                    COALESCE(
                        (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id),
                        0
                    )
                    - 
                    COALESCE(
                    (
                        SELECT SUM(ss.quantity) 
                        FROM stock_expiries AS ss 
                        WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                        AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL),
                        0
                    ) 
                ) < :quantity
                AND medicines.deleted_by IS NULL
                AND medicines.status = 'Active'
            ORDER BY quantity asc
            LIMIT 10
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':quantity', $quantity);
        $this->statement->execute();

        return $this->statement->fetchAll();
    }

    public function expiringStocks() {
        // DATEDIFF(ss.expired_at, CURRENT_DATE) < 30
        $this->sql = "
            SELECT 
                m.image, m.name, m.description, ss.quantity, ss.expired_at,ss.medicine_id,
                DATEDIFF(ss.expired_at, CURRENT_DATE)  AS days
            FROM stock_expiries AS ss
            LEFT JOIN medicines AS m ON ss.medicine_id=m.id
            WHERE 
                DATEDIFF(ss.expired_at, CURRENT_DATE) > 0
            ORDER BY days ASC
            LIMIT 10
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->execute();

        return $this->statement->fetchAll();
    }

    public function stockByCategories() {
        $this->sql = "
            SELECT 
                c.name as category, m.name,
                (
                    COALESCE(
                        (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id),
                        0
                    )
                    - 
                    COALESCE(
                        (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id),
                        0
                    )
                    - 
                    COALESCE(
                    (
                        SELECT SUM(ss.quantity) 
                        FROM stock_expiries AS ss 
                        WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                        AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                        0
                    ) 
                ) as quantity
            FROM categories AS c
            LEFT JOIN medicines AS m ON c.id=m.category_id
            GROUP BY c.id
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->execute();

        return $this->statement->fetchAll();
    }

    public function expiredByCategories() {
        $this->sql = "
            SELECT 
            c.name as category, m.name,
            (
                COALESCE(
                (
                    SELECT SUM(ss.quantity) 
                    FROM stock_expiries AS ss 
                    WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                    AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                    0
                ) 
            ) as quantity
            FROM categories AS c
            LEFT JOIN medicines AS m ON c.id=m.category_id
            GROUP BY c.id
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->execute();

        return $this->statement->fetchAll();
    }

    public function monthlyReceived($month) {
        $this->sql = "
            SELECT  
                COALESCE(SUM(sim.quantity), 0) as  total 
            FROM stockin_medicines AS sim
            LEFT JOIN stockin_transactions AS sit ON sim.stockin_transaction_id=sit.id
            WHERE 
                YEAR(sit.received_at) = YEAR(CURDATE())
                AND MONTH(sit.received_at) =:month
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':month', $month);
        $this->statement->execute();

        return $this->statement ->fetchColumn();
    }

    public function monthlyDespensed($month) {
        $this->sql = "
            SELECT
                COALESCE(SUM(som.quantity), 0) as  total 
            FROM stockout_medicines AS som
            LEFT JOIN stockout_transactions AS sot ON som.stockout_transaction_id=sot.id
            WHERE 
                YEAR(sot.dispenced_at) = YEAR(CURDATE())
                AND MONTH(sot.dispenced_at) =:month
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':month', $month);
        $this->statement->execute();

        return $this->statement ->fetchColumn();
    }

    public function monthlyExpired($month) {
        $this->sql = "
            SELECT
                COALESCE(SUM(ss.quantity), 0) as  total 
            FROM stock_expiries AS ss
            WHERE 
                ss.deleted_at IS NULL
                AND YEAR(ss.expired_at) = YEAR(CURDATE())
                AND MONTH(ss.expired_at) =:month
        ";
        $this->statement  = $this->connection()->prepare($this->sql);
        $this->statement->bindValue(':month', $month);
        $this->statement->execute();

        return $this->statement ->fetchColumn();
    }

    public function filterStockLevel($request, $levels) {
        $level = 0;
        $level2 = 0;
        $status = "";

        if((int) $request->level == $levels['low_level'] ) {
            $level = $levels['low_level'];
            $status = "low_level";
        }
        else if((int) $request->level == $levels['moderate_level'] ) {
            $level = $levels['moderate_level'];
            $status = "moderate_level";
            $level2 =  $levels['low_level'];
        }
        else if((int) $request->level == $levels['high_level'] ) {
            $level = $levels['high_level'];
            $status = "high_level";
            $level2 = $levels['moderate_level'];
        }
        else if((int) $request->level > $levels['high_level'] ) {
            $level = $levels['high_level'];
            $status = "excelent_level";
        }
        
        $this->sql = "
            SELECT 
                m.name, m.description, c.name as category, t.name as type, 
                (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id ) as stockin,
                (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id ) as stockout,
                (SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=m.id AND ss.deleted_at IS NULL) as expired,
                (
                    COALESCE(
                        (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id),
                        0
                    )
                    - 
                    COALESCE(
                        (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id),
                        0
                    )
                    - 
                    COALESCE(
                    (
                        SELECT SUM(ss.quantity) 
                        FROM stock_expiries AS ss 
                        WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                        AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                        0
                    ) 
                ) as available
            FROM medicines AS m
            LEFT JOIN categories AS c ON c.id=m.category_id
            LEFT JOIN types AS t ON t.id=m.type_id
            WHERE 
            m.deleted_by IS NULL
            AND m.status = 'Active'
        ";
        
        if($request->category_id != 'All' && $request->category_id != null) {
            $this->sql = $this->sql . " AND m.category_id =:category_id";
        }
        if($request->type_id != 'All' && $request->type_id != null) {
            $this->sql = $this->sql . " AND m.type_id =:type_id";
        }
        if($request->level != 'All' && $request->level != null) {

            if($status == "low_level") {
                $this->sql = $this->sql . "
                    AND (
                        COALESCE(
                            (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                            (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                        (
                            SELECT SUM(ss.quantity) 
                            FROM stock_expiries AS ss 
                            WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                            AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                            0
                        ) 
                    ) <= :level
                ";
            }
            else if($status == "moderate_level") {
                $this->sql = $this->sql . "
                    AND (
                        COALESCE(
                            (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                            (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                        (
                            SELECT SUM(ss.quantity) 
                            FROM stock_expiries AS ss 
                            WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                            AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                            0
                        ) 
                    ) <= :level
                    AND (
                        COALESCE(
                            (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                            (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                        (
                            SELECT SUM(ss.quantity) 
                            FROM stock_expiries AS ss 
                            WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                            AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                            0
                        ) 
                    ) > :level2
                ";
            }
            else if($status == "high_level") {
                $this->sql = $this->sql . "
                    AND (
                        COALESCE(
                            (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                            (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                        (
                            SELECT SUM(ss.quantity) 
                            FROM stock_expiries AS ss 
                            WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                            AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                            0
                        ) 
                    ) <= :level
                    AND (
                        COALESCE(
                            (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                            (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                        (
                            SELECT SUM(ss.quantity) 
                            FROM stock_expiries AS ss 
                            WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                            AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                            0
                        ) 
                    ) > :level2
                ";
            }
            else if($status == "excelent_level") {
                $this->sql = $this->sql . "
                    AND (
                        COALESCE(
                            (SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                            (SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=m.id),
                            0
                        )
                        - 
                        COALESCE(
                        (
                            SELECT SUM(ss.quantity) 
                            FROM stock_expiries AS ss 
                            WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 
                            AND ss.medicine_id=m.id AND ss.deleted_at IS NULL),
                            0
                        ) 
                    ) > :level
                ";
            }
        }
        
        $this->sql = $this->sql . " ORDER BY m.name ASC";
        $this->statement  = $this->connection()->prepare($this->sql);

        if($request->category_id != 'All' && $request->category_id != null) {
            $this->statement->bindValue(':category_id', $request->category_id);
        }
        if($request->type_id != 'All' && $request->type_id != null) {
            $this->statement->bindValue(':type_id', $request->type_id);
        }
        if($request->level != '' && $request->level != null) {

            if($status == "low_level") {
                $this->statement->bindValue(':level',  (int) $level);
            }
            else if($status == "moderate_level") {
                $this->statement->bindValue(':level',  (int) $level);
                $this->statement->bindValue(':level2',  (int) $level2);
            }
            else if($status == "high_level") {
                $this->statement->bindValue(':level',  (int) $level);
                $this->statement->bindValue(':level2',  (int) $level2);
            }
            else if($status == "excelent_level") {
                $this->statement->bindValue(':level',  (int) $level);
            }
        }
        $this->statement->execute();

        return $this->statement->fetchAll();
    }
}

?>