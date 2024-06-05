<?php
// Include configuration file
@include 'config.php';

// Check if the "add to cart" form is submitted
if(isset($_POST['add_to_cart'])){
   
   // Retrieve product details from the form
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = 1;
   $product_size = $_POST['product_size'];

   // Capitalize the first letter of product size for display
   $product_size_display = ucfirst($product_size);

   // Insert the product into the cart
   $insert_product = mysqli_query($conn, "INSERT INTO cart (name, price, image, size, quantity) VALUES ('$product_name', '$product_price', '$product_image', '$product_size_display', '$product_quantity')");
   
   // Check if insertion was successful
   if($insert_product){
       $message[] = 'Product added to cart successfully';
   } else {
       $message[] = 'Failed to add product to cart';
   }
}

// Count the number of rows in the cart table
$select_rows = mysqli_query($conn, "SELECT * FROM cart") or die('query failed');
$total_items_in_cart = mysqli_num_rows($select_rows);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brewed by Friend</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Angkor&family=Bebas+Neue&family=Leckerli+One&family=Luckiest+Guy&family=Madimi+One&family=Pangolin&family=Permanent+Marker&family=Tenor+Sans&display=swap&family=East+Sea+Dokdo&family=Honk&family=Gluten:wght@100..900" rel="stylesheet">
    <style>
        /* Inline CSS for demonstration purposes */
        #search-bar {
            display: none;
            margin-right: 10px; /* Pakanan ang pagbukas ng search bar */
        }
    
        .search-active #search-bar {
            display: block;
        }
    
        .h-icons i {
            font-size: 25px;
            color: black;
            margin-left: 5px;
            margin-right: 20px;
            transition: all .50s ease;
            cursor: pointer;
        } 

        .h-icons i:hover {
            transform: translateY(-4px);
            color: gray;
        }

        /* Styles for sidebar and main content */
        .container {
            display: flex;
            margin-top: 90px;
        }

        .Category .Menu p {
            margin: 10px 0;
            margin-bottom: 80px;
        
        }

        .products {
            width: 80%;
            padding: 20px;
        }

    </style>
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php">
            <img src="css\afterfive.png">
        </a>
    </div>

    <ul class="navbar">
        <li><a href="Menu.php">MENU</a></li>
        <li><a href="address.php">LOCATION</a></li>
        <li><a href="aboutUs.php">ABOUT US</a></li>
        <li><a href="logout.php">LOG OUT</a></li>
    </ul>

    <div class="h-icons" id="h-icons">
        <a href="#" id="search-icon">
            <img src="search.png" alt="Search Icon">
        </a>
        <input type="text" id="search-bar" placeholder="Search...">
        <i class="bx bxs-phone"></i>
        <a href="login.php" class="admin-icon"><i class='bx bx-user'></i></a>
        <a href="cart.php" class="cart"><i class="bx bxs-shopping-bag"></i> <span class="cart-count"><?php echo $total_items_in_cart; ?></span></a>    
        <div class="bx bx-menu" id="menu-icon"></div>
    </div>
</header>

<main>
    <div class="container">
        <div class="Category">
            <div class="collapse navbar-collapse" id="sideNavContent">
                <li class="nav-item">
                    <nav class="Menu">
                        <p><b>Drinks</b></p><br><br>
                        <p><a href="Category/Brewed_By_Friend.php">Brewed By Friends Coffee</a></p><br>
                        <p><a href="#">Espresso Based</a></p><br>
                        <p><a href="#">Non - Coffee</a></p><br>
                        <p><a href="#">Frappe</a></p><br>
                        <p><a href="#">Sparkling Drinks</a></p><br><br>
                        <p><b>Foods</b></p><br><br>
                        <p><a href="#">Around the clock Breakfast</a></p><br>
                        <p><a href="#">Day and Night Pasta</a></p><br>
                        <p><a href="#">Daily Catch of Cakes and Churros</a></p><br>
                        <p><a href="#">Counter Clock Sandwich</a></p><br>
                        <p><a href="#">Friend Toast</a></p>
                    </nav>
                </li>
            </div>
        </div>

        <section class="products">
            <div class="box-container" style="margin-top: 100px;"> 
                <?php
                // Retrieve products from the database
                $select_products = mysqli_query($conn, "SELECT * FROM products");
                if(mysqli_num_rows($select_products) > 0){
                    while($fetch_product = mysqli_fetch_assoc($select_products)){
                ?>
        
                <!-- Form to add products to the cart -->
                <form action="" method="post">
                    <div class="box">
                        <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">
                        <h3><?php echo $fetch_product['name']; ?></h3>
                        <div class="price">₱<?php echo $fetch_product['price']; ?>/-</div>
                        <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                        <!-- Select dropdown for cup size -->
                        <select name="product_size" style="background: #ffcd59;display: flex; padding: 10px;border-radius: 30px;font-size: 15px;font-weight: 500;">
                            <option value="small">Small</option>
                            <option value="large">Large</option>
                        </select>
                        <form action="" method="POST">
                            <div class="form-group mb-3"> </div>
                        </form>
                        <input type="submit" class="btn" value="Add to Cart" name="add_to_cart">
                    </div>
                </form>
                <?php
                    }
                } else {
                    echo "<p>No products available</p>";    
                }
                ?>
            </div>
        </section>
    </div>
</main>


<script>
   document.addEventListener("DOMContentLoaded", function() {
    var searchBar = document.getElementById("search-bar");
    var searchIcon = document.getElementById("search-icon");
    var hIcons = document.getElementById("h-icons");

    // Toggle search bar visibility when clicking on the search icon
    searchIcon.addEventListener("click", function(event) {
        event.stopPropagation(); // Prevent body click event from triggering    
        searchBar.style.display = (searchBar.style.display === "none" || searchBar.style.display === "") ? "block" : "none";
        hIcons.classList.toggle("search-active"); // Add or remove class for icon
    });

    // Close search bar if clicked outside of it
    document.body.addEventListener("click", function(event) {
        if (!event.target.closest('#search-bar') && !event.target.closest('#search-icon')) {
            searchBar.style.display = "none";
            hIcons.classList.remove("search-active");
        }
    });
});

</script>


