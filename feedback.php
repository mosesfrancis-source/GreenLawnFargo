<?php
include "db.php";
include "header.php";

$message = "";
$customerID = isset($_SESSION["CustomerID"]) ? $_SESSION["CustomerID"] : null;

// Handle feedback submission (for authenticated users)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] === "submit_feedback") {
    $name = trim($_POST["Name"] ?? '');
    $email = trim($_POST["Email"] ?? '');
    $rating = isset($_POST["Rating"]) ? intval($_POST["Rating"]) : null;
    $comment = trim($_POST["Message"] ?? '');

    if ($name == "" || $email == "" || $comment == "") {
        $message = "Please fill in all fields.";
    } else {
        $sql = "
        INSERT INTO feedback
        (CustomerID, Rating, Comment)
        VALUES (?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);
        $fullComment = "[" . htmlspecialchars($name) . " (" . htmlspecialchars($email) . ")] " . $comment;
        $stmt->bind_param("iis", $customerID, $rating, $fullComment);

        if ($stmt->execute()) {
            $message = "Your feedback has been submitted successfully. Thank you!";
        } else {
            $message = "Failed to submit feedback. Please try again.";
        }

        $stmt->close();
    }
}

// Fetch recent feedback
$feedbackSQL = "
SELECT
    f.Comment,
    f.Rating,
    f.CreatedAt,
    COALESCE(c.FirstName, 'Customer') AS FirstName
FROM feedback f
LEFT JOIN customers c ON f.CustomerID = c.CustomerID
ORDER BY f.FeedbackID DESC
LIMIT 10
";

$feedbackResult = $conn->query($feedbackSQL);
?>

<h2 class="text-success mb-4 text-center">Customer Feedback</h2>

<div class="row">
    <!-- Testimonials -->
    <div class="col-md-8 mb-4">
        <div class="card shadow p-4">
            <h4 class="text-success mb-3">Recent Reviews</h4>

            <?php if ($feedbackResult && $feedbackResult->num_rows > 0) { ?>

                <?php while ($fb = $feedbackResult->fetch_assoc()) { ?>

                    <div class="card mb-3 p-3 border-left-success" style="border-left: 4px solid #28a745;">

                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($fb["FirstName"]); ?></h5>

                                <div class="text-warning mb-2">
                                    <?php echo str_repeat('⭐', $fb["Rating"] ?? 5); ?>
                                </div>

                                <p class="mb-0"><?php echo htmlspecialchars($fb["Comment"]); ?></p>
                            </div>

                            <small class="text-muted"><?php echo htmlspecialchars($fb["CreatedAt"]); ?></small>
                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <p class="text-muted">No feedback yet. Be the first to share your experience!</p>

            <?php } ?>

        </div>
    </div>

    <!-- Submit Feedback Form -->
    <div class="col-md-4 mb-4">
        <div class="card shadow p-4">
            <h4 class="text-success mb-3">Share Your Feedback</h4>

            <?php if ($message != "") { ?>
                <div class="alert alert-info">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="POST">
                <input type="hidden" name="action" value="submit_feedback">

                <div class="mb-3">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="Name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="Email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <div>
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Rating" id="rating<?php echo $i; ?>" value="<?php echo $i; ?>">
                                <label class="form-check-label" for="rating<?php echo $i; ?>">
                                    <?php echo str_repeat('⭐', $i); ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Your Feedback</label>
                    <textarea name="Message" class="form-control" rows="4" placeholder="Share your experience with our services..." required></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Submit Feedback
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
