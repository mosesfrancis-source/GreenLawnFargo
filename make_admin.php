<?php
include "db.php";

$username = "admin";
$password = "admin123";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (Username, PasswordHash)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $passwordHash);

if ($stmt->execute()) {
    echo "Admin created successfully.";
} else {
    echo "Admin already exists or error.";
}

$stmt->close();
