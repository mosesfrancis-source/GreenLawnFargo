<?php
include "auth_check.php";
include "db.php";
include "header.php";

$customerID = $_SESSION["CustomerID"];

$sql = "
SELECT
    sr.RequestID,
    sr.YardSize,
    sr.PreferredDate,
    sr.BaseTotal,
    sr.DiscountPercent,
    sr.FinalEstimate,
    sr.Status
FROM service_requests sr
WHERE sr.CustomerID = ?
ORDER BY sr.RequestID DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2 class="text-success mb-4">My Service Estimates</h2>

<div class="card shadow p-4">

    <?php if ($result->num_rows === 0) { ?>
        <div class="alert alert-info">
            <p>You have no service estimates yet. <a href="request_service.php">Request a service</a> to get started!</p>
        </div>
    <?php } else { ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Request ID</th>
                        <th>Yard Size</th>
                        <th>Preferred Date</th>
                        <th>Base Total</th>
                        <th>Discount</th>
                        <th>Final Estimate</th>
                        <th>Status</th>
                        <th>Book</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["RequestID"]; ?></td>
                            <td><?php echo htmlspecialchars($row["YardSize"]); ?></td>
                            <td><?php echo htmlspecialchars($row["PreferredDate"]); ?></td>
                            <td>$<?php echo number_format($row["BaseTotal"], 2); ?></td>
                            <td><?php echo number_format($row["DiscountPercent"], 2); ?>%</td>
                            <td>$<?php echo number_format($row["FinalEstimate"], 2); ?></td>
                            <td><?php echo htmlspecialchars($row["Status"]); ?></td>
                            <td>
                                <a href="book_service.php" class="btn btn-success btn-sm">
                                    Book
                                </a>
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