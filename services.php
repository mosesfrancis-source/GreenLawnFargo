<?php
include "db.php";
include "header.php";

$sql = "
SELECT *
FROM services
ORDER BY Name ASC
";

$result = $conn->query($sql);
?>

<h2 class="text-success mb-4 text-center">
    Our Lawn Services
</h2>

<div class="row">

    <?php while ($row = $result->fetch_assoc()) { ?>

        <?php
        $serviceName = strtolower($row["Name"]);
        if (str_contains($serviceName, "weed")) {
            $serviceImage = "images/weedcontrol.avif";
        } elseif (str_contains($serviceName, "fert")) {
            $serviceImage = "images/fertilizer.webp";
        } elseif (str_contains($serviceName, "seed")) {
            $serviceImage = "images/seeds.webp";
        } elseif (str_contains($serviceName, "grass") || str_contains($serviceName, "lawn") || str_contains($serviceName, "mow") || str_contains($serviceName, "cut")) {
            $serviceImage = "images/lawnGrass.webp";
        } else {
            $serviceImage = "images/cleanUp.webp";
        }
        ?>

        <div class="col-md-6 col-lg-4 mb-4">

            <div class="card shadow h-100">

                <img
                    src="<?php echo htmlspecialchars($serviceImage); ?>"
                    alt="<?php echo htmlspecialchars($row['Name']); ?>"
                    class="card-img-top"
                    style="height: 250px; object-fit: cover;">

                <div class="card-body d-flex flex-column">

                    <h4 class="text-success">
                        <?php echo htmlspecialchars($row["Name"]); ?>
                    </h4>

                    <p class="flex-grow-1">
                        <?php echo htmlspecialchars($row["Description"]); ?>
                    </p>

                    <div class="mb-2">

                        <strong>Price:</strong>

                        $<?php echo number_format($row["BasePrice"], 2); ?>

                    </div>

                    <div class="mb-3">

                        <strong>Duration:</strong>

                        <?php echo htmlspecialchars($row["BaseDuration"]); ?>

                    </div>

                    <?php if (isset($_SESSION["CustomerID"])) { ?>

                        <a
                            href="request_service.php"
                            class="btn btn-success">
                            Request Service
                        </a>

                    <?php } else { ?>

                        <a
                            href="login.php"
                            class="btn btn-outline-success">
                            Login to Request
                        </a>

                    <?php } ?>

                </div>

            </div>

        </div>

    <?php } ?>

</div>

<?php include "footer.php"; ?>