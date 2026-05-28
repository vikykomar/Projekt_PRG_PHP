<?php
require_once __DIR__ . '/src/bootstrap.php';

$productRepo = new ProductRepository();
$cart = new Cart();

$featuredProducts = $productRepo->getFeatured(limit: 6);
?>

<?php
require __DIR__ . '/partials/header.php';
?>

    <section class="featured-products">
        <div class="container">
            <h2>Doporučené produkty</h2>
            <div class="products-grid">
                <?php 
                    foreach ($featuredProducts as $product):
                        require __DIR__ . '/partials/product-card.php';
                    endforeach;
                ?>
            </div>
        </div>
    </section>

<?php
require __DIR__ . '/partials/footer.php';
?>