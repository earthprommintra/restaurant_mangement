<?php
session_start(); // เพิ่ม session_start() ที่หายไป
include "db.php";

$sql = "select * from menu_items";
$result = mysqli_query($conn,$sql);
if(!$result){
    echo "Error: {$conn->error}";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
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

        /* Navbar */
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

        /* Header */
        .header {
            background-color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header hr {
            border: none;
            height: 3px;
            background: linear-gradient(to right, #f39c12, #e74c3c);
            width: 200px;
            margin: 20px auto;
        }

        /* Success Message */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #c3e6cb;
        }

        /* Product Cards Section */
        .product_card {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 30px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card h3 {
            color: #2c3e50;
            padding: 15px 15px 10px;
            font-size: 1.3em;
        }

        .card p {
            color: #e74c3c;
            font-size: 1.4em;
            font-weight: bold;
            padding: 0 15px 15px;
        }

        .card a {
            display: block;
            text-align: center;
            background-color: #f39c12;
            color: white;
            padding: 12px;
            text-decoration: none;
            margin: 10px 15px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-weight: 600;
        }

        .card a:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if(!isset($_SESSION['user_id'])){ ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Registration</a></li>
            <?php } ?>
            <?php if(isset($_SESSION['user_id'])){ ?>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php } ?>
        </ul>
    </nav>
    
    <header class="header">
        <h1>Welcome to Our Restaurant</h1>
        <hr>
    </header>

    <?php if(isset($_GET['added_message'])){ ?>
        <div class="success-message">
            <h2><?php echo htmlspecialchars($_GET['added_message']); ?></h2>
        </div>
    <?php } ?>

    <section class="product_card">
        <?php while($row = mysqli_fetch_assoc($result)){ ?>
        <div class="card">
            <img src="image/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p>฿<?php echo number_format($row['price'], 2); ?></p>
            <?php if(isset($_SESSION['user_id'])){ ?>
                <a href="order_item.php?user_id=<?php echo $_SESSION['user_id']; ?>&menu_id=<?php echo $row['id']; ?>">Order Now</a>
            <?php } else { ?>
                <a href="login.php">Login to Order</a>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
</body>
</html>
