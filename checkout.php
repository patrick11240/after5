<?php

// Include configuration file
@include 'config.php';

// Handle order submission
if(isset($_POST['order_btn'])){
   // Retrieve form data
   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];

   // Retrieve cart items and calculate total price
   $cart_query = mysqli_query($conn, "SELECT * FROM `cart`");
   $price_total = 0;
   $product_name = [];
   if(mysqli_num_rows($cart_query) > 0){
      while($product_item = mysqli_fetch_assoc($cart_query)){
         $product_name[] = $product_item['name'] .' ('. $product_item['quantity'] .') ';
         $product_price = number_format($product_item['price'] * $product_item['quantity']);
         $price_total += $product_price;
      }
   }

   // Prepare total products string
   $total_product = implode(', ',$product_name);

   // Insert order details into the database
   $detail_query = mysqli_query($conn, "INSERT INTO `order` (name, number, email, method, total_products, total_price) VALUES ('$name','$number','$email','$method','$total_product','$price_total')") or die('query failed');

   // Display order confirmation message
   if($cart_query && $detail_query){
      echo "
      <div class='order-message-container'>
      <div class='message-container'>
         <h3>Thank you for shopping!</h3>
         <div class='order-detail'>
            <span>".$total_product."</span>
            <span class='total'>Total: $".$price_total."/-</span>
         </div>
         <div class='customer-details'>
            <p>Your name: <span>".$name."</span></p>
            <p>Your number: <span>".$number."</span></p>
            <p>Your email: <span>".$email."</span></p>
            <p>Your payment mode: <span>".$method."</span></p>
            <p>(*Pay when product arrives*)</p>
         </div>
         <a href='products.php' class='btn'>Continue Shopping</a>
      </div>
      </div>
      ";
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

<section class="checkout-form">

   <h1 class="heading">Complete Your Order</h1>

   <form action="" method="post">

   <!-- Display selected products and total price -->
   <div class="display-order">
    <?php
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
    $total = 0;
    $grand_total = 0;
    if(mysqli_num_rows($select_cart) > 0){
        while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
            $total += $total_price;
            ?>
            <span><?= $fetch_cart['name']; ?>(<?= $fetch_cart['quantity']; ?>)</span>
            <?php
        }
        $grand_total = $total;
    }else{
        echo "<div class='display-order'><span>Your cart is empty!</span></div>";
    }
    ?>
    <span class="grand-total">Grand Total: $<?= number_format($grand_total); ?>/-</span>
</div>

      <!-- Input fields for customer details -->
      <div class="flex">
         <div class="inputBox">
            <span>Name</span>
            <input type="text" placeholder="Enter your name" name="name" required>
         </div>
         <div class="inputBox">
            <span>Phone Number</span>
            <input type="number" placeholder="Enter your number" name="number" required>
         </div>
         <div class="inputBox">
            <span>Email</span>
            <input type="email" placeholder="Enter your email" name="email" required>
         </div>
         <div class="inputBox">
            <span>Payment Method</span>
            <select name="method">
               <option value="credit cart">Pick to Pay</option>
            </select>
         </div>
      </div>
      <input type="submit" value="Order Now" name="order_btn" class="btn">
   </form>

</section>

</div>

<!-- Custom JS file link -->
<script src="js/script.js"></script>
   
</body>
</html>
