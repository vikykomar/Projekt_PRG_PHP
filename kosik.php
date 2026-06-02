<?php
require_once __DIR__ . '/src/bootstrap.php';

$productRepo = new ProductRepository();
$cart = new Cart();

$CartProducts = $cart->getItems(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $productId = (int) $_POST['product_id'];
    $variant = $_POST['variant'];
    $quantity = $_POST['quantity'];

    $cart->updateQuantity(productId: $productId, quantity: $quantity, variant: $variant);

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $productId = (int) $_POST['product_id'];
    $variant = $_POST['variant'];

    $cart->remove(productId: $productId, variant: $variant);

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$cartItemCount = $cart->getTotalQuantity();
?>

<?php
require __DIR__ . '/partials/header.php';
?>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Shopping Cart</h1>
        </div>
    </section>

    <!-- Cart Content -->
    <section class="cart-section">
        <div class="container">
            <div class="cart-layout">
                <!-- Cart Items -->
                <div class="cart-items">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach ($CartProducts as $product):
                                    require __DIR__ . '/partials/product-card-kosik.php';
                                endforeach;
                            ?>
                        </tbody>
                    </table>
                    
                    <div class="cart-actions">
                        <a href="kategorie.php" class="btn btn-secondary">Pokračovat v nakupování</a>
                    </div>
                </div>

                <!-- Cart Summary -->
                <aside class="cart-summary">
                    <div class="summary-box">
                        <h3>Shrnutí objednávky</h3>
                                                
                        <div class="summary-line total">
                            <span>Celkem:</span>
                            <span><?= number_format($cart->getTotalPrice(), 0, ',', ' ') ?> Kč</span>
                        </div>

                        <a href="checkout.html" class="btn btn-primary btn-lg <?= $cart->getTotalQuantity() === 0 ? "disabled" : "" ?>">Pokračovat k pokladně</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

<?php
require __DIR__ . '/partials/footer.php';
?>