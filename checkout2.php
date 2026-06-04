<?php
require_once __DIR__ . '/src/bootstrap.php';

$productRepo = new ProductRepository();
$shippingRepo = new ShippingMethodRepository();
$paymentRepo = new PaymentMethodRepository();
$customerRepo = new CustomerRepository();
$orderRepo = new OrderRepository();

$cart = new Cart();

$CartProducts = $cart->getItems(); 
$shippingMethods = $shippingRepo->getAll();
$paymentMethods = $paymentRepo->getAll();

$id = trim($_GET['id'] ?? '');
$order = $id !== '' ? $orderRepo->getById((int)$id) : null;

if($order === NULL){
    header('Location: /');
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
            <h1>Pokladna</h1>
        </div>
    </section>

    <!-- Checkout Steps Progress -->
    <section class="checkout-progress">
        <div class="container">
            <div class="progress-bar">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Doprava a platba</div>
                </div>
                <div class="step-line active"></div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-label">Úspěšne objednáno</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Checkout Content -->
    <section class="checkout-section">
        <div class="container">
            <div class="checkout-layout">
                <div  class="checkout-form">
                    <div class="checkout-step">
                        <h2>Shrnutí úspěšné objednávky</h2>
                        
                        <div class="review-section">
                            <h3>Doručovací adresa</h3>
                            <div class="review-box">
                                <p>
                                    <?= htmlspecialchars($order->customer->firstName) ?> <?= htmlspecialchars($order->customer->lastName) ?><br>
                                    <?= htmlspecialchars($order->customer->street) ?><br>
                                    <?= htmlspecialchars($order->customer->city) ?>, <?= htmlspecialchars($order->customer->zip) ?><br>
                                    <?= htmlspecialchars($order->customer->email) ?> <br>
                                    <?= htmlspecialchars($order->customer->phone) ?>
                                </p>
                            </div>
                        </div>

                        <div class="review-section">
                            <h3>Způsob platby</h3>
                            <div class="review-box">
                                <p>
                                    <?= htmlspecialchars($order->paymentMethod->name) ?><br>
                                </p>
                            </div>
                        </div>

                        <div class="review-section">
                            <h3>Položky objednáky</h3>
                            <div class="review-box">
                                <table class="review-table">
                                    <?foreach($order->items as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product->productName) ?> × <?= htmlspecialchars($product->quantity) ?></td>
                                            <td><?= htmlspecialchars($product->getTotalPrice()) ?> Kč</td>
                                        </tr>     
                                    <?endforeach;?>
                                </table>
                            </div>
                        </div>
                    </div>      
                </div>

                <!-- Order Summary Sidebar -->
                <aside class="order-summary">
                    <div class="summary-box">
                        <h3>Shrnutí objednávky</h3>
                        
                        <div class="summary-items">
                            <? foreach ($order->items as $product):?>
                                <div class="summary-item">
                                    <span><?= htmlspecialchars($product->productName) ?> × <?= htmlspecialchars($product->quantity) ?></span>
                                    <span><?= number_format($product->getTotalPrice(), 0, ',', ' ') ?> Kč</span>
                                </div>
                            <? endforeach; ?>
                        </div>
                        <div class="summary-line">
                            <span>Doprava:</span>
                            <span><?= htmlspecialchars($order->shippingPrice) ?> Kč</span>
                        </div>
                        <div class="summary-line">
                            <span>Platba:</span>
                            <span><?= htmlspecialchars($order->paymentPrice) ?> Kč</span>
                        </div>
                        
                        <div class="summary-line total">
                            <span>Celkem:</span>
                            <span><?= number_format($order->totalPrice, 0, ',', ' ') ?> Kč</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

<?php
require __DIR__ . '/partials/footer.php';
?>