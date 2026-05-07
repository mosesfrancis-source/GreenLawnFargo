<?php
include "db.php";
include "header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["FirstName"]);
    $lastName = trim($_POST["LastName"]);
    $email = trim($_POST["Email"]);
    $phone = trim($_POST["Phone"]);
    $address = trim($_POST["Address"]);
    $password = $_POST["Password"];
    $confirmPassword = $_POST["ConfirmPassword"];

    if (
        $firstName == "" ||
        $lastName == "" ||
        $email == "" ||
        $password == ""
    ) {

        $message = "Please fill in all required fields.";
    } elseif ($password != $confirmPassword) {

        $message = "Passwords do not match.";
    } else {

        $checkSql = "SELECT CustomerID FROM customers WHERE Email = ?";

        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();

        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {

            $message = "Email already exists.";
        } else {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO customers
                    (
                        FirstName,
                        LastName,
                        Email,
                        PasswordHash,
                        Phone,
                        Address
                    )
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssssss",
                $firstName,
                $lastName,
                $email,
                $passwordHash,
                $phone,
                $address
            );

            if ($stmt->execute()) {

                $message = "Account created successfully.";
            } else {

                $message = "Registration failed.";
            }

            $stmt->close();
        }

        $checkStmt->close();
    }
}
?>

<div class="row justify-content-center">

    <div class="col-md-8 col-lg-6">

        <div class="card shadow p-4">

            <h2 class="text-success text-center mb-4">
                Create Account
            </h2>

            <?php if ($message != "") { ?>

                <div class="alert alert-info">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>

            <form method="POST">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>

                        <input
                            type="text"
                            name="FirstName"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>

                        <input
                            type="text"
                            name="LastName"
                            class="form-control"
                            required>
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>

                    <input
                        type="email"
                        name="Email"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>

                    <input
                        type="text"
                        name="Phone"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>

                    <textarea
                        name="Address"
                        class="form-control"
                        rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>

                    <input
                        type="password"
                        name="Password"
                        class="form-control"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>

                    <input
                        type="password"
                        name="ConfirmPassword"
                        class="form-control"
                        required>
                </div>

                <button
                    type="submit"
                    class="btn btn-success w-100">
                    Create Account
                </button>

            </form>

            <p class="text-center mt-3">
                Already have an account?

                <a href="login.php" class="text-success fw-bold">
                    Login here
                </a>
            </p>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>