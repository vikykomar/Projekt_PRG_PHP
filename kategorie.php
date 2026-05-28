<?php
require_once __DIR__ . '/src/bootstrap.php';

$categoryRepo = new CategoryRepository();
$productRepo = new ProductRepository();
$cart = new Cart();

$categories = $categoryRepo->getAll();

$slug = trim($_GET['slug'] ?? '');

$nactenakategorie = $slug === '' ? $categoryRepo->getById(1) : $categoryRepo->getBySlug($slug);

if ($nactenakategorie === null) {
    http_response_code(404);
    $pageTitle = 'Kategorie nenalezena';
    $cartItemCount = $cart->getTotalQuantity();
    require __DIR__ . '/partials/header.php';
    echo '<main class="container"><h1>Kategorie nenalezena</h1><p>Zkuste se vrátit na <a href="index.php">hlavní stránku</a>.</p></main>';
    require __DIR__ . '/partials/footer.php';
    exit;
}

$nactenakategorieid = $nactenakategorie -> id;

$nacteneprodukty = $productRepo->getByCategory($nactenakategorieid);
?>

<?php
require __DIR__ . '/partials/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Shop by Category</h1>
        </div>
    </section>

    <section class="categories-section">
        <div class="container">
            <div class="categories-layout">
                <aside class="sidebar">
                    <div class="category-filter">
                        <h3>Kategorie</h3>
                        <ul class="category-list">
                            <? foreach ($categories as $kategorie): ?>
                                <li><a href="?slug=<?= htmlspecialchars($kategorie->slug)?>" <?php if($nactenakategorieid === $kategorie->id): ?>class="active"<? endif;?>><?= htmlspecialchars($kategorie->name) ?></a></li>
                            <? endforeach;?>
                        </ul>
                    </div>
                </aside>

                <main class="products-main">
                    <div class="products-grid">
                        <?php 
                            foreach ($nacteneprodukty as $product):
                                require __DIR__ . '/partials/product-card.php';
                            endforeach;
                        ?>
                    </div>
                </main>
            </div>
        </div>
    </section>

<?php
require __DIR__ . '/partials/footer.php';
?>