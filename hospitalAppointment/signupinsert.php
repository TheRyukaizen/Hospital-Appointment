<?php
    $databaseservername =   "localhost";
    $databaseusername   =   "root";
    $databasepassword   =   "root";
    $databasename       =   "cmpe321";

    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);


    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
    $sql = "SELECT * FROM cmpe321.users WHERE username = '" . $username ."' ";
    $result = $conn->query($sql);

    if($result->num_rows > 0 ){
        $conn->close();
        die("There is already a user with this username, please give another name");
    }elseif($result->num_rows == 0 ){
        $userType = "patient";
        $sqlInsert = "INSERT INTO users(userID, username, password, userType) VALUES('null', '" . $username . "','" . $password . "','" . $userType . "')";
        $resultInsert = $conn->query($sqlInsert);

        $resultNewAdded = $conn->query($sql);
        $row = $resultNewAdded->fetch_row();
        session_start();

        $_SESSION["userID"] = $row["userID"];
        $_SESSION["username"] = $username;
        $_SESSION["userType"] = $userType;
        $conn->close();
        header("Location:http://localhost:8888/hospitalAppointment/homepage.php");
    }



    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>