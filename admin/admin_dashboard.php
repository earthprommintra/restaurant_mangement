<?php
session_start();
include "../db.php";

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_type'] == "admin"){
        // ดึงสถิติต่างๆ สำหรับแสดงใน Dashboard
        $total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders"))['count'];
        $pending_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='pending'"))['count'];
        $delivered_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='delivered'"))['count'];
        $total_items = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM menu_items"))['count'];
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
    <title>Admin Dashboard</title>
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

        /* Header */
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

        /* Sidebar */
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

        /* Main Content */
        .main {
            margin-left: 250px;
            padding: 40px;
        }

        .main h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        /* Statistics Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #666;
            font-size: 0.9em;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            color: #2c3e50;
        }

        .stat-card.orders {
            border-left: 5px solid #3498db;
        }

        .stat-card.pending {
            border-left: 5px solid #f39c12;
        }

        .stat-card.delivered {
            border-left: 5px solid #27ae60;
        }

        .stat-card.items {
            border-left: 5px solid #9b59b6;
        }

        /* Quick Actions */
        .quick-actions {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .quick-actions h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            display: block;
            padding: 15px 20px;
            background-color: #f39c12;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-weight: 600;
        }

        .action-btn:hover {
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
        <h1>Admin Dashboard</h1>
        
        <div class="stats-container">
            <div class="stat-card orders">
                <h3>Total Orders</h3>
                <div class="number"><?php echo $total_orders; ?></div>
            </div>
            
            <div class="stat-card pending">
                <h3>Pending Orders</h3>
                <div class="number"><?php echo $pending_orders; ?></div>
            </div>
            
            <div class="stat-card delivered">
                <h3>Delivered Orders</h3>
                <div class="number"><?php echo $delivered_orders; ?></div>
            </div>
            
            <div class="stat-card items">
                <h3>Menu Items</h3>
                <div class="number"><?php echo $total_items; ?></div>
            </div>
        </div>

        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="add_items.php" class="action-btn">Add New Item</a>
                <a href="view_items.php" class="action-btn">Manage Items</a>
                <a href="view_order_items.php" class="action-btn">Manage Orders</a>
            </div>
        </div>
    </div>
</body>
</html>