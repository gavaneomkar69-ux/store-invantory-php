<?php

/* INCLUDE DATABASE CONNECTION */
require "config.php";

/* LOW STOCK QUERY */

$query = "
SELECT 
p.product_id,
p.product_name,
sup.supplier_name,
s.quantity,
s.reorder_level

FROM stock s
JOIN products p ON s.product_id = p.product_id
JOIN suppliers sup ON p.supplier_id = sup.supplier_id

WHERE s.quantity > 0 
AND s.quantity <= s.reorder_level

ORDER BY s.quantity ASC
";

$result = pg_query($conn,$query);

if(!$result){
die("Query failed: ".pg_last_error($conn));
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Low Stock Products</title>

<style>

body{
background:black;
color:#00ff00;
font-family:Consolas, monospace;
}

/* BACK BUTTON */

.back-btn{
position:absolute;
top:20px;
right:20px;
border:1px solid #00ff00;
color:#00ff00;
background:black;
padding:8px 12px;
text-decoration:none;
}

.back-btn:hover{
background:#00ff00;
color:black;
}

h1{
text-align:center;
}

table{
width:85%;
margin:auto;
border-collapse:collapse;
margin-top:30px;
}

th,td{
border:1px solid #00ff00;
padding:10px;
text-align:center;
}

th{
background:#003300;
}

.low{
color:red;
font-weight:bold;
}

</style>

</head>

<body>

<a href="dashboard.php" class="back-btn">Back to Dashboard</a>

<h1>Low Stock Products</h1>

<table>

<tr>
<th>Product ID</th>
<th>Product Name</th>
<th>Supplier</th>
<th>Quantity</th>
<th>Reorder Level</th>
<th>Status</th>
</tr>

<?php

if(pg_num_rows($result) == 0){
echo "<tr><td colspan='6'>No Low Stock Products</td></tr>";
}

while($row = pg_fetch_assoc($result)){

echo "<tr>";

echo "<td>".$row['product_id']."</td>";
echo "<td>".$row['product_name']."</td>";
echo "<td>".$row['supplier_name']."</td>";
echo "<td>".$row['quantity']."</td>";
echo "<td>".$row['reorder_level']."</td>";

echo "<td class='low'>LOW STOCK</td>";

echo "</tr>";

}

?>

</table>

</body>
</html>