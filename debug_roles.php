<?php
include('db.php');

$sql = "SELECT * FROM roles";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Existing Roles:</h2><ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>ID: " . $row['role_id'] . " - Name: " . $row['role_name'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No roles found in the 'roles' table.";
}
?>
