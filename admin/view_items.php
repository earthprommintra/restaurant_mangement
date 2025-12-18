<?php
session_start();
include "../db.php";

// *** เพิ่มส่วนนี้: การลบรายการ ***
if(isset($_GET['delete_id'])){
    $delete_id = $_GET['delete_id'];
    
    // ดึงชื่อรูปภาพก่อนลบเพื่อลบไฟล์
    $get_image = mysqli_query($conn, "SELECT image FROM menu_items WHERE id='$delete_id'");
    $image_row = mysqli_fetch_assoc($get_image);
    
    // ลบข้อมูลจากฐานข้อมูล
    $delete_sql = "DELETE FROM menu_items WHERE id='$delete_id'";
    if(mysqli_query($conn, $delete_sql)){
        // ลบไฟล์รูปภาพ
        if(file_exists("../image/".$image_row['image'])){
            unlink("../image/".$image_row['image']);
        }
        header("Location: view_items.php?message=deleted");
        exit();
    }
}

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_type'] == "admin"){
        $sql = "select * from menu_items";
        $result = mysqli_query($conn,$sql);
        if(!$result){
            echo "Error!: {$conn->error}";
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
    <title>View Menu Items</title>
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

        /* *** เพิ่มส่วนนี้: Success Message *** */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }

        table {
            width: 100%;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-collapse: collapse;
        }

        thead {
            background-color: #2c3e50;
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        td img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* *** เพิ่มส่วนนี้: Styling สำหรับปุ่ม Edit & Delete *** */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .edit-btn, .delete-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .edit-btn {
            background-color: #3498db;
            color: white;
        }

        .edit-btn:hover {
            background-color: #2980b9;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
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
        <h2>All Menu Items</h2>
        
        <!-- *** เพิ่มส่วนนี้: แสดง Success Message *** -->
        <?php if(isset($_GET['message']) && $_GET['message'] == 'deleted'){ ?>
            <div class="success-message">Item deleted successfully!</div>
        <?php } ?>
        <?php if(isset($_GET['message']) && $_GET['message'] == 'updated'){ ?>
            <div class="success-message">Item updated successfully!</div>
        <?php } ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th> <!-- *** เพิ่มคอลัมน์นี้ *** -->
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><img src="../image/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>฿<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <!-- *** เพิ่มส่วนนี้: ปุ่ม Edit และ Delete *** -->
                    <td>
                        <div class="action-buttons">
                            <a href="edit_item.php?edit_id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                            <a href="view_items.php?delete_id=<?php echo $row['id']; ?>" 
                               class="delete-btn" 
                               onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>