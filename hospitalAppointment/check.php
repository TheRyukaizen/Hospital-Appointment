<?php
    $databaseservername =   "localhost";
    $databaseusername   =   "root";
    $databasepassword   =   "root";
    $databasename       =   "cmpe321";

    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
 if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
        $username = test_input($_POST["username"]);
        $password = test_input($_POST["password"]);

        $sql = "SELECT userID, username, userType FROM cmpe321.users WHERE username = '" . $username ."' AND password = '" . $password ."' ";
        $result = $conn->query($sql);



        if( $result->num_rows > 0 ){
            session_start();
            $row = $result->fetch_assoc();

            $_SESSION["userID"] =   $row["userID"];
            $_SESSION["userType"] = $row["userType"];
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;

            header("Location:http://localhost:8888/hospitalAppointment/homepage.php");
            $conn->close();
            die();
        }else{
            $conn->close();
            die("Wrong username or password");
        }

    }


    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
