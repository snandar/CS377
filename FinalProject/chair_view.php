<?php

    session_start();
    require 'configure.php';
    
    $chairID = $_SESSION["chairID"];

    $departmentID_query = "SELECT departmentID FROM department WHERE chairID ='$chairID'";
    if($deptID_result = mysqli_query($conn, $departmentID_query)){
        if(mysqli_num_rows($deptID_result) > 0){
            $rowdeptID = mysqli_fetch_array($deptID_result);
            $departmentID = $rowdeptID[0];
            $_SESSION["departmentID"] = $departmentID;
            
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
      <title>Department Chair View</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
      
      <style>
        body {
            background-image: url("korea.jpg");
            background-repeat: no-repeat;
            background-size: cover;
        }
        .center {
            position: absolute;
            height: 200px;
            width: 400px;
            margin: -100px 0 0 -200px;
            top: 50%;
            left: 50%;
        }
       </style>
      
    </head>
    
    <?php
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        if (isset($_POST['question'])) { 
                $_SESSION['qID'] = $_POST['questionID'];
                header("location: chair_class_stats.php");
        }
    }
    
    ?>
    
    <body>
        <div class="center">
        <div class="w3-panel w3-round-xlarge w3-padding-24">
        <div class="container">

            <form id ="question" method="POST">
                <div class="form-group">
                    <?php

                        $question_query=" SELECT qID, qtext  FROM question  WHERE qID IN (SELECT qID FROM evaluation WHERE classID IN (SELECT classID FROM class WHERE courseID IN (SELECT courseID FROM course WHERE departmentID = '$departmentID'))) AND qTypeID < 4 ORDER BY qtext ASC";
                        if($questionresult = mysqli_query($conn, $question_query)){
                            
                            if(mysqli_num_rows($questionresult) > 0){ 
                                echo '<h4 style="color:white;">Choose a question you would like to investigate further:</h4>';
                                echo '<select class="form-control" name="questionID" form="question">';
                                while ($row = mysqli_fetch_array($questionresult)) {
                                    
                                    $qID = $row['qID'];
                                    $qtext = $row['qtext'];
                                    
                                    echo '<option value="'.$qID.'">'.$qtext.'</option>';
                                    
                                }
                                echo '</select>';
                            }
                            
                            elseif(mysqli_num_rows($questionresult) == 0){
                                echo '<h3> There are no questions in the database </h3>';
                            }
                        }
                    ?>
                </div>
                <button class="btn btn-primary" name="question" style="display: block; margin: 0 auto;" onclick=""/>Submit</button>
            </form>
        
        </div>
        </div>
        </div>
    
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
        
    </body>
</html>
