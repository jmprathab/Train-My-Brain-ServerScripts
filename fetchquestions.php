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
  if (isset($_POST['user_id']) && isset($_POST['category']) && isset($_POST['limit'])) {
    $user_id=$_POST['user_id'];
    $category=$_POST['category'];
    $limit=$_POST['limit'];
    if(!empty($user_id) && !empty($category) && !empty($limit)){
      $check_user_id="SELECT * FROM `user` WHERE `USER_ID`='$user_id'";
      $check_user_id_run=mysqli_query($connect,$check_user_id);
      if(mysqli_fetch_assoc($check_user_id_run)){
        //User id verified
        switch ($category) {
          case 'aptitude':
            $query="SELECT `QUESTION_NUMBER`,`CATEGORY`,`QUESTION`,`OPTION1`,`OPTION2`,`OPTION3`,`OPTION4` FROM `aptitude` ORDER BY rand() LIMIT ".$limit;
            break;
          case 'verbal':
            $query="SELECT `QUESTION_NUMBER`,`CATEGORY`,`QUESTION`,`OPTION1`,`OPTION2`,`OPTION3`,`OPTION4` FROM `verbal` ORDER BY rand() LIMIT ".$limit;
            break;
          case 'technical':
            $query="SELECT `QUESTION_NUMBER`,`CATEGORY`,`QUESTION`,`OPTION1`,`OPTION2`,`OPTION3`,`OPTION4` FROM `technical` ORDER BY rand() LIMIT ".$limit;
            break;
          default:
            $query="SELECT `QUESTION_NUMBER`,`CATEGORY`,`QUESTION`,`OPTION1`,`OPTION2`,`OPTION3`,`OPTION4` FROM `aptitude` ORDER BY rand() LIMIT ".$limit;
            break;
        }
        //user id for checking : 26647722bc76122885ad06c05bcf5c26
        $query_run=mysqli_query($connect,$query);
        if($query_run){
          $response['success']=1;
          $response['data']=array();
          while($query_array=mysqli_fetch_assoc($query_run)){
            $question=array();
            $question['qno']=$query_array['QUESTION_NUMBER'];
            $question['category']=$query_array['CATEGORY'];
            $question['question']=$query_array['QUESTION'];
            $question['o1']=$query_array['OPTION1'];
            $question['o2']=$query_array['OPTION2'];
            $question['o3']=$query_array['OPTION3'];
            $question['o4']=$query_array['OPTION4'];
            array_push($response['data'],$question);
          }
          echo json_encode($response);
      }else {
        $response['success']=0;
        $response['message']="Cannot fetch Questions from Database";
        echo json_encode($response);
      }

      }else {
        $response['success']=2;
        $response['message']="Cannot verify your User ID\nPlease login again and try to take quiz again";
        echo json_encode($response);
      }
    }else{
      $response['success']=0;
      $response['message']="Field cannot be empty";
      echo json_encode($response);
    }
  }
}
mysqli_close($connect);
?>

<form action="fetchquestions.php" method="post">
  User ID:<br><input type="text" name="user_id" maxlength="33"><br><br>
  Category:<br><input type="text" name="category" maxlength="40"><br><br>
  Number Of Questions to fetch:<br><input type="number" name="limit" maxlength="2"><br><br>
  <input type="Submit" name="submit" value="Submit">
</form>
