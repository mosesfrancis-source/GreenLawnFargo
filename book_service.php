<?php
include "auth_check.php";
include "db.php";

$message = "";
$customerID = $_SESSION["CustomerID"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $serviceID = $_POST["ServiceID"];
    $requestedDate = $_POST["RequestedDate"];
    $bookingTime = $_POST["BookingTime"];
    $serviceProvider = "Not Assigned";
    $status = "Pending";

    $sql = "SELECT BasePrice, Name
            FROM services
            WHERE ServiceID = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $message = "Unable to load the selected service.";
    } else {
        $stmt->bind_param("i", $serviceID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $service = $result->fetch_assoc();

            $finalPrice = $service["BasePrice"];

            $insertSql = "INSERT INTO bookings
                          (CustomerID, RequestID, FinalPrice, RequestedDate, BookingTime, Status, ServiceProvider)
                          VALUES (?, ?, ?, ?, ?, ?, ?)";

            $insertStmt = $conn->prepare($insertSql);

            if (!$insertStmt) {
                $message = "Unable to save your booking right now.";
            } else {
                $insertStmt->bind_param(
                    "iidssss",
                    $customerID,
                    $serviceID,
                    $finalPrice,
                    $requestedDate,
                    $bookingTime,
                    $status,
                    $serviceProvider
                );

                if ($insertStmt->execute()) {

                    $bookingID = $insertStmt->insert_id;

                    $confirmMessage = "Your booking has been submitted successfully and is pending review.";

                    $confirmSql = "INSERT INTO confirmations
                                   (BookingID, Message, SentEmail)
                                   VALUES (?, ?, ?)";

                    $sentEmail = 0;

                    $confirmStmt = $conn->prepare($confirmSql);
                    if ($confirmStmt) {
                        $confirmStmt->bind_param("isi", $bookingID, $confirmMessage, $sentEmail);
                        $confirmStmt->execute();
                        $confirmStmt->close();
                    }

                    header("Location: confirmation.php?id=" . $bookingID);
                    exit();
                } else {
                    $message = "Booking failed.";
                }

                $insertStmt->close();
            }
        } else {
            $message = "Invalid request selected.";
        }

        $stmt->close();
    }
}

include "header.php";

$sql = "SELECT ServiceID, Name, Description, BasePrice, BaseDuration
        FROM services
        ORDER BY Name ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$services = $stmt->get_result();
?>

<h2 class="text-success mb-4">Book a Service</h2>

<?php if ($message != "") { ?>
    <div class="alert alert-info">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php } ?>

<div class="card shadow p-4">

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Select Service</label>

            <select name="ServiceID" class="form-select" required>
                <option value="">Choose Service</option>

                <?php while ($row = $services->fetch_assoc()) { ?>
                    <option value="<?php echo $row["ServiceID"]; ?>">
                        <?php echo htmlspecialchars($row["Name"]); ?>
                        | $<?php echo number_format($row["BasePrice"], 2); ?>
                        | <?php echo htmlspecialchars($row["BaseDuration"]); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Requested Date</label>
            <input type="date" name="RequestedDate" class="form-control" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Booking Time</label>
            <input type="time" name="BookingTime" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">
            Confirm Booking
        </button>

    </form>

</div>

<?php include "footer.php"; ?>