<?php
include "auth_check.php";
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customerID = $_SESSION["CustomerID"];
    $yardSize = trim($_POST["YardSize"]);
    $preferredDate = $_POST["PreferredDate"];
    $notes = trim($_POST["Notes"]);
    $selectedServices = $_POST["Services"] ?? [];

    if ($yardSize == "" || $preferredDate == "" || count($selectedServices) == 0) {
        $message = "Please select yard size, preferred date, and at least one service.";
    } else {

        $baseTotal = 0;

        foreach ($selectedServices as $serviceID) {
            $priceSql = "SELECT BasePrice FROM services WHERE ServiceID = ?";
            $priceStmt = $conn->prepare($priceSql);
            $priceStmt->bind_param("i", $serviceID);
            $priceStmt->execute();

            $priceResult = $priceStmt->get_result();

            if ($priceResult->num_rows == 1) {
                $service = $priceResult->fetch_assoc();
                $baseTotal += $service["BasePrice"];
            }

            $priceStmt->close();
        }

        $discountPercent = count($selectedServices) >= 3 ? 10.00 : 0.00;
        $finalEstimate = $baseTotal - ($baseTotal * ($discountPercent / 100));
        $status = "Pending";

        $insertRequestSql = "
            INSERT INTO service_requests
            (
                CustomerID,
                YardSize,
                PreferredDate,
                Notes,
                BaseTotal,
                DiscountPercent,
                FinalEstimate,
                Status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($insertRequestSql);

        $stmt->bind_param(
            "isssddds",
            $customerID,
            $yardSize,
            $preferredDate,
            $notes,
            $baseTotal,
            $discountPercent,
            $finalEstimate,
            $status
        );

        if ($stmt->execute()) {

            $requestID = $stmt->insert_id;
            $stmt->close();

            foreach ($selectedServices as $serviceID) {

                $insertItemSql = "
                    INSERT INTO service_request_items
                    (
                        RequestID,
                        ServiceID
                    )
                    VALUES (?, ?)
                ";

                $itemStmt = $conn->prepare($insertItemSql);
                $itemStmt->bind_param("ii", $requestID, $serviceID);
                $itemStmt->execute();
                $itemStmt->close();
            }

            header("Location: estimate.php");
            exit();
        } else {
            $message = "Failed to submit service request.";
            $stmt->close();
        }
    }
}

include "header.php";

$servicesSql = "SELECT * FROM services ORDER BY Name ASC";
$servicesResult = $conn->query($servicesSql);
?>

<h2 class="text-success mb-4">
    Request Lawn Services
</h2>

<?php if ($message != "") { ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php } ?>

<div class="card shadow p-4">

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Yard Size</label>

            <select name="YardSize" class="form-select" required>
                <option value="">Select Yard Size</option>
                <option value="Small">Small</option>
                <option value="Medium">Medium</option>
                <option value="Large">Large</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Preferred Service Date</label>

            <input
                type="date"
                name="PreferredDate"
                class="form-control"
                required>
        </div>

        <div class="mb-4">
            <label class="form-label">Additional Notes</label>

            <textarea
                name="Notes"
                class="form-control"
                rows="4"
                placeholder="Example: Front yard only, backyard cleanup, or special instructions"></textarea>
        </div>

        <h4 class="text-success mb-3">
            Select Services
        </h4>

        <div class="row g-2">

            <?php while ($service = $servicesResult->fetch_assoc()) { ?>

                <div class="col-12 col-sm-6 mb-3">

                    <div class="card p-3 h-100">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="Services[]"
                                value="<?php echo $service["ServiceID"]; ?>"
                                id="service<?php echo $service["ServiceID"]; ?>">

                            <label
                                class="form-check-label"
                                for="service<?php echo $service["ServiceID"]; ?>">
                                <strong>
                                    <?php echo htmlspecialchars($service["Name"]); ?>
                                </strong>

                                <br>

                                <small>
                                    <?php echo htmlspecialchars($service["Description"]); ?>
                                </small>

                                <br>

                                <span class="text-success fw-bold">
                                    $<?php echo number_format($service["BasePrice"], 2); ?>
                                </span>

                                <br>

                                <small class="text-muted">
                                    Duration:
                                    <?php echo htmlspecialchars($service["BaseDuration"]); ?>
                                </small>
                            </label>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

        <div class="alert alert-info mt-3">
            Select 3 or more services to receive a 10% discount.
        </div>

        <button
            type="submit"
            class="btn btn-success mt-3">
            Submit Request
        </button>

        <a
            href="services.php"
            class="btn btn-outline-secondary mt-3">
            Back to Services
        </a>

    </form>

</div>

<?php include "footer.php"; ?>