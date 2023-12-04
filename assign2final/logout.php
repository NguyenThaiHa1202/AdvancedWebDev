<?php
    //clear session variable
    session_unset();
    session_destroy();
    
    //redirect to home
    header("Location: index.php");
    exit;
?>