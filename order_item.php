<?php
session_start();
include "db.php";

if(isset($_SESSION['user_id'])){ 
    if($_SESSION['user_type'] == "user"){
        if(isset($_SESSION['user_id']) && isset($_GET['menu_id'])){ 
            $user_id = $_SESSION['user_id']; 
            $menu_id = $_GET['menu_id']; 
            $status = "pending";

            $sql = "insert into orders(customer_id, item_id, status)
                    values('$user_id', '$menu_id', '$status')"; 
            $result = mysqli_query($conn, $sql);
            
            if(!$result){
                echo "Error!: " . $conn->error;
            }
            else{
                $message = "Order added successfully";
                header("Location: index.php?added_message=" . urlencode($message));
                exit();
            }
        }
    }
    if($_SESSION['user_type'] == "admin"){
        header("Location: admin/admin_dashboard.php");
        exit();
    }
}
else{
    header("Location: login.php");
    exit();
}
?>