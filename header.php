<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Green Lawn Fargo</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- CSS Files -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    if ($currentPage === 'about.php') {
        echo '<link rel="stylesheet" href="about.css">';
    }
    ?>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">

        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand fw-bold"
                href="index.php">

                Green Lawn Fargo

            </a>

            <!-- Mobile Toggle -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu">

                <span class="navbar-toggler-icon"></span>

            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse"
                id="navbarMenu">

                <ul class="navbar-nav ms-auto">

                    <!-- Public Links -->
                    <li class="nav-item">
                        <a class="nav-link"
                            href="index.php">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                            href="about.php">
                            About
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                            href="services.php">
                            Services
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                            href="contact.php">
                            Contact
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                            href="feedback.php">
                            Feedback
                        </a>
                    </li>

                    <!-- Customer Logged In -->
                    <?php if (isset($_SESSION["CustomerID"])) { ?>

                        <li class="nav-item">
                            <a class="nav-link"
                                href="request_service.php">
                                Request Service
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                                href="booking_history.php">
                                Booking History
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                                href="profile.php">
                                Profile
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                                href="logout.php">
                                Logout
                            </a>
                        </li>

                    <?php } else { ?>

                        <!-- Guest Links -->
                        <li class="nav-item">
                            <a class="nav-link"
                                href="register.php">
                                Register
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                                href="login.php">
                                Login
                            </a>
                        </li>

                    <?php } ?>

                    <!-- Admin Links -->
                    <?php if (isset($_SESSION["AdminID"])) { ?>

                        <li class="nav-item dropdown">

                            <a
                                class="nav-link dropdown-toggle fw-bold text-warning"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown">
                                Admin
                            </a>

                            <ul class="dropdown-menu">

                                <li>
                                    <a class="dropdown-item"
                                        href="admin_dashboard.php">
                                        Dashboard
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item"
                                        href="admin_services.php">
                                        Manage Services
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item"
                                        href="admin_bookings.php">
                                        Manage Bookings
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item"
                                        href="logout.php">
                                        Logout
                                    </a>
                                </li>

                            </ul>

                        </li>

                    <?php } else { ?>

                        <li class="nav-item">
                            <a class="nav-link fw-bold text-warning"
                                href="admin_login.php">
                                Admin
                            </a>
                        </li>

                    <?php } ?>

                </ul>

            </div>

        </div>

    </nav>

    <main class="container my-5">