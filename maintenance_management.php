<?php
include 'db.php';

// เพิ่มการบันทึกการใช้อะไหล่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_part_usage'])) {
        $usage_id = $_POST['usage_id'];
        $quantity_used = $_POST['quantity_used'];
        $log_id = $_POST['log_id'];
        $part_id = $_POST['part_id'];

        $sql = "INSERT INTO parts_usage (usage_id, quantity_used, log_id, part_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$usage_id, $quantity_used, $log_id, $part_id]);
    }

    // บันทึกการซ่อมบำรุง
    if (isset($_POST['add_maintenance_log'])) {
        $log_id = $_POST['log_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $performed_by = $_POST['performed_by'];
        $actions_taken = $_POST['actions_taken'];
        $cost = $_POST['cost'];
        $request_id = $_POST['request_id'];

        $sql = "INSERT INTO maintenance_log (log_id, start_date, end_date, performed_by, actions_taken, cost, request_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$log_id, $start_date, $end_date, $performed_by, $actions_taken, $cost, $request_id]);
    }

    // แก้ไขสถานะคำขอซ่อมบำรุง
    if (isset($_POST['update_status'])) {
        $request_id = $_POST['request_id'];
        $status_mr = $_POST['status_mr'];

        $sql = "UPDATE maintenance_request SET status_mr = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$status_mr, $request_id]);
    }
}

// ดึงข้อมูลทั้งหมดจาก maintenance_request, maintenance_log และ parts_usage
$requests = $conn->query("SELECT * FROM maintenance_request")->fetchAll();
$maintenance_logs = $conn->query("SELECT * FROM maintenance_log")->fetchAll();
$parts_usage = $conn->query("SELECT * FROM parts_usage")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="mm.css">
    <title>Maintenance Management</title>
</head>
<body>
<h2>รายการการใช้อะไหล่</h2>
    <table>
        <tr>
            <th>Usage ID</th>
            <th>Quantity Used</th>
            <th>Log ID</th>
            <th>Part ID</th>
        </tr>
        <?php foreach ($parts_usage as $usage): ?>
            <tr>
                <td><?= $usage['usage_id'] ?></td>
                <td><?= $usage['quantity_used'] ?></td>
                <td><?= $usage['log_id'] ?></td>
                <td><?= $usage['part_id'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>บันทึกการใช้อะไหล่</h2>
    <form method="POST">
        <label>Usage ID:</label>
        <input type="text" name="usage_id" required><br>

        <label>Quantity Used:</label>
        <input type="number" name="quantity_used" required><br>

        <label>Log ID:</label>
        <input type="text" name="log_id" required><br>

        <label>Part ID:</label>
        <input type="text" name="part_id" required><br>

        <button type="submit" name="add_part_usage">Add Part Usage</button>
    </form>

    <h2>รายการบันทึกการซ่อมบำรุง</h2>
    <table>
        <tr>
            <th>Log ID</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Performed By</th>
            <th>Actions Taken</th>
            <th>Cost</th>
            <th>Request ID</th>
        </tr>
        <?php foreach ($maintenance_logs as $log): ?>
            <tr>
                <td><?= $log['log_id'] ?></td>
                <td><?= $log['start_date'] ?></td>
                <td><?= $log['end_date'] ?></td>
                <td><?= $log['performed_by'] ?></td>
                <td><?= $log['actions_taken'] ?></td>
                <td><?= $log['cost'] ?></td>
                <td><?= $log['request_id'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>บันทึกการซ่อมบำรุง</h2>
    <form method="POST">
        <label>Log ID:</label>
        <input type="text" name="log_id" required><br>

        <label>Start Date:</label>
        <input type="datetime-local" name="start_date" required><br>

        <label>End Date:</label>
        <input type="datetime-local" name="end_date"><br>

        <label>Performed By:</label>
        <input type="text" name="performed_by" required><br>

        <label>Actions Taken:</label>
        <input type="text" name="actions_taken" required><br>

        <label>Cost:</label>
        <input type="number" step="0.01" name="cost" required><br>

        <label>Request ID:</label>
        <input type="text" name="request_id" required><br>

        <button type="submit" name="add_maintenance_log">Add Maintenance Log</button>
    </form>
    <h2>รายการคำขอซ่อมบำรุง</h2>
    <table>
        <tr>
            <th>Request ID</th>
            <th>Request Date</th>
            <th>Requested By</th>
            <th>Issue Description</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Equipment ID</th>
        </tr>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= $request['request_id'] ?></td>
                <td><?= $request['request_date'] ?></td>
                <td><?= $request['requested_by'] ?></td>
                <td><?= $request['issue_description'] ?></td>
                <td><?= $request['priority'] ?></td>
                <td><?= $request['status_mr'] ?></td>
                <td><?= $request['equipment_id'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>แก้ไขสถานะคำขอซ่อมบำรุง</h2>
    <form method="POST">
        <label>Request ID:</label>
        <input type="text" name="request_id" required><br>

        <label>New Status:</label>
        <select name="status_mr">
            <option value="ร้องขอ">ร้องขอ</option>
            <option value="กำลังดำเนินการ">กำลังดำเนินการ</option>
            <option value="เสร็จสิ้น">เสร็จสิ้น</option>
        </select><br>

        <button type="submit" name="update_status">Update Status</button>
    </form>

    


    
</body>
</html>
