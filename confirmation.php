<?php
include "auth_check.php";
include "db.php";
include "header.php";

$bookingID = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$customerID = $_SESSION["CustomerID"];

$booking = null;
if ($bookingID > 0) {
    $sql = "SELECT BookingID, RequestedDate, BookingTime, FinalPrice, Status FROM bookings WHERE BookingID = ? AND CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $bookingID, $customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $booking = $result->fetch_assoc();
    }
    $stmt->close();
}
?>

<h2 class="text-success mb-4 text-center">
    Booking Confirmation
</h2>

<?php if ($booking) { ?>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow p-4 text-center">

                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                </div>

                <h4 class="text-success mb-3">Thank you for your booking!</h4>

                <p>Your booking has been confirmed. Here are your details:</p>

                <div class="alert alert-light text-start mt-3">

                    <p><strong>Booking ID:</strong> #<?php echo $booking["BookingID"]; ?></p>

                    <p><strong>Date:</strong> <?php echo htmlspecialchars($booking["RequestedDate"]); ?></p>

                    <p><strong>Time:</strong> <?php echo htmlspecialchars($booking["BookingTime"]); ?></p>

                    <p><strong>Total Price:</strong> $<?php echo number_format($booking["FinalPrice"], 2); ?></p>

                    <p><strong>Status:</strong> <span class="badge bg-success"><?php echo htmlspecialchars($booking["Status"]); ?></span></p>

                </div>

                <p class="text-muted mt-3">
                    You will receive a confirmation email shortly. Check your <a href="booking_history.php">booking history</a> for updates.
                </p>

                <a href="index.php" class="btn btn-success mt-3">Back to Home</a>

            </div>

        </div>
    </div>

<?php } else { ?>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="alert alert-warning text-center p-4">
                <p>Booking not found or access denied.</p>
                <a href="booking_history.php" class="btn btn-outline-success">View Booking History</a>
            </div>

        </div>
    </div>

<?php } ?>

<?php include "footer.php"; ?>