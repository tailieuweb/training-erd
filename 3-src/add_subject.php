<?php
require 'vendor/autoload.php';
require 'pagination.php';

//Tạo client
$client = new MongoDB\Client();
$db = $client->StudentManagement;

$collectionSubject = $db->subjects;

//getLoginSession
session_start();
if(!isset($_SESSION['login']))
{
  header('Location: login.php');
  die();
}

$name = '';
$subjectLevel = '';
$message = '';
if(isset($_POST['name']) && isset($_POST['subject_level']))
{
  $name = $_POST['name'];
  $subjectLevel = $_POST['subject_level'];

  if(empty($name) || empty($subjectLevel)){
    $message = 'Please enter your complete information and try again';
  }
  else{
    $document =[
      'name' => $name,
      'subject_level' => $subjectLevel,
    ];
    $collectionSubject->insertOne($document);
    header('Location: subject.php');
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
    <title>Add-Teacher</title>
</head>
<body>
<?php include_once './navigation.php' ?>
<div class="container">
    <h1 class="mt-5">Add Subject</h1>
       <a href="index.php" class="btn btn-success float-right mb-2">Home</a>
     <form action="add_subject.php" method="POST">
         <div class="form-group">
           <label for="name">Subject Name</label>
           <input type="text" name="name" id="name" class="form-control" placeholder="Enter name subject" value="<?php echo $name?>">
         </div>
         <div class="form-group">
           <label for="email">Subject Level</label>
           <input type="text" name="subject_level" id="email" class="form-control" placeholder="Enter subject level" value="<?php echo $subjectLevel?>">
         </div>
         <div class="text-danger"><?php echo $message ?></div>
         <button type="submit" class="btn btn-primary">Submit</button>
       </form>
   </div>
</body>
</html>

