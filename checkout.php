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

if($cart->getTotalQuantity() <= 0){
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $adresa = $_POST['adresa'];
    $mesto = $_POST['mesto'];
    $psc = $_POST['psc'];

    $shipping = (int) $_POST['shipping'];
    $payment = (int) $_POST['payment'];

    $customer = $customerRepo->create(
        firstName: $jmeno,
        lastName: $prijmeni,
        email: $email,
        phone: $telefon,
        street: $adresa,
        city: $mesto,
        zip: $psc,
    );

    $order = $orderRepo->create(
        customerId: $customer->id,
        shippingMethodId: $shipping,
        paymentMethodId: $payment,
        note: '',
        cartItems: $cart->getItems(),
    );

    $cart->clear();

    exit;
}

?>

<?php
require __DIR__ . '/partials/header.php';
?>


    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Checkout</h1>
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
                <div class="step">
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
                <form method="POST" class="checkout-form">
                    <!-- Step 1: Shipping -->
                    <div class="checkout-step active" id="step-1">
                        <h2>1. Informace o doručení</h2>
                        <div class="form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Jméno *</label>
                                    <input type="text" name="jmeno" required>
                                </div>
                                <div class="form-group">
                                    <label>Příjmení *</label>
                                    <input type="text" name="prijmeni" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" required>
                            </div>

                            <div class="form-group">
                                <label>Telefoní číslo *</label>
                                <input type="tel" name="telefon" required>
                            </div>

                            <div class="form-group">
                                <label>Adresa *</label>
                                <input type="text" name="adresa" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Město *</label>
                                    <input type="text" name="mesto" required>
                                </div>
                                <div class="form-group">
                                    <label>PSČ *</label>
                                    <input type="text" name="psc" required>
                                </div>
                            </div>


                            <div class="shipping-options">
                                <h3>Způsob doručení</h3>
                                <? foreach ($shippingMethods as $shipping):?>
                                    <div class="radio-group">
                                        <label>
                                            <input type="radio" name="shipping" value="<?= $shipping->id ?>" required>
                                            <span><?= htmlspecialchars($shipping->name) ?> (<?= htmlspecialchars($shipping->deliveryDays) ?>)</span>
                                            <span class="price"><?= htmlspecialchars($shipping->price) ?> Kč</span>
                                        </label>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Payment -->
                    <div class="checkout-step">
                        <h2>2. Informace o platbě</h2>
                        <div class="form">

                            <? foreach ($paymentMethods as $payment):?>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="payment" value="<?= $payment->id ?>" required>
                                        <span><?= htmlspecialchars($payment->name) ?></span>
                                        <span class="price"><?= htmlspecialchars($payment->price) ?> Kč</span>
                                    </label>
                                </div>
                            <? endforeach; ?>
                            <div class="form-actions">
                                <button type="submit" name="order" class="btn btn-primary" >Odeslat objednáku</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Order Summary Sidebar -->
                <aside class="order-summary">
                    <div class="summary-box">
                        <h3>Shrnutí objednávky</h3>
                        
                        <div class="summary-items">
                            <? foreach ($CartProducts as $product):?>
                                <div class="summary-item">
                                    <span><?= htmlspecialchars($product->productName) ?> × <?= htmlspecialchars($product->quantity) ?></span>
                                    <span><?= number_format($product->getTotalPrice(), 0, ',', ' ') ?> Kč</span>
                                </div>
                            <? endforeach; ?>
                        </div>
                        
                        
                        <div class="summary-line total">
                            <span>Celkem (Bez dopravy a platby):</span>
                            <span><?= number_format($cart->getTotalPrice(), 0, ',', ' ') ?> Kč</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

<?php
require __DIR__ . '/partials/footer.php';
?>