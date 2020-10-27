<?php
require 'pagination.php';

$collectionClass = $db->classes;
$collectionStudent = $db->students;

$classList = $collectionClass->find()->toArray();

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
  <link rel="stylesheet" href="/css/styles.css">
  <title>edit-student</title>
  <style>
    h1 {
      margin: 30px 0;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Edit Student</h1>
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
            <!-- Chạy vòng lặp ở đây -->
            <?php foreach ($classList as $value) {
            ?>
            <option value="<?php echo $value['_id']?>" <?php echo $value['_id'] == $idClass ? 'selected' : ''?>><?php echo $value['name']?></option>
            <!-- Chạy vòng lặp ở đây -->
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