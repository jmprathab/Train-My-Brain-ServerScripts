<?php

require 'db_connect.inc.php';

$response=array();//Array to hold JSON information

$connect=mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

if(mysqli_connect_errno()){
    //Not connected
    $response['success']=0;
    $response['message']="Cannot connect with MySql:".mysqli_connect_errno();
    //echo "Cannot connect with MySql:". mysqli_connect_errno();
    echo json_encode($response);
}else{
  //Successfully connected
  if (isset($_POST['answer'])) {
    $answer=$_POST['answer'];
    if(!empty($answer)){
      $answer=stripslashes($answer);
      $data=json_decode($answer,true);
      $user_id=$data['user_id'];
      $table=$data['table'];
      $score=0;
      $total_questions=sizeof($data['answer']);
      $error_flag=1;
      $negativeMarking=0;
      $errors="";
      $check_user_id="SELECT * FROM `user` WHERE `USER_ID`='$user_id'";
      $check_user_id_run=mysqli_query($connect,$check_user_id);
      if(mysqli_fetch_assoc($check_user_id_run)){
        for($i=0;$i<sizeof($data['answer']);$i++){
          $qno=$data['answer'][$i]['qno'];
          $query="SELECT `ANSWER` FROM `$table` WHERE `QUESTION_NUMBER`='$qno'";
          $query_run=mysqli_query($connect,$query);
          if($correct=mysqli_fetch_assoc($query_run)){
            if($correct['ANSWER']==$data['answer'][$i]['answer']){
              $score=$score+1;
            }else {
              $score=$score-$negativeMarking;
            }
          }else {
            $error_flag=1;
            $errors=$errors."\n"."Cannot check answer for Question Number :".$qno;
          }
        }//end of for loop
        $response['success']=1;
        $response['marks']="Score Obtained is :".$score;
        $response['total_questions']="Total Questions Attended :".$total_questions;
        $response['error_flag']=$error_flag;
        $response['error']=$errors;

        echo json_encode($response);

        }else {
          $response['success']=2;
          $response['message']="Cannot verify your User ID\nYou will now be redirected to Login Page\nPlease login again and try to take quiz again";
          echo json_encode($response);
        }

      }else{
        $response['success']=0;
        $response['message']="Field cannot be empty";
        echo json_encode($response);
      }
    }

mysqli_close($connect);
}
?>
<form action="result.php" method="post">
  Answer:<br><input type="text" name="answer"><br><br>
  <input type="Submit" name="submit" value="Submit">
</form>
