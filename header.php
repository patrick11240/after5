<header class="header">
   <!-- Container for logo, navigation links, and cart icon -->
   <div class="flex">
      <!-- Logo -->
      <a href="#" class="logo">after5</a>
      
      <!-- Navigation links -->
      <nav class="navbar">
         <!-- Link to add products -->
         <a href="admin.php">Add Products</a>
         <!-- Link to view products -->
         <a href="menu.php">View Products</a>

         <a href="orderlist.php">orderlist</a>
      </nav>

      <?php
      // Count the number of rows in the cart table
      $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
      $row_count = mysqli_num_rows($select_rows);
      ?>

      <!-- Cart icon with count -->
      <a href="cart.php" class="cart">Cart <span><?php echo $row_count; ?></span> </a>

      <!-- Menu button for mobile -->
      <div id="menu-btn" class="fas fa-bars"></div>
   </div>
</header>
