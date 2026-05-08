<?php
include "admin_check.php";
include "db.php";
include "header.php";

$sql = "
SELECT
    f.FeedbackID,
    f.Comment,
    f.Rating,
    c.FirstName,
    c.LastName,
    c.Email,
    f.CreatedAt
FROM feedback f
LEFT JOIN customers c ON f.CustomerID = c.CustomerID
ORDER BY f.FeedbackID DESC
";

$result = $conn->query($sql);

if ($result === false) {
    echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($conn->error) . '</div>';
    $result = null;
}
?>

<h2 class="text-success mb-4">
    Manage Feedback
</h2>

<?php if ($result && $result->num_rows > 0) { ?>

    <div class="card shadow p-4">

        <table class="table table-bordered table-striped">

            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($row = $result->fetch_assoc()) { ?>

                    <tr>
                        <td><?php echo $row["FeedbackID"]; ?></td>

                        <td>
                            <?php
                            $name = trim(($row["FirstName"] ?? '') . ' ' . ($row["LastName"] ?? ''));
                            echo htmlspecialchars($name ?: 'Guest');
                            ?>
                        </td>

                        <td><?php echo htmlspecialchars($row["Email"] ?? 'N/A'); ?></td>

                        <td>
                            <?php
                            if ($row["Rating"]) {
                                echo str_repeat('⭐', intval($row["Rating"]));
                            } else {
                                echo 'No rating';
                            }
                            ?>
                        </td>

                        <td><?php echo htmlspecialchars(substr($row["Comment"], 0, 100)) . (strlen($row["Comment"]) > 100 ? '...' : ''); ?></td>

                        <td><?php echo htmlspecialchars($row["CreatedAt"] ?? 'Unknown'); ?></td>
                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

<?php } else { ?>

    <div class="alert alert-info">
        <p>No feedback received yet.</p>
    </div>

<?php } ?>

<?php include "footer.php"; ?>