<?php
session_start();
require_once 'config/database.php';

// Cek apakah user sudah login dan memiliki role manager
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'manager') {
    header('Location: login.php');
    exit();
}

$query = "SELECT id, username, role, created_at FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar User</title>
</head>
<body>
    <h2>Daftar User</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div style='color: green'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>
    <a href="add_user.php">Tambah User Baru</a>
    <table border="1">
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html> 