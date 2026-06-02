<?php
require_once __DIR__ . '/src/bootstrap.php';

$productRepo = new ProductRepository();
$cart = new Cart();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = (int) $_POST['product_id'];
    $product = $productRepo->getById($productId);

    if ($product !== null) {
        $variant = '';
        if (isset($_POST['variants']) && is_array($_POST['variants'])) {
            $parts = $_POST['variants'];
            ksort($parts);
            $variantParts = [];
            foreach ($parts as $paramName => $paramValue) {
                $variantParts[] = $paramName . ': ' . $paramValue;
            }
            $variant = implode(', ', $variantParts);
        }

        $cart->add(
            productId: $product->id,
            productName: $product->name,
            unitPrice: $product->price,
            image: $product->image,
            variant: $variant,
        );
        
    }

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$slug = trim($_GET['slug'] ?? '');
$id = trim($_GET['id'] ?? '');
$product = $slug !== '' ? $productRepo->getBySlug($slug) : ($id !== '' ? $productRepo->getById($id) : null);

if ($product === null) {
    http_response_code(404);
    $pageTitle = 'Produkt nenalezen';
    $cartItemCount = $cart->getTotalQuantity();
    require __DIR__ . '/partials/header.php';
    echo '<main class="container"><h1>Produkt nenalezen</h1><p>Zkuste se vrátit na <a href="index.php">hlavní stránku</a>.</p></main>';
    require __DIR__ . '/partials/footer.php';
    exit;
}

$images = $productRepo->getImages($product->id);
$params = $productRepo->getParameters($product->id);

$selectableParams = array_filter($params, fn(ProductParameterDTO $p) => $p->isSelectable());
$infoParams = array_filter($params, fn(ProductParameterDTO $p) => !$p->isSelectable());

$pageTitle = $product->name . ' – SportShop';
$cartItemCount = $cart->getTotalQuantity();
?>

<?php
require __DIR__ . '/partials/header.php';
?>

    <section class="product-details">
        <div class="container">
            <div class="product-detail-layout">
                <!-- Product Images -->
                <div class="product-images">
                    <div class="main-image">
                        <img src="<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->name) ?>">
                    </div>
                    <?php if ($images !== []): ?>
                        <div class="thumbnail-images">
                            <?php foreach ($images as $img): ?>
                                <img
                                    src="<?= htmlspecialchars($img->image) ?>"
                                    alt="<?= htmlspecialchars($product->name) ?>"
                                    class="thumbnail"
                                >
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-details-info">
                    <h1>Premium Product Name</h1>
                    <p class="category-badge"><?= htmlspecialchars($product->categoryName ?? '') ?></p>

                    <div class="price-section">
                        <p class="price"><?= number_format($product->price, 0, ',', ' ') ?> Kč</p>
                    </div>

                    <p class="description"><?= htmlspecialchars($product->description) ?></p>
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= $product->id ?>">
                    <div>
                        <?php foreach ($selectableParams as $param):?>
                            <div class="option-group">
                                <label for="variant-<?= htmlspecialchars($param->name) ?>"><?= htmlspecialchars($param->name) ?>:</label>
                                <select class="option-select" name="variants[<?= htmlspecialchars($param->name) ?>]" id="variant-<?= htmlspecialchars($param->name) ?>" required>
                                    <option value="">-- Vyberte --</option>
                                    <?php foreach ($param->getOptions() as $option): ?>
                                        <option value="<?= htmlspecialchars($option) ?>">
                                            <?= htmlspecialchars($option) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach;?>
                    </div>

                    <div class="product-actions">
                        <button class="btn btn-primary btn-lg" name="add_to_cart" type="submit">Přidat do košíku</button>
                    </div>
                    </form>
                    <div class="product-meta">
                        <?php foreach ($infoParams as $param): ?>
                            <p><strong><?= htmlspecialchars($param->name) ?>:</strong> <?= htmlspecialchars($param->value) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
    </section>

<?php
require __DIR__ . '/partials/footer.php';
?>