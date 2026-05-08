<?php
include "db.php";
include "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["Name"]);
    $email = trim($_POST["Email"]);
    $comment = trim($_POST["Message"]);

    if ($name == "" || $email == "" || $comment == "") {
        $message = "Please fill in all fields.";
    } else {
        $sql = "
        INSERT INTO feedback
        (CustomerID, Rating, Comment)
        VALUES (NULL, NULL, ?)
        ";

        $stmt = $conn->prepare($sql);
        $fullMessage = "Contact from " . $name . " (" . $email . "): " . $comment;
        $stmt->bind_param("s", $fullMessage);

        if ($stmt->execute()) {
            $message = "Your message has been sent successfully.";
        } else {
            $message = "Failed to send message.";
        }

        $stmt->close();
    }
}
?>

<h2 class="text-success mb-4 text-center">Contact Us</h2>

<div class="row g-4">
    <div class="col-12 col-md-6 mb-4">
        <div class="card shadow p-3 p-md-4 h-100">
            <h4 class="text-success">Green Lawn Fargo</h4>

            <p>Have questions about our services? Contact us using the form.</p>

            <p><strong>Phone:</strong> (701) 555-0100</p>
            <p><strong>Email:</strong> info@greenlawnfargo.com</p>
            <p><strong>Location:</strong> Fargo, North Dakota</p>

            <h5 class="text-success mt-4">Business Hours</h5>
            <p>Monday - Friday: 8:00 AM - 6:00 PM</p>
            <p>Saturday: 9:00 AM - 4:00 PM</p>
            <p>Sunday: Closed</p>
        </div>
    </div>

    <div class="col-12 col-md-6 mb-4">
        <div class="card shadow p-3 p-md-4 h-100">
            <h4 class="text-success mb-3">Send a Message</h4>

            <?php if ($message != "") { ?>
                <div class="alert alert-info">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="Name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="Email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Message</label>
                    <textarea name="Message" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>