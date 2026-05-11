<?php
include "auth_check.php";
include "db.php";

$message = "";
$customerID = $_SESSION["CustomerID"];
$selectedRequestID = isset($_GET["request"]) ? intval($_GET["request"]) : 0;
$selectedRequest = null;
$requestSql = "
    SELECT
        sr.RequestID,
        sr.YardSize,
        sr.PreferredDate,
        sr.Notes,
        sr.BaseTotal,
        sr.DiscountPercent,
        sr.FinalEstimate,
        sr.Status,
        GROUP_CONCAT(s.Name ORDER BY s.Name SEPARATOR ', ') AS Services
    FROM service_requests sr
    LEFT JOIN service_request_items sri ON sr.RequestID = sri.RequestID
    LEFT JOIN services s ON sri.ServiceID = s.ServiceID
    WHERE sr.RequestID = ?
      AND sr.CustomerID = ?
      AND NOT EXISTS (
          SELECT 1
          FROM bookings b
          WHERE b.RequestID = sr.RequestID
      )
    GROUP BY
        sr.RequestID,
        sr.YardSize,
        sr.PreferredDate,
        sr.Notes,
        sr.BaseTotal,
        sr.DiscountPercent,
        sr.FinalEstimate,
        sr.Status
";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $requestID = intval($_POST["RequestID"] ?? 0);
    $requestedDate = $_POST["RequestedDate"];
    $bookingTime = $_POST["BookingTime"];
    $serviceProvider = "Not Assigned";
    $status = "Pending";

    if ($requestID <= 0) {
        $message = "Please select a service request to book.";
    } else {
        $stmt = $conn->prepare($requestSql);

        if (!$stmt) {
            $message = "Unable to load the selected service request.";
        } else {
            $stmt->bind_param("ii", $requestID, $customerID);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows == 1) {

                $request = $result->fetch_assoc();
                $finalPrice = $request["FinalEstimate"];
                $bookingDate = trim($requestedDate) !== "" ? $requestedDate : $request["PreferredDate"];

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
                        $requestID,
                        $finalPrice,
                        $bookingDate,
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

                        $markBookedStmt = $conn->prepare("UPDATE service_requests SET Status = 'Booked' WHERE RequestID = ? AND CustomerID = ?");
                        if ($markBookedStmt) {
                            $markBookedStmt->bind_param("ii", $requestID, $customerID);
                            $markBookedStmt->execute();
                            $markBookedStmt->close();
                        }

                        header("Location: confirmation.php?id=" . $bookingID);
                        exit();
                    } else {
                        $message = "Booking failed.";
                    }

                    $insertStmt->close();
                }
            }

            if ($message === "") {
                $message = "That service request is already booked or unavailable.";
            }

            $stmt->close();
        }
    }
}

if ($selectedRequestID > 0) {
    $selectedStmt = $conn->prepare($requestSql);

    if ($selectedStmt) {
        $selectedStmt->bind_param("ii", $selectedRequestID, $customerID);
        $selectedStmt->execute();
        $selectedResult = $selectedStmt->get_result();
        if ($selectedResult && $selectedResult->num_rows === 1) {
            $selectedRequest = $selectedResult->fetch_assoc();
        }
        $selectedStmt->close();
    }
}

$availableRequestsSql = "
    SELECT
        sr.RequestID,
        sr.YardSize,
        sr.PreferredDate,
        sr.BaseTotal,
        sr.DiscountPercent,
        sr.FinalEstimate,
        sr.Status,
        GROUP_CONCAT(s.Name ORDER BY s.Name SEPARATOR ', ') AS Services
    FROM service_requests sr
    LEFT JOIN service_request_items sri ON sr.RequestID = sri.RequestID
    LEFT JOIN services s ON sri.ServiceID = s.ServiceID
    WHERE sr.CustomerID = ?
      AND NOT EXISTS (
          SELECT 1
          FROM bookings b
          WHERE b.RequestID = sr.RequestID
      )
    GROUP BY
        sr.RequestID,
        sr.YardSize,
        sr.PreferredDate,
        sr.BaseTotal,
        sr.DiscountPercent,
        sr.FinalEstimate,
        sr.Status
    ORDER BY sr.RequestID DESC
";

