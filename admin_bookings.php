<?php
include "admin_check.php";
include "db.php";
include "header.php";

$sql = "
SELECT *
FROM vw_booking_summary
ORDER BY BookingID DESC
";

$result = $conn->query($sql);
?>

<h2 class="text-success mb-4">
    Manage Bookings
</h2>

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
                    <span class="badge bg-success">
                        <?php echo htmlspecialchars($row["Status"]); ?>
                    </span>
                </td>

                <td>
                    $<?php echo number_format($row["FinalPrice"], 2); ?>
                </td>

                <td><?php echo htmlspecialchars($row["ServiceProvider"]); ?></td>
            </tr>

        <?php } ?>

    </tbody>

</table>

<?php include "footer.php"; ?>