<?php
include 'db.php'; // เชื่อมต่อกับไฟล์ฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานการซ่อมบำรุง</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
    </style>
</head>
<body>

<h1>รายงานการซ่อมบำรุง</h1>

<!-- 9.1 รายงานสถานะการซ่อมบำรุง -->
<h2>รายงานสถานะการซ่อมบำรุง</h2>
<form method="GET" action="report.php">
    <label for="status_mr">เลือกสถานะ:</label>
    <select id="status_mr" name="status_mr">
        <option value="">ทั้งหมด</option>
        <option value="ร้องขอ">ร้องขอ</option>
        <option value="กำลังดำเนินการ">กำลังดำเนินการ</option>
        <option value="เสร็จสิ้น">เสร็จสิ้น</option>
    </select>
    <button type="submit">ดูรายงาน</button>
</form>

<table>
    <tr>
        <th>Request ID</th>
        <th>Equipment ID</th>
        <th>Request Date</th>
        <th>Requested By</th>
        <th>Status</th>
    </tr>
    <?php
    $status_mr = $_GET['status_mr'] ?? '';
    $sql = "SELECT request_id, equipment_id, request_date, requested_by, status_mr FROM Maintenance_Request";
    if ($status_mr) {
        $sql .= " WHERE status_mr = :status_mr";
    }
    $stmt = $conn->prepare($sql);
    if ($status_mr) {
        $stmt->execute(['status_mr' => $status_mr]);
    } else {
        $stmt->execute();
    }
    foreach ($stmt as $row) {
        echo "<tr>
                <td>{$row['request_id']}</td>
                <td>{$row['equipment_id']}</td>
                <td>{$row['request_date']}</td>
                <td>{$row['requested_by']}</td>
                <td>{$row['status_mr']}</td>
              </tr>";
    }
    ?>
</table>

<!-- 9.2 รายงานค่าใช้จ่ายการซ่อมบำรุง -->
<h2>รายงานค่าใช้จ่ายการซ่อมบำรุง</h2>
<form method="GET" action="report.php">
    <label for="start_date">วันที่เริ่มต้น:</label>
    <input type="date" id="start_date" name="start_date">
    <label for="end_date">วันที่สิ้นสุด:</label>
    <input type="date" id="end_date" name="end_date">
    <button type="submit">ดูรายงาน</button>
</form>

<table>
    <tr>
        <th>Equipment ID</th>
        <th>Equipment Name</th>
        <th>Total Cost</th>
        <th>Start Period</th>
        <th>End Period</th>
    </tr>
    <?php
    $start_date = $_GET['start_date'] ?? '';
    $end_date = $_GET['end_date'] ?? '';
    $sql = "SELECT e.equipment_id, e.name AS equipment_name, SUM(m.cost) AS total_cost, 
            MIN(m.start_date) AS start_period, MAX(m.end_date) AS end_period
            FROM maintenance_log m
            JOIN maintenance_request r ON m.request_id = r.request_id
            JOIN equipments e ON r.equipment_id = e.equipment_id";
    if ($start_date && $end_date) {
        $sql .= " WHERE m.start_date BETWEEN :start_date AND :end_date";
    }
    $sql .= " GROUP BY e.equipment_id, e.name";
    $stmt = $conn->prepare($sql);
    if ($start_date && $end_date) {
        $stmt->execute(['start_date' => $start_date, 'end_date' => $end_date]);
    } else {
        $stmt->execute();
    }
    foreach ($stmt as $row) {
        echo "<tr>
                <td>{$row['equipment_id']}</td>
                <td>{$row['equipment_name']}</td>
                <td>{$row['total_cost']}</td>
                <td>{$row['start_period']}</td>
                <td>{$row['end_period']}</td>
              </tr>";
    }
    ?>
</table>


<!-- 9.4 รายงานประวัติการซ่อมบำรุง -->
<h2>รายงานประวัติการซ่อมบำรุง</h2>
<form method="GET" action="report.php">
    <label for="equipment_id">เลือก Equipment ID:</label>
    <select id="equipment_id" name="equipment_id">
        <option value="">ทั้งหมด</option>
        <?php
        // ดึงรายการ Equipment ID จากฐานข้อมูลเพื่อสร้างตัวเลือกในดรอปดาวน์
        $equipments = $conn->query("SELECT DISTINCT equipment_id FROM Maintenance_Request");
        foreach ($equipments as $equipment) {
            echo "<option value=\"{$equipment['equipment_id']}\">{$equipment['equipment_id']}</option>";
        }
        ?>
    </select>
    <button type="submit">ดูประวัติการซ่อมบำรุง</button>
</form>

<table>
    <tr>
        <th>Log ID</th>
        <th>Equipment ID</th>
        <th>Issue Description</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Performed By</th>
        <th>Actions Taken</th>
        <th>Cost</th>
    </tr>
    <?php
    $equipment_id = $_GET['equipment_id'] ?? '';
    $sql = "SELECT ML.log_id, MR.equipment_id, MR.issue_description, ML.start_date, ML.end_date, 
                   ML.performed_by, ML.actions_taken, ML.cost
            FROM Maintenance_Log AS ML
            JOIN Maintenance_Request AS MR ON ML.request_id = MR.request_id";
    if ($equipment_id) {
        $sql .= " WHERE MR.equipment_id = :equipment_id";
    }
    $stmt = $conn->prepare($sql);
    if ($equipment_id) {
        $stmt->execute(['equipment_id' => $equipment_id]);
    } else {
        $stmt->execute();
    }
    foreach ($stmt as $row) {
        echo "<tr>
                <td>{$row['log_id']}</td>
                <td>{$row['equipment_id']}</td>
                <td>{$row['issue_description']}</td>
                <td>{$row['start_date']}</td>
                <td>{$row['end_date']}</td>
                <td>{$row['performed_by']}</td>
                <td>{$row['actions_taken']}</td>
                <td>{$row['cost']}</td>
              </tr>";
    }

    ?>
</table>

<!-- 9.3 รายงานการใช้อะไหล่ -->
<h2>รายงานการใช้อะไหล่</h2>
<table>
    <tr>
        <th>Usage ID</th>
        <th>Part Name</th>
        <th>Quantity Used</th>
        <th>Log ID</th>
        <th>Actions Taken</th>
    </tr>
    <?php
    $sql = "SELECT PU.usage_id, SP.name AS part_name, PU.quantity_used, ML.log_id, ML.actions_taken
            FROM Parts_Usage AS PU
            JOIN Spare_Parts AS SP ON PU.part_id = SP.part_id
            JOIN Maintenance_Log AS ML ON PU.log_id = ML.log_id";
    $stmt = $conn->query($sql);
    foreach ($stmt as $row) {
        echo "<tr>
                <td>{$row['usage_id']}</td>
                <td>{$row['part_name']}</td>
                <td>{$row['quantity_used']}</td>
                <td>{$row['log_id']}</td>
                <td>{$row['actions_taken']}</td>
              </tr>";
    }
    ?>
</table>

</body>
</html>
