<?php
include "admin_check.php";
include "db.php";
include "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["Name"]);
    $description = trim($_POST["Description"]);
    $price = $_POST["BasePrice"];
    $duration = trim($_POST["BaseDuration"]);

    if (
        $name == "" ||
        $description == "" ||
        $price == "" ||
        $duration == ""
    ) {

        $message = "Please fill in all fields.";
    } else {

        $sql = "
        INSERT INTO services
        (
            Name,
            Description,
            BasePrice,
            BaseDuration
        )
        VALUES (?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssds",
            $name,
            $description,
            $price,
            $duration
        );

        if ($stmt->execute()) {

            $message = "Service added successfully.";
        } else {

            $message = "Failed to add service.";
        }

        $stmt->close();
    }
}

$services = $conn->query("
SELECT *
FROM services
ORDER BY ServiceID DESC
");
?>

<h2 class="text-success mb-4">
    Manage Services
</h2>

<?php if ($message != "") { ?>

    <div class="alert alert-info">
        <?php echo htmlspecialchars($message); ?>
    </div>

<?php } ?>

<div class="card shadow p-3 p-md-4 mb-5">

    <h4 class="mb-4">
        Add New Service
    </h4>

    <form method="POST">

        <div class="row g-3">

            <div class="col-12 mb-3">

                <label class="form-label">
                    Service Name
                </label>

                <input
                    type="text"
                    name="Name"
                    class="form-control"
                    required>

            </div>

            <div class="col-12 mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="Description"
                    class="form-control"
                    rows="3"
                    required></textarea>

            </div>

            <div class="col-12 col-sm-6 mb-3">

                <label class="form-label">
                    Base Price
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="BasePrice"
                    class="form-control"
                    required>

            </div>

            <div class="col-12 col-sm-6 mb-4">

                <label class="form-label">
                    Duration
                </label>

                <input
                    type="text"
                    name="BaseDuration"
                    class="form-control"
                    placeholder="Example: 2 Hours"
                    required>

            </div>

            <div class="col-12">
                <button
                    class="btn btn-success"
                    type="submit">
                    Add Service
                </button>
            </div>
        </div>

    </form>

</div>

<div class="card shadow p-4">

    <h4 class="mb-4">
        Current Services
    </h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>

                <?php while ($row = $services->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo $row["ServiceID"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["Name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["Description"]); ?>
                        </td>

                        <td>
                            $<?php echo number_format($row["BasePrice"], 2); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["BaseDuration"]); ?>
                        </td>

                    </tr>

                <?php } ?>

            </tbody>
        </table>
    </div>

</div>

<?php include "footer.php"; ?>