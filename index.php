<?php
// Start or resume the session
session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        // Update quantity if the product is already in the cart
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Add a new product to the cart
        $_SESSION['cart'][$product_id] = $quantity;
    }
    //Add the session array in the session variable here.
    
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to display the cart
function displayCart() {
    echo '<h2>Your Shopping Cart</h2>';
    if (empty($_SESSION['cart'])) {
        echo 'Your cart is empty.';
    } else {
        echo '<ul>';
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            echo '<li>Product ID: ' . $product_id . ' - Quantity: ' . $quantity . ' <a href="?remove=' . $product_id . '">Remove</a></li>';
        }
        echo '</ul>';
    }
}

// Check if a product removal is requested
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    removeFromCart($product_id);
}

// Check if a product is being added to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart Example</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Welcome to our online store!</h1>

    <!-- Display the shopping cart -->
    <?php displayCart(); ?>

    <!-- Product list -->
    <h2>Product List</h2>
    <ul>
        <li>Product A - $10
            <form method="post" action="index.php">
                <input type="hidden" name="product_id" value="A">
                <input type="number" name="quantity" value="1">
                <input type="submit" name="add_to_cart" value="Add to Cart">
            </form>
        </li>
        <li>Product B - $15
            <form method="post" action="index.php">
                <input type="hidden" name="product_id" value="B">
                <input type="number" name="quantity" value="1">
                <input type="submit" name="add_to_cart" value="Add to Cart">
            </form>
        </li>
        <li>Product C - $20
            <form method="post" action="index.php">
                <input type="hidden" name="product_id" value="C">
                <input type="number" name="quantity" value="1">
                <input type="submit" name="add_to_cart" value="Add to Cart">
            </form>
        </li>
    </ul>
</body>
</html>
