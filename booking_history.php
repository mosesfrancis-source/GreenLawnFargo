<?php
include "auth_check.php";
include "db.php";
include "header.php";

$customerID = $_SESSION["CustomerID"];

$sql = "
SELECT
    b.BookingID,
    b.FinalPrice,
    b.RequestedDate,
    b.BookingTime,
    b.Status,
    b.ServiceProvider,
    c.Message
FROM bookings b
LEFT JOIN confirmations c
ON b.BookingID = c.BookingID
WHERE b.CustomerID = ?
ORDER BY b.BookingID DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2 class="text-success mb-4">
    My Booking History
</h2>

<div class="card shadow p-4">

    <?php if ($result->num_rows === 0) { ?>
        <div class="alert alert-info">
            <p>You have no bookings yet. <a href="book_service.php">Book a service now</a>!</p>
        </div>
    <?php } else { ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Booking ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Final Price</th>
                        <th>Provider</th>
                        <th>Confirmation</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while ($row = $result->fetch_assoc()) { ?>

                        <tr>
                            <td><?php echo $row["BookingID"]; ?></td>

                            <td><?php echo htmlspecialchars($row["RequestedDate"]); ?></td>

                            <td><?php echo htmlspecialchars($row["BookingTime"]); ?></td>

                            <td>
                                <span class="badge bg-success">
                                    <?php echo htmlspecialchars($row["Status"]); ?>
                                </span>
                            </td>

                            <td>
                                $<?php echo number_format($row["FinalPrice"], 2); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["ServiceProvider"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["Message"] ?? "No confirmation yet"); ?>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>

    <?php } ?>

</div>

<?php
$stmt->close();
include "footer.php";
?>