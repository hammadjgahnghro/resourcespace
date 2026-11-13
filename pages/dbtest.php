<?php

error_reporting(0);

// Prevent mysqli from throwing uncaught fatal exceptions in PHP 8.1+
mysqli_report(MYSQLI_REPORT_OFF);

if (file_exists("../include/config.php")) {
    die('1');
}

$server   = trim($_REQUEST['mysqlserver'] ?? '');
$username = $_REQUEST['mysqlusername'] ?? '';
$password = $_REQUEST['mysqlpassword'] ?? '';
$dbname   = $_REQUEST['mysqldb'] ?? '';

if ($server === '') {
    echo '202';
    exit();
}

if ($username === '') {
    echo '201';
    exit();
}

if (isset($_REQUEST['mysqlserver'], $_REQUEST['mysqlusername'], $_REQUEST['mysqlpassword'])) {
    // Initialize without SSL flags
    $dbtest_connection = mysqli_init();
    
    // Attempt plain, non-SSL connection
    if (!@mysqli_real_connect($dbtest_connection, $server, $username, $password)) {
        // Use mysqli_connect_errno() because $dbtest_connection failed
        if (mysqli_connect_errno() === 1045) {
            echo '201'; // Access denied (invalid username/password)
        } else {
            echo '202'; // Host unreachable or connection error
        }
        exit();
    }

    if ($dbname !== '' && mysqli_select_db($dbtest_connection, $dbname)) {
        echo '200'; // Success
    } else {
        echo '203'; // Connected to server, but database selection failed
    }

    mysqli_close($dbtest_connection);
}
