<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear session storage via JavaScript
echo "<script>
    sessionStorage.clear();
    window.location.href = '../WebDevelopmentCourse/signin.html';
</script>";
exit();
?>