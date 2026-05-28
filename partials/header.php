<?php
$pageTitle ??= 'Super eshop';
$cartItemCount ??= 0;

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<header class="header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="/" class="logo">Super eshop</a>
            </div>
            <ul class="navbar-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="kategorie.php">Kategorie</a></li>
                <li><a href="about.html">O Nás</a></li>
                <li><a href="cart.html" class="cart-link">🛒 Košík <span class="cart-count"><?= $cartItemCount ?></span></a></li>
            </ul>
        </div>
    </nav>
</header>
