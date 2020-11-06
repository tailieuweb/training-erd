<?php
require 'vendor/autoload.php';
require 'pagination.php';

//Tạo client
$client = new MongoDB\Client();
$db = $client->StudentManagement;

$collectionSubjectStudent = $db->subjects_student;
$collectionStudent = $db->students;
$collectionTeacher = $db->teachers;
$collectionSubject = $db->subjects;

//getLoginSession
session_start();
if(!isset($_SESSION['login']))
{
  header('Location: login.php');
  die();
}

$studentId = '';
$subjectId = '';
$teacherId = '';
$point = 0;
$message = '';
if(isset($_POST['student_id']) && isset($_POST['subject_id']) && isset($_POST['teacher_id']) && isset($_POST['point']))
{
  $studentId = $_POST['student_id'];
  $subjectId = $_POST['subject_id'];
  $teacherId = $_POST['teacher_id'];
  $point = $_POST['point'];
  $studentIdReg = preg_match('/^(?=[a-f\d]{24}$)(\d+[a-f]|[a-f]+\d)/i', $studentId);
  $subjectIdReg = preg_match('/^(?=[a-f\d]{24}$)(\d+[a-f]|[a-f]+\d)/i', $subjectId);
  $teacherIdReg = preg_match('/^(?=[a-f\d]{24}$)(\d+[a-f]|[a-f]+\d)/i', $teacherId);
  $checkPoint = $point >= 0 && $point <= 10;
  if(empty($studentId) || empty($subjectId) || empty($teacherId) || empty($point) || !$studentIdReg || !$subjectIdReg || !$teacherIdReg || !$checkPoint){
    $message = 'Please check your complete information and try again';
  }
  else {
    $studentObject = $collectionStudent->findOne(['_id' => new MongoDB\BSON\ObjectID($studentId)]);
    $subjectObject = $collectionSubject->findOne(['_id' => new MongoDB\BSON\ObjectID($subjectId)]);
    $teacherObject = $collectionTeacher->findOne(['_id' => new MongoDB\BSON\ObjectID($teacherId)]);
    if($studentObject && $subjectObject && $teacherObject){
      $document =[
        'student_id' => $studentId,
        'subject_id' => $subjectId,
        'teacher_id' => $teacherId,
        'point' => $point
      ];
      $collectionSubjectStudent->insertOne($document);
      header('Location: index.php');
      exit();
    }
    else{
      $message = 'Can not find the data please check the information';
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
    <title>Add-Teacher</title>
</head>
<body>
<?php include_once './navigation.php' ?>
<div class="container">
    <h1 class="mt-5">Add Subject for Student</h1>
       <a href="index.php" class="btn btn-success float-right mb-2">Home</a>
     <form action="" method="POST">
         <div class="form-group">
           <label for="student_id">Student ID</label>
           <input type="text" name="student_id" id="name" class="form-control" placeholder="Enter student id" value="<?php echo $studentId?>">
         </div>
         <div class="form-group">
           <label for="subject_id">Subject ID</label>
           <input type="text" name="subject_id" id="name" class="form-control" placeholder="Enter name subject" value="<?php echo $subjectId?>">
         </div>
         <div class="form-group">
           <label for="teacher_id">Teacher ID</label>
           <input type="text" name="teacher_id" id="name" class="form-control" placeholder="Enter name subject" value="<?php echo $teacherId?>">
         </div>
         <div class="form-group">
           <label for="point">Point</label>
           <input type="number" name="point" id="name" class="form-control" placeholder="Enter name subject" value="<?php echo $point?>">
         </div>
         <div class="text-danger"><?php echo $message ?></div>
         <button type="submit" class="btn btn-primary">Submit</button>
       </form>
   </div>
</body>
</html>

