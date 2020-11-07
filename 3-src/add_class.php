<?php
require 'vendor/autoload.php';
require 'pagination.php';

//Tạo client
$client = new MongoDB\Client();
$db = $client->StudentManagement;

$collectionClass = $db->classes;

//getLoginSession
session_start();
if(!isset($_SESSION['login']))
{
  header('Location: login.php');
  die();
}

$name = '';
$message = '';
if(isset($_POST['name']))
{
  $name = $_POST['name'];

  if(empty($name)){
    $message = 'Please enter your complete information and try again';
  }
  else{
    $document =[
      'name' => $name,
    ];
    $collectionClass->insertOne($document);
    header('Location: class.php');
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
    <title>Add-Class</title>
</head>
<body>
<?php include_once './navigation.php' ?>
<div class="container">
    <h1 class="mt-5">Add Class</h1>
       <a href="index.php" class="btn btn-success float-right mb-2">Home</a>
     <form action="add_class.php" method="POST">
         <div class="form-group">
           <label for="name">Class Name</label>
           <input type="text" name="name" id="name" class="form-control" placeholder="Enter name subject" value="<?php echo $name?>">
         </div>
         <div class="text-danger"><?php echo $message ?></div>
         <button type="submit" class="btn btn-primary">Submit</button>
       </form>
   </div>
</body>
</html>

