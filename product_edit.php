<?php
	//require_once("auth_check.php");
	
	//Start PHP session
	session_start();

    //establish the connection to the database
	require_once("mysqli_conn.php");
	
	//function to clean the inputs from the form.  
	
	function sanitize($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
?>
<!DOCTYPE HTML>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Edit Product</title>

      <meta name="description" content="MySQLi Test Page">
      <meta name="keywords" content="bootstrap themes, responsive theme">
      <meta name="author" content="FW" >

      <!-- Bootstrap -->
      <!-- ================================================== -->
      <link rel="stylesheet" type="text/css"  href="css/bootstrap.css">
      <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">

      <!-- Stylesheet -->
      <!-- ================================================== -->
      <link rel="stylesheet" type="text/css"  href="css/style.css">
      <link rel="stylesheet" type="text/css" href="css/responsive.css">
	  
      <style>
        body {
          background-image: url('img/wall.jpg');
        }
      </style> 

  </head>
  <body>
<?php

// define variables and set to empty values
$product_code = "";
$product_name = "";
$description = "";
$list_price = "";
$discount_percent = "";
$error = false;

//Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  //Check for errors
  if (empty($_POST['product_id'])) {
	  die("Error - Product ID Empty");
  } else {
	  //Make sure the form product_id is the same as the $SESSION['product_id'].
	  //This prevents a malicious user from changing the product_id and updating the wrong record.
	  $product_id = sanitize($_POST["product_id"]);
	  if ($product_id != $_SESSION['product_id']) {
		die("Error - Invalid Product ID");
	  }
  }

  //This section is to validate the data from the form.
  //If there are any errors, set the variable $error = true. 
  //This cause the form to be redisplayed.  
  
  if (empty($_POST['product_code'])) {
    $ProdCodeErr = "Product Code is required\n";
	$error = true;
  } else {
    $product_code = sanitize($_POST["product_code"]);
  }
  
  if (empty($_POST["product_name"])) {
    $ProdNameErr = "Product Name is required";
	$error = true;
  } else {
    $product_name = sanitize($_POST["product_name"]);
  }
    
  if (empty($_POST["description"])) {
    $DescErrMsg = "Description is required";
	$error = true;
  } else {
    $description = sanitize($_POST["description"]);
  }
  if (empty($_POST["list_price"])) {
    $LPriceErrMsg = "List Price is required";
	$error = true;
  } else {
    $list_price = sanitize($_POST["list_price"]);
  }
  //If discount_percent is not set, set it to 0 and do not display an error.
  if (empty($_POST["discount_percent"])) {
		$discount_percent = 0;
  } else {
    $discount_percent = sanitize($_POST["discount_percent"]);
  }
  //If there where no errors, update the record.
  if($error === false) {
	  //mysqli_real_escape_string() gets the string ready to use in a SQL statement to update the record.
	  $product_code = mysqli_real_escape_string($db_conn, $product_code);
	  $product_name = mysqli_real_escape_string($db_conn, $product_name);
	  $description = mysqli_real_escape_string($db_conn, $description);
	  $list_price = mysqli_real_escape_string($db_conn, $list_price);
	  $discount_percent = mysqli_real_escape_string($db_conn, $discount_percent);
	  //SQL statement to update the record.
	  $sql = "UPDATE products SET product_code='". $product_code ."', product_name='" . $product_name . "', description='" . $description . "', list_price=" . $list_price . ", discount_percent=" . $discount_percent . " WHERE product_id=". $product_id . ";";
	  if (mysqli_query($db_conn, $sql)) {
		echo('<div class="alert alert-success" role="alert">');
	    echo "  <h1>Record updated successfully</h1>";
		echo('</div>');  
	  } else {
		echo('<div class="alert alert-danger" role="alert">');
        echo "<h1>Error updating record: " . mysqli_error($db_conn) . "</h1>";
		echo('</div>');  
      }
      echo('<h1><a href="product_list.php">Return to Product List</a></h1>');
	  echo("<br>");
	  die();
  }

} else {
	//Load the record from the table.  The varibles will be used to set the value of the form fields.
	if(isset($_GET['product_id'])) {
		$product_id = sanitize($_GET["product_id"]);
		$_SESSION["product_id"] = $product_id;
		$sql = "SELECT * FROM products WHERE product_id=" . $product_id . ";";
		$result = mysqli_query($db_conn, $sql) or die(mysqli_error($db_conn));
		$row_count=mysqli_num_rows($result);
		//If the record is found, copy values to varibles.
		if ($row_count > 0) {
			$row = mysqli_fetch_assoc($result);
			$product_code = $row["product_code"];
			$product_name = $row["product_name"];
			$description = $row["description"];
			$list_price = $row["list_price"];
			$discount_percent = $row["discount_percent"];
		}
	}
}

//Show the form with the values set to the data preloaded in the variables.
// This is done by these statments:  <?php if(isset($product_id)) { echo("value=$product_id");}
?>

<div class="container">
  <br>
  <div class="formheader">
	<h2>Edit Product</h2>
  </div>
  <div class="editform">
    <p><span class="error">* Required Field</span></p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
	  <input type="hidden" name="product_id" <?php if(isset($product_id)) { echo("value=$product_id");}?>>
	  <label for="product_code">* Product Code:</label>
      <input type="text" name="product_code" size="10" value="<?php echo $product_code;?>">
	  <?php
	    //If there was an error, display what the error was
		if(isset($ProdCodeErr)) {
			echo('<span class="alert alert-danger"> * ' . $ProdCodeErr . ';</span>');
		}
      ?>
	  <br><br>
	  <label for="product_name">* Product Name:</label>
      <input type="text" name="product_name" size="100" value="<?php echo $product_name;?>">
	  <?php
		if(isset($ProdNameErr)) {
			echo('<br><br><span class="alert alert-danger">' . $ProdNameErr . ';</span>');
		}
      ?>
	  <br><br>
	  <label for="description">* Description:</label>
      <textarea name="description" rows="10" cols="100"><?php echo $description;?></textarea>
	  <?php
		if(isset($DescErrMsg)) {
			echo('<br><br><span class="alert alert-danger">' . $DescErrMsg . ';</span>');
		}
      ?>
	  <br><br>
	  <label for="list_price">* List Price:</label>
      <input type="number" min=".01"  step="0.01" class="currency" name="list_price" value="<?php echo $list_price;?>">
	  <?php
		if(isset($LPriceErrMsg)) {
			echo('<span class="alert alert-danger">' . $LPriceErrMsg . ';</span>');
		}
      ?>
	  <br><br>
	  <label for="discount_percent">Discount Percent:</label>
	  <input type="number" min="0" step="0.01" class="currency" name="discount_percent" value="<?php echo $discount_percent;?>">
	  <?php
		if(isset($DiscPerErrMsg)) {
			echo('<span class="alert alert-danger">' . $DiscPerErrMsg . ';</span>');
		}
      ?>
	  <br><br>

      <input type="submit" name="submit" value="Submit">  
    </form>
  </div>
</div>

</body>
</html>