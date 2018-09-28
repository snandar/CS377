<?php

    session_start();
    require 'configure.php';
    
    $studentID = $_SESSION['studentID'];
    $courseID = $_SESSION['courseID'];
    $instructorID = $_SESSION['instructorID'];
    
    
    //find randomID
    $randomID_query = "SELECT randomID FROM ranID WHERE studentID = '$studentID'";
    if($randomID_result = mysqli_query($conn, $randomID_query)){
        if(mysqli_num_rows($randomID_result) > 0){
            $rowrandomID = mysqli_fetch_array($randomID_result);
            $randomID = $rowrandomID[0];
        }
    }    
            
    //find classID
    $classID_query = "SELECT classNumber FROM student_takes_class WHERE studentID='$studentID' AND classNumber IN (SELECT classID FROM class WHERE courseID='$courseID' AND instructorID ='$instructorID')";
    if($classID_result = mysqli_query($conn, $classID_query)){
        if(mysqli_num_rows($classID_result) > 0){
            $rowclassID = mysqli_fetch_array($classID_result);
            $classID = $rowclassID[0];
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
      <title>Verify Class</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        
        <script src="../../assets/js/html5shiv.js"></script>
	<script src="../../assets/js/respond.min.js"></script>

      
    </head>
    
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        if (isset($_POST['exit'])) { 
            header("location: logout.php");
        }
    }
    ?>

    <body>
        <div class="container">
            <div class="page-header">
                <h1>Form submitted</h1>
            </div>
            <div class="container"><p>Here is what is submitted:</p></div>
            <?php
                $qID_query ="SELECT qID, qtext FROM question";
                if($qID_result = mysqli_query($conn, $qID_query)){
                    if(mysqli_num_rows($qID_result) > 0){

                        while ($row = mysqli_fetch_array($qID_result)) {
                            $qID = $row['qID'];
                            $qtext = $row['qtext'];
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qtext.'</div>';
                            echo '<div class="panel-body">';
                            if (isset($_POST[$qID])) {

                                $comment = $_POST[$qID];


                                $insert_query="INSERT INTO evaluation VALUES('$qID', '$classID', '$randomID', '$comment')";
                                if($insert_result = mysqli_query($conn, $insert_query)){
                                echo ''.$comment.'';

                                }
                            }
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                        }

                    }
                }

            ?>
        </div>
        <div class="container">
        <form id ="exit" method="POST">
        <button class="btn btn-primary btn-lg btn-block" name="exit" style="display: block; margin: 0 auto;"/>Log Out</button>
        </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    </body>
</html>
