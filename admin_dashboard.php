<?php
include "admin_check.php";
include "db.php";
include "header.php";

$customerCount =
    $conn->query("SELECT COUNT(*) AS total FROM customers")
        ->fetch_assoc()["total"];

$serviceCount =
    $conn->query("SELECT COUNT(*) AS total FROM services")
        ->fetch_assoc()["total"];

$requestCount =
    $conn->query("SELECT COUNT(*) AS total FROM service_requests")
        ->fetch_assoc()["total"];

$bookingCount =
    $conn->query("SELECT COUNT(*) AS total FROM bookings")
        ->fetch_assoc()["total"];
?>

<h2 class="text-success mb-4">
    Admin Dashboard
</h2>

<div class="row">

    <div class="col-md-3 mb-4">

        <div class="card shadow text-center p-4">

            <h3><?php echo $customerCount; ?></h3>

            <p>Total Customers</p>

        </div>

    </div>

    <div class="col-md-3 mb-4">

        <div class="card shadow text-center p-4">

            <h3><?php echo $serviceCount; ?></h3>

            <p>Total Services</p>

        </div>

    </div>

    <div class="col-md-3 mb-4">

        <div class="card shadow text-center p-4">

            <h3><?php echo $requestCount; ?></h3>

            <p>Total Requests</p>

        </div>

    </div>

    <div class="col-md-3 mb-4">

        <div class="card shadow text-center p-4">

            <h3><?php echo $bookingCount; ?></h3>

            <p>Total Bookings</p>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>