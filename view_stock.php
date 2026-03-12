<?php

include "config.php";

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

ORDER BY p.product_id

";

$result = pg_query($conn,$query);

?>

<!DOCTYPE html>
<html>

<head>

<title>View Stock</title>

<style>

body{
background:black;
color:#00ff00;
font-family:Consolas;
}

h1{
text-align:center;
}

table{
width:90%;
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
color:orange;
font-weight:bold;
}

.out{
color:red;
font-weight:bold;
}

.normal{
color:#00ff00;
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

<h1>Current Stock</h1>

<table>

<tr>
<th>ID</th>
<th>Product</th>
<th>Supplier</th>
<th>Quantity</th>
<th>Reorder Level</th>
<th>Status</th>
</tr>

<?php

while($row = pg_fetch_assoc($result)){

$status = "NORMAL";
$class = "normal";

if($row['quantity'] == 0){
$status = "OUT OF STOCK";
$class = "out";
}

elseif($row['quantity'] <= $row['reorder_level']){
$status = "LOW STOCK";
$class = "low";
}

echo "<tr>";

echo "<td>".$row['product_id']."</td>";
echo "<td>".$row['product_name']."</td>";
echo "<td>".$row['supplier_name']."</td>";
echo "<td>".$row['quantity']."</td>";
echo "<td>".$row['reorder_level']."</td>";

echo "<td class='$class'>$status</td>";

echo "</tr>";

}

?>

</table>

</body>
</html>