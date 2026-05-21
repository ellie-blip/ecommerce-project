<?php
// cart_sidebar.php
// Included automatically via navbar.php — appears on every page.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Robust path detection: check which connect.php actually exists on disk
if (!isset($conn)) {
    $root_path = __DIR__ . '/connect.php';
    $base_path = dirname(__DIR__) . '/connect.php';

    if (file_exists($root_path)) {
        require_once $root_path;
    } elseif (file_exists($base_path)) {
        require_once $base_path;
    }
}

// Detect if we are inside the admin subfolder for building links
$in_admin = (strpos(str_replace('\\', '/', __DIR__), '/admin') !== false);
$prefix   = $in_admin ? '../' : '';

$sidebar_items = [];
$sidebar_total = 0;
$sidebar_count = 0;

if (!empty($_SESSION['cart']) && isset($conn)) {
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $pid = (int)$pid;
        $qty = (int)$qty;
        $r   = mysqli_query($conn, "SELECT id, name, price, image FROM products WHERE id = $pid");
        if ($p = mysqli_fetch_assoc($r)) {
            $p['qty']      = $qty;
            $p['subtotal'] = $p['price'] * $qty;
            $sidebar_items[] = $p;
            $sidebar_total  += $p['subtotal'];
            $sidebar_count  += $qty;
        }
    }
}
?>

<!-- FLOATING CART BUTTON -->
<button class="cart-fab" id="cartFab" onclick="toggleCartSidebar()" aria-label="Ouvrir le panier">
    🛒
    <?php if ($sidebar_count > 0): ?>
        <span class="cart-fab-badge"><?php echo $sidebar_count; ?></span>
    <?php endif; ?>
</button>

<!-- OVERLAY -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCartSidebar()"></div>

<!-- SLIDING PANEL -->
<aside class="cart-sidebar" id="cartSidebar">

    <div class="cs-header">
        <span class="cs-label">MA SÉLECTION</span>
        <button class="cs-close" onclick="toggleCartSidebar()" aria-label="Fermer">✕</button>
    </div>

    <div class="cs-body">
        <?php if (empty($sidebar_items)): ?>
            <div class="cs-empty">
                <span>🚗</span>
                <p>Votre sélection est vide.</p>
                <a href="<?php echo $prefix; ?>cars.php" class="cs-browse-btn">
                    Parcourir la collection
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($sidebar_items as $item): ?>
                <div class="cs-item">
                    <img src="<?php echo $prefix . 'photo/' . htmlspecialchars($item['image']); ?>"
                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="cs-item-info">
                        <p class="cs-item-name"><?php echo htmlspecialchars($item['name']); ?></p>
                        <p class="cs-item-price">
                            <?php echo number_format($item['price'], 0, ',', ' '); ?> €
                            <span class="cs-item-qty">× <?php echo $item['qty']; ?></span>
                        </p>
                    </div>
                    <a href="<?php echo $prefix . 'remove_from_cart.php?id=' . $item['id']; ?>"
                       class="cs-remove" title="Supprimer">✕</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($sidebar_items)): ?>
        <div class="cs-footer">
            <div class="cs-total">
                <span>Total</span>
                <strong><?php echo number_format($sidebar_total, 0, ',', ' '); ?> €</strong>
            </div>
            <a href="<?php echo $prefix; ?>cart.php"     class="cs-btn cs-btn-secondary">Voir le panier</a>
            <a href="<?php echo $prefix; ?>checkout.php" class="cs-btn cs-btn-primary">Commander</a>
        </div>
    <?php endif; ?>

</aside>

