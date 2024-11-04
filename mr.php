<?php
include 'db.php';

// ดึงรายการอุปกรณ์ที่มีอยู่จากฐานข้อมูล
$equipments_sql = "SELECT equipment_id, name FROM Equipments";
$equipments_result = $conn->query($equipments_sql);

// ฟังก์ชันบันทึกคำขอซ่อมบำรุง
if (isset($_POST['submit_request'])) {
    $request_id = $_POST['request_id'];
    $request_date = $_POST['request_date'];
    $requested_by = $_POST['requested_by'];
    $issue_description = $_POST['issue_description'];
    $priority = $_POST['priority'];
    $status_mr = "ร้องขอ";  // กำหนดค่าเริ่มต้นของสถานะเป็น "ร้องขอ"
    $equipment_id = $_POST['equipment_id'];

    $sql = "INSERT INTO Maintenance_Request (request_id, request_date, requested_by, issue_description, priority, status_mr, equipment_id) 
            VALUES ('$request_id', '$request_date', '$requested_by', '$issue_description', '$priority', '$status_mr', '$equipment_id')";
    
    if ($conn->query($sql)) {
        echo "<p>บันทึกคำขอซ่อมบำรุงสำเร็จ</p>";
    } else {
        echo "<p>เกิดข้อผิดพลาด: " . $conn->error . "</p>";
    }
}

// ดึงรายการคำขอซ่อมบำรุงที่มีอยู่เพื่อแสดงผล
$requests_sql = "SELECT * FROM Maintenance_Request";
$requests_result = $conn->query($requests_sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>บันทึกคำขอซ่อมบำรุง</title>
    <link rel="stylesheet" href="re.css">
</head>
<body>
    <div class="container">
    <h3>รายการคำขอซ่อมบำรุงที่</h3>
        <table>
            <tr>
                <th>Request ID</th>
                <th>วันที่ร้องขอ</th>
                <th>ผู้ร้อง</th>
                <th>รายละเอียดของปัญหา</th>
                <th>ความสำคัญ</th>
                <th>สถานะ</th>
                <th>อุปกรณ์</th>
            </tr>
            <?php while ($row = $requests_result->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo $row['request_id']; ?></td>
                    <td><?php echo $row['request_date']; ?></td>
                    <td><?php echo $row['requested_by']; ?></td>
                    <td><?php echo $row['issue_description']; ?></td>
                    <td><?php echo $row['priority']; ?></td>
                    <td><?php echo $row['status_mr']; ?></td>
                    <td><?php echo $row['equipment_id']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <h2>บันทึกคำขอซ่อมบำรุง</h2>
        <form method="POST">
            <label for="request_id">Request ID (กรุณาดู Request ID จากด้านล่างและเขียนให้ถูกต้อง):</label>
            <input type="text" id="request_id" name="request_id" required>

            <label for="request_date">วันที่ร้องขอ:</label>
            <input type="datetime-local" id="request_date" name="request_date" required>

            <label for="requested_by">ผู้ร้อง:</label>
            <input type="text" id="requested_by" name="requested_by" required>

            <label for="issue_description">รายละเอียดของปัญหา:</label>
            <textarea id="issue_description" name="issue_description" required></textarea>

            <label for="priority">ความสำคัญ:</label>
            <select id="priority" name="priority">
                <option value="สูง">สูง</option>
                <option value="ปานกลาง">ปานกลาง</option>
                <option value="ต่ำ">ต่ำ</option>
            </select>

            <label for="equipment_id">อุปกรณ์ที่ต้องซ่อม:</label>
            <select id="equipment_id" name="equipment_id" required>
                <?php while ($row = $equipments_result->fetch(PDO::FETCH_ASSOC)) : ?>
                    <option value="<?php echo $row['equipment_id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="submit_request">บันทึกคำขอ</button>
        </form>

        
    </div>
</body>
</html>
