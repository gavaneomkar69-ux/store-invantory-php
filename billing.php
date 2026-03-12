<?php
session_start();

require_once 'config.php';

/* CREATE CART */
if ( !isset( $_SESSION[ 'cart' ] ) ) {
    $_SESSION[ 'cart' ] = [];
}

/* DELETE ITEM */
if ( isset( $_GET[ 'delete' ] ) ) {

    $index = $_GET[ 'delete' ];

    unset( $_SESSION[ 'cart' ][ $index ] );

    $_SESSION[ 'cart' ] = array_values( $_SESSION[ 'cart' ] );

}

/* ADD ITEM */

if ( isset( $_POST[ 'add' ] ) ) {

    $product_id = $_POST[ 'product' ];
    $qty = $_POST[ 'qty' ];

    $query = "SELECT p.product_name,p.mrp,p.gst_rate,s.quantity AS stock
FROM products p
JOIN stock s ON p.product_id=s.product_id
WHERE p.product_id=$1";

    $result = pg_query_params( $conn, $query, array( $product_id ) );

    $row = pg_fetch_assoc( $result );

    $stock = $row[ 'stock' ];

    if ( $qty > $stock ) {

        $error = 'Not enough stock! Available: '.$stock;

    } else {

        $price = $row[ 'mrp' ];
        $gst = $row[ 'gst_rate' ];

        $subtotal = $price * $qty;
        $gst_amount = $subtotal * $gst / 100;
        $total = $subtotal + $gst_amount;

        $_SESSION[ 'cart' ][] = [
            'product_id'=>$product_id,
            'name'=>$row[ 'product_name' ],
            'qty'=>$qty,
            'price'=>$price,
            'total'=>$total
        ];

    }

}

/* GENERATE BILL */

if ( isset( $_POST[ 'generate' ] ) ) {

    foreach ( $_SESSION[ 'cart' ] as $item ) {

        $pid = $item[ 'product_id' ];
        $qty = $item[ 'qty' ];

        $update = "UPDATE stock
SET quantity = quantity - $1
WHERE product_id = $2
AND quantity >= $1";

        pg_query_params( $conn, $update, array( $qty, $pid ) );

    }

    $_SESSION[ 'cart' ] = [];

    $message = 'Bill Generated Successfully!';

}

/* LOAD PRODUCTS */

$products = pg_query( $conn, 'SELECT product_id,product_name FROM products ORDER BY product_name' );

?>

<!DOCTYPE html>
<html>

<head>

<title>Billing System</title>

<style>

body {
    background:black;
    color:#00ff00;
    font-family:Consolas;
}

.container {
    width:900px;
    margin:auto;
}

h1 {
    text-align:center;
}

select, input, button {
    background:black;
    color:#00ff00;
    border:1px solid #00ff00;
    padding:8px;
    margin:5px;
}

table {
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th, td {
    border:1px solid #00ff00;
    padding:10px;
    text-align:center;
}

button:hover {
    background:#00ff00;
    color:black;
}

.delete {
    border:1px solid red;
    color:red;
    background:black;
    padding:5px;
}

.total {
    font-size:20px;
    text-align:right;
    margin-top:10px;
}

.error {
    color:red;
}

.success {
    color:#00ff00;
}

/* BACK BUTTON */

.back-btn {
    position:absolute;
    top:20px;
    right:20px;
}

.back-btn button {
    background:black;
    color:#00ff00;
    border:1px solid #00ff00;
    padding:8px 16px;
    font-family:Consolas;
    cursor:pointer;
}

.back-btn button:hover {
    background:#00ff00;
    color:black;
}

</style>

</head>

<body>

<div class = 'back-btn'>
<a href = 'dashboard.php'>
<button>Back to Dashboard</button>
</a>
</div>

<div class = 'container'>

<h1>Billing System</h1>

<?php
if ( isset( $error ) ) {
    echo "<p class='error'>$error</p>";
}

if ( isset( $message ) ) {
    echo "<p class='success'>$message</p>";
}
?>

<form method = 'POST'>

<select name = 'product' required>

<option value = ''>Select Product</option>

<?php
while( $p = pg_fetch_assoc( $products ) ) {
    echo "<option value='{$p['product_id']}'>{$p['product_name']}</option>";
}
?>

</select>

<input type = 'number' name = 'qty' placeholder = 'Quantity' min = '1' required>

<button name = 'add'>Add Item</button>

</form>

<table>

<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
<th>Action</th>
</tr>

<?php

$total_bill = 0;

foreach ( $_SESSION[ 'cart' ] as $index => $item ) {

    echo '<tr>';

    echo '<td>'.$item[ 'name' ].'</td>';
    echo '<td>'.$item[ 'qty' ].'</td>';
    echo '<td>'.$item[ 'price' ].'</td>';
    echo '<td>'.number_format( $item[ 'total' ], 2 ).'</td>';

    echo "<td>
<a href='?delete=$index'>
<button class='delete' type='button'>Delete</button>
</a>
</td>";

    echo '</tr>';

    $total_bill += $item[ 'total' ];

}

?>

</table>

<div class = 'total'>

Total Bill: ₹ <?php echo number_format( $total_bill, 2 );
?>

</div>

<form method = 'POST'>

<button name = 'generate'>Generate Bill</button>

</form>

</div>

</body>
</html>