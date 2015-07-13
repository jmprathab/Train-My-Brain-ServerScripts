<?php

require 'db_connect.inc.php';

$response=array();//Array to hold json information

$connect=mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

if(mysqli_connect_errno()){
  //Not connected
  $response['success']=0;
  $response['message']="Cannot connect with MySql :".mysqli_connect_errno();
  //echo "Cannot connect with MySql:". mysqli_connect_errno();
  echo json_encode($response);
}else{
  //Successfully connected
  if(isset($_POST['email']) && isset($_POST['mobile_number']) && isset($_POST['new_password'])){
    $email=$_POST['email'];
    $mobile_number=$_POST['mobile_number'];
    $password=$_POST['new_password'];
    if(!empty($email) && !empty($mobile_number) && !empty($password)){
      $query="SELECT * FROM `user` WHERE `EMAIL`='$email' AND `MOBILE_NUMBER`='$mobile_number' LIMIT 1";
      $query_run=mysqli_query($connect,$query);
      if($data=mysqli_fetch_assoc($query_run)){
        if($data['ACTIVATED']==1){
          $password=md5($password);
          $temp_query="UPDATE `user` SET `PASSWORD`='$password' WHERE `EMAIL`='$email' AND `MOBILE_NUMBER`='$mobile_number'";
          $temp_query_run=mysqli_query($connect,$temp_query);
          if($temp_query_run){
            $response['success']=1;
            $response['message']="Your password has been changed\nLogin with your new password";
            //echo "Details Added into Database";
            echo json_encode($response);
        }else {
          $response['success']=0;
          $response['message']="Cannot change your password\nTry again later";
        }
        }else{
          $response['success']=0;
          $response['message']="Your account is not yet activated\nTry again after activating your account";
          //echo "Details Added into Database";
          echo json_encode($response);
        }
      }else {
        $response['success']=0;
        $response['message']="Check your credentials\nYour e-mail ID, Mobile Number combination is invalid or not registered";
        //echo "Fill in all the fields";
        echo json_encode($response);
      }

    }else {
      $response['success']=0;
      $response['message']="Fields cannot be empty.";
      //echo "Fill in all the fields";
      echo json_encode($response);
    }
  }
  mysqli_close($connect);
}
?>
<form  action="forgotpassword.php" method="post">
  e-mail:<br><input type="text" name="email" maxlength="40"><br><br>
  Mobile Number:<br><input type="text" name="mobile_number" maxlength="40"><br><br>
  New password:<br><input type="password" name="new_password" maxlength="40"><br><br>
  <input type="Submit" name="submit" value="Submit">
</form>
