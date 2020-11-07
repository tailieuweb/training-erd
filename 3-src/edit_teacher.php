<?php
require 'vendor/autoload.php';
require 'pagination.php';

//Tạo client
$client = new MongoDB\Client();
$db = $client->StudentManagement;

$collectionTeacher = $db->teachers;

//getLoginSession
session_start();
if(!isset($_SESSION['login']))
{
  header('Location: login.php');
  die();
}

//Thực hiện tìm một sinh viên mang id nhận trên URL
$id = $_GET['id'];
$studentList = $collectionTeacher->findOne(['_id' => new MongoDB\BSON\ObjectID($id)]);

$name = $studentList['name'];
$email = $studentList['email'];
$faculty = $studentList['faculty'];
$message = '';
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['faculty']))
{
  $name = $_POST['name'];
  $email = $_POST['email'];
  $faculty = $_POST['faculty'];

  if(empty($name) || empty($email) || empty($faculty)){
    $message = 'Please enter your complete information and try again';
  }
  else{
    $document =[
      'name' => $name,
      'email' => $email,
	  'faculty' => $faculty
    ];
    if($collectionTeacher->updateOne(['_id' => new MongoDB\BSON\ObjectID($id)], ['$set' => $document]));
    {
        header('Location: teacher.php');
        exit();
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <base href="http:localhost:3000">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Edit-Teacher</title>
</head>

<body>
<?php include_once './navigation.php' ?>
  <div class="container">
    <h1 class="mt-5">Edit Teacher</h1>
       <a href="index.php" class="btn btn-success float-right mb-2">Home</a>
     <form action="" method="POST">
         <div class="form-group">
           <label for="name">Teacher Name</label>
           <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" 	value="<?php echo $name?>">
         </div>
         <div class="form-group">
           <label for="email">Email</label>
           <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" value="<?php echo $email?>">
         </div>
		 <div class="form-group">
           <label for="faculty">Faculty</label>
           <input type="text" name="faculty" id="email" class="form-control" placeholder="Enter faculty" value="<?php echo $faculty?>">
         </div>
         <div class="text-danger"><?php echo $message ?></div>
         <button type="submit" class="btn btn-primary">Submit</button>
       </form>
   </div>
</body>
</html>