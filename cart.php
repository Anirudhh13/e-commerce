<?php
include('includes/connect.php');

include('./functions/common_functions.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart details</title>
    <!-- bootstrap CSS link  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    
    <!-- font awesom link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- css -->
    <link rel="stylesheet" href="style.css">
    <style>
        .cart_img{
    width: 80px;
    height: 80px;
    object-fit: contain;
}
    </style>
</head>
<body>
    <!-- boorstrap JS link  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
   <!-- navbar -->
   <div class="container-fluid p-0">
    <!-- first child -->
    <nav class="navbar navbar-expand-lg navbar-light bg-info">
  <div class="container-fluid">
    <img src="./images/logo.jpg" alt="logo" class="logo">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Products</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="./user_area/user_registration.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup><?php count_cart() ?></sup></a>
        </li>
        
      </ul>
      
    </div>
  </div>
</nav>

<!-- second child  -->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <ul class="navbar-nav me-auto">
    <?php
        if(!isset($_SESSION['username'])){
          echo "<li class='nav-item'>
          <a class='nav-link' href='#'>Welcome Guest</a></li>";
         }else{
          echo "<li class='nav-item'>
          <a class='nav-link' href='#'>Welcome ".$_SESSION['username']."</a></li>";
         }
        
        if(!isset($_SESSION['username'])){
         echo "<li class='nav-item'>
          <a class='nav-link' href='./user_area/user_login.php'>Login</a>
        </li>";
        }else{
          echo "<li class='nav-item'>
          <a class='nav-link' href='./user_area/user_logout.php'>Logout</a>
        </li>";
        }
        ?>
    </ul>
</nav>

<!-- third child  -->
<div class="bg-light">
    <h3 class="text-center">Hidden Store</h3>
    <p class="text-center">Communications is at heart of e-commerce and community</p>
</div>

<!-- fourth child  -->
<div class="container">
    <div class="row">
        <form action="" method="post">
        <table class="table table-bordered text-center">
            <!-- <thead>
                <tr>
                    <th>Product Title</th>
                    <th>Product Image</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Remove</th>
                    <th aria-colspan="2">Operations</th>
                </tr>
            </thead> -->
            <tbody>
            <!-- php code to display dynamic data  -->
            <?php
                global $conn;
                $ip = getIPAddress();  
                $total=0;
                $cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip'";
                $result = mysqli_query($conn, $cart_query);
                $count_result = mysqli_num_rows($result);
                if($count_result>0){
                  echo "<thead>
                  <tr>
                      <th>Product Title</th>
                      <th>Product Image</th>
                      <th>Quantity</th>
                      <th>Total Price</th>
                      <th>Remove</th>
                      <th aria-colspan='2'>Operations</th>
                  </tr>
              </thead>";
                
                while($row= mysqli_fetch_array($result)){
                  $product_id = $row['product_id'];
                  $select_products = "SELECT * FROM `products` WHERE product_id=$product_id";
                  $result_products = mysqli_query($conn, $select_products);
                  while($row= mysqli_fetch_array($result_products)){
                    $product_price = array($row['product_price']);
                    $price_table = $row['product_price'];
                    $product_title = $row['product_title'];
                    $product_image = $row['product_image1'];

                    $product_values = array_sum($product_price);
                    $total+=$product_values;
                  
            ?>
            <tr>
                <td><?php echo $product_title?></td>
                <td><img src="./images/<?php echo $product_image?>" alt="" srcset="" class="cart_img"></td>
                <td><input type="text" name="qty" id="" class="form-input w-50"></td>
                <?php
                $ip = getIPAddress();  
                    if(isset($_POST['update_cart'])){
                    $quantity = $_POST['qty'];
                    $update_quantity= "UPDATE `cart_details` SET quantity=$quantity WHERE ip_address='$ip'";
                    $result_update = mysqli_query($conn,$update_quantity);
                    echo "<script>alert('Cart updated successfully!')</script>";
                    $total= $total*$quantity;
                    }
                ?>
                <td><?php echo $price_table?></td>
                <td><input type="checkbox" name="removecart[]" value="<?php echo $product_id ?>"></td>
                <td>
                <!-- <button class="bg-info px-3 py-2  mx-3">Update</button> -->
                <input type="submit" value="Update Cart" class="bg-info px-3 py-2  mx-3" name="update_cart">
                <!-- <button class="bg-info px-3 py-2  mx-3">Remove</button> -->
                <input type="submit" value="Remove Cart" class="bg-info px-3 py-2  mx-3" name="remove_cart">
                </td>
            </tr>

                  <?php
                  } }
                }else{
                  echo "<h2 class='text-center text-danger'>Cart is empty!</h2>";
                }
                ?>

            </tbody>
        </table>
        </form>
        <!-- subtotal -->
        <div class="d-flex mb-5">
          <?php
           $ip = getIPAddress();  
           
           $cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip'";
           $result = mysqli_query($conn, $cart_query);
           $count_result = mysqli_num_rows($result);
           if($count_result>0){
          echo "<h4 class='px-3'>Subtotal:<strong class='text-info'> $total/-</strong></h4>
          <a href='index.php'><button class='bg-info px-3 py-2 border-0  mx-3'>Continue Shopping</button></a>
          
          <a href='./user_area/checkout.php'><button class='bg-secondary p-3 py-2 border-0'>Checkout</button></a>";
          }else{
            echo "<a href='index.php'><button class='bg-info px-3 py-2 border-0  mx-3'>Continue Shopping</button></a>";
          }
          ?>
        
        </div>
    </div>
</div>


<!-- function to delete cart items -->
<?php
function remove_cart_items(){
    global $conn;
    if(isset($_POST['remove_cart'])){
foreach($_POST['removecart'] as $removeitem){
    $delete_query = "DELETE FROM `cart_details` WHERE product_id=$removeitem";
    $run_query = mysqli_query($conn,$delete_query);
    if($run_query){
        echo "<script>window.open('cart.php','_self')</script>";
    }
}
    }
}
echo $remove=remove_cart_items();
?>

<!-- footer -->
<div class="bg-info p-3 text-center">
    <p>All rights reserved © - Designed by Anirudh-2023</p>
</div>


</body>
</html>