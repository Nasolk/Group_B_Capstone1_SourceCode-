<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/BaGoApp/includes/db_connection.php';

// Ensure resident is logged in
if (!isset($_SESSION['resident_id'])) {
    header("Location: /BaGoApp/residents_auth/login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_SESSION['resident_id'];
    $type = $_POST['type'];
    $purpose = $_POST['purpose'];

    $stmt = $conn->prepare("INSERT INTO certificates (resident_id, type, purpose, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("iss", $resident_id, $type, $purpose);
    $stmt->execute();

    $success = "Certificate request submitted successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Certificate</title>
    <link rel="stylesheet" href="/BaGoApp/assets/bootstrap.min.css">
</head>
<body class="container mt-5">

<h3>Request a Barangay Certificate</h3>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label>Certificate Type</label>
        <select name="type" class="form-select" required>
            <option value="">Select Type</option>
            <option value="Barangay Clearance">Barangay Clearance</option>
            <option value="Indigency">Certificate of Indigency</option>
            <option value="Residency">Certificate of Residency</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Purpose</label>
        <textarea name="purpose" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Request</button>
</form>

</body>
</html>
