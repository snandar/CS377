<?php

    session_start();
    require 'configure.php';
    
    $chairID = $_SESSION["chairID"];
    $qID = $_SESSION["qID"];
    $departmentID = $_SESSION["departmentID"];
    
    $dname_query = "SELECT department FROM department WHERE departmentID ='$departmentID'";
    if($dname_result = mysqli_query($conn, $dname_query)){
        if(mysqli_num_rows($dname_result) > 0){ 
            $row = mysqli_fetch_array($dname_result);
            $dname = $row[0];
        }
    }
    
    $qt_query = "SELECT qtext FROM question WHERE qID ='$qID'";
    if($qt_result = mysqli_query($conn, $qt_query)){
        if(mysqli_num_rows($qt_result) > 0){ 
            $row = mysqli_fetch_array($qt_result);
            $qtext = $row[0];
        }
    }
    
    $qt_query = "SELECT qTypeID FROM question WHERE qID ='$qID'";
    if($qt_result = mysqli_query($conn, $qt_query)){
        if(mysqli_num_rows($qt_result) > 0){ 
            $row = mysqli_fetch_array($qt_result);
            $qtypeID = $row[0];
        }
    }
    
    
    
    function mean($array){
        
        $average = array_sum($array)/count($array);
        
        return $average;
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
    
    //standard deviation function credit to: Sharon @ php manual link: function.stats-standard-deviation.php#97369
    function sd_square($x, $mean) { 
        return pow($x - $mean,2); 
        
    }
    
    function sd($array) {

        return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
    }
    
    function valueOf($a){
        
        if($a === "Strongly Agree"){
            $a = 5;
        }
        elseif($a === "Agree"){
            $a = 4;
        }
        elseif($a === "Neutral"){
            $a = 3;
        }
        elseif($a === "Disagree"){
            $a = 2;
        }
        elseif($a === "Strongly Disagree"){
            $a = 1;
        }
        
        return intval($a); 
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
        <h2>The department of <?php echo ''.$dname.''; ?></h2>
        <h5>You have selected the question: <?php echo ' '.$qtext.' ' ?></h5>
        </div>
        
        
        <?php
            //find year
            $year_query = "SELECT DISTINCT year FROM class WHERE courseID IN (SELECT courseID FROM course WHERE departmentID = '$departmentID') ORDER BY year ASC";
            if($year_result = mysqli_query($conn, $year_query)){
                $ad_stats = array();
                if(mysqli_num_rows($year_result) > 0){ 
                    while ($row = mysqli_fetch_array($year_result)) {
                        $year = $row['year'];
                        
                        //find semester
                        $semester_query = "SELECT DISTINCT semester FROM class WHERE year ='$year' AND courseID IN (SELECT courseID FROM course WHERE departmentID ='$departmentID')";
                        if($semester_result = mysqli_query($conn, $semester_query)){
                            if(mysqli_num_rows($semester_result) > 0){ 
                                while ($rowa = mysqli_fetch_array($semester_result)) {
                                    echo '<div class="container">';
                                    echo '<div class="panel-group">';
                                    echo '<div class="panel panel-info">';                           
                                    
                                    $semester = $rowa['semester'];
                                    
                                    echo '<div class="panel-heading"> '.$semester.''.$year.'</div>';
                                    
                                    //Agree/Disagree
                                    if($qtypeID == 3){
                                        //find ans to specific question
                                        $qID_atext_query ="SELECT DISTINCT atext FROM evaluation WHERE qID = '$qID' ORDER BY atext ASC";
                                        if($atext_result = mysqli_query($conn, $qID_atext_query)){
                                            if(mysqli_num_rows($atext_result) > 0){

                                                echo '<div class="container">';
                                                echo '<br><table>';

                                                while ($rowans = mysqli_fetch_array($atext_result)) {

                                                    $atext = $rowans['atext'];

                                                    echo '<tr><td> '.$atext.' </td>';

                                                    $agree_ans_query = "SELECT COUNT(atext) as count FROM evaluation WHERE qID = '$qID' AND atext = '$atext' AND classID IN (SELECT classID FROM class WHERE semester='$semester' AND year='$year' AND courseID IN (SELECT courseID FROM course WHERE departmentID = '$departmentID'))";
                                                    if($result = mysqli_query($conn, $agree_ans_query)){
                                                        if(mysqli_num_rows($result) > 0){ 
                                                            $row = mysqli_fetch_array($result);
                                                            $count = $row['count'];
                                                            
                                                            //for mean/std of class
                                                            for($k = 0; $k<$count; $k++){
                                                                array_push($ad_stats, valueOf($atext));
                                                            }
                                                            
                                                            echo '<td>'.$count.'</td></tr>';
                                                        }

                                                    }

                                                }
                                                
                                                echo '</table><br>';
                                                echo '</div>';

                                            }
                                        }
                                        
                                    }
                                    
                                    //Scale Question (1-10)
                                    if($qtypeID == 2){
                                        
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
                                            $scalequery = "SELECT COUNT(atext) as count FROM evaluation WHERE qID = '$qID' AND atext = '$x' AND classID IN (SELECT classID FROM class WHERE semester='$semester' AND year='$year' AND courseID IN (SELECT courseID FROM course WHERE departmentID = '$departmentID'))";
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
                                                            array_push($ad_stats, $x);
                                                        }
                                                    }
                                                }
                                        }

                                        echo '</table>';
                                        echo '<br>';

                                        $m = median($question_array);
                                        echo 'Median: '.$m.' ';
                                        echo '</div>';                     
                                        
                                    }
                                    
                                    //Processing Multiple Choice
                                    if($qtypeID == 1){
                                        
                                        //find ans to specific question
                                        $qID_atext_query ="SELECT DISTINCT atext FROM evaluation WHERE qID = '$qID' ORDER BY atext ASC";
                                        if($atext_result = mysqli_query($conn, $qID_atext_query)){
                                            if(mysqli_num_rows($atext_result) > 0){

                                                echo '<div class="container">';
                                                echo '<br><table>';

                                                while ($rowans = mysqli_fetch_array($atext_result)) {

                                                    $atext = $rowans['atext'];

                                                    echo '<tr><td> '.$atext.' </td>';

                                                    $agree_ans_query = "SELECT COUNT(atext) as count FROM evaluation WHERE qID = '$qID' AND atext = '$atext' AND classID IN (SELECT classID FROM class WHERE semester='$semester' AND year='$year' AND courseID IN (SELECT courseID FROM course WHERE departmentID = '$departmentID'))";
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
                                        
                                        
                                    }

                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }

                            }
                            elseif(mysqli_num_rows($semester_result) > 0){
                                
                            }
                        }
                        else{
                            echo 'semester query fail';
                        }

                    }
                    
                    
                }
                
                $mean = mean($ad_stats);
                $std = sd($ad_stats);
            }
        ?>
        
        <!--        Poorly Classes-->
        <?php

            $class = array();
            $find_class="SELECT classID FROM class WHERE courseID IN (SELECT courseID FROM course WHERE departmentID = '$departmentID')";
            if($result = mysqli_query($conn, $find_class)){
                if(mysqli_num_rows($result) > 0){ 
                    
                    echo '<div class="container">';
                    echo '<div class="panel panel-success">';
                    echo '<div class="panel-heading">Poorly Calculation for Classes</div>';
                    echo '<div class="panel-body">';
                    
                    echo '<h4>Here are the results for the all classes in this department: Pass <span class="glyphicon glyphicon-ok"></span> Fail <span class="glyphicon glyphicon-remove"></span> </h4>';
                    echo "Mean: $mean";
                    echo "<br>Standard deviation: $std";
                    $poorly = $mean - 1.5 * $std;
                    echo "<br>Poorly value: $poorly<br><br>";
                    

                    while ($row = mysqli_fetch_array($result)) {
                        $classID = $row['classID'];
                        
                        if($qtypeID == 2 || $qtypeID == 3){
                            
                            $find_atext = "SELECT atext FROM evaluation WHERE classID ='$classID' AND qID='$qID'";
                            if($r = mysqli_query($conn, $find_atext)){
                                if(mysqli_num_rows($r) > 0){
                                    $array = array();
                                    
                                    while ($ro = mysqli_fetch_array($r)) {
                                        $atext = $ro['atext'];
                                        
                                            array_push($array, valueOf($atext));
                                            
                                    }
                                    
                                    $meanofClass = mean($array);
                                    if ($meanofClass < $poorly){
                                        echo '<p>'.$classID.' <span class="glyphicon glyphicon-remove"></span> Mean: '.$meanofClass.' </p>';
                                    }
                                    else{
                                        echo '<p>'.$classID.' <span class="glyphicon glyphicon-ok"></span> Mean: '.$meanofClass.' </p>';
                                    }
                                    $array = array();
                                }
                            }
                            
                        }
                        
                        
                        
                    }
                    if($qtypeID == 4 || $qtypeID == 1){
                            echo 'No individual class statistics are avaliable for multiple choice questions and comments';
                    }
                    
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            
            
        ?>
                                            
        <!--        Poorly Instructors-->
        <?php

            $class = array();
            $find_instructor="SELECT DISTINCT instructorID FROM class WHERE courseID IN (SELECT courseID FROM course WHERE departmentID = '$departmentID')";
            if($result = mysqli_query($conn, $find_instructor)){
                if(mysqli_num_rows($result) > 0){ 
                    
                    echo '<div class="container">';
                    echo '<div class="panel panel-warning">';
                    echo '<div class="panel-heading">Poorly Calculation for Instructors</div>';
                    echo '<div class="panel-body">';
                    
                    echo '<h4>Here are the results for the all instructors in this department: Pass <span class="glyphicon glyphicon-ok"></span> Fail <span class="glyphicon glyphicon-remove"></span> </h4>';
                    echo "Mean: $mean";
                    echo "<br>Standard deviation: $std";
                    $poorly = $mean - 1.5 * $std;
                    echo "<br>Poorly value: $poorly<br><br>";
                    

                    while ($row = mysqli_fetch_array($result)) {
                        $instructorID = $row['instructorID'];
                        $inameq= "SELECT ifname, ilname FROM instructor WHERE instructorID = '$instructorID'";
                        if($re = mysqli_query($conn, $inameq)){
                            while ($ro = mysqli_fetch_array($re)){
                                $ifname = $ro['ifname'];
                                $ilname = $ro['ilname'];
                                
                            }
                        }
                        
                        if($qtypeID == 2 || $qtypeID == 3){
                            
                            $find_atext = "SELECT atext FROM evaluation WHERE qID='$qID' AND classID IN (SELECT classID FROM class WHERE instructorID='$instructorID')";
                            if($r = mysqli_query($conn, $find_atext)){
                                if(mysqli_num_rows($r) > 0){
                                    $array = array();
                                    
                                    while ($ro = mysqli_fetch_array($r)) {
                                        $atext = $ro['atext'];
                                        
                                            array_push($array, valueOf($atext));
                                            
                                    }
                                    
                                    $meanofClass = mean($array);
                                    if ($meanofClass < $poorly){
                                        echo '<p>'.$instructorID.' <span class="glyphicon glyphicon-remove"></span> Mean: '.$meanofClass.' </p>';
                                    }
                                    else{
                                        echo "$ifname $ilname";
                                        echo '<p>'.$instructorID.' <span class="glyphicon glyphicon-ok"></span> Mean: '.$meanofClass.' </p>';
                                    }
                                    $array = array();
                                }
                            }
                            
                        }
                        
                        
                        
                    }
                    if($qtypeID == 4 || $qtypeID == 1){
                            echo 'No individual instructor statistics are avaliable for multiple choice questions and comments';
                    }
                    
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            
            
        ?>
                                 
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    
    </body>
</html>
