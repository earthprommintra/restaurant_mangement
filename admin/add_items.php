<?php
session_start();
include "../db.php";
$success_message = "";

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_type'] == "admin"){
        if(isset($_POST['submit'])){
            $image = $_FILES['image']['name'];
            $temp_location = $_FILES['image']['tmp_name'];
            $tar_location = "../image/";
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            $sql = "insert into menu_items(image,name, price,category)
            values('$image','$name','$price','$category')";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "Error!:{$conn->error}";
            }
            else{
                move_uploaded_file($temp_location, $tar_location.$image);
                $success_message = "Item added successfully!";
            }
        }
    }
    if($_SESSION['user_type'] == "user"){
        header("Location: ../user_dashboard.php");
        exit();
    }
}
else{
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Items</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
        background-color: #f4f4f9;
    }

    .header {
        padding: 15px 30px;
        background-color: #ffffff;
        color: #333;
        margin-left: 250px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 60px;
    }

    .header a {
        text-decoration: none;
        color: white;
        padding: 8px 15px;
        background: #e74c3c;
        border-radius: 4px;
        transition: background 0.3s;
    }

    .header a:hover {
        background: #c0392b;
    }

    .sidebar {
        background-color: #2c3e50;
        color: #ecf0f1;
        position: fixed;
        top: 0;
        height: 100%;
        width: 250px;
        padding-top: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar a {
        text-decoration: none;
        display: block;
        padding: 15px 20px;
        margin: 5px 0;
        color: #ecf0f1;
        transition: background-color 0.3s, color 0.3s;
        border-left: 5px solid transparent;
    }

    .sidebar a:hover {
        background-color: #34495e;
        color: #f39c12;
        border-left: 5px solid #f39c12;
    }

    .main {
        margin-left: 250px;
        padding: 40px;
    }

    .main h2 {
        color: #2c3e50;
        margin-bottom: 30px;
        font-size: 2em;
    }

    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 25px;
        border: 1px solid #c3e6cb;
    }

    .form-container {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        max-width: 600px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #f39c12;
    }

    .submit-btn {
        width: 100%;
        padding: 14px;
        background-color: #f39c12;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1.1em;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #e67e22;
    }
</style>
</head>
<body>
    <div class="header">
        <a href="../logout.php">Log Out</a>
    </div>
    <div class="sidebar">
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="add_items.php">Add Menu Items</a>
    <a href="view_items.php">View Menu Items</a>
    <a href="view_order_items.php">View Orders</a>
</div>

<div class="main">
    <h2>Add New Menu Item</h2>
    
    <?php if(!empty($success_message)){ ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php } ?>

    <div class="form-container">
        <form action="add_items.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Item Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            
            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="price">Item Price (฿)</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="category">Item Category</label>
                <input type="text" id="category" name="category" required>
            </div>
            
            <button type="submit" name="submit" class="submit-btn">Add Item</button>
        </form>
    </div>
</div>
</body>
</html>