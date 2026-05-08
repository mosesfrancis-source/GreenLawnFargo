<?php
include "auth_check.php";
include "db.php";
include "header.php";

$customerID = $_SESSION["CustomerID"];

$message = "";

$sql = "
SELECT *
FROM customers
WHERE CustomerID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerID);
$stmt->execute();

$result = $stmt->get_result();

$customer = $result->fetch_assoc();

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["FirstName"]);
    $lastName = trim($_POST["LastName"]);
    $email = trim($_POST["Email"]);
    $phone = trim($_POST["Phone"]);
    $address = trim($_POST["Address"]);

    // Handle profile image upload
    $profileImagePath = null;
    if (isset($_FILES["ProfileImage"]) && $_FILES["ProfileImage"]["error"] === UPLOAD_ERR_OK) {
        $file = $_FILES["ProfileImage"];
        $fileName = $file["name"];
        $fileTmp = $file["tmp_name"];
        $fileSize = $file["size"];
        $fileType = $file["type"];

        // Validate file
        $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($fileType, $allowedTypes)) {
            $message = "Only JPG, PNG, GIF, and WebP images are allowed.";
        } elseif ($fileSize > $maxSize) {
            $message = "File size must be less than 5MB.";
        } else {
            // Create profiles directory if it doesn't exist
            if (!is_dir("images/profiles")) {
                mkdir("images/profiles", 0755, true);
            }

            // Generate unique filename with customer ID
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = "customer_" . $customerID . "." . $ext;
            $uploadPath = "images/profiles/" . $newFileName;

            if (move_uploaded_file($fileTmp, $uploadPath)) {
                $profileImagePath = $uploadPath;
            } else {
                $message = "Failed to upload image. Please try again.";
            }
        }
    }

    // Only update if no message (no file error)
    if ($message === "") {
        $updateSql = "
        UPDATE customers
        SET
            FirstName = ?,
            LastName = ?,
            Email = ?,
            Phone = ?,
            Address = ?
        WHERE CustomerID = ?
        ";

        $updateStmt = $conn->prepare($updateSql);

        $updateStmt->bind_param(
            "sssssi",
            $firstName,
            $lastName,
            $email,
            $phone,
            $address,
            $customerID
        );

        if ($updateStmt->execute()) {

            $_SESSION["FirstName"] = $firstName;
            $_SESSION["LastName"] = $lastName;

            $message = "Profile updated successfully.";

            $customer["FirstName"] = $firstName;
            $customer["LastName"] = $lastName;
            $customer["Email"] = $email;
            $customer["Phone"] = $phone;
            $customer["Address"] = $address;
        } else {

            $message = "Failed to update profile.";
        }

        $updateStmt->close();
    }
}
?>

<h2 class="text-success mb-4">
    My Profile
</h2>

<?php if ($message != "") { ?>

    <div class="alert alert-info">
        <?php echo htmlspecialchars($message); ?>
    </div>

<?php } ?>

<div class="row justify-content-center">

    <div class="col-11 col-md-8">

        <div class="card shadow p-3 p-md-4">

            <!-- Profile Image Section -->
            <div class="mb-4 text-center">
                <?php
                $profileImagePath = null;
                // Check for profile image
                $possibleExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
                foreach ($possibleExtensions as $ext) {
                    $path = "images/profiles/customer_" . $customerID . "." . $ext;
                    if (file_exists($path)) {
                        $profileImagePath = $path;
                        break;
                    }
                }
                ?>

                <?php if ($profileImagePath) { ?>
                    <img src="<?php echo htmlspecialchars($profileImagePath); ?>" alt="Profile Picture" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #28a745;">
                <?php } else { ?>
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; border: 3px solid #ccc;">
                        <i class="fas fa-user" style="font-size: 2.5rem; color: #999;"></i>
                    </div>
                <?php } ?>
            </div>

            <form method="POST" enctype="multipart/form-data">

                <!-- Profile Image Upload -->
                <div class="mb-4">
                    <label class="form-label">
                        Profile Picture
                    </label>
                    <input
                        type="file"
                        name="ProfileImage"
                        class="form-control"
                        accept="image/jpeg,image/png,image/gif,image/webp">
                    <small class="form-text text-muted">
                        Supported formats: JPG, PNG, GIF, WebP (Max 5MB)
                    </small>
                </div>

                <div class="row g-2">

                    <div class="col-12 col-sm-6 mb-3">

                        <label class="form-label">
                            First Name
                        </label>

                        <input
                            type="text"
                            name="FirstName"
                            class="form-control"
                            value="<?php echo htmlspecialchars($customer["FirstName"]); ?>"
                            required>

                    </div>

                    <div class="col-12 col-sm-6 mb-3">

                        <label class="form-label">
                            Last Name
                        </label>

                        <input
                            type="text"
                            name="LastName"
                            class="form-control"
                            value="<?php echo htmlspecialchars($customer["LastName"]); ?>"
                            required>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="Email"
                        class="form-control"
                        value="<?php echo htmlspecialchars($customer["Email"]); ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        name="Phone"
                        class="form-control"
                        value="<?php echo htmlspecialchars($customer["Phone"]); ?>">

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Address
                    </label>

                    <textarea
                        name="Address"
                        class="form-control"
                        rows="3"><?php echo htmlspecialchars($customer["Address"]); ?></textarea>

                </div>

                <button
                    type="submit"
                    class="btn btn-success">
                    Update Profile
                </button>

            </form>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>