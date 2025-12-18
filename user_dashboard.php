<?php
session_start();
include "db.php";

if(isset($_SESSION['user_id'])){
    if($_SESSION['user_type'] == "admin"){
        header("Location: admin/admin_dashboard.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT orders.id AS order_id,
            orders.status,
            menu_items.name AS product_name,
            menu_items.image,
            menu_items.price
            FROM orders
            JOIN menu_items ON orders.item_id = menu_items.id
            WHERE orders.customer_id = '$user_id'
            ORDER BY orders.id DESC";
    $result = mysqli_query($conn, $sql);
}
else{
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f4f9;
        }

        .navbar {
            background-color: #2c3e50;
            padding: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar ul {
            list-style: none;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            margin: 0;
        }

        .navbar a {
            display: block;
            padding: 18px 25px;
            color: #ecf0f1;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar a:hover {
            background-color: #34495e;
            color: #f39c12;
        }

        .navbar .logout {
            margin-left: auto;
            background-color: #e74c3c;
        }

        .navbar .logout:hover {
            background-color: #c0392b;
        }

        .main-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-section {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .welcome-section h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: #666;
            font-size: 1.1em;
        }

        .orders-section {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .orders-section h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 1.8em;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table thead {
            background-color: #2c3e50;
            color: white;
        }

        .orders-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .orders-table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        .orders-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }

        .orders-table img {
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

        .no-orders {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .no-orders a {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 24px;
            background-color: #f39c12;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .no-orders a:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="user_dashboard.php">My Orders</a></li>
            <li><a href="logout.php" class="logout">Log Out</a></li>
        </ul>
    </nav>

    <div class="main-content">
        <div class="welcome-section">
            <h1>Welcome to Your Dashboard</h1>
            <p>Track your orders and manage your account</p>
        </div>

        <div class="orders-section">
            <h2>My Orders</h2>
            <?php if($result && mysqli_num_rows($result) > 0){ ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr>
                            <td>#<?php echo $row['order_id']; ?></td>
                            <td><img src="image/<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>"></td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td>฿<?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <span class="status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="no-orders">
                    <p>You haven't placed any orders yet.</p>
                    <a href="index.php">Browse Menu</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
