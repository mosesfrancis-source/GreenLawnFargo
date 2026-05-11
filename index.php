<?php
session_start();
include 'db.php';
?>

<?php include 'header.php'; ?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Welcome to Green Lawn Fargo</h2>
                <p>Professional Lawn Care & Landscaping Services</p>
                <a href="./book_service.php" class="cta-button">Book a Service Today</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h3>🔪 Lawn Mowing</h3>
                    <p>Professional lawn mowing service keeping your grass at the perfect height. We use the latest equipment for a clean, even cut every time.</p>
                </div>
                <div class="service-card">
                    <h3>🌿 Landscaping</h3>
                    <p>Transform your outdoor space with our expert landscaping design and installation services. Create your dream yard today.</p>
                </div>
                <div class="service-card">
                    <h3>🪴 Garden Maintenance</h3>
                    <p>Keep your gardens thriving with our comprehensive maintenance packages including weeding, trimming, and seasonal care.</p>
                </div>
                <div class="service-card">
                    <h3>🌳 Tree Care</h3>
                    <p>Professional tree trimming, pruning, and removal services to keep your property safe and beautiful.</p>
                </div>
                <div class="service-card">
                    <h3>🧹 Yard Cleanup</h3>
                    <p>Complete yard cleanup services including leaf removal, debris clearing, and seasonal preparation.</p>
                </div>
                <div class="service-card">
                    <h3>❄️ Snow Removal</h3>
                    <p>Winter-ready snow and ice removal services to keep your driveway and walkways safe and accessible.</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 3rem;">
                <a href="./services.php" class="cta-button">View All Services</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Why Choose Us?</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <h4>✨ Expert Team</h4>
                    <p>Our experienced professionals have years of expertise in lawn care and landscaping.</p>
                </div>
                <div class="feature-item">
                    <h4>🎯 Quality Service</h4>
                    <p>We pride ourselves on delivering exceptional service and attention to detail on every project.</p>
                </div>
                <div class="feature-item">
                    <h4>💰 Affordable Pricing</h4>
                    <p>Competitive rates without compromising on quality. Get a free estimate today!</p>
                </div>
                <div class="feature-item">
                    <h4>📞 24/7 Support</h4>
                    <p>Questions? We're here to help. Reach out to us anytime for more information.</p>
                </div>
                <div class="feature-item">
                    <h4>🚀 Quick Booking</h4>
                    <p>Easy online booking system. Schedule your service in just a few clicks.</p>
                </div>
                <div class="feature-item">
                    <h4>🌱 Eco-Friendly</h4>
                    <p>Sustainable practices that care for your lawn and the environment.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="hero" style="padding: 60px 20px;">
        <div class="container">
            <h2>Ready to Transform Your Lawn?</h2>
            <p>Get a free estimate or book your first service today!</p>
            <?php if (isset($_SESSION['CustomerID'])): ?>
                <a href="./book_service.php" class="cta-button">Book Now</a>
            <?php elseif (isset($_SESSION['AdminID'])): ?>
                <a href="./admin_dashboard.php" class="cta-button">Admin Dashboard</a>
            <?php else: ?>
                <a href="./register.php" class="cta-button" style="margin-right: 1rem;">Sign Up Now</a>
                <a href="./login.php" class="cta-button" style="background-color: transparent; border: 2px solid var(--accent-color);">Login</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>