<?php
/**
 * Created by PhpStorm.
 * User: tunahansalih
 * Date: 12/05/16
 * Time: 00:58
 */


    session_start();
    session_destroy();

    header("Location:http://localhost:8888/hospitalAppointment/login.php");
?>
