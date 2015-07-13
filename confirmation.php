<?php

require 'db_connect.inc.php';

$response=array();//Array to hold JSON Information

$connect=mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

if(mysqli_connect_errno()){
  //Not connected
  $response['success']=0;
  $response['message']="Cannot connect with MySql :".mysqli_connect_errno();
  //echo "Cannot connect with MySql:". mysqli_connect_errno();
  //echo json_encode($response);
  die($response['message']);
}else{
  //Successfully connected
  if (isset($_GET['email']) && isset($_GET['user_id'])) {
    $email=$_GET['email'];
    $user_id=$_GET['user_id'];
    if(!empty($email) && !empty($user_id)){
      $temp_query="SELECT `USER_NO` FROM `user` WHERE `EMAIL`='$email' AND `ACTIVATED`=0";
      $temp_query_run=mysqli_query($connect,$temp_query);
      if($data=mysqli_fetch_array($temp_query_run)){
        if($user_id==md5($data['USER_NO'])){
          $query="UPDATE `user` SET `ACTIVATED`='1' WHERE `EMAIL`='$email'";
          $query_run=mysqli_query($connect,$query);
          if(mysqli_affected_rows($connect)){
            $response['success']=1;
            $response['message']="Your account was activated successfully.";
            //echo "Successfully logged in";
            //echo json_encode($response);
            die($response['message']);
          }else{
            $response['success']=0;
            $response['message']="Cannot activate your account.Confirmation link is invalid.Your e-mail ID is not registered";
            //echo "Cannot login.Check your Credentials";
            //echo json_encode($response);
            die($response['message']);
          }
        }
      }else{
        $response['success']=0;
        $response['message']="Cannot activate your account.Confirmation link is invalid or you have already activated (Try to login using your e-mail ID and password).";
        //echo "Cannot login.Check your Credentials";
        //echo json_encode($response);
        die($response['message']);
      }

    }else {
      $response['success']=0;
      $response['message']="Fields cannot be empty.";
      //echo "Fill in all the fields";
      //echo json_encode($response);
      die($response['message']);
    }
  }
  mysqli_close($connect);
}
?>

<form  action="confirmation.php" method="get">
  e-mail:<br><input type="text" name="email" maxlength="40"><br><br>
  User ID:<br><input type="text" name="user_id" maxlength="32"><br><br>
  <input type="Submit" name="submit" value="Submit">
</form>
