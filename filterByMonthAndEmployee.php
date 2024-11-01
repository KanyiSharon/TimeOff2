<?php
include 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=timeoff", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "CONNECTION FAILED: " . $e->getMessage();
}
//CHECKS IF MONTH IS AVALABLE
$month= isset($_GET['month']) ? (INT)$_GETT['month'] : 0;
$search = isset($_GET['search']) ? $_GET['search'] : '';


try{
    if($month>0 && $month<12 && $search==''){
        $stmt=$conn->prepare("SELECT * FROM leaverequests where month(start_date)= :month && employee_id LIKE :search OR employee_name LIKE :search");
        $stmt->bindParam(':month',$month);
        $stmt->bindValue(':search', '%' . $search . '%');
        }else{
            $stmt=$conn->prepare("SELECT * FROM leaverequests");
        }
        $stmt->execute();
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
    
}
