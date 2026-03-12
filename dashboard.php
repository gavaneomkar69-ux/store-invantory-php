<?php
session_start();

/* DATABASE CONNECTION */
require_once 'config.php';

/* Total Products */
$total_products = pg_fetch_result(
    pg_query( $conn, 'SELECT COUNT(*) FROM products' ), 0, 0
);

/* Total Suppliers */
$total_suppliers = pg_fetch_result(
    pg_query( $conn, 'SELECT COUNT(*) FROM suppliers' ), 0, 0
);

/* Low Stock */
$low_stock = pg_fetch_result(
    pg_query( $conn, "
    SELECT COUNT(*) 
    FROM stock 
    WHERE quantity > 0 AND quantity <= reorder_level
" ), 0, 0 );

    /* Out of Stock */
    $out_stock = pg_fetch_result(
        pg_query( $conn, "
    SELECT COUNT(*) 
    FROM stock 
    WHERE quantity = 0
" ), 0, 0 );
        ?>

        <!DOCTYPE html>
        <html>
        <head>

        <title>Store Inventory Dashboard</title>

        <style>

        body {
            font-family:Consolas, monospace;
            background:black;
            color:#00ff00;
            text-align:center;
        }

        .container {
            margin-top:70px;
        }

        .stats {
            display:flex;
            justify-content:center;
            gap:30px;
            margin-bottom:40px;
        }

        .card {
            border:2px solid #00ff00;
            padding:20px;
            width:180px;
        }

        .low {
            color:orange;
        }

        .out {
            color:red;
        }

        .menu {
            margin-top:20px;
        }

        .menu a {
            display:block;
            margin:10px auto;
            width:240px;
            padding:10px;
            background:#00ff00;
            color:black;
            text-decoration:none;
            font-weight:bold;
        }

        .menu a:hover {
            background:white;
        }

        .logout {
            background:red;
            color:white;
        }

        </style>

        </head>

        <body>

        <div class = 'container'>

        <h1>Welcome, <?php echo htmlspecialchars( $_SESSION[ 'username' ] );
        ?></h1>

        <h2>Store Inventory Dashboard</h2>

        <div class = 'stats'>

        <div class = 'card'>
        Total Products
        <br><br>
        <?php echo $total_products;
        ?>
        </div>

        <div class = 'card'>
        Total Suppliers
        <br><br>
        <?php echo $total_suppliers;
        ?>
        </div>

        <div class = 'card low'>
        Low Stock
        <br><br>
        <?php echo $low_stock;
        ?>
        </div>

        <div class = 'card out'>
        Out Of Stock
        <br><br>
        <?php echo $out_stock;
        ?>
        </div>

        </div>

        <div class = 'menu'>

        <a href = 'low_stock.php'>Low Stock</a>

        <a href = 'outofstock.php'>Out of Stock</a>

        <a href = 'view_products.php'>View Products</a>

        <a href = 'view_stock.php'>View Stock</a>

        <a href = 'update_stock.php'>Update Stock</a>

        <a href = 'billing.php'>Billing System</a>

        <a href = 'logout.php' class = 'logout'>Logout</a>

        </div>

        </div>

        </body>
        </html>