<?php include 'includes/header.php'; ?>

<div class="dashboard-layout">
    
    <aside class="sidebar">
        <div class="brand">Velvet<br>Vogue</div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="shop.php">Explore</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="my-orders.php">My Orders</a></li>
            <li><a href="account.php">Profile</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>

        <div class="help-box">
            <h3>Need Help?</h3>
            <p>Discover the latest fashion trends and get support anytime.</p>
            <a href="contact.php" class="btn small-btn">Customer Service</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Fashion</h1>
                <p>For your perfect style</p>
            </div>

            <div class="top-actions">
                <input type="text" placeholder="Search Product" class="search-box">
                <a href="login.php" class="icon-btn">❤</a>
                <a href="cart.php" class="icon-btn">🛒</a>
                <a href="account.php" class="icon-btn">👤</a>
            </div>
        </div>

        <div class="hero-grid">
            <div class="featured-card">
                <div class="featured-image">
                    <img src="assets/images/featured-fashion.png" alt="Featured Fashion">
                </div>
                <div class="featured-info">
                    <h2>Elegant Summer Dress</h2>
                    <p class="rating">★★★★★ <span>(200+ Reviews)</span></p>
                    <p>Premium quality outfit designed for modern fashion lovers. Stylish, comfortable, and elegant for any occasion.</p>
                    <h3 class="price">Rs. 6,500.00</h3>

                    <div class="color-dots">
                        <span class="dot black"></span>
                        <span class="dot beige"></span>
                        <span class="dot pink"></span>
                        <span class="dot green"></span>
                    </div>

                    <div class="hero-buttons">
                        <a href="shop.php" class="btn">Add to Cart</a>
                        <a href="checkout.php" class="btn secondary-btn">Buy Now</a>
                    </div>
                </div>
            </div>

           <div class="promo-card">
    <h2>Summer Collection<br>from top brands</h2>
    <p>Shop it now</p>
    <a href="shop.php" class="btn promo-btn">Explore Now</a>
    <img src="assets/images/promo-fashion.png" alt="Promo">
</div>

        <section class="product-row-section">
            <h2 class="section-heading">Trending Products</h2>
            <div class="product-row">
                <div class="mini-product-card">
                    <img src="assets/images/blazer.jpg" alt="">
                    <h3>Classic Blazer</h3>
                    <p>Price Rs. 8,500</p>
                </div>

                <div class="mini-product-card">
                    <img src="assets/images/product2.jpg" alt="">
                    <h3>Casual Jacket</h3>
                    <p>Price Rs. 4,500</p>
                </div>

                <div class="mini-product-card">
                    <img src="assets/images/handbag.jpg" alt="">
                    <h3>Luxury Handbag</h3>
                    <p>Price Rs. 5,500</p>
                </div>

                <div class="mini-product-card faded-card">
                    <img src="assets/images/shirt.jpg" alt="">
                    <h3>Modern Shirt</h3>
                    <p>Price Rs. 3,500</p>
                </div>
            </div>
        </section>

        <section class="category-boxes">
            <h2 class="section-heading">Explore Popular Categories</h2>
            <div class="category-grid">
                <div class="info-box">
                    <h3>Popular Top Brands</h3>
                    <p>400+ orders & reviews</p>
                </div>
                <div class="info-box">
                    <h3>Newest Sellers</h3>
                    <p>460+ orders & reviews</p>
                </div>
            </div>
        </section>
    </main>

    <aside class="right-panel">
        <div class="deal-list">
            <div class="panel-title">
                <h2>Daily Deals</h2>
                <a href="shop.php">View All</a>
            </div>

            <div class="deal-item">
                <img src="assets/images/product1.jpg" alt="">
                <div>
                    <h4>Formal Blazer</h4>
                    <p>Price Rs. 8,500</p>
                </div>
            </div>

            <div class="deal-item">
                <img src="assets/images/blazer.jpg" alt="">
                <div>
                    <h4>Casual Jacket</h4>
                    <p>Price Rs. 4,500</p>
                </div>
            </div>

            <div class="deal-item">
                <img src="assets/images/handbag.jpg" alt="">
                <div>
                    <h4>Luxury Handbag</h4>
                    <p>Price Rs. 5,500</p>
                </div>
            </div>

            <div class="deal-item">
                <img src="assets/images/women.jpg" alt="">
                <div>
                    <h4>Elegant Dress</h4>
                    <p>Price Rs. 6,500</p>
                </div>
            </div>
        </div>
    </aside>
</div>

<?php include 'includes/footer.php'; ?>