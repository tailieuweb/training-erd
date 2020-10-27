<?php
require 'pagination.php';

$collectionClass = $db->classes;
$collectionStudent = $db->students;

$classList = $collectionClass->find()->toArray();

$name = '';
$email = '';
$class = '';
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['selectClass']))
{
  $name = $_POST['name'];
  $email = $_POST['email'];
  $class = $_POST['selectClass'];
}

if($name != '' && $email != '' && $class != '')
{
  $document =[
    'name' => $name,
    'email' => $email,
    'class' => new MongoDB\BSON\ObjectID($class)
  ];
  $collectionStudent->insertOne($document);
  header('Location: index.php');
  exit();
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
    <title>add-student</title>
    <style>
      h1{
        margin : 30px 0;
      }
    </style>
</head>
<body>
  <div class="container">
   <h1>Add Student</h1>
      <a href="index.php" class="btn btn-success float-right mb-2">Home</a>
    <form action="add.php" method="POST">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" name="email" id="email" class="form-control" placeholder="Enter email">
        </div>
        <div class="form-group">
          <label for="selectClass">Class</label>
          <select class="form-control" name="selectClass" id="selectClass">
            <!-- Chạy vòng lặp ở đây -->
            <?php foreach ($classList as $value) {
            ?>
            <option value="<?php echo $value['_id']?>"><?php echo $value['name']?></option>
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