$availableStmt = $conn->prepare($availableRequestsSql);
$availableRequests = null;
if ($availableStmt) {
    $availableStmt->bind_param("i", $customerID);
    $availableStmt->execute();
    $availableRequests = $availableStmt->get_result();
}

include "header.php";
?>

<h2 class="text-success mb-4">Book a Service Request</h2>

<?php if ($message != "") { ?>
    <div class="alert alert-info">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php } ?>

<?php if ($selectedRequest) { ?>
    <div class="card shadow p-4 mb-4">
        <h4 class="text-success mb-3">Request Preview</h4>

        <div class="row g-3">
            <div class="col-md-4"><strong>Request ID:</strong> #<?php echo $selectedRequest["RequestID"]; ?></div>
            <div class="col-md-4"><strong>Yard Size:</strong> <?php echo htmlspecialchars($selectedRequest["YardSize"]); ?></div>
            <div class="col-md-4"><strong>Preferred Date:</strong> <?php echo htmlspecialchars($selectedRequest["PreferredDate"]); ?></div>
            <div class="col-md-12"><strong>Services:</strong> <?php echo htmlspecialchars($selectedRequest["Services"] ?? "No services listed"); ?></div>
            <div class="col-md-12"><strong>Notes:</strong> <?php echo htmlspecialchars($selectedRequest["Notes"] ?: "No notes provided."); ?></div>
            <div class="col-md-4"><strong>Base Total:</strong> $<?php echo number_format($selectedRequest["BaseTotal"], 2); ?></div>
            <div class="col-md-4"><strong>Discount:</strong> <?php echo number_format($selectedRequest["DiscountPercent"], 2); ?>%</div>
            <div class="col-md-4"><strong>Estimate:</strong> $<?php echo number_format($selectedRequest["FinalEstimate"], 2); ?></div>
        </div>
    </div>
<?php } ?>

<div class="card shadow p-4 mb-4">
    <h4 class="text-success mb-3">Available Requests to Book</h4>

    <?php if ($availableRequests && $availableRequests->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Request ID</th>
                        <th>Yard Size</th>
                        <th>Preferred Date</th>
                        <th>Services</th>
                        <th>Estimate</th>
                        <th>Preview</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $availableRequests->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["RequestID"]; ?></td>
                            <td><?php echo htmlspecialchars($row["YardSize"]); ?></td>
                            <td><?php echo htmlspecialchars($row["PreferredDate"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Services"] ?? "No services listed"); ?></td>
                            <td>$<?php echo number_format($row["FinalEstimate"], 2); ?></td>
                            <td>
                                <a href="book_service.php?request=<?php echo $row["RequestID"]; ?>" class="btn btn-sm btn-outline-success">
                                    Preview &amp; Book
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info mb-0">
            You do not have any unbooked service requests right now.
        </div>
    <?php } ?>
</div>

<div class="card shadow p-4">

    <?php if ($selectedRequest) { ?>
        <form method="POST">
            <input type="hidden" name="RequestID" value="<?php echo $selectedRequest["RequestID"]; ?>">

            <div class="mb-3">
                <label class="form-label">Requested Date</label>
                <input type="date" name="RequestedDate" class="form-control" value="<?php echo htmlspecialchars($selectedRequest["PreferredDate"]); ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Booking Time</label>
                <input type="time" name="BookingTime" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Confirm Booking</button>
            <a href="book_service.php" class="btn btn-outline-secondary ms-2">Choose Another Request</a>
        </form>
    <?php } else { ?>
        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Select Service Request</label>

                <select name="RequestID" class="form-select" required>
                    <option value="">Choose Request</option>

                    <?php if ($availableRequests) {
                        while ($row = $availableRequests->fetch_assoc()) { ?>
                            <option value="<?php echo $row["RequestID"]; ?>">
                                Request #<?php echo $row["RequestID"]; ?>
                                | <?php echo htmlspecialchars($row["YardSize"]); ?>
                                | <?php echo htmlspecialchars($row["PreferredDate"]); ?>
                                | $<?php echo number_format($row["FinalEstimate"], 2); ?>
                            </option>
                        <?php } ?>
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
    <?php } ?>

</div>

<?php
if (isset($availableStmt) && $availableStmt) {
    $availableStmt->close();
}
?>

<?php include "footer.php"; ?>