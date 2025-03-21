<?php

if (!isset($_SESSION['role'])) {
    header("Location: admin_login.php");
    exit();
}

$role = $_SESSION['role'];

// Include sidebar based on role
if ($role === 'superadmin') {
    include '../components/superadmin_sidebar.php';
} elseif ($role === 'subadmin') {
    include '../components/subadmin_sidebar.php';
} else {
    echo "Unauthorized access.";
    exit();
}
?>
