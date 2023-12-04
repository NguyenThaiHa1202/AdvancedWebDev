<?php
    function connect_db() {
        $host = 'feenix-mariadb.swin.edu.au';
        $user = "s103430053"; // your user name
        $pswd = "120202"; // your password d(date of birth – ddmmyy)
        $dbnm = "s103430053_db"; // your database
        
        // Create connection
        $conn = mysqli_connect($host, $user, $pswd, $dbnm);
        
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $conn;
    };
?>