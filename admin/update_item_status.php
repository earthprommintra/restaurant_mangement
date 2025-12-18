<?php
session_start();
include "../db.php";
if(isset($_SESSION['user_id'])){ 
    if($_SESSION['user_type'] == "admin"){
        if(isset($_POST['submit'])){
            $order_id = $_POST['order_id'];
            $status = $_POST['status'];
            $sql = "update orders set status ='$status' where id='$order_id'";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error!: {$conn->error}";
            }
            else{
                header("Location: view_order_items.php");
                exit();
            }
        }
    }
}
?></parameter>