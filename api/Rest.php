<?php
class Rest{
    private $host = 'localhost';
    private $user = 'root';
    private $password = "";
    private $database = "json";
    private $carTable = 'mobil';
    private $dbConnect = false;

    // skrip fungsi-fungsi letakkan/sisipkan disini

    public function __construct(){
        if(!$this->dbConnect){
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }

    public function getMobil($carId='') {
        $sqlQuery = '';
        if($carId) {
            $sqlQuery = "WHERE id_mobil = '".$carId."'";
        }
        $carQuery = "
            SELECT id_mobil, nama_mobil, transmisi, bahan_bakar, mesin, tenaga, harga
            FROM ".$this->carTable." $sqlQuery
            ORDER BY id_mobil ASC";    
        $resultData = mysqli_query($this->dbConnect, $carQuery);
        $carData = array();
        while( $carRecord = mysqli_fetch_assoc($resultData) ) {
            $carData[] = $carRecord;
        }
        header('Content-Type: application/json');
        echo json_encode($carData);
    }

    public function insertMobil($carData){
        //id_mobil, nama_mobil, transmisi, bahan_bakar, mesin, tenaga, harga
        $carNama=$carData["nama_mobil"];
        $carTransmisi=$carData["transmisi"];
        $carBBM=$carData["bahan_bakar"];
        $carMesin=$carData["mesin"];
        $carTenaga=$carData["tenaga"];
        $carHarga=$carData["harga"];

        $carQuery="
        INSERT INTO ".$this->carTable."
        SET nama_mobil='".$carNama."', transmisi='".$carBBM."', mesin='".$carMesin."'";
        
        mysqli_query($this->dbConnect, $carQuery);
        if(mysqli_affected_rows($this->dbConnect) > 0) {
            $message = "mobil sukses dibuat.";
            $status = 1;
        } else {
            $message = "mobil gagal dibuat.";
            $status = 0;
        }
        $carResponse = array(
            'status' => $status,
            'status_message' => $message
        );
        header('Content-Type: application/json');
        echo json_encode($carResponse);
    }

    public function updateMobil($carData){
        if($carData["id"]) {
            $carKota=$carData["kota"];
            $carLandmark=$carData["landmark"];
            $carTarif=$carData["tarif"];
            
            $carQuery="
                UPDATE ".$this->carTable."
                SET kota='".$carKota."', landmark='".$carLandmark."', tarif='".$carTarif."'
                WHERE id_mobil = '".$carData["id"]."'";

            mysqli_query($this->dbConnect, $carQuery);
            if(mysqli_affected_rows($this->dbConnect) > 0) {
                $message = "mobil sukses diperbaiki.";
                $status = 1;
            } else {
                $message = "mobil gagal diperbaiki.";
                $status = 0;
            }
        } else {
            $message = "Invalid request.";
            $status = 0;
        }
        $carResponse = array(
            'status' => $status,
            'status_message' => $message
        );
        header('Content-Type: application/json');
        echo json_encode($carResponse);
    }

    public function deleteMobil($carId) {
        if($carId) {
            $carQuery = "
                DELETE FROM ".$this->carTable."
                WHERE id_mobil = '".$carId."'
                ORDER BY id_mobil DESC";
            mysqli_query($this->dbConnect, $carQuery);
            if(mysqli_affected_rows($this->dbConnect) > 0) {
                $message = "mobil sukses dihapus.";
                $status = 1;
            } else {
                $message = "mobil gagal dihapus.";
                $status = 0;
            }
        } else {
            $message = "Invalid request.";
            $status = 0;
        }
        $carResponse = array(
            'status' => $status,
            'status_message' => $message
        );
        header('Content-Type: application/json');
        echo json_encode($carResponse);
    }
}
?>    