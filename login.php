<?php
session_start();

require_once "config.php";  

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    $username = $_POST['username'];
    $password_input = $_POST['password'];

    $query = "SELECT * FROM users WHERE username=$1 AND password=$2";

    $result = pg_query_params($conn, $query, array($username, $password_input));

    if ($result && pg_num_rows($result) == 1) {
        $_SESSION['username'] = $username;

        header("Location: dashboard.php");
        exit();
    } 
    else {
        $message = "ACCESS DENIED!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Store Inventory Login</title>

<style>

body{
font-family:Consolas, monospace;
background-color:black;
color:#00ff00;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.card{
width:350px;
padding:25px;
border:2px solid #00ff00;
border-radius:30px;
text-align:center;
}

input{
width:95%;
padding:8px;
margin:8px 0;
background-color:black;
border:1px solid #00ff00;
color:#00ff00;
outline:none;
}

button{
width:100%;
padding:8px;
margin-top:10px;
background-color:#00ff00;
color:black;
border:none;
cursor:pointer;
}

button:hover{
background-color:#00cc00;
}

.error{
color:red;
margin-top:10px;
}

</style>

</head>

<body>

<div class="card">

<h2>STORE INVENTORY SYSTEM</h2>
<p>Enter your credentials to login</p>

<form method="POST">

<input type="text" name="username" placeholder="Username" autocomplete="off" required>

<input type="password" name="password" placeholder="Password" autocomplete="off" required>

<button type="submit">ACCESS SYSTEM</button>

</form>

<div class="error">

<?php echo htmlspecialchars($message); ?>

</div>

<p>Developed by: Omkar</p>

</div>

</body>
</html>