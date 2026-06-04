<tr class="cart-item">
    <td class="product-cell">
        <img src="<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->productName) ?>" class="cart-product-image">
        <div>
            <a href="produkt.php?id=<?= htmlspecialchars($product->productId)?>"><?= htmlspecialchars($product->productName) ?></a>
            <p class="variant"><?= htmlspecialchars($product->variant) ?></p>
        </div>
    </td>
    <td><?= number_format($product->unitPrice, 0, ',', ' ') ?> Kč</td>
    <td>
        <form method="POST">
            <div class="quantity-selector">
                <button class="qty-btn" id="minus">-</button>
                <input type="number" name="quantity" readonly value="<?= htmlspecialchars($product->quantity) ?>" class="qty-input">
                <input type="hidden" name="update_quantity" value="true">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product->productId) ?>">
                <input type="hidden" name="variant" value="<?= htmlspecialchars($product->variant) ?>">
                <button class="qty-btn" id="plus" type="button">+</button>
            </div>
        </form>
    </td>
    <td><?= number_format($product->getTotalPrice(), 0, ',', ' ') ?> Kč</td>
    <td>
        <form method="POST">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product->productId) ?>">
            <input type="hidden" name="variant" value="<?= htmlspecialchars($product->variant) ?>">
            <button type="submit" name="remove_item" class="btn-remove">Odstranit</button>
        <form>
    </td>
</tr>

<script>
    document.querySelectorAll('.quantity-selector').forEach(function (control) {
        const input = control.querySelector("input")
        const minusBtn = control.querySelector('#minus');
        const plusBtn = control.querySelector('#plus');
        const form = control.closest('form');

        function submitForm() {
            form.submit();
        }

        minusBtn.addEventListener('click', function () {
            let value = parseInt(input.value, 10) || 1;
            
            input.value = value - 1;
            submitForm();
        });

        plusBtn.addEventListener('click', function () {
            let value = parseInt(input.value, 10) || 1;
            input.value = value + 1;

            submitForm();
        });
    });
</script>