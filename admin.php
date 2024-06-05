<?php
@include 'config.php';

if (isset($_POST['add_product'])) {
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_FILES['p_image']['name'];
    $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
    $p_image_folder = 'uploaded_img/' . basename($p_image);

    if (!is_dir('uploaded_img')) {
        mkdir('uploaded_img', 0777, true);
    }

    $insert_query = mysqli_query($conn, "INSERT INTO `products` (name, price, image) VALUES ('$p_name', '$p_price', '$p_image')") or die('query failed');

    if ($insert_query) {
        if (move_uploaded_file($p_image_tmp_name, $p_image_folder)) {
            $message[] = 'Product added successfully';
        } else {
            $message[] = 'Could not upload the product image';
        }
    } else {
        $message[] = 'Could not add the product';
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_query = mysqli_query($conn, "DELETE FROM `products` WHERE id = $delete_id") or die('query failed');
    if ($delete_query) {
        header('location:admin.php');
        $message[] = 'Product has been deleted';
    } else {
        header('location:admin.php');
        $message[] = 'Product could not be deleted';
    }
}

if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_p_name = $_POST['update_p_name'];
    $update_p_price = $_POST['update_p_price'];
    $update_p_image = $_FILES['update_p_image']['name'];
    $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
    $update_p_image_folder = 'uploaded_img/' . basename($update_p_image);
    $update_p_category = $_POST['update_p_category'];
    $update_p_size = $_POST['update_p_size'];

    if (!empty($update_p_image)) {
        $update_query = mysqli_query($conn, "UPDATE `products` SET name = '$update_p_name', price = '$update_p_price', image = '$update_p_image', category = '$update_p_category', cup_size = '$update_p_size' WHERE id = '$update_p_id'") or die('query failed');
        move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
    } else {
        $update_query = mysqli_query($conn, "UPDATE `products` SET name = '$update_p_name', price = '$update_p_price', category = '$update_p_category', cup_size = '$update_p_size' WHERE id = '$update_p_id'") or die('query failed');
    }

    if ($update_query) {
        $message[] = 'Product updated successfully';
        header('location:admin.php');
    } else {
        $message[] = 'Product could not be updated';
        header('location:admin.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '<div class="message"><span>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
    }
}
?>

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
         <a href="orderlist.php">Order List</a>
      </nav>

      <!-- Cart icon with count -->
      <a href="cart.php" class="cart">Cart <span>1</span> </a>

      <!-- Menu button for mobile -->
      <div id="menu-btn" class="fas fa-bars"></div>
   </div>
</header>

<div class="container">

<section>
    <form action="" method="post" class="add-product-form" enctype="multipart/form-data">
        <h3>Add a new product</h3>
        <input type="text" name="p_name" placeholder="Enter the product name" class="box" required>
        <input type="number" name="p_price" min="0" placeholder="Enter the product price" class="box" required>
        <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
        <input type="submit" value="Add the product" name="add_product" class="btn">
    </form>
</section>

<section class="display-product-table">
    <table>
        <thead>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Product Price</th>
            <th>Action</th>
        </thead>
        <tbody>
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`");
            if (mysqli_num_rows($select_products) > 0) {
                while ($row = mysqli_fetch_assoc($select_products)) {
            ?>
            <tr>
                <td><img src="uploaded_img/<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>" height="100" alt=""></td>
                <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td>₱<?php echo htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8'); ?>/-</td>
                <td>
                    <a href="admin.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this?');"> <i class="fas fa-trash"></i> Delete </a>
                    <a href="admin.php?edit=<?php echo $row['id']; ?>" class="option-btn"> <i class="fas fa-edit"></i> Update </a>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<div class='empty'>No product added</div>";
            }
            ?>
        </tbody>
    </table>
</section>

<section class="edit-form-container">
    <?php
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];

        // Using prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $fetch_edit = $result->fetch_assoc();
    ?>

   
<section class="edit-form-container">

   <?php
   
   if(isset($_GET['edit'])){
      $edit_id = $_GET['edit'];
      $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $edit_id");
      if(mysqli_num_rows($edit_query) > 0){
         while($fetch_edit = mysqli_fetch_assoc($edit_query)){
   ?>

   <form action="" method="post" class="add-product-form" enctype="multipart/form-data">

      <img src="uploaded_img/<?php echo $fetch_edit['image']; ?>" height="200" alt="">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">

      <input type="text" class="box" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">

      <input type="number" min="0" class="box" required name="update_p_price" value="<?php echo $fetch_edit['price']; ?>">

      <input type="file" class="box" required name="update_p_image" accept="image/png, image/jpg, image/jpeg">

      <input type="submit" value="update the prodcut" name="update_product" class="btn">

      <input type="reset" value="option" class="box">
      <select class="box" required name="update_p_category">
                <option value="Brewed By Friends Coffee" <?php echo $fetch_edit['category'] === 'Brewed By Friends Coffee' ? 'selected' : ''; ?>>Brewed By Friends Coffee</option>
                <option value="Espresso Based" <?php echo $fetch_edit['category'] === 'Espresso Based' ? 'selected' : ''; ?>>Espresso Based</option>
                <option value="Non - Coffee" <?php echo $fetch_edit['category'] === 'Non - Coffee' ? 'selected' : ''; ?>>Non - Coffee</option>
                <option value="Frappe" <?php echo $fetch_edit['category'] === 'Frappe' ? 'selected' : ''; ?>>Frappe</option>
                <option value="Sparkling Drinks" <?php echo $fetch_edit['category'] === 'Sparkling Drinks' ? 'selected' : ''; ?>>Sparkling Drinks</option>
                <option value="Around the clock Breakfast" <?php echo $fetch_edit['category'] === 'Around the clock Breakfast' ? 'selected' : ''; ?>>Around the clock Breakfast</option>
                <option value="Day and Night Pasta" <?php echo $fetch_edit['category'] === 'Day and Night Pasta' ? 'selected' : ''; ?>>Day and Night Pasta</option>
                <option value="Daily Catch of Cakes and Churros" <?php echo $fetch_edit['category'] === 'Daily Catch of Cakes and Churros' ? 'selected' : ''; ?>>Daily Catch of Cakes and Churros</option>
                <option value="Counter Clock Sandwich" <?php echo $fetch_edit['category'] === 'Counter Clock Sandwich' ? 'selected' : ''; ?>>Counter Clock Sandwich</option>
                <option value="Friend Toast" <?php echo $fetch_edit['category'] === 'Friend Toast' ? 'selected' : ''; ?>>Friend Toast</option>
            </select>
   </form>

   <?php
            };
         };
         echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script>";
      };
   ?>

</section>

    <script>
        document.querySelector('.edit-form-container').style.display = 'flex';
    </script>

    <?php
        }
        $stmt->close();
    }
    ?>
</section>

</div>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
