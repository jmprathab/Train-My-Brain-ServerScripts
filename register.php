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
  if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['mobile_number'])) {
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $mobile_number=$_POST['mobile_number'];
    if(!empty($name) && !empty($email) && !empty($password) && !empty($mobile_number)){
      $password=md5($password);
      $user_id=md5(uniqid(rand()));
      $query="SELECT * FROM `user` WHERE `EMAIL`='$email' LIMIT 1";
      $query_run=mysqli_query($connect,$query);
      if(mysqli_fetch_assoc($query_run)){
        $response['success']=0;
        $response['message']="e-mail has already been registered.";
        //echo "Details Added into Database";
        echo json_encode($response);
      }else {
        $query="INSERT INTO `user` (`USER_NO`, `NAME`, `EMAIL`, `PASSWORD`,`MOBILE_NUMBER`, `TIME`, `ACTIVATED`, `USER_ID`) VALUES (NULL, '$name', '$email', '$password','$mobile_number', CURRENT_TIMESTAMP, 0, '$user_id')";
        $query_run=mysqli_query($connect,$query);
        if($query_run){
          $query="SELECT `USER_NO` FROM `user` WHERE `EMAIL`='$email' LIMIT 1";
          $temp_query_run=mysqli_query($connect,$query);
          if($data=mysqli_fetch_assoc($temp_query_run)){
            $user_id=md5($data['USER_NO']);
          }else{
            $response['success']=0;
            $response['message']="Oops!...Details cannot be added into Database.Try again later.";
            //echo "Details cannot be added into Database";
            echo json_encode($response);
            die();
          }
          $subject="Train My Brain Application Account Activation";
          $message="Registration Successful !"."\r\n"."Your account has been created."."\r\n"."After activation you can log in to your account using your e-mail and your password."."\r\n"."To ativate your account click on the activation link below"."\r\n"."www.thin.webuda.com/confirmation.php?email=".$email."&user_id=".$user_id."&submit=Submit";
          $headers='From: Train My Brain'."\r\n".'Reply-To: jm.prathab@gmail.com'."\r\n";
          mail($email,$subject,$message,$headers);
          $response['success']=1;
          $response['message']="An e-mail with a confirmation link has been sent to your e-mail.Click on the link to activate your account.Check your spam folder if email cannot be found.";
          $message = wordwrap($message, 70, "\r\n");
          //echo "Details Added into Database";
          echo json_encode($response);
        }else{
          $response['success']=0;
          $response['message']="Oops!...Details cannot be added into Database.Try again later.";
          //echo "Details cannot be added into Database";
          echo json_encode($response);
        }
      }
    } else {
      $response['success']=0;
      $response['message']="Fields cannot be empty.";
      //echo "Fill in all the fields";
      echo json_encode($response);
    }
  }
  mysqli_close($connect);
}
?>
<form  action="register.php" method="post">
  Name:<br><input type="text" name="name" maxlength="40"><br><br>
  e-mail:<br><input type="text" name="email" maxlength="40"><br><br>
  Password:<br><input type="password" name="password" maxlength="40"><br><br>
  Mobile Number:<br><input type="tel" name="mobile_number" maxlength="13"><br><br>
  <input type="Submit" name="submit" value="Submit">
</form>
