<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบบันทึกข้อมูล</title>
    <link rel="stylesheet" href="aa.css">
</head>
<body>
<h1>ระบบบันทึกข้อมูล</h1>

<!-- Section: บันทึกอุปกรณ์ -->
<section>
    <?php
    include 'db.php';
    if (isset($_POST['save_equipment'])) {
        $stmt = $conn->prepare("INSERT INTO Equipments (equipment_id, name, description, model, purchase_date, warranty_expiration, status_e, manufacturer_id) VALUES (?,?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['equipment_id'], $_POST['equipment_name'], $_POST['description'], $_POST['model'], $_POST['purchase_date'], $_POST['warranty_expiration'], $_POST['status_e'], $_POST['manufacturer_id']]);
    }

    $equipments = $conn->query("SELECT * FROM Equipments")->fetchAll();
    echo "<h3>ข้อมูลอุปกรณ์ที่มีอยู่</h3><table><tr><th>ID</th><th>ชื่อ</th><th>รายละเอียด</th><th>รุ่น</th><th>วันที่ซื้อ</th><th>วันหมดประกัน</th><th>สถานะ</th><th>ID ผู้ผลิต</th></tr>";
    foreach ($equipments as $equipment) {
        echo "<tr><td>{$equipment['equipment_id']}</td><td>{$equipment['name']}</td><td>{$equipment['description']}</td><td>{$equipment['model']}</td><td>{$equipment['purchase_date']}</td><td>{$equipment['warranty_expiration']}</td><td>{$equipment['status_e']}</td><td>{$equipment['manufacturer_id']}</td></tr>";
    }
    echo "</table>";
    ?>
        <h2>บันทึกอุปกรณ์</h2>
    <form method="POST" action="tc.php">
        <input type="text" name="equipment_id" placeholder="รหัสอุปกรณ์" required>
        <input type="text" name="equipment_name" placeholder="ชื่ออุปกรณ์" required>
        <input type="text" name="description" placeholder="รายละเอียด" required>
        <input type="text" name="model" placeholder="รุ่น" required>
        <input type="date" name="purchase_date" required>
        <input type="date" name="warranty_expiration" required>
        <input type="text" name="status_e" placeholder="สถานะ" required>
        <input type="text" name="manufacturer_id" placeholder="ID ผู้ผลิต" required>
        <button type="submit" name="save_equipment">บันทึกอุปกรณ์</button>
    </form>
</section>

<!-- Section: บริษัทผู้ผลิต -->
<section>


    <?php
    if (isset($_POST['save_manufacturer'])) {
        $stmt = $conn->prepare("INSERT INTO Manufacturers (manufacturer_id, name, contact_info) VALUES (?,?, ?)");
        $stmt->execute([$_POST['manufacturer_id'],$_POST['manufacturer_name'], $_POST['contact_info']]);
        echo "<p>บันทึกบริษัทผู้ผลิตเรียบร้อยแล้ว!</p>";
    }

    $manufacturers = $conn->query("SELECT * FROM Manufacturers")->fetchAll();
    echo "<h3>ข้อมูลบริษัทผู้ผลิตที่มีอยู่</h3><table><tr><th>ID</th><th>ชื่อ</th><th>ช่องทางการติดต่อ</th></tr>";
    foreach ($manufacturers as $manufacturer) {
        echo "<tr><td>{$manufacturer['manufacturer_id']}</td><td>{$manufacturer['name']}</td><td>{$manufacturer['contact_info']}</td></tr>";
    }
    echo "</table>";
    ?>
        <h2>บริษัทผู้ผลิต</h2>
    <form method="POST" action="tc.php">
        <input type="text" name="manufacturer_id" placeholder="รหัสผู้ผลิต" required>
        <input type="text" name="manufacturer_name" placeholder="ชื่อผู้ผลิต" required>
        <input type="text" name="contact_info" placeholder="ช่องทางการติดต่อ" required>
        <button type="submit" name="save_manufacturer">บันทึกบริษัทผู้ผลิต</button>
    </form>
</section>

<!-- Section: เจ้าหน้าที่ซ่อมบำรุง -->
<section>


    <?php
    if (isset($_POST['save_technician'])) {
        $stmt = $conn->prepare("INSERT INTO Technicians (technician_id, name, phone, email, specialization) VALUES (?,?, ?, ?, ?)");
        $stmt->execute([$_POST['technician_id'], $_POST['technician_name'], $_POST['phone'], $_POST['email'], $_POST['specialization']]);
        echo "<p>บันทึกเจ้าหน้าที่ซ่อมบำรุงเรียบร้อยแล้ว!</p>";
    }

    $technicians = $conn->query("SELECT * FROM Technicians")->fetchAll();
    echo "<h3>ข้อมูลเจ้าหน้าที่ซ่อมบำรุงที่มีอยู่</h3><table><tr><th>ID</th><th>ชื่อ</th><th>โทรศัพท์</th><th>อีเมล</th><th>ความเชี่ยวชาญ</th></tr>";
    foreach ($technicians as $technician) {
        echo "<tr><td>{$technician['technician_id']}</td><td>{$technician['name']}</td><td>{$technician['phone']}</td><td>{$technician['email']}</td><td>{$technician['specialization']}</td></tr>";
    }
    echo "</table>";
    ?>
        <h2>เจ้าหน้าที่ซ่อมบำรุง</h2>
    <form method="POST" action="tc.php">
        <input type="text" name="technician_id" placeholder="รหัสเจ้าหน้าที่" required>
        <input type="text" name="technician_name" placeholder="ชื่อเจ้าหน้าที่" required>
        <input type="text" name="phone" placeholder="เบอร์โทรศัพท์" required>
        <input type="email" name="email" placeholder="อีเมล" required>
        <input type="text" name="specialization" placeholder="ความเชี่ยวชาญ" required>
        <button type="submit" name="save_technician">บันทึกเจ้าหน้าที่ซ่อมบำรุง</button>
    </form>
</section>

<!-- Section: อะไหล่ -->
<section>


    <?php
    if (isset($_POST['save_part'])) {
        $stmt = $conn->prepare("INSERT INTO Spare_Parts (part_id, name, description, quantity_in_stock, price_per_unit) VALUES (?,?, ?, ?, ?)");
        $stmt->execute([$_POST['part_id'], $_POST['part_name'], $_POST['description'], $_POST['quantity_in_stock'], $_POST['price_per_unit']]);
    }

    $parts = $conn->query("SELECT * FROM Spare_Parts")->fetchAll();
    echo "<h3>ข้อมูลอะไหล่ที่มีอยู่</h3><table><tr><th>ID</th><th>ชื่อ</th><th>รายละเอียด</th><th>จำนวนในคลัง</th><th>ราคาต่อหน่วย</th></tr>";
    foreach ($parts as $part) {
        echo "<tr><td>{$part['part_id']}</td><td>{$part['name']}</td><td>{$part['description']}</td><td>{$part['quantity_in_stock']}</td><td>{$part['price_per_unit']}</td></tr>";
    }
    echo "</table>";
    ?>
        <h2>อะไหล่</h2>
    <form method="POST" action="tc.php">
        <input type="text" name="part_id" placeholder="รหัสอะไหล่" required>
        <input type="text" name="part_name" placeholder="ชื่ออะไหล่" required>
        <input type="text" name="description" placeholder="รายละเอียด" required>
        <input type="number" name="quantity_in_stock" placeholder="จำนวนในคลัง" required>
        <input type="number" step="0.01" name="price_per_unit" placeholder="ราคาต่อหน่วย" required>
        <button type="submit" name="save_part">บันทึกอะไหล่</button>
    </form>
</section>


</body>
</html>
