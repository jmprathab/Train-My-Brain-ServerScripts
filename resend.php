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
  if(isset($_POST['email']) && isset($_POST['mobile_number'])){
    $email=$_POST['email'];
    $mobile_number=$_POST['mobile_number'];
    if(!empty($email) && !empty($mobile_number)){
      $query="SELECT * FROM `user` WHERE `EMAIL`='$email' AND `MOBILE_NUMBER`='$mobile_number' LIMIT 1";
      $query_run=mysqli_query($connect,$query);
      if($data=mysqli_fetch_assoc($query_run)){
        if($data['ACTIVATED']==1){
          $response['success']=0;
          $response['message']="Your account is already Activated";
          //echo "Details Added into Database";
          echo json_encode($response);
        }else{
          $user_id=md5($data['USER_NO']);
          $subject="Train My Brain Application Account Activation";
          $message="Registration Successful !"."\r\n"."Your account has been created."."\r\n"."After activation you can log in to your account using your e-mail and your password."."\r\n"."To ativate your account click on the activation link below"."\r\n"."www.thin.comyr.com/confirmation.php?email=".$email."&user_id=".$user_id."&submit=Submit";
          $headers='From: Train My Brain'."\r\n".'Reply-To: jm.prathab@gmail.com'."\r\n";
          mail($email,$subject,$message,$headers);
          $response['success']=1;
          $response['message']="An e-mail with a confirmation link has been sent to your e-mail.Click on the link to activate your account.Check your spam folder if email cannot be found.";
          $message = wordwrap($message, 70, "\r\n");
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
<form  action="resend.php" method="post">
  e-mail:<br><input type="text" name="email" maxlength="40"><br><br>
  Mobile Number:<br><input type="text" name="mobile_number" maxlength="40"><br><br>
  <input type="Submit" name="submit" value="Submit">
</form>
