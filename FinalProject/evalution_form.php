<?php
    session_start();
    require 'configure.php';
    
    $instructorID = $_SESSION["instructorID"];
    $courseID = $_SESSION["courseID"];
    $studentID = $_SESSION["studentID"];
    $classID = $_SESSION["classID"];
    
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
        
        
        <script src="../../assets/js/html5shiv.js"></script>
	<script src="../../assets/js/respond.min.js"></script>

        
      <title>Evaluation Form</title>
    </head>
    
    <body>
        
        <div class ="container">
            <div class="page-header">
                <h1>Evaluation Form</h1>
            </div>
        </div>
            <br>
        
            <form action="form_processing.php" method="post" id="evaluation">
            
            
            <?php
                
            
                $count = 1; 
                //processing multiple choice
                $question_query = "SELECT DISTINCT question.qID as questionID, qtext FROM question,(SELECT qID FROM evaluation WHERE classID IN (SELECT class.classID FROM class WHERE courseID = '$courseID' AND instructorID = '$instructorID')) as s WHERE s.qID = question.qID AND qTypeID = 1";
                
                if($questionresult = mysqli_query($conn, $question_query)){
                    if(mysqli_num_rows($questionresult) > 0){ 
                        
                        while ($row = mysqli_fetch_array($questionresult)) {
                            $qID = $row['questionID'];
                            $qText = $row['qtext'];
                            
                            echo "<div id=''.$count.''>";
                            
                                echo '<div class="container">';
                                    echo '<div class="panel-group">';
                                        echo '<div class="panel panel-info">';                           
                                            echo '<div class="panel-heading">'.$qText.'</div>';
                                                echo '<div class="panel-body">';

                                                //find ans to specific question
                                                $qID_atext_query ="SELECT DISTINCT atext FROM evaluation WHERE qID = '$qID' ORDER BY atext ASC";
                                                if($atext_result = mysqli_query($conn, $qID_atext_query)){
                                                    if(mysqli_num_rows($atext_result) > 0){
                                                        while ($rowans = mysqli_fetch_array($atext_result)) {
                                                            $atext = $rowans['atext'];
                                                            echo '<div class="radio">';
                                                            echo '<label><input type="radio" value="'.$atext.'" name="'.$qID.'" checked/>'.$atext.'</label>';
                                                            echo '</div>';
                                                        }
                                                    }
                                                }
                                                
                                                echo '</div>';
                                                
                                                
                                                
                                            echo '</div>';
                                            
                                            
                                        echo '</div>';
                                        
                                        
                                    echo '</div>';
                                    
                                echo '</div>';
                            
                            echo "</div>";


                        }
                    }
                }
                
            ?>
        

        
            <?php
                
                //processing 1-10
                $question_query = "SELECT DISTINCT question.qID as questionID, qtext FROM question,(SELECT qID FROM evaluation WHERE classID IN (SELECT class.classID FROM class WHERE courseID = '$courseID' AND instructorID = '$instructorID')) as s WHERE s.qID = question.qID AND qTypeID = 2";
                
                if($questionresult = mysqli_query($conn, $question_query)){
                    if(mysqli_num_rows($questionresult) > 0){ 
                        
                        while ($row = mysqli_fetch_array($questionresult)) {
                            $qID = $row['questionID'];
                            $qText = $row['qtext'];
                            
                            
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qText.'</div>';
                            echo '<div class="panel-body">';
                            
                            //find ans to specific question
                            $qID_atext_query ="SELECT DISTINCT atext FROM evaluation WHERE qID IN (SELECT qID FROM question WHERE qTypeID = (SELECT qTypeID FROM question WHERE qID = '$qID')) ORDER BY cast(atext as unsigned);";
                            if($atext_result = mysqli_query($conn, $qID_atext_query)){
                                if(mysqli_num_rows($atext_result) > 0){
                                    
                                    echo '<div class="form-group">';
                                    echo '<label>10 = very, 1 = not at all</label>';
                                    echo '<select class="form-control" name="'.$qID.'" form="evaluation" required>';
                                    
                                    while ($rowans = mysqli_fetch_array($atext_result)) {
                                        $atext = $rowans['atext'];
                                        echo '<option value="'.$atext.'">'.$atext.'</option>';
                                    }
                                    echo '</select>';
                                    
                                    echo '</div>';
                                    
                                }
                            }

                            echo '</div>';
                            echo '</div>';        
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
  
                            
                        }
                    }
                }
                
            ?>
        
            <?php
                
                //processing agree/disagree
                $question_query = "SELECT DISTINCT question.qID as questionID, qtext FROM question,(SELECT qID FROM evaluation WHERE classID IN (SELECT class.classID FROM class WHERE courseID = '$courseID' AND instructorID = '$instructorID')) as s WHERE s.qID = question.qID AND qTypeID = 3";
                
                if($questionresult = mysqli_query($conn, $question_query)){
                    if(mysqli_num_rows($questionresult) > 0){ 
                        
                        while ($row = mysqli_fetch_array($questionresult)) {
                            $qID = $row['questionID'];
                            $qText = $row['qtext'];
                            
                            
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qText.'</div>';
                            echo '<div class="panel-body">';
                            
                            //find ans to specific question
                            $qID_atext_query ="SELECT DISTINCT atext FROM evaluation WHERE qID IN (SELECT qID FROM question WHERE qTypeID = (SELECT qTypeID FROM question WHERE qID = '$qID'));";
                            if($atext_result = mysqli_query($conn, $qID_atext_query)){
                                if(mysqli_num_rows($atext_result) > 0){
                                    
                                    echo '<div class="form-group">';
                                    echo '<label>Choose an option:</label>';
                                    echo '<select class="form-control" name="'.$qID.'" form="evaluation">';
                                    
                                    while ($rowans = mysqli_fetch_array($atext_result)) {
                                        $atext = $rowans['atext'];
                                        echo '<option value="'.$atext.'">'.$atext.'</option>';
                                    }
                                    echo '<option value="none" selected></option>';
                                    echo '</select>';
                                    echo '</div>';
                                    
                                }
                            }
                             
                            echo '</div>';
                            echo '</div>';        
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                           
                        }
                    }
                }
                
            ?>
        
        
            <?php
                
                //processing comments
                $question_query = "SELECT DISTINCT question.qID as questionID, qtext FROM question,(SELECT qID FROM evaluation WHERE classID IN (SELECT class.classID FROM class WHERE courseID = '$courseID' AND instructorID = '$instructorID')) as s WHERE s.qID = question.qID AND qTypeID = 4";
                
                if($questionresult = mysqli_query($conn, $question_query)){
                    if(mysqli_num_rows($questionresult) > 0){ 
                        
                        while ($row = mysqli_fetch_array($questionresult)) {
                            $qID = $row['questionID'];
                            $qText = $row['qtext'];
                            
                            
                                echo '<div class="container">';
                                
                                
                                    echo '<div class="panel-group">';
                                        echo '<div class="panel panel-info">';                           
                                            echo '<div class="panel-heading">'.$qText.'</div>';
                                                echo '<div class="panel-body">';

                                                    echo '<div class="form-group">';
                                                    echo '<label>Comment here:</label>';
                                                    echo '<textarea class="form-control" rows="5" name="'.$qID.'" form="evaluation"></textarea>';
                                                    echo '</div>';
                                                    
                                                   

                                                echo '</div>';
                                            echo '</div>';        
                                        echo '</div>';
                                    echo '</div>';
                                    
                                    
                                    
                                echo '</div>';
                            
                        }
                    }
                }
                
            ?>
                <div class="container">
                    <input type="submit" class="btn btn-info">
                </div>
            </form>        
        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    </body>
</html>
