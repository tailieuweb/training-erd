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

$collectionSubject = $db->subjects;


//getLoginSession
session_start();
if(!isset($_SESSION['login']))
{
  header('Location: login.php');
  die();
}

// Insert 10 subject
// $documentSubject= [];
// for($i = 1; $i <= 10;$i++)
// {
//   $documentSubject[] = [
//     'name' => 'Subject ' . $i,
//     'subject_level' => $i%2 + 1,
//   ];
// }
// $collectionSubject->insertMany($documentSubject);

//Delete
if(isset($_POST['subjectID']))
{
  $id = new MongoDB\BSON\ObjectID($_POST['subjectID']);
  $collectionSubject->deleteOne(['_id' => $id]);
}

//Số trang = 1 mặc định
$page = 1;

//Nếu trên URL có request page (sau khi click vào button phân trang)
if(isset($_GET['page']))
{
  $page = $_GET['page'];
}

//Số sản phẩm ở một trang :
$limit = 20;

//Bỏ qua $limit phần tử để lấy $limit phần tử trang tiếp theo : 
$skip = ($page - 1) * $limit;

//Tổng số trang :
$totalPage = ceil($collectionSubject->count()/$limit);

//
//$id = new MongoDB\BSON\ObjectID("5f96dc42ba250000c1004d76");
$studentObject = $collectionSubject->find()->toArray();
// var_dump($studentObject[0]['classObject']);
// die();

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
      <h1 class="title mt-5">Subject List</h1>
      <a class="btn btn-success" href="add_subject.php">Add Subject</a>

      <table class="table">
          <thead>
            <tr>
              <th scope="col">Code</th>
              <th scope="col">Name</th>
              <th scope="col">Subject Level</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($studentObject as $value) 
            {
            ?>
            <tr class="student">
              <th><?php echo $value['_id']?></th>
              <th><?php echo $value['name']?></th>
              <td><?php echo $value['subject_level']?></td>
              <td>
                  <a href="edit_subject.php?id=<?php echo $value['_id']?>" class="btn btn-primary">Edit</a>
                    <form class="d-inline" action="subject.php?page=<?php echo $page?>" method="POST" id="formSubmit">
                      <input type="hidden" value="<?php echo $value['_id']?>" name="subjectID">
                      <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
              </td>
              <td>
            </tr>
            <?php }
            ?>
          </tbody>
        </table>
          <?php
          echo createPageLinks($totalPage, $page, "TEACHER");
          ?>
    </div>
</body>
</html>