<?php
    session_start();
    require 'configure.php';
    
    $instructorID = $_SESSION['instructorID'];
    $classID = $_SESSION['classID'];
    
    $courseID_query="SELECT sectionNumber, semester, year, courseID FROM class WHERE classID = '$classID'";
                        
    if($classresult = mysqli_query($conn, $courseID_query)){                          
        if(mysqli_num_rows($classresult) > 0){                               
            while ($row = mysqli_fetch_array($classresult)) {
                    
                $sectionNumber = $row['sectionNumber'];                 
                $semester = $row['semester'];                 
                $year = $row['year'];             
                $courseID = $row['courseID'];

            }

        }
    }
    
    function median($array){
        $count = count($array);
        $mid = $count/2;

        sort($array);
        if(($count % 2) == 0){
            $high = ceil($mid);
            $low = floor($mid-1);
            $median = ($array[$high]+$array[$low])/2;
        }
        if(($count)%2 !=0){
            $median = $array[$mid];
        }
        return $median;
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
        
        <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 20%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 2px;
        }

        tr:nth-child(even) {
            background-color: #d1e8f1;
        }
        </style>
        
    </head>
    
    <body>
        
        <div class = "container">
        <h2>Here are some statistics for the course <?php echo ''.$courseID.''; ?> <?php echo ''.$semester.''; ?><?php echo ''.$year.''; ?> - section <?php echo ''.$sectionNumber.'';?> </h2>
        </div>
        
        <?php
                $question_query_two = "SELECT DISTINCT question.qID as questionID, qtext FROM question, (SELECT qID FROM evaluation WHERE classID = '$classID') as s WHERE question.qID = s.qID and qTypeID = 2";
            
                if($questionresult_two = mysqli_query($conn, $question_query_two)){
                    if(mysqli_num_rows($questionresult_two) > 0){ 
                        
                        while ($row_two = mysqli_fetch_array($questionresult_two)) {
                            $qID_two = $row_two['questionID'];
                            $qText_two = $row_two['qtext'];
                            
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qText_two.'</div>';
                                
                            
                            echo '<div class = "container">';
                            ?>
                            <br>
                            <table>
                                <tr>
                                  <th>Value</th>
                                  <th>Frequency</th>
                                </tr>
                            <?php
                            
                            $question_array = array();
                            
                            for($x = 1; $x<11 ; $x++){
                                $scalequery = "SELECT COUNT(atext) FROM evaluation WHERE qID = '$qID_two' AND classID = '$classID' AND atext = '$x'";
                                    if($scalequery_result = mysqli_query($conn, $scalequery)){
                                        if(mysqli_num_rows($scalequery_result) > 0){ 
                                            $row_scale = mysqli_fetch_array($scalequery_result);
                                            $frequency = $row_scale[0];
                                            echo '<tr>';
                                            echo '<td>'.$x.'</td>';
                                            echo '<td>'.$frequency.' </td>';
                                            echo '</tr>';
                                            
                                            for($y = 0; $y<$frequency ; $y++){
                                                array_push($question_array, $x);
                                            }
                                        }
                                    }
                            }
                            
                            echo '</table>';
                            echo '<br>';
                            
                            $m = median($question_array);
                            echo 'Median: '.$m.' ';

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
                //Calculate Agreed or Strongly Agreed
                $agree_q_query = "SELECT DISTINCT question.qID as questionID, qtext FROM question, (SELECT qID FROM evaluation WHERE classID = '$classID') as s WHERE question.qID = s.qID and qTypeID = 3";
                
                if($agree_q_result = mysqli_query($conn, $agree_q_query)){
                    if(mysqli_num_rows($agree_q_result) > 0){ 
                        
                        while ($agree_q_row = mysqli_fetch_array($agree_q_result)) {
                            $qID_a = $agree_q_row['questionID'];
                            $qText_a = $agree_q_row['qtext'];
                            
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qText_a.'</div>';
                                
                            $agreequery ="SELECT DISTINCT atext FROM evaluation WHERE qID IN (SELECT qID FROM question WHERE qTypeID = '3') ORDER BY atext ASC";    
                            
                            if($agreequery_result = mysqli_query($conn, $agreequery)){
                                if(mysqli_num_rows($agreequery_result) > 0){ 
                                    
                                    echo '<div class="container">';
                                    echo '<br><table>';
                                    
                                    while ($row_agree = mysqli_fetch_array($agreequery_result)) {
                                        $atext = $row_agree['atext'];
                                        
                                        echo '<tr><td> '.$atext.' </td>';
                                        
                                        $agree_ans_query = "SELECT COUNT(atext) as count FROM evaluation WHERE qID = '$qID_a' AND atext = '$atext' AND classID='$classID'";
                                        if($result = mysqli_query($conn, $agree_ans_query)){
                                            if(mysqli_num_rows($result) > 0){ 
                                                $row = mysqli_fetch_array($result);
                                                $count = $row['count'];
                                                echo '<td>'.$count.'</td></tr>';
                                                
                                            }
                                            
                                        }
                                        
                                    }
                                    
                                    echo '</table><br>';
                                    echo '</div>';
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
                                    
                                    echo '<div class="container">';
                                    echo '<br><table>';
                                    
                                    while ($rowans = mysqli_fetch_array($atext_result)) {
                                        
                                        $atext = $rowans['atext'];
                                        
                                        echo '<tr><td> '.$atext.' </td>';
                                        
                                        $agree_ans_query = "SELECT COUNT(atext) as count FROM evaluation WHERE qID = '$qID' AND atext = '$atext' AND classID='$classID'";
                                        if($result = mysqli_query($conn, $agree_ans_query)){
                                            if(mysqli_num_rows($result) > 0){ 
                                                $row = mysqli_fetch_array($result);
                                                $count = $row['count'];
                                                echo '<td>'.$count.'</td></tr>';
                                                
                                            }
                                            
                                        }
  
                                    }
                                    echo '</table><br>';
                                    echo '</div>';
                                    
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
                $q = "SELECT DISTINCT question.qID as questionID, qtext FROM question, (SELECT qID FROM evaluation WHERE classID = '$classID') as s WHERE question.qID = s.qID and qTypeID = 4";
            
                if($res = mysqli_query($conn, $q)){
                    if(mysqli_num_rows($res) > 0){ 
                        
                        while ($ro = mysqli_fetch_array($res)) {
                            $qID = $ro['questionID'];
                            $qtext = $ro['qtext'];
                            
                            echo '<div class="container">';
                            echo '<div class="panel-group">';
                            echo '<div class="panel panel-info">';                           
                            echo '<div class="panel-heading">'.$qtext.'</div>';
                                
                            
                            echo '<div class = "container">';
                            ?>
                            <br>
                            <ul class="list-group">
                            <?php

                                $commentq = " SELECT atext FROM evaluation WHERE qID = '$qID' AND classID = '$classID' ";
                                    if($commentr = mysqli_query($conn, $commentq)){
                                        if(mysqli_num_rows($commentr) > 0){ 
                                            while ($rowc = mysqli_fetch_array($commentr)){
                                                $comment = $rowc['atext'];
                                                echo '<div class="container"><li>'.$comment.'</li></div>';
                                            }
                                        }
                                    }
                                    
                            echo '</ul>';
                            echo '<br>';
                            echo '</div>';     
                            echo '</div>';        
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                        }
                        
                    }
                }
                
        ?>
                                
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    </body>
</html>