<style>
.cart-fab {
    position: fixed;
    bottom: 36px;
    right: 36px;
    z-index: 1100;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    background-color: var(--dark);
    color: var(--cream);
    border: 2px solid var(--gold);
    font-size: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 28px rgba(18,51,43,0.35);
    transition: transform 0.25s ease, background-color 0.25s ease;
}
.cart-fab:hover {
    background-color: var(--gold);
    transform: scale(1.1);
}
.cart-fab-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background-color: #c0392b;
    color: #fff;
    font-size: 11px;
    font-weight: bold;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: Georgia, serif;
    border: 2px solid var(--cream);
}
.cart-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(18,51,43,0.45);
    z-index: 1200;
    backdrop-filter: blur(2px);
}
.cart-overlay.open { display: block; }
.cart-sidebar {
    position: fixed;
    top: 0;
    right: -420px;
    width: 400px;
    max-width: 95vw;
    height: 100vh;
    background-color: var(--cream);
    border-left: 3px solid var(--gold);
    z-index: 1300;
    display: flex;
    flex-direction: column;
    box-shadow: -10px 0 40px rgba(18,51,43,0.25);
    transition: right 0.38s cubic-bezier(0.4,0,0.2,1);
    font-family: Georgia, "Times New Roman", serif;
}
.cart-sidebar.open { right: 0; }
.cs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 22px 24px 18px;
    background-color: var(--dark);
    border-bottom: 1px solid var(--gold);
}
.cs-label {
    color: var(--gold);
    font-size: 12px;
    font-weight: bold;
    letter-spacing: 4px;
}
.cs-close {
    background: none;
    border: none;
    color: var(--cream);
    font-size: 18px;
    cursor: pointer;
    padding: 4px 8px;
    transition: color 0.2s;
}
.cs-close:hover { color: var(--gold); }
.cs-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
}
.cs-body::-webkit-scrollbar { width: 4px; }
.cs-body::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 2px; }
.cs-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--text);
}
.cs-empty span { font-size: 48px; display: block; margin-bottom: 16px; }
.cs-empty p { margin-bottom: 24px; font-size: 15px; }
.cs-browse-btn {
    display: inline-block;
    padding: 12px 28px;
    background-color: var(--dark);
    color: var(--cream);
    text-decoration: none;
    font-weight: bold;
    letter-spacing: 1px;
    transition: background-color 0.25s;
}
.cs-browse-btn:hover { background-color: var(--gold); color: var(--dark); }
.cs-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #e4ddd1;
}
.cs-item img {
    width: 70px;
    height: 52px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ddd5c7;
    flex-shrink: 0;
}
.cs-item-info { flex: 1; min-width: 0; }
.cs-item-name {
    font-size: 14px;
    font-weight: bold;
    color: var(--dark);
    margin: 0 0 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cs-item-price { font-size: 13px; color: var(--text); margin: 0; }
.cs-item-qty { color: var(--gold); font-weight: bold; margin-left: 6px; }
.cs-remove {
    color: #aaa;
    text-decoration: none;
    font-size: 13px;
    flex-shrink: 0;
    transition: color 0.2s;
    padding: 4px;
}
.cs-remove:hover { color: #c0392b; }
.cs-footer {
    padding: 20px 24px;
    border-top: 1px solid var(--gold);
    background-color: var(--white);
}
.cs-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    font-size: 16px;
    color: var(--dark);
}
.cs-total strong { color: var(--gold); font-size: 20px; }
.cs-btn {
    display: block;
    text-align: center;
    text-decoration: none;
    padding: 13px;
    font-weight: bold;
    letter-spacing: 1px;
    font-family: Georgia, serif;
    transition: 0.25s ease;
    margin-bottom: 10px;
}
.cs-btn-secondary {
    background: transparent;
    border: 1px solid var(--dark);
    color: var(--dark);
}
.cs-btn-secondary:hover { background-color: var(--dark); color: var(--cream); }
.cs-btn-primary {
    background-color: var(--dark);
    color: var(--cream);
    border: 1px solid var(--dark);
}
.cs-btn-primary:hover { background-color: var(--gold); color: var(--dark); }
</style>

<script>
function toggleCartSidebar() {
    const sidebar = document.getElementById('cartSidebar');
    const overlay = document.getElementById('cartOverlay');
    const isOpen  = sidebar.classList.contains('open');
    sidebar.classList.toggle('open', !isOpen);
    overlay.classList.toggle('open', !isOpen);
    document.body.style.overflow = isOpen ? '' : 'hidden';
}
</script>
