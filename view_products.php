<?php

include "config.php";

/* LOAD CATEGORIES */

$cat_query = "SELECT * FROM categories ORDER BY category_name";
$categories = pg_query($conn,$cat_query);


/* IF CATEGORY SELECTED */

if(isset($_GET['category'])){

$category_id = $_GET['category'];

$product_query = "SELECT 
p.product_id,
p.product_name,
c.category_name,
s.supplier_name,
p.mrp,
p.standard_cost,
(p.mrp - p.standard_cost) AS profit

FROM products p
JOIN categories c ON p.category_id = c.category_id
JOIN suppliers s ON p.supplier_id = s.supplier_id

WHERE p.category_id = $1

ORDER BY p.product_name";

$products = pg_query_params($conn,$product_query,array($category_id));

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Products By Category</title>

<style>

body{
background:black;
color:#00ff00;
font-family:Consolas;
}

.container{
width:1000px;
margin:auto;
}

h1{
text-align:center;
}

select,button{
background:black;
color:#00ff00;
border:1px solid #00ff00;
padding:8px;
margin:5px;
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

th,td{
border:1px solid #00ff00;
padding:8px;
text-align:center;
}

th{
background:#003300;
}

tr:hover{
background:#001100;
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

<h1>Products By Category</h1>

<form method="GET">

<select name="category" required>

<option value="">Select Category</option>

<?php

while($cat = pg_fetch_assoc($categories)){

echo "<option value='".$cat['category_id']."'>".$cat['category_name']."</option>";

}

?>

</select>

<button type="submit">View Products</button>

</form>


<?php

if(isset($products)){

echo "<table>";

echo "<tr>
<th>ID</th>
<th>Product</th>
<th>Category</th>
<th>Supplier</th>
<th>MRP</th>
<th>Cost</th>
<th>Profit</th>
</tr>";

while($row = pg_fetch_assoc($products)){

echo "<tr>";

echo "<td>".$row['product_id']."</td>";
echo "<td>".$row['product_name']."</td>";
echo "<td>".$row['category_name']."</td>";
echo "<td>".$row['supplier_name']."</td>";
echo "<td>".$row['mrp']."</td>";
echo "<td>".$row['standard_cost']."</td>";
echo "<td>".$row['profit']."</td>";

echo "</tr>";

}

echo "</table>";

}

?>

</div>

</body>
</html>