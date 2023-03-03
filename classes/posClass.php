<?php 
    require_once "database.php";

    class POS{
        private $conn;

        public function __construct(){
            $database = new Database();
            $db = $database->dbConnection();
            $this->conn = $db;
        }

        //Execute Queries SQL
        public function runQuery($sql){
            $stmt =  $this->conn->prepare($sql);
            return $stmt;
        }
        //INSERT POS RECORD
        public function insertPos($productId,$unitPrice,$qnty,$total){
            try {
                $stmt = $this->conn->prepare("INSERT INTO pos(invt_id, unit_price, qnty, total, date_process, status) VALUES(:productId, :unitPrice, :qnty, :total, NOW(), 'processing')");
                $stmt->bindparam(":productId", $productId);
                $stmt->bindparam(":unitPrice", $unitPrice);
                $stmt->bindparam(":qnty", $qnty);
                $stmt->bindparam(":total", $total);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        //INSERT POS RECORD
        
        public function updateProcessedItems(){
            try {
                $stmt = $this->conn->prepare("UPDATE pos set status='completed' WHERE status='processing'");
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

         //DELETE POS RECORD
         public function deletePOS($pos_id){
            try{
                $stmt = $this->conn->prepare("DELETE FROM pos WHERE pos_id=:pos_id");
                $stmt->bindParam(":pos_id", $pos_id);
                $stmt->execute();
                return $stmt;
            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }
        //DELETE POS RECORD
        
        
        
        public function redirect($url){
            header("location:$url");
        }
    }
?>