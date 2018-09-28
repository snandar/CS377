<?php
    session_start();
    require 'configure.php';
    
    $instructorID = $_SESSION["instructorID"];
    $courseID = $_SESSION["courseID"];
    $studentID = $_SESSION["studentID"];
    
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
    
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        if (isset($_POST['evaluate'])) { //user logging in
                header("location: verifystudent.php");
        }
    }
    ?>
    
    <body>
        
        <div class = "container">
        <h2>Here are some statistics for the course <?php echo ''.$courseID.''; ?> </h2>
        </div>
        
            <?php
                
                //processing multiple choice
                $question_query = "SELECT DISTINCT question.qID as questionID, qtext FROM question,(SELECT qID FROM evaluation WHERE classID IN (SELECT class.classID FROM class WHERE courseID = '$courseID' AND instructorID = '$instructorID')) as s WHERE s.qID = question.qID AND qTypeID = 1";
                
                if($questionresult = mysqli_query($conn, $question_query)){
                    if(mysqli_num_rows($questionresult) > 0){ 
                        
                        while ($row = mysqli_fetch_array($questionresult)) {
                            $qID = $row['questionID'];
                            $qText = $row['qtext'];
                            
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qText.'</div>';
                            
                            //find ans to specific question
                            $qID_atext_query ="SELECT DISTINCT atext FROM evaluation WHERE qID = '$qID' ORDER BY atext ASC";
                            if($atext_result = mysqli_query($conn, $qID_atext_query)){
                                if(mysqli_num_rows($atext_result) > 0){
                                    while ($rowans = mysqli_fetch_array($atext_result)) {
                                        
                                        //find percentage
                                        $atext = $rowans['atext'];

                                        $percent_query = "SELECT ((nume.nu/deno.de)*100) as percentage FROM (SELECT COUNT(atext) as nu FROM evaluation WHERE qID ='$qID' AND atext = '$atext' AND classID IN (SELECT classID FROM class WHERE courseID='$courseID' AND instructorID = '$instructorID') )as nume, (SELECT COUNT(atext) as de FROM evaluation WHERE qID ='$qID' AND classID IN (SELECT classID FROM class WHERE courseID='$courseID' AND instructorID = '$instructorID')) as deno";
                                        if($percent_result = mysqli_query($conn, $percent_query)){
                                            if(mysqli_num_rows($percent_result) > 0){
                                                while($perans = mysqli_fetch_array($percent_result)){
                                                    
                                                    $percent = $perans['percentage'];
                                                    echo '<div class = "container">'.$atext.' : '.$percent.'%<br></div>';
                                                    
                                                }
                                            }
                                        }
  
                                    }
                                }
                            }
                                             
                            echo '</div>';        
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                        }
                    }
                    elseif(mysqli_num_rows($questionresult) == 0){ 
                        echo '<div class="container"><h5> No Multiple Choice Statistics avaliable for this class</h5></div>';
                    }
                }
                
            ?>
        
            <?php
                $question_query_two = "SELECT DISTINCT question.qID as questionID, qtext FROM question,(SELECT qID FROM evaluation WHERE classID IN (SELECT class.classID FROM class WHERE courseID = '$courseID' AND instructorID = '$instructorID')) as s WHERE s.qID = question.qID AND qTypeID = 2";
            
                if($questionresult_two = mysqli_query($conn, $question_query_two)){
                    if(mysqli_num_rows($questionresult_two) > 0){ 
                        
                        while ($row_two = mysqli_fetch_array($questionresult_two)) {
                            $qID_two = $row_two['questionID'];
                            $qText_two = $row_two['qtext'];
                            
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qText_two.'</div>';
                                
                            $scalequery = "SELECT AVG(atext) as avg FROM evaluation WHERE qID = '$qID_two' AND classID IN (SELECT classID FROM class WHERE courseID = '$courseID' AND instructorID = '$instructorID')";    
                            
                            if($scalequery_result = mysqli_query($conn, $scalequery)){
                                if(mysqli_num_rows($scalequery_result) > 0){ 
                                    while ($row_scale = mysqli_fetch_array($scalequery_result)) {
                                        $avg = $row_scale['avg'];
                                        echo '<div class = "container"> Average Result: '.$avg.'</div>';
                                    }
                                }
                            }
                            
                            echo '</div>';        
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                        }
                        
                    }
                    elseif (mysqli_num_rows($questionresult_two) == 0){ 
                        echo '<div class="container"><h5>No 1-10 type question statistics avaliable for this class</h5></div>';
                    }
                }
                
            ?>

            <?php
                //Calculate Agreed or Strongly Agreed
                $agree_q_query = "SELECT DISTINCT question.qID as questionID, qtext FROM question,(SELECT qID FROM evaluation WHERE classID IN (SELECT class.classID FROM class WHERE courseID = '$courseID' AND instructorID = '$instructorID')) as s WHERE s.qID = question.qID AND qTypeID = 3";
                
                if($agree_q_result = mysqli_query($conn, $agree_q_query)){
                    if(mysqli_num_rows($agree_q_result) > 0){ 
                        
                        while ($agree_q_row = mysqli_fetch_array($agree_q_result)) {
                            $qID_a = $agree_q_row['questionID'];
                            $qText_a = $agree_q_row['qtext'];
                            
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qText_a.'</div>';
                                
                            $agreequery = "SELECT ((((one.count + two.count))/total.count)*100) as agree FROM (SELECT COUNT(atext) as count FROM evaluation WHERE qID = '$qID_a' AND atext = 'Agree' AND classID IN (SELECT classID FROM class WHERE courseID='$courseID' AND instructorID ='$instructorID')) as one, (SELECT COUNT(atext) as count FROM evaluation WHERE qID = '$qID_a' AND atext = 'Strongly Agree' AND classID IN (SELECT classID FROM class WHERE courseID='$courseID' AND instructorID ='$instructorID')) as two, (SELECT COUNT(atext) as count FROM evaluation WHERE qID = '$qID_a' AND classID IN (SELECT classID FROM class WHERE courseID='$courseID' AND instructorID ='$instructorID')) as total";    
                            
                            if($agreequery_result = mysqli_query($conn, $agreequery)){
                                if(mysqli_num_rows($agreequery_result) > 0){ 
                                    while ($row_agree = mysqli_fetch_array($agreequery_result)) {
                                        $agree = $row_agree['agree'];
                                        echo '<div class = "container"> Percentage Agree/Strongly Agree: '.$agree.'%</div>';
                                    }
                                }
                            }
                            
                            echo '</div>';        
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                        }
                        
                    }
                    elseif (mysqli_num_rows($questionresult_two) == 0){ 
                        echo '<div class="container"><h5>No agree/disagree type question statistics avaliable for this class</h5></div>';
                    }
                }
                
            ?>
        
        <div class='container'>
            <form method = "POST">
            <button class="btn btn-primary" name="evaluate" style="display: block; margin: 0 auto;"/>Click here to evaluate this class</button><br>
            </form>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    </body>
</html>

