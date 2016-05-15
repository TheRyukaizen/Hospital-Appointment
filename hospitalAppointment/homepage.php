<html>

<head>
    <title>Home Page</title>
</head>
<body>
<?php

/**
 * Created by PhpStorm.
 * User: tunahansalih
 * Date: 11/05/16
 * Time: 23:19
 */
$databaseservername =   "localhost";
$databaseusername   =   "root";
$databasepassword   =   "root";
$databasename       =   "cmpe321";
session_start();
//If session is not started
if (!isset($_SESSION['username'])) {
    
    $msg = "Please <a href = 'http://localhost:8888/hospitalAppointment/login.php'>log in</a> to view this page";
    echo $msg;

}
//If session is already started
else{
    //If Current user is admin
    if($_SESSION["userType"] == 'admin') {
        ?>
            Welcome, <? echo $_SESSION['username'] ?>.

            <form method="POST" action="logout.php">
                <input type="submit" value="Log Out">
            </form>
            <p style="text-align: center"> BRANCHES</p>

        <?
            //Create connection
            $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
            $sql = "SELECT * FROM cmpe321.branches ";
            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                ?><p style="text-align: center"> No Branch Exists.</p><?
            } else {
                while ($row = $result->fetch_assoc()) {
                    ?><p style="text-align: center"><? echo $row['branchName'] ?></p>
        <?
                }
            }

        ?>
        <form method="POST" action="homepage.php">
            <p style="text-align: center">
                <input type="submit" value="Add" name="addBranch">
                <input type="submit" value="Remove" name="removeBranch">
                <input type="submit" value="Edit" name="editBranch">
            </p>
        </form>
        <?
                if (isset($_POST["addBranch"])){
        ?>
                    <p style="text-align: center">
                        <form method="POST" action="homepage.php">
                        Branch <input type="text" name="branchName" required> <input type="submit" name="addBranchName"
                                                                                     value="Add Branch">
                        </form>
                    </p>
        <?

                } elseif (isset($_POST["removeBranch"])) {
        ?>
                    <p style="text-align: center">
                        <form method="POST" action="homepage.php">
                        Branch <input type="text" name="branchName" required> <input type="submit" name="removeBranchName"
                                                                                     value="Remove Branch">
                        </form>
                    </p>
        <?
                } elseif (isset($_POST["editBranch"])) {
        ?>
                    <p style="text-align: center">
                        <form method="POST" action="homepage.php">
                            Old Branch Name<input type="text" name="oldBranchName" required> <br>
                            New Branch Name <input type="text" name="newBranchName" required> <br>
                            <input type="submit" name="editBranchName" value="Edit Branch">
                        </form>
                    </p>
        <?

                } elseif (isset($_POST["addBranchName"])) {
                    $branchName = test_input($_POST["branchName"]);
                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.branches WHERE branchName = '" . $branchName . "' ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo "A branch with the given name already exists. Cannot Add " . $branchName;
                        $conn->close();
                    } else {
                        $sqlInsert = "INSERT INTO branches(branchID, branchName) VALUES('null', '" . $branchName . "')";
                        $resultInsert = $conn->query($sqlInsert);
                        if ($resultInsert > 0) {
                            echo("Branch " . $branchName . " added successfully.");
                            $conn->close();

                        } else {
                            echo("Branch " . $branchName . " couldn't be added.");
                            $conn->close();
                        }

                    }
                } elseif (isset($_POST["removeBranchName"])) {
                    $branchName = test_input($_POST["branchName"]);
                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.branches WHERE branchName = '" . $branchName . "' ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $sqlInsert = "DELETE FROM cmpe321.branches WHERE branchName = '" . $branchName . "' ";
                        $resultInsert = $conn->query($sqlInsert);
                        if ($resultInsert > 0) {
                            //echo("Branch " . $branchName . " added successfully.");
                            header("Location: http://localhost:8888/hospitalAppointment/homepage.php");
                        } else {
                            echo("Branch " . $branchName . " couldn't be added.");
                        }
                    } else {
                        echo "A Branch With This Name Doesn't Exist";
                    }
                    $conn->close();
                } elseif (isset($_POST["editBranchName"])){
                    $oldBranchName = test_input($_POST["oldBranchName"]);
                    $newBranchName = test_input($_POST["newBranchName"]);
                    $oldBranchID = 0;
                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.branches WHERE branchName = '" . $oldBranchName . "' ";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $oldBranchID = $row["branchID"];
                        $sqlUpdate = "UPDATE cmpe321.branches SET branchName='" . $newBranchName ."' WHERE branchID='" . $oldBranchID."' ";
                        $resultUpdate = $conn->query($sqlUpdate);
                        if($resultUpdate > 0){
                            echo "Updated Successfully";
                        }else{
                            echo "Couldn't updated";
                        }
                    }else{
                        echo "No branch with that name";
                    }
                    $conn->close();
                }
        ?>


            <p style="text-align: center"> DOCTORS </p>
                <?
                //Create connection
                $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                $sql = "SELECT * FROM cmpe321.doctors ";
                $result = $conn->query($sql);

                if ($result->num_rows == 0) {
                    ?><p style="text-align: center"> No Doctor Exists.</p><?
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $sqlBranch = "SELECT * FROM cmpe321.branches WHERE branchID='" . $row["branchID"] ."' ";
                        $resultBranch = $conn->query($sqlBranch);
                        $rowBranch = $resultBranch->fetch_assoc();
                        ?><p style="text-align: center"><? echo $rowBranch['branchName'] . " " .$row['doctorName'] ?></p>
                        <?
                    }
                    $conn->close();
                }

                ?>
        <form method="post" action="homepage.php">
            <p style="text-align: center">
                <input type="submit" value="Add" name="addDoctor">
                <input type="submit" value="Remove" name="removeDoctor">
                <input type="submit" value="Edit" name="editDoctor">
            </p>
        </form>
            <?
                if (isset($_POST["addDoctor"])) {
        ?>
            <p style="text-align: center">
                <form method="POST" action="homepage.php">
                    Doctor <input type="text" name="doctorName" required><br>
                    Branch <input type="text" name="branchName" required><br>
                    <input type="submit" name="addDoctorName" value="Add Doctor">
                </form>
            </p>
        <?
                } elseif (isset($_POST["removeDoctor"])) {
        ?>
            <p style="text-align: center">
                <form method="POST" action="homepage.php">
                    Doctor <input type="text" name="doctorName" required><br>
                    <input type="submit" name="removeDoctorName" value="Remove Doctor">
                </form>
            </p>
        <?
                } elseif (isset($_POST["editDoctor"])) {
        ?>
            <p style="text-align: center">
                <form method="POST" action="homepage.php">
                    Old Doctor Name <input type="text" name="oldDoctorName" required><br>
                    New Doctor Name <input type="text" name="newDoctorName" required>
                    New Doctor Branch <input type="text" name="newDoctorBranch" required><br>
                    <input type="submit" name="editDoctorName" value="Edit Doctor">
                </form>
            </p>
        <?
                } elseif (isset($_POST["addDoctorName"])){
                    $doctorName = test_input($_POST["doctorName"]);
                    $branchName = test_input($_POST["branchName"]);

                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.doctors WHERE doctorName='" .$doctorName. "' ";
                    $resultDoctor = $conn->query($sql);
                    if($resultDoctor->num_rows > 0){
                        echo "A doctor with that name exists.";
                    }else{
                        $sqlBranch = "SELECT * FROM cmpe321.branches WHERE branchname= '" . $branchName ."' ";
                        $resultBranch = $conn->query($sqlBranch);
                        if($resultBranch->num_rows == 0){
                            echo "There is no such branch";
                        }else{
                            $rowBranch = $resultBranch->fetch_assoc();
                            $branchID = $rowBranch["branchID"];
                            $sqlDoctor = "INSERT INTO cmpe321.doctors(doctorID, doctorName, branchID) VALUES( 'null', '". $doctorName ."', '".$branchID. "') ";
                            $resultAdd = $conn->query($sqlDoctor);
                            if($resultAdd > 0){
                                echo "Doctor Added Successfully";
                            }
                        }
                    }

                    $conn->close();



                } elseif (isset($_POST["removeDoctorName"])){
                    $doctorName = test_input($_POST["doctorName"]);
                    $branchName = test_input($_POST["branchName"]);

                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.doctors WHERE doctorName='" .$doctorName. "' ";
                    $resultDoctor = $conn->query($sql);
                    if($resultDoctor->num_rows == 0) {
                        echo "A doctor with that name doesn't exist.";
                    }else{
                        $sqlRemove = "DELETE FROM cmpe321.doctors WHERE doctorName='" . $doctorName . "'";
                        echo $sqlRemove;
                        $resultRemove = $conn->query($sqlRemove);
                        if($resultRemove > 0){
                            echo "Doctor Removed Successfully.";
                        }else{
                            echo "Doctor Could't Remove";
                        }
                    }
                    $conn->close();
                } elseif (isset($_POST["editDoctorName"])){
                    $oldDoctorName = test_input($_POST["oldDoctorName"]);
                    $newDoctorName = test_input($_POST["newDoctorName"]);
                    $newDoctorBranch = test_input($_POST["newDoctorBranch"]);

                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sqlOldDoctor = "SELECT * FROM cmpe321.doctors WHERE doctorName='" .$oldDoctorName. "' ";
                    $resultDoctor = $conn->query($sqlOldDoctor);
                    if($resultDoctor->num_rows > 0){
                        $sqlNewDoctor = "SELECT * FROM cmpe321.doctors WHERE doctorName='" .$newDoctorName. "' ";
                        $resultDoctor = $conn->query($sqlNewDoctor);
                        if($resultDoctor->num_rows > 0){
                            echo "A doctor with the given new name exists";
                        }else{
                            $sqlBranch = "SELECT * FROM cmpe321.branches WHERE branchName='".$newDoctorBranch."' ";
                            $resultBranch = $conn->query($sqlBranch);
                            if($resultBranch->num_rows == 0){
                                echo "There is no such branch";
                            }else{
                                $rowBranch = $resultBranch->fetch_assoc();
                                $newBranchID = $rowBranch["branchID"];
                                $sqlUpdate = "UPDATE cmpe321.doctors 
                                                  SET doctorName='" .$newDoctorName . "' , branchID='" . $newBranchID ."' 
                                                  WHERE doctorname='". $oldDoctorName."'" ;
                                $resultUpdate = $conn->query($sqlUpdate);
                                if($resultUpdate > 0){
                                    echo "Doctor Updated Successfully";
                                }else{
                                    echo "Doctor Couldn't Updated";
                                }

                            }
                        }

                    }else{
                        echo "A doctor with that name doesn't exist.";

                    }
                    $conn->close();
                }

                ?>

        <?
    }elseif($_SESSION["userType"] == 'patient'){


        ?>
        Welcome, <? echo $_SESSION['username'] ?>.

        <form method="POST" action="logout.php">
            <input type="submit" value="Log Out">
        </form>
        <p style="text-align: center"> APPOINTMENTS </p>

        <?
        $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
        $sqlUser = "SELECT * FROM cmpe321.users WHERE username='" .$_SESSION["username"]."' ";
        $resultUser = $conn->query($sqlUser);
        $rowUser = $resultUser->fetch_assoc();

        $sqlAppointment = "SELECT * FROM cmpe321.appointment WHERE patientID='".$rowUser["userID"] ."'";
        $result = $conn->query($sqlAppointment);

        if ($result->num_rows == 0) {
            ?><p style="text-align: center"> No Appointment </p><?
        } else {
            while ($row = $result->fetch_assoc()) {
                $sqlDoctor = "SELECT * FROM cmpe321.doctors WHERE doctorID='" .$row["doctorID"]."' ";
                $resultDoctor = $conn->query($sqlDoctor);
                $rowDoctor = $resultDoctor->fetch_assoc();
                $sqlBranch = "SELECT * FROM cmpe321.branches WHERE branchID='".$rowDoctor["branchID"]."'";
                $resultBranch = $conn->query($sqlBranch);
                $rowBranch = $resultBranch->fetch_assoc();
                ?><p style="text-align: center"><? echo $rowBranch['branchName'] ."-".$rowDoctor["doctorName"] ." ". $row["dateAppointment"] ?></p>
                <?
            }
        }

        $conn->close();
        ?>
        <form method="POST" action="homepage.php">
            <p style="text-align: center">
                <input type="submit" value="Make an Appointment" name="makeAppointment">
                <input type="submit" value="Cancel Appointment" name="removeAppointment">
                <input type="submit" value="Edit Appointment" name="editAppointment">
            </p>
        </form>
        <?
        
        if(isset($_POST["makeAppointment"])){
            ?>
            <p style="text-align: center">
            <form method="POST" action="homepage.php" >
                <select name="selectedDoctor">
                    <?
                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.doctors ";
                    $result = $conn->query($sql);

                    while($row = $result->fetch_assoc()){
                        $sqlBranch = "SELECT * FROM cmpe321.branches WHERE branchID='". $row["branchID"]."'";
                        $resultBranch = $conn->query($sqlBranch);
                        $rowBranch = $resultBranch->fetch_assoc();
                        ?><option value="<?echo $row["doctorID"]?>"><?echo$rowBranch["branchName"]."-".$row["doctorName"]?></option><?
                    }
                    ?>

                </select>

                    Date And Time<input type="datetime-local" step="300" name="dateAndTime" value="<? echo str_replace(" ","T" ,date("Y-m-d H:i" ,(time()-time()%300)));?>"
                                                                                            min="<? echo str_replace(" ","T" ,date("Y-m-d H:i" ,(time()-time()%300)));?>">
                <input type="submit" value="Submit" name="submitAppointment">
            </form>
            </p>

            <?
            $conn->close();
        }elseif(isset($_POST["removeAppointment"])){

            ?>
            <form method="POST" action="homepage.php">
                <select name="removedAppointment">
                    <?
                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.appointment WHERE patientID='" .$_SESSION["userID"]. "' ";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        ?><option value="<? echo $row["appointmentID"]?>"><?echo $row["dateAppointment"]?></option><?
                    }
                    ?>
                </select>
                <input type="submit" value="Remove" name="submitRemoval">
            </form>
            <?
            $conn->close();
        }elseif (isset($_POST["editAppointment"])){
            ?>
            <form method="POST" action="homepage.php">
                Old Appointment
                <select name="editedAppointment">
                    <?
                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.appointment WHERE patientID='" .$_SESSION["userID"]. "' ";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        ?><option value="<? echo $row["appointmentID"]?>"><?echo $row["dateAppointment"]?></option><?
                    }
                    ?>
                </select>
                New Appointment
                <select name="editedDoctor">
                    <?
                    $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
                    $sql = "SELECT * FROM cmpe321.doctors ";
                    $result = $conn->query($sql);

                    while($row = $result->fetch_assoc()){
                        $sqlBranch = "SELECT * FROM cmpe321.branches WHERE branchID='". $row["branchID"]."'";
                        $resultBranch = $conn->query($sqlBranch);
                        $rowBranch = $resultBranch->fetch_assoc();
                        ?><option value="<?echo $row["doctorID"]?>"><?echo$rowBranch["branchName"]."-".$row["doctorName"]?></option><?
                    }
                    ?>

                </select>

                <input type="datetime-local" step="300" name="dateAndTime" value="<? echo str_replace(" ","T" ,date("Y-m-d H:i" ,(time()-time()%300)));?>"
                                    min="<? echo str_replace(" ","T" ,date("Y-m-d H:i" ,(time()-time()%300)));?>">

                <input type="submit" value="Edit" name="submitEdit">
            </form>
            <?
        }elseif(isset($_POST["submitAppointment"])){
            $doctorID = $_POST["selectedDoctor"];
            $dateAndTime = str_replace("T" ," " , $_POST["dateAndTime"]);

            $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
            $sql = "SELECT * FROM cmpe321.users WHERE username='" .$_SESSION["username"] . "'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $userID = $row["userID"];

            $sql = "SELECT * FROM cmpe321.appointment WHERE patientID='" .$userID."' AND dateAppointment='" .$dateAndTime. "'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                echo "You have another appointment at that time.";
            }else{
                $sql = "SELECT * FROM cmpe321.appointment WHERE doctorID='" .$doctorID."' AND dateAppointment='" .$dateAndTime. "'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    echo "Doctor has another appointment at this time";
                }else{
                    $sql = "INSERT INTO cmpe321.appointment(appointmentID, patientID, doctorID, dateAppointment) VALUES('null', '".$userID."','".$doctorID."','".$dateAndTime."')";
                    $result = $conn->query($sql);
                    if($result > 0){
                        echo "Appointment Successful";
                    }else{
                        echo "Appointment Failed";
                    }

                }
            }
            $conn->close();
        }elseif(isset($_POST["submitRemoval"])){

            $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);
            $sql = "DELETE FROM cmpe321.appointment WHERE appointmentID='" .$_POST["removedAppointment"]."'";
            $result = $conn->query($sql);
            if($result > 0){
                echo "Removed Successfully";
            }else{
                echo "Couldn't Removed";
            }
        }elseif(isset($_POST["submitEdit"])){

            $doctorID = $_POST["editedDoctor"];
            $dateAndTime = str_replace("T" ," " , $_POST["dateAndTime"]);

            $conn = new mysqli($databaseservername, $databaseusername, $databasepassword, $databasename);

            $userID = $SESSION["userID"];

            $sql = "SELECT * FROM cmpe321.appointment WHERE doctorID='" .$doctorID."' AND dateAppointment='" .$dateAndTime. "'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                echo "The doctor has another appointment at that time.";
            }else{
                $row = $result->fetch_assoc();
                $sql = "UPDATE cmpe321.appointment SET doctorID='" .$doctorID."', dateAppointment='" .$dateAndTime."' WHERE appointmentID='".$_POST["editedAppointment"]."'";
                $result = $conn->query($sql);
                if($result > 0){
                    echo "Appointment Successful";
                }else {
                    echo "Appointment Failed";
                }

            }

            $conn->close();
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


</body>
</html>