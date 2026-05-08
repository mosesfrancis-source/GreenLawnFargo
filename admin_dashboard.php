<?php
include "admin_check.php";
include "db.php";
include "header.php";

// Handle admin actions (update request status)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "update_request_status") {
    $rid = intval($_POST["RequestID"]);
    $newStatus = trim($_POST["Status"]);
    if ($rid > 0 && $newStatus !== "") {
        $u = $conn->prepare("UPDATE service_requests SET Status = ? WHERE RequestID = ?");
        $u->bind_param("si", $newStatus, $rid);
        $u->execute();
        $u->close();
        $actionMessage = "Request #$rid status updated to $newStatus.";
    }
}

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

// Recent items for dashboard quick actions
$recentRequests = $conn->query(
    "SELECT sr.RequestID, sr.PreferredDate, sr.FinalEstimate, sr.Status, c.FirstName, c.LastName
     FROM service_requests sr
     LEFT JOIN customers c ON sr.CustomerID = c.CustomerID
     ORDER BY sr.RequestID DESC
     LIMIT 8"
);

$recentBookings = $conn->query(
    "SELECT BookingID, RequestedDate, BookingTime, FinalPrice, Status
     FROM bookings
     ORDER BY BookingID DESC
     LIMIT 8"
);
?>

<h2 class="text-success mb-4">
    Admin Dashboard
</h2>

<div class="row">

    <div class="col-6 col-md-3 mb-4">

        <div class="card shadow text-center p-3 p-md-4">

            <h3><?php echo $customerCount; ?></h3>

            <p class="mb-0">Customers</p>

        </div>

    </div>

    <div class="col-6 col-md-3 mb-4">

        <div class="card shadow text-center p-3 p-md-4">

            <h3><?php echo $serviceCount; ?></h3>

            <p class="mb-0">Services</p>

        </div>

    </div>

    <div class="col-6 col-md-3 mb-4">

        <div class="card shadow text-center p-3 p-md-4">

            <h3><?php echo $requestCount; ?></h3>

            <p class="mb-0">Requests</p>

        </div>

    </div>

    <div class="col-6 col-md-3 mb-4">

        <div class="card shadow text-center p-3 p-md-4">

            <h3><?php echo $bookingCount; ?></h3>

            <p class="mb-0">Bookings</p>

        </div>

    </div>

</div>

<?php if (isset($actionMessage)) { ?>
    <div class="alert alert-info mt-3"><?php echo htmlspecialchars($actionMessage); ?></div>
<?php } ?>

<div class="row mt-4">

    <div class="col-lg-6 mb-4">

        <div class="card shadow p-3">

            <h4 class="mb-3">Recent Service Requests</h4>

            <?php if ($recentRequests && $recentRequests->num_rows > 0) { ?>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Estimate</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($r = $recentRequests->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $r["RequestID"]; ?></td>
                                    <td><?php echo htmlspecialchars(trim($r["FirstName"] . ' ' . $r["LastName"])); ?></td>
                                    <td><?php echo $r["PreferredDate"]; ?></td>
                                    <td>$<?php echo number_format($r["FinalEstimate"], 2); ?></td>
                                    <td><?php echo htmlspecialchars($r["Status"]); ?></td>
                                    <td>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="action" value="update_request_status">
                                            <input type="hidden" name="RequestID" value="<?php echo $r["RequestID"]; ?>">
                                            <select name="Status" class="form-select form-select-sm d-inline-block" style="width:120px;">
                                                <option value="Pending">Pending</option>
                                                <option value="Reviewed">Reviewed</option>
                                                <option value="Booked">Booked</option>
                                                <option value="Rejected">Rejected</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary ms-1">Update</button>
                                        </form>
                                        <a href="estimate.php?request=<?php echo $r["RequestID"]; ?>" class="btn btn-sm btn-outline-secondary ms-1">View</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            <?php } else { ?>
                <p class="text-muted">No recent requests.</p>
            <?php } ?>

        </div>

    </div>

    <div class="col-lg-6 mb-4">

        <div class="card shadow p-3">

            <h4 class="mb-3">Recent Bookings</h4>

            <?php if ($recentBookings && $recentBookings->num_rows > 0) { ?>

                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($b = $recentBookings->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $b["BookingID"]; ?></td>
                                    <td><?php echo htmlspecialchars($b["RequestedDate"]); ?></td>
                                    <td><?php echo htmlspecialchars($b["BookingTime"]); ?></td>
                                    <td>$<?php echo number_format($b["FinalPrice"], 2); ?></td>
                                    <td><?php echo htmlspecialchars($b["Status"]); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <a href="admin_bookings.php" class="btn btn-sm btn-outline-secondary">Manage Bookings</a>

            <?php } else { ?>
                <p class="text-muted">No recent bookings.</p>
            <?php } ?>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>