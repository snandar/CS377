<?php
    session_start();
    require 'configure.php';
    
    $instructorID = $_SESSION["instructorID"];
    $courseID = $_SESSION["courseID"];
    $studentID = $_SESSION["studentID"];
    
    
    //find randomID
    $randomID_query = "SELECT randomID FROM ranID WHERE studentID = '$studentID'";
    if($randomID_result = mysqli_query($conn, $randomID_query)){
        if(mysqli_num_rows($randomID_result) > 0){
            $rowrandomID = mysqli_fetch_array($randomID_result);
            $randomID = $rowrandomID[0];
        }
    }    
    
?>


<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        
        <?php

            $class_query = "SELECT classNumber FROM student_takes_class WHERE studentID='$studentID' AND classNumber IN(SELECT classID from class WHERE courseID = '$courseID' AND instructorID = '$instructorID')";
            if($class_result = mysqli_query($conn, $class_query)){
                if(mysqli_num_rows($class_result) > 0){ 
                    while ($class_row = mysqli_fetch_array($class_result)) {
                        
                        $classID = $class_row['classNumber'];
                        $_SESSION['classID'] = $classID;
                        
                        $taken_query = "SELECT randomID FROM evaluation WHERE randomID='$randomID' AND classID ='$classID'";
                        if($taken_result = mysqli_query($conn, $taken_query)){
                            if(mysqli_num_rows($taken_result) > 0){
                                $_SESSION['message'] = "You have already evaluated this class";
                                header("location: error.php");
                            }
                            elseif(mysqli_num_rows($taken_result) == 0){
                                header("location: evaluation_form.php");
                            }
                        }
                        else{
                            echo 'error';
                        }

                    }
                }
                elseif(mysqli_num_rows($class_result) == 0){
                    $_SESSION['message'] = "You have not taken this course with this professor!";
                    header("location: error.php");
                }
            }

        ?>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    </body>
</html>
