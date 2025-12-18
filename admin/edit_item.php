<?php
session_start();
include "../db.php";

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_type'] == "admin"){
        // ดึงข้อมูลรายการที่ต้องการแก้ไข
        if(isset($_GET['edit_id'])){
            $edit_id = $_GET['edit_id'];
            $sql = "SELECT * FROM menu_items WHERE id='$edit_id'";
            $result = mysqli_query($conn, $sql);
            $item = mysqli_fetch_assoc($result);
        }

        // อัปเดตข้อมูลเมื่อกดปุ่ม Update
        if(isset($_POST['update'])){
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $old_image = $_POST['old_image'];

            // ตรวจสอบว่ามีการอัปโหลดรูปใหม่หรือไม่
            if($_FILES['image']['name'] != ""){
                $image = $_FILES['image']['name'];
                $temp_location = $_FILES['image']['tmp_name'];
                $tar_location = "../image/";
                
                // ลบรูปเก่า
                if(file_exists("../image/".$old_image)){
                    unlink("../image/".$old_image);
                }
                
                // อัปโหลดรูปใหม่
                move_uploaded_file($temp_location, $tar_location.$image);
            } else {
                $image = $old_image;
            }

            // อัปเดตข้อมูลในฐานข้อมูล
            $update_sql = "UPDATE menu_items SET 
                          image='$image', 
                          name='$name', 
                          price='$price', 
                          category='$category' 
                          WHERE id='$id'";
            
            if(mysqli_query($conn, $update_sql)){
                header("Location: view_items.php?message=updated");
                exit();
            } else {
                $error_message = "Error updating item: " . $conn->error;
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
    <title>Edit Menu Item</title>
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

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border: 1px solid #f5c6cb;
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

        .current-image {
            margin-top: 10px;
        }

        .current-image img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #ddd;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .update-btn, .cancel-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .update-btn {
            background-color: #f39c12;
            color: white;
        }

        .update-btn:hover {
            background-color: #e67e22;
        }

        .cancel-btn {
            background-color: #95a5a6;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cancel-btn:hover {
            background-color: #7f8c8d;
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
        <h2>Edit Menu Item</h2>
        
        <?php if(isset($error_message)){ ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <div class="form-container">
            <form action="edit_item.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="old_image" value="<?php echo $item['image']; ?>">
                
                <div class="form-group">
                    <label for="image">Item Image (Leave empty to keep current image)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="../image/<?php echo $item['image']; ?>" alt="Current Image">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="name">Item Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Item Price (฿)</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo $item['price']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Item Category</label>
                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($item['category']); ?>" required>
                </div>
                
                <div class="button-group">
                    <button type="submit" name="update" class="update-btn">Update Item</button>
                    <a href="view_items.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>