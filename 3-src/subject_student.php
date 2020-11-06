<?php
require 'vendor/autoload.php';
require 'pagination.php';

//Use scss, compile and put scss into css
require_once "scssphp/scss.inc.php";
use ScssPhp\ScssPhp\Compiler;
$scss = new Compiler();
$scss_string = file_get_contents('css/style.scss');
file_put_contents('css/style.css', $scss->compile($scss_string));

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

if(!isset($_GET['id'])){
  header('Location: index.php');
}


$id = $_GET['id'];
// Insert 5 subject for student
// $documentSubject= [];
// for($i = 1; $i <= 5;$i++)
// {
//   $documentSubject[] = [
//     'student_id' => '5fa4f3d2f91200009600759e',
//     'teacher_id' => '5fa502eb71250000ec004288',
//     'subject_id' => '5fa505df71250000ec004454',
//     'point' => "9"
//   ];
// }
// $collectionSubjectStudent->insertMany($documentSubject);
$final_scrore = 0;
$total_subject_level = 0;
$text_welcome = '';
$subjectHas = false;

$subjectStudentObject = $collectionSubjectStudent->find(['student_id' => $id])->toArray();
$studentObject = $collectionStudent->findOne(['_id' => new MongoDB\BSON\ObjectID($id)]);
if($subjectStudentObject){
  $text_welcome = $studentObject['name'] . ' learning result';
  $subjectHas = true;
}else{
  $text_welcome = 'There are no data available for ' . $studentObject['name'] . ' students';
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Subjects</title>
</head>
<body>
<?php include_once './navigation.php' ?>
    <div class="container">
      <h1 class="title mt-5">Subject Detail</h1>
      <h2 class="title mt-5 text-info"> <?php echo  $text_welcome?> </h2>
      <?php if($subjectHas){ ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Subject Code</th>
              <th scope="col">Student Name</th>
              <th scope="col">Subject Name</th>
              <th scope="col">Subject Level</th>
              <th scope="col">Teacher Name</th>
              <th scope="col">Point</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($subjectStudentObject as $value) 
            { 
              $teacherObject = $collectionTeacher->findOne(['_id' => new MongoDB\BSON\ObjectID($value['teacher_id'])]);
              $subjectObject = $collectionSubject->findOne(['_id' => new MongoDB\BSON\ObjectID($value['subject_id'])]);
              $final_scrore += $value['point'] * $subjectObject['subject_level'];
              $total_subject_level += $subjectObject['subject_level'];
            ?>
            <tr class="student">
              <th><?php echo $subjectObject['_id']?></th>
              <th><?php echo $studentObject['name']?></th>
              <td><?php echo $subjectObject['name']?></td>
              <th><?php echo $subjectObject['subject_level']?></th>
              <th><?php echo $teacherObject['name']?></th>
              <td><?php echo $value['point']?></td>
              <td>
            </tr>
            <?php }
            ?>
          </tbody>
        </table>
        <h2 class="title mt-5">Final grade: <?php echo round($final_scrore/$total_subject_level, 2) ?></h2>
      <?php } ?>
    </div>
</body>
</html>