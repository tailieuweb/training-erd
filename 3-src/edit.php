<?php
require 'vendor/autoload.php';
require 'pagination.php';

//Tạo client
$client = new MongoDB\Client();
$db = $client->StudentManagement;

$collectionClass = $db->classes;
$collectionStudent = $db->students;

//getLoginSession
session_start();
if(!isset($_SESSION['login']))
{
  header('Location: login.php');
  die();
}

//Thực hiện tìm hết danh sách lớp
$classList = $collectionClass->find()->toArray();

//Thực hiện tìm một sinh viên mang id nhận trên URL
$id = $_GET['id'];
$studentList = $collectionStudent->findOne(['_id' => new MongoDB\BSON\ObjectID($id)]);

$name = $studentList['name'];
$email = $studentList['email'];
$idClass = $studentList['class'];

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['selectClass']))
{
  $name = $_POST['name'];
  $email = $_POST['email'];
  $idClass = $_POST['selectClass'];

  $document =[
    'name' => $name,
    'email' => $email,
    'class' => new MongoDB\BSON\ObjectID($idClass)
  ];
  if($collectionStudent->updateOne(['_id' => new MongoDB\BSON\ObjectID($id)], ['$set' => $document]));
  {
      header('Location: index.php');
      exit();
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
  <title>edit-student</title>
</head>

<body>
  <div class="container">
    <h1 class="mt-5">Edit Student</h1>
       <a href="index.php" class="btn btn-success float-right mb-2">Home</a>
     <form action="" method="POST">
         <div class="form-group">
           <label for="name">Name</label>
           <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" value="<?php echo $name?>">
         </div>
         <div class="form-group">
           <label for="email">Email</label>
           <input type="text" name="email" id="email" class="form-control" placeholder="Enter email" value="<?php echo $email?>">
         </div>
         <div class="form-group">
           <label for="selectClass">Class</label>
           <select class="form-control" name="selectClass" id="selectClass">
            <?php foreach ($classList as $value) {
            ?>
            <option value="<?php echo $value['_id']?>" <?php echo $value['_id'] == $idClass ? 'selected' : ''?>>
              <?php echo $value['name']?>
            </option>
            <?php 
            }
            ?>
           </select>
         </div>
         <button type="submit" class="btn btn-primary">Submit</button>
       </form>
   </div>
</body>
</html>