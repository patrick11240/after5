<?php
// Include configuration file
@include 'config.php';

// Function to delete an order by ID
function deleteOrder($conn, $order_id) {
    $delete_query = "DELETE FROM `order` WHERE id = $order_id";
    if (mysqli_query($conn, $delete_query)) {
        return true;
    } else {
        return false;
    }
}

// Check if remove button is clicked
if(isset($_GET['remove_id'])) {
    $order_id = $_GET['remove_id'];
    if(deleteOrder($conn, $order_id)) {
        echo '<script>alert("Order removed successfully.")</script>';
        echo '<script>window.location.href = "orderlist.php";</script>';
    } else {
        echo '<script>alert("Failed to remove order. Please try again.")</script>';
    }
}

// Fetch orders from the database
$order_query = mysqli_query($conn, "SELECT * FROM `order`");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order List</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Link to external CSS file -->
   <link rel="stylesheet" href="css/css.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

<section class="display-product-table">
   <h1 class="heading">Order List</h1>

   <?php if(mysqli_num_rows($order_query) > 0): ?>
      <table class="table">
         <thead>
            <tr>
               <th>Order ID</th>
               <th>Name</th>
               <th>Phone Number</th>
               <th>Email</th>
               <th>Payment Method</th>
               <th>Total Products</th>
               <th>Total Price</th>
               <th>Action  </th>
            </tr>
         </thead>
         <tbody>
            <?php
            @include 'config.php';  
            ?>
            <?php while($order = mysqli_fetch_assoc($order_query)): ?>
               <tr>
                  <td><?= $order['id']; ?></td>
                  <td><?= $order['name']; ?></td>
                  <td><?= $order['number']; ?></td>
                  <td><?= $order['email']; ?></td>
                  <td><?= $order['method']; ?></td>
                  <td><?= $order['total_products']; ?></td>
                  <td>$<?= $order['total_price']; ?>/-</td>
                  <td>
   <a href="?remove_id=<?= $order['id']; ?>" onclick="return confirm('Are you sure you want to remove this order?')" class="delete-btn">
      <i class="fas fa-trash"></i> Remove
   </a>
</td>

               </tr>
            <?php endwhile; ?>
         </tbody>
      </table>
   <?php else: ?>
      <p>No orders found.</p>
   <?php endif; ?>
</section>

</div>

<!-- Custom JS file link -->
<script src="js/script.js"></script>
   
</body>
</html>
