<?php

require 'db_connect.inc.php';

$response=array();//Array to hold JSON Information

$connect=mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

if(mysqli_connect_errno()){
  //Not connected
  $response['success']=0;
  $response['message']="Cannot connect with MySql :".mysqli_connect_errno();
  //echo "Cannot connect with MySql:". mysqli_connect_errno();
  echo json_encode($response);
}else{
  //Successfully connected
  if (isset($_POST['email']) && isset($_POST['password'])) {
    $email=$_POST['email'];
    $password=$_POST['password'];
    if(!empty($email) && !empty($password)){
      $password=md5($password);//Add md5
      $query="SELECT * FROM `user` WHERE `EMAIL`='$email' AND `PASSWORD`='$password' AND `ACTIVATED`=1";
      $query_run=mysqli_query($connect,$query);
      $login_array=mysqli_fetch_array($query_run);
      if($login_array){
        $response['success']=1;
        $response['message']="Successfully logged in";
        $response['user_id']=$login_array['USER_ID'];
        //echo "Successfully logged in";
        echo json_encode($response);
      }else{
        $response['success']=0;
        $response['message']="Cannot login\nCheck your Credentials";
        //echo "Cannot login.Check your Credentials";
        echo json_encode($response);
      }

    }else {
      $response['success']=0;
      $response['message']="Fill in all the fields";
      //echo "Fill in all the fields";
      echo json_encode($response);
    }
  }
  mysqli_close($connect);
}
?>

<form  action="login.php" method="post">
  e-mail:<br><input type="text" name="email" maxlength="40"><br><br>
  Password:<br><input type="password" name="password" maxlength="40"><br><br>
  <input type="Submit" name="submit" value="Submit">
</form>
