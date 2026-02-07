<?php
include('db.php');

// Array of roles to insert
$roles = ['Admin', 'User', 'Guard'];

foreach ($roles as $role_name) {
    // Check if role exists
    $check = $conn->query("SELECT * FROM roles WHERE role_name = '$role_name'");
    if ($check->num_rows == 0) {
        // Insert role
        $sql = "INSERT INTO roles (role_name) VALUES ('$role_name')";
        if ($conn->query($sql) === TRUE) {
            echo "New role created: $role_name <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Role already exists: $role_name <br>";
    }
}

echo "<br><a href='user.php'>Go back to User Management</a>";
?>
