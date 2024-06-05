<?php

// Include configuration file
@include 'config.php';

// Handle quantity update
if(isset($_POST['update_update_btn'])){
   // Retrieve updated quantity and item ID
   $update_value = $_POST['update_quantity'];
   $update_id = $_POST['update_quantity_id'];

   
   // Update quantity in the database
   $update_quantity_query = mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_value' WHERE id = '$update_id'");
   
   // Redirect to cart page after updating
   if($update_quantity_query){
      header('location:cart.php');
   }
}

// Handle item removal
if(isset($_GET['remove'])){
   // Retrieve ID of item to be removed
   $remove_id = $_GET['remove'];
   
   // Delete item from the cart in the database
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'");
   
   // Redirect to cart page after removal
   header('location:cart.php');
}

// Handle deleting all items from the cart
if(isset($_GET['delete_all'])){
   // Delete all items from the cart in the database
   mysqli_query($conn, "DELETE FROM `cart`");
   
   // Redirect to cart page after deletion
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

<section class="shopping-cart">

   <h1 class="heading">Shopping Cart</h1>

   <table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Size of Cup</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Retrieve items from the cart in the database
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
        
        // Initialize grand total variable
        $grand_total = 0;
        
        // Display cart items
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                // Calculate subtotal for each item
                $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                $grand_total += $sub_total;  // Add subtotal to grand total
                
        ?>
        <tr>
            <td><img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
            <td><?php echo $fetch_cart['name']; ?></td>
            <td>₱<?php echo number_format($fetch_cart['price']); ?>/-</td>
            <td><?php echo ucfirst($fetch_cart['size']); ?></td> <!-- Displaying size of cup -->
            <td>
                <!-- Form to update quantity -->
                <form action="" method="post">
                    <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>" >
                    <input type="number" name="update_quantity" class="quantity-input" min="1" value="<?php echo $fetch_cart['quantity']; ?>">
                    <input type="submit" value="Update" name="update_update_btn">
                </form>   
            </td>
            <td>₱<?php echo number_format($sub_total); ?>/-</td>
            <td><a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('Remove item from cart?')" class="delete-btn"> <i class="fas fa-trash"></i> Remove</a></td>
        </tr>
        <?php
            }
        }
        ?>
        <!-- Row for displaying grand total -->
        <tr class="table-bottom">
            <td colspan="4"></td>
            <td>Grand Total</td>
            <td>₱<?php echo number_format($grand_total); ?>/-</td>
            <td><a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to delete all?');" class="delete-btn"> <i class="fas fa-trash"></i> Delete All </a></td>
        </tr>
    </tbody>
</table>


   <!-- Checkout button -->
   <div class="checkout-btn">
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout</a>
   </div>

</section>

   
<!-- Custom JS file link -->
<script src="js/script.js"></script>
<script src="script.js"></script>
<script>
   // Attach event listener to each quantity input field
   const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const row = input.closest('tr');
            const price = parseFloat(row.querySelector('td:nth-child(3)').textContent.replace('₱', '').replace('/-', ''));
            const quantity = parseInt(input.value);
            const subtotal = price * quantity;

            // Update the subtotal for this product
            row.querySelector('td:nth-child(6)').textContent = '₱' + subtotal.toLocaleString() + '/-';

            // Recalculate the grand total
            let grandTotal = 0;
            document.querySelectorAll('.quantity-input').forEach(input => {
                const row = input.closest('tr');
                const price = parseFloat(row.querySelector('td:nth-child(3)').textContent.replace('₱', '').replace('/-', ''));
                const quantity = parseInt(input.value);
                grandTotal += price * quantity;
            });

            // Display the updated grand total
            document.querySelector('.table-bottom td:nth-child(6)').textContent = '₱' + grandTotal.toLocaleString() + '/-';
        });
    });
</script>

</body>
</html>
