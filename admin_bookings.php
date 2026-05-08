<?php
include "admin_check.php";
include "db.php";
include "header.php";

// Use explicit JOIN query instead of relying on a view which may be missing
$sql = "SELECT b.BookingID, c.FirstName, c.LastName, c.Email, b.RequestedDate, b.BookingTime, b.Status, b.FinalPrice, b.ServiceProvider
        FROM bookings b
        LEFT JOIN customers c ON b.CustomerID = c.CustomerID
        ORDER BY b.BookingID DESC";

// Handle booking updates from admin
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "update_booking") {
    $bookingId = intval($_POST["BookingID"] ?? 0);
    $newStatus = trim($_POST["Status"] ?? "");
    $provider = trim($_POST["ServiceProvider"] ?? "");

    if ($bookingId > 0 && $newStatus !== "") {
        $update = $conn->prepare("UPDATE bookings SET Status = ?, ServiceProvider = ? WHERE BookingID = ?");
        $update->bind_param("ssi", $newStatus, $provider, $bookingId);
        $update->execute();
        $update->close();
        $actionMsg = "Booking #$bookingId updated.";
    } else {
        $actionMsg = "Invalid update data.";
    }
}

$result = $conn->query($sql);

if ($result === false) {
    echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($conn->error) . '</div>';
    $result = null;
}
?>

<h2 class="text-success mb-4">
    Manage Bookings
</h2>

<?php if (isset($actionMsg)) { ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($actionMsg); ?></div>
<?php } ?>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Price</th>
                <th>Provider</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($row = $result->fetch_assoc()) { ?>

                <tr>
                    <td><?php echo $row["BookingID"]; ?></td>

                    <td>
                        <?php echo htmlspecialchars($row["FirstName"] . " " . $row["LastName"]); ?>
                    </td>

                    <td><?php echo htmlspecialchars($row["Email"]); ?></td>

                    <td><?php echo $row["RequestedDate"]; ?></td>

                    <td><?php echo $row["BookingTime"]; ?></td>

                    <td>
                        <form method="post" class="d-flex flex-column flex-sm-row align-items-start align-sm-items-center gap-2">
                            <input type="hidden" name="action" value="update_booking">
                            <input type="hidden" name="BookingID" value="<?php echo $row["BookingID"]; ?>">
                            <select name="Status" class="form-select form-select-sm" style="min-width:120px;">
                                <?php $statuses = ["Pending", "Confirmed", "In Progress", "Completed", "Cancelled"]; ?>
                                <?php foreach ($statuses as $s) { ?>
                                    <option value="<?php echo $s; ?>" <?php if ($row["Status"] === $s) echo 'selected'; ?>><?php echo $s; ?></option>
                                <?php } ?>
                            </select>
                            <input type="text" name="ServiceProvider" value="<?php echo htmlspecialchars($row["ServiceProvider"]); ?>" placeholder="Provider" class="form-control form-control-sm" style="min-width:120px;">
                            <button class="btn btn-sm btn-primary" type="submit">Save</button>
                        </form>
                    </td>

                    <td>
                        $<?php echo number_format($row["FinalPrice"], 2); ?>
                    </td>

                    <td><?php echo htmlspecialchars($row["ServiceProvider"]); ?></td>
                </tr>

            <?php } ?>

        </tbody>

    </table>
</div>

<?php include "footer.php"; ?>