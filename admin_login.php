<?php
include "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["Username"]);
    $password = $_POST["Password"];

    $sql = "SELECT * FROM admins WHERE Username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $admin = $result->fetch_assoc();
        $storedPassword = $admin["PasswordHash"];
        $isValidPassword = false;

        if (password_verify($password, $storedPassword)) {
            $isValidPassword = true;
        } elseif ($password === $storedPassword) {
            // Backward compatibility for legacy plain-text admin passwords.
            $isValidPassword = true;

            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE admins SET PasswordHash = ? WHERE AdminID = ?");
            if ($updateStmt) {
                $adminID = (int) $admin["AdminID"];
                $updateStmt->bind_param("si", $newHash, $adminID);
                $updateStmt->execute();
                $updateStmt->close();
            }
        }

        if ($isValidPassword) {

            $_SESSION["AdminID"] = $admin["AdminID"];
            $_SESSION["AdminUsername"] = $admin["Username"];

            header("Location: admin_dashboard.php");
            exit();
        } else {

            $message = "Invalid password.";
        }
    } else {

        $message = "Admin not found.";
    }

    $stmt->close();
}

include "header.php";
?>

<div class="row justify-content-center">

    <div class="col-md-5">

        <div class="card shadow p-4">

            <h2 class="text-center text-success mb-4">
                Admin Login
            </h2>

            <?php if ($message != "") { ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Username</label>

                    <input
                        type="text"
                        name="Username"
                        class="form-control"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>

                    <input
                        type="password"
                        name="Password"
                        class="form-control"
                        required>
                </div>

                <button
                    type="submit"
                    class="btn btn-success w-100">
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>