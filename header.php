<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$displayName = "";
$profileImage = "images/profile-placeholder.png";

if (isset($_SESSION["FirstName"])) {
    $displayName = $_SESSION["FirstName"];
}

// Find customer's profile image if they're logged in
if (isset($_SESSION["CustomerID"])) {
    $customerID = $_SESSION["CustomerID"];
    $possibleExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
    foreach ($possibleExtensions as $ext) {
        $path = "images/profiles/customer_" . $customerID . "." . $ext;
        if (file_exists($path)) {
            $profileImage = $path;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Green Lawn Fargo</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
        <div class="container">

            <a class="navbar-brand fw-bold" href="index.php">
                Green Lawn Fargo
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu"
                aria-controls="navbarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="feedback.php">Feedback</a>
                    </li>

                    <?php if (isset($_SESSION["CustomerID"])) { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="request_service.php">
                                Request Service
                            </a>
                        </li>

                        <li class="nav-item dropdown">

                            <a
                                class="nav-link dropdown-toggle d-flex align-items-center"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img
                                    src="<?php echo htmlspecialchars($profileImage); ?>"
                                    alt="Profile"
                                    width="35"
                                    height="35"
                                    class="rounded-circle me-2 border border-light"
                                    style="object-fit: cover;">

                                <?php echo htmlspecialchars($displayName); ?>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="profile.php">
                                        My Profile
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="booking_history.php">
                                        Booking History
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="calendar.php">
                                        Calendar
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-danger" href="logout.php">
                                        Logout
                                    </a>
                                </li>

                            </ul>

                        </li>

                    <?php } else { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                Register
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                Login
                            </a>
                        </li>

                    <?php } ?>

                    <?php if (isset($_SESSION["AdminID"])) { ?>

                        <li class="nav-item dropdown">

                            <a
                                class="nav-link dropdown-toggle fw-bold text-warning"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Admin
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="admin_dashboard.php">
                                        Dashboard
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="admin_services.php">
                                        Manage Services
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="admin_bookings.php">
                                        Manage Bookings
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="admin_feedback.php">
                                        View Feedback
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-danger" href="logout.php">
                                        Admin Logout
                                    </a>
                                </li>

                            </ul>

                        </li>

                    <?php } else { ?>

                        <li class="nav-item">
                            <a class="nav-link fw-bold text-warning" href="admin_login.php">
                                Admin
                            </a>
                        </li>

                    <?php } ?>

                </ul>

            </div>

        </div>
    </nav>

    <main class="container my-5">