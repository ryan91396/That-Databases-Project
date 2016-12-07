<html>
<head>
<title>Cart</title>
<?php
include "css.php";
scripts();
?>
</head>
<body>
<?php
session_start();
//insert into orders values (uuid(), 'test', 'apples', 'incart', CURDATE(), 3, 2);
$conn = new mysqli('rash227.netlab.uky.edu', 'root', 'root','PROJECT');
$uname = $_SESSION['username'];
$pos = $_SESSION['position'];
navbarCust($uname,$pos);
if (isset($_GET["item"])) {
	$item = htmlspecialchars($_GET["item"]);
	$amount = htmlspecialchars($_GET["amount"]);
	$q = "select price, discount from merch where mid=\"".$item."\";";
	//echo $q."<br>";
	$result = $conn->query($q);
	$row = $result->fetch_assoc();
	$tprice = ( $row["price"] - ( $row["price"] * $row["discount"] ) ) * $amount;
	$q = "insert into orders values (uuid(), \"".$uname."\", \"".$item."\", \"incart\", CURDATE(), ".$tprice.", ".$amount.");";
	//echo $q;
	$result = $conn->query($q);
	//echo "done";
}
?>
<div class=container>
<div class=jumbotron>
<h1>Your Cart</h1>
<table class="table table-striped table-hover">
<thead>
<tr>
	<th>Name</th>
	<th>Quantity</th>
	<th>Price</th>
</tr>
</thead>
<tbody>
<?php
	$total = 0;
	$q = "select mid, quantity, totalPrice from orders where cid=\"".$uname."\" and status=\"incart\";";
	$result = $conn->query($q);
	while ( $row = $result->fetch_assoc() ) {
		echo "<tr>";
		echo "<td>".$row["mid"]."</td>";
		echo "<td>".$row["quantity"]."</td>";
		echo "<td>".$row["totalPrice"]."</td>";
		echo "</tr>";
		$total = $total + $row["totalPrice"];
	}
	echo "</tbody></table>";
	echo "<h2>Total = $".$total."</h2>";
?>
<form method=POST action=checkout.php>
	<?php
		echo "<input type=hidden name=user value=".$uname.">";
		echo "<input type=hidden name=pos value=".$pos.">";
	?>
	<button type="submit" class="btn btn-primary">Checkout</button>
</form>
</div></div>
<h2>Your Past Orders</h2>
<table class="table table-striped table-hover">
<thead>
<tr>
        <th>Name</th>
        <th>Quantity</th>
        <th>Status</th>
</tr>
</thead>
<tbody>

<?php
$q = "select mid, quantity, status from orders where cid=\"".$uname."\" and status!=\"incart\";";
$result = $conn->query($q);
        while ( $row = $result->fetch_assoc() ) {
                echo "<tr>";
                echo "<td>".$row["mid"]."</td>";
                echo "<td>".$row["quantity"]."</td>";
                echo "<td>".$row["status"]."</td>";
                echo "</tr>";
                $total = $total + $row["totalPrice"];
        }
?>

</tbody>
</table>
</body>
</html>
