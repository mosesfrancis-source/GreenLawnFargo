<?php
include "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["Email"]);
    $password = $_POST["Password"];

    $sql = "SELECT CustomerID, FirstName, LastName, Email, PasswordHash
            FROM customers
            WHERE Email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $customer = $result->fetch_assoc();

        if (password_verify($password, $customer["PasswordHash"])) {
            $_SESSION["CustomerID"] = $customer["CustomerID"];
            $_SESSION["FirstName"] = $customer["FirstName"];
            $_SESSION["LastName"] = $customer["LastName"];

            header("Location: services.php");
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "No account found with that email.";
    }

    $stmt->close();
}

include "header.php";
?>

<div class="row justify-content-center">
    <div class="col-11 col-md-6 col-lg-5">
        <div class="card shadow p-3 p-md-4">
            <h2 class="text-success text-center mb-4">Customer Login</h2>

            <?php if ($message != "") { ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="Email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="Password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>

            <p class="text-center mt-3">
                Don't have an account?
                <a href="register.php" class="text-success fw-bold">Register here</a>
            </p>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>