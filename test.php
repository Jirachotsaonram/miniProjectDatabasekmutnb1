<?php 
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['table'])) {
    $table = $_POST['table'];

    // ตรวจสอบ ID ซ้ำก่อนบันทึกข้อมูล
    function checkDuplicateID($pdo, $table, $column, $id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    switch ($table) {
        case 'equipments':
            if (checkDuplicateID($pdo, 'Equipments', 'equipment_id', $_POST['equipment_id'])) {
                echo "<p style='color:red;'>Error: Equipment ID already exists!</p>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO Equipments (equipment_id, name, description, model, purchase_date, warranty_expiration, status_e, manufacturer_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['equipment_id'], $_POST['name'], $_POST['description'], $_POST['model'], $_POST['purchase_date'], $_POST['warranty_expiration'], $_POST['status_e'], $_POST['manufacturer_id']]);
                echo "<p style='color:green;'>Equipment added successfully.</p>";
            }
            break;

        case 'manufacturers':
            if (checkDuplicateID($pdo, 'manufacturers', 'manufacturer_id', $_POST['manufacturer_id'])) {
                echo "<p style='color:red;'>Error: Manufacturer ID already exists!</p>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO manufacturers (manufacturer_id, name, contact_info) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['manufacturer_id'], $_POST['name'], $_POST['contact_info']]);
                echo "<p style='color:green;'>Manufacturer added successfully.</p>";
            }
            break;

        case 'technicians':
            if (checkDuplicateID($pdo, 'Technicians', 'technician_id', $_POST['technician_id'])) {
                echo "<p style='color:red;'>Error: Technician ID already exists!</p>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO Technicians (technician_id, name, phone, email, specialization) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['technician_id'], $_POST['name'], $_POST['phone'], $_POST['email'], $_POST['specialization']]);
                echo "<p style='color:green;'>Technician added successfully.</p>";
            }
            break;

        case 'spare_parts':
            if (checkDuplicateID($pdo, 'Spare_Parts', 'part_id', $_POST['part_id'])) {
                echo "<p style='color:red;'>Error: Part ID already exists!</p>";
            } else {
                $stmt = $pdo->prepare("INSERT INTO Spare_Parts (part_id, name, description, quantity_in_stock, price_per_unit) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['part_id'], $_POST['name'], $_POST['description'], $_POST['quantity_in_stock'], $_POST['price_per_unit']]);
                echo "<p style='color:green;'>Spare Part added successfully.</p>";
            }
            break;
    }
}

// ฟังก์ชันดึงข้อมูลจากแต่ละตารางเพื่อแสดงผล
function fetchData($table, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM $table");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$equipments = fetchData('Equipments', $pdo);
$manufacturers = fetchData('manufacturers', $pdo);
$technicians = fetchData('Technicians', $pdo);
$spare_parts = fetchData('Spare_Parts', $pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance System</title>
    <link rel="stylesheet" href="aa.css">
</head>
<body>
    <h1>Maintenance Management System</h1>

    <!-- Equipment Form -->
    <h2>Add Equipment</h2>
    <form method="post">
        <input type="hidden" name="table" value="equipments">
        <input type="text" name="equipment_id" placeholder="Equipment ID" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="text" name="model" placeholder="Model" required>
        <input type="date" name="purchase_date" required>
        <input type="date" name="warranty_expiration" required>
        <input type="text" name="status_e" placeholder="Status" required>
        <input type="text" name="manufacturer_id" placeholder="Manufacturer ID" required>
        <button type="submit">Save Equipment</button>
    </form>

    <h2>Existing Equipments</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Description</th></tr>
        <?php foreach ($equipments as $equipment): ?>
            <tr><td><?= htmlspecialchars($equipment['equipment_id']) ?></td><td><?= htmlspecialchars($equipment['name']) ?></td><td><?= htmlspecialchars($equipment['description']) ?></td></tr>
        <?php endforeach; ?>
    </table>

    <!-- Manufacturer Form -->
    <h2>Add Manufacturer</h2>
    <form method="post">
        <input type="hidden" name="table" value="manufacturers">
        <input type="text" name="manufacturer_id" placeholder="Manufacturer ID" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="contact_info" placeholder="Contact Info" required>
        <button type="submit">Save Manufacturer</button>
    </form>

    <h2>Existing Manufacturers</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Contact Info</th></tr>
        <?php foreach ($manufacturers as $manufacturer): ?>
            <tr><td><?= htmlspecialchars($manufacturer['manufacturer_id']) ?></td><td><?= htmlspecialchars($manufacturer['name']) ?></td><td><?= htmlspecialchars($manufacturer['contact_info']) ?></td></tr>
        <?php endforeach; ?>
    </table>

    <!-- Technician Form -->
    <h2>Add Technician</h2>
    <form method="post">
        <input type="hidden" name="table" value="technicians">
        <input type="text" name="technician_id" placeholder="Technician ID" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="specialization" placeholder="Specialization" required>
        <button type="submit">Save Technician</button>
    </form>

    <h2>Existing Technicians</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Specialization</th></tr>
        <?php foreach ($technicians as $technician): ?>
            <tr><td><?= htmlspecialchars($technician['technician_id']) ?></td><td><?= htmlspecialchars($technician['name']) ?></td><td><?= htmlspecialchars($technician['phone']) ?></td><td><?= htmlspecialchars($technician['email']) ?></td><td><?= htmlspecialchars($technician['specialization']) ?></td></tr>
        <?php endforeach; ?>
    </table>

    <!-- Spare Parts Form -->
    <h2>Add Spare Part</h2>
    <form method="post">
        <input type="hidden" name="table" value="spare_parts">
        <input type="text" name="part_id" placeholder="Part ID" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="number" name="quantity_in_stock" placeholder="Quantity in Stock" required>
        <input type="number" step="0.01" name="price_per_unit" placeholder="Price per Unit" required>
        <button type="submit">Save Spare Part</button>
    </form>

    <h2>Existing Spare Parts</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Description</th><th>Quantity in Stock</th><th>Price per Unit</th></tr>
        <?php foreach ($spare_parts as $part): ?>
            <tr><td><?= htmlspecialchars($part['part_id']) ?></td><td><?= htmlspecialchars($part['name']) ?></td><td><?= htmlspecialchars($part['description']) ?></td><td><?= htmlspecialchars($part['quantity_in_stock']) ?></td><td><?= htmlspecialchars($part['price_per_unit']) ?></td></tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
