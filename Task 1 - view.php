<?php
$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, email, dob, department, phone, created_at FROM students ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #667eea; color: white; }
        tr:hover { background: #f5f5f5; }
        .back { display: block; margin: 20px 0; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registered Students</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Registered On</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row["id"] ?></td>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["email"]) ?></td>
                        <td><?= $row["dob"] ?></td>
                        <td><?= htmlspecialchars($row["department"]) ?></td>
                        <td><?= $row["phone"] ?></td>
                        <td><?= date("d M Y h:i A", strtotime($row["created_at"])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:#555;">No students registered yet.</p>
        <?php endif; ?>

        <a href="index.html" class="view-link back">← Back to Registration Form</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>