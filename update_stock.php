<?php
session_start();
require "config.php";

/* CREATE SESSION CART */
if(!isset($_SESSION['stock_cart'])){
    $_SESSION['stock_cart'] = [];
}

/* ADD ITEM */
if(isset($_POST['add'])){

$product_id = $_POST['product'];
$qty = $_POST['qty'];

$query = "SELECT product_name FROM products WHERE product_id=$1";
$result = pg_query_params($conn,$query,array($product_id));
$row = pg_fetch_assoc($result);

$_SESSION['stock_cart'][] = [
"product_id"=>$product_id,
"name"=>$row['product_name'],
"qty"=>$qty
];

header("Location: update_stock.php");
exit();
}

/* DELETE ITEM */
if(isset($_GET['delete'])){

$index = $_GET['delete'];

unset($_SESSION['stock_cart'][$index]);

$_SESSION['stock_cart'] = array_values($_SESSION['stock_cart']);

header("Location: update_stock.php");
exit();
}

/* FINAL UPDATE STOCK */
if(isset($_POST['update'])){

foreach($_SESSION['stock_cart'] as $item){

$pid = $item['product_id'];
$qty = $item['qty'];

$query = "UPDATE stock
SET quantity = quantity + $1,
last_updated = CURRENT_TIMESTAMP
WHERE product_id = $2";

pg_query_params($conn,$query,array($qty,$pid));

}

$_SESSION['stock_cart'] = [];
$message = "Stock updated successfully!";
}

/* LOAD PRODUCTS */
$products = pg_query($conn,"SELECT product_id, product_name FROM products ORDER BY product_name");

?>

<!DOCTYPE html>
<html>

<head>

<title>Update Stock</title>

<style>

body{
background:black;
color:#00ff00;
font-family:Consolas;
}

.container{
width:800px;
margin:auto;
}

h1{
text-align:center;
}

select,input,button{
background:black;
color:#00ff00;
border:1px solid #00ff00;
padding:8px;
margin:5px;
}

button:hover{
background:#00ff00;
color:black;
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

th,td{
border:1px solid #00ff00;
padding:10px;
text-align:center;
}

.delete{
border:1px solid red;
color:red;
background:black;
padding:5px;
}

.delete:hover{
background:red;
color:black;
}

.message{
color:#00ff00;
font-weight:bold;
text-align:center;
}

/* BACK BUTTON */

.back-btn{
position:absolute;
top:20px;
right:20px;
}

.back-btn button{
background:black;
color:#00ff00;
border:1px solid #00ff00;
padding:8px 16px;
font-family:Consolas;
cursor:pointer;
}

.back-btn button:hover{
background:#00ff00;
color:black;
}

</style>

</head>

<body>

<div class="back-btn">
<a href="dashboard.php">
<button>Back to Dashboard</button>
</a>
</div>

<div class="container">

<h1>Update Stock</h1>

<?php
if(isset($message)){
echo "<p class='message'>$message</p>";
}
?>

<!-- ADD PRODUCT -->

<form method="POST">

<select name="product" required>

<option value="">Select Product</option>

<?php
while($p = pg_fetch_assoc($products)){
echo "<option value='{$p['product_id']}'>{$p['product_name']}</option>";
}
?>

</select>

<input type="number" name="qty" placeholder="Quantity to Add" min="1" required>

<button name="add">Add Item</button>

</form>


<!-- STOCK LIST -->

<table>

<tr>
<th>Product</th>
<th>Quantity</th>
<th>Action</th>
</tr>

<?php

if(!empty($_SESSION['stock_cart'])){

foreach($_SESSION['stock_cart'] as $index => $item){

echo "<tr>";

echo "<td>".$item['name']."</td>";
echo "<td>".$item['qty']."</td>";

echo "<td>
<a href='?delete=$index'>
<button class='delete' type='button'>Delete</button>
</a>
</td>";

echo "</tr>";

}

}
else{

echo "<tr>";
echo "<td colspan='3'>No items added</td>";
echo "</tr>";

}

?>

</table>


<!-- FINAL UPDATE -->

<form method="POST">

<button name="update">Final Update Stock</button>

</form>

</div>

</body>
</html>