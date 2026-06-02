<div class="product-card">
    <div class="product-image">
        <img src="<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->name) ?>">
    </div>
    <div class="product-info">
        <h3><?= htmlspecialchars($product->name) ?></h3>
        <p class="category"><?= htmlspecialchars($product->categoryName ?? '') ?></p>
        <p class="price"><?= number_format($product->price, 0, ',', ' ') ?> Kč</p>
        <a href="produkt.php?slug=<?= htmlspecialchars($product->slug)?>" class="btn btn-secondary">Zobrazit detail</a>
    </div>
</div>