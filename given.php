<?php
	//require_once("auth_check.php");
	
	//Start PHP session
	session_start();
	require_once("mysqli_conn.php");
?> 
<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Product List</title>

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

  </head>
  <body>
    <div id="home">
      <div class="container">
	    <h2 style="color:white;">Products</h2>
        <table class="table">
          <thead class="thead-dark">
            <tr>
			  <th class="col-xs-1;"></th>
              <th class="col-xs-1; text-center">Product ID</th>				  
              <th class="col-xs-1; text-center">Product Code</th>				  
              <th class="col-xs-2">Product Name</th>
		      <th class="col-xs-4">Description</th>
		      <th class="col-xs-1; text-center">List Price</th>
			  <th class="col-xs-1; text-center">Discount %</th>
			  <th class="col-xs-1; text-center">Our Price</th>		  
            </tr>
          </thead>
          <tbody>
<?php			  

	$sql = "SELECT * FROM products ORDER BY product_name;";

	//echo("<br>" . $sql . "<br>");

	$result = mysqli_query($db_conn, $sql) or die(mysqli_error($db_conn));
    while($row = mysqli_fetch_assoc($result)) {
	  $discount_price = $row["list_price"] - ($row["list_price"] * ($row["discount_percent"]/100));
	  $our_price = number_format($discount_price, 2 );
      echo("		    <tr>") . PHP_EOL;
	  
	  // ====================================================================
	  // The following HTML line adds the edit box to the table.  
	  // The edit box contains the link to edit the record.  <a href></a>
	  // The link is a GET request.
	  //
      echo('			  <td class="text-center">' . '<a href="product_edit.php?product_id=' . $row["product_id"] . '" class="btn btn-warning btn-sm">' . "Edit"  . '</a></td>') . PHP_EOL;
	  // ====================================================================
      
      echo('			  <td class="text-center">' . $row["product_id"]  . '</td>') . PHP_EOL;	  
      echo('			  <td>' .  $row["product_code"] . '</td>') . PHP_EOL;
      echo('			  <td>' . $row["product_name"] . '</td>') . PHP_EOL;
      echo('			  <td>' . substr($row["description"], 0, 45) . ' ...</td>') . PHP_EOL;
      echo('			  <td class="text-center">$ ' . $row["list_price"] . '</td>') . PHP_EOL;
      echo('			  <td class="text-center">' . $row["discount_percent"] . '%</td>') . PHP_EOL;
      echo('			  <td class="text-center">$ ' . $our_price . '</td>') . PHP_EOL;
      echo("            </tr>") . PHP_EOL;
    }
?>
          </tbody>
       </table>
       <br>
       <br>
       <br>
       <br>
      </div>
    </div>
  </body>
</html>