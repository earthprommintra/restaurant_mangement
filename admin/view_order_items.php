<?php
session_start();
include "../db.php"; 

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_type'] == "admin"){
        
        $sql = "SELECT users.id AS user_id,
                users.name AS customer_name,
                users.email,
                users.address,
                users.phone,
                menu_items.id AS item_id,
                menu_items.image,
                menu_items.name AS product_name,
                menu_items.price,
                menu_items.category,
                orders.id AS order_id,
                orders.status FROM orders
                JOIN users ON orders.customer_id = users.id
                JOIN menu_items ON orders.item_id = menu_items.id
                ORDER BY orders.id DESC";
        
        $result = mysqli_query($conn, $sql);
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
    <title>View Orders</title>
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
        vertical-align: middle;
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    td img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
    }

    .status-pending {
        display: inline-block;
        padding: 5px 12px;
        background-color: #ffc107;
        color: #000;
        border-radius: 15px;
        font-size: 0.9em;
        font-weight: 600;
    }

    .status-delivered {
        display: inline-block;
        padding: 5px 12px;
        background-color: #28a745;
        color: white;
        border-radius: 15px;
        font-size: 0.9em;
        font-weight: 600;
    }

    select {
        padding: 8px 12px;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
        cursor: pointer;
        transition: border-color 0.3s;
    }

    select:focus {
        outline: none;
        border-color: #f39c12;
    }

    .update-btn {
        padding: 8px 16px;
        background-color: #f39c12;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s;
        margin-left: 8px;
    }

    .update-btn:hover {
        background-color: #e67e22;
    }

    .action-form {
        display: flex;
        align-items: center;
        gap: 8px;
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
    <h2>Customer Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Item</th>
                <th>Image</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><img src="../image/<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>"></td>
                <td>
                    <span class="status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </span>
                </td>
                <td>
                    <form action="update_item_status.php" method="post" class="action-form">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <select name="status">
                            <option value="pending" <?php if($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="delivered" <?php if($row['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                        </select>
                        <button type="submit" name="submit" class="update-btn">Update</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
