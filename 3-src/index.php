<?php
require 'vendor/autoload.php';
require 'pagination.php';

//Tạo client
$client = new MongoDB\Client();
$db = $client->StudentManagement;

$collectionClass = $db->classes;
$collectionStudent = $db->students;

//Insert 5 classes
// $documentClass = [];
// for($i = 1;$i <= 5;$i++)
// {
//     $documentClass[] = [
//       'name' => 'Công nghệ thông tin ' . $i,
//     ];
// }
// $collectionClass->insertMany($documentClass);

//Insert 100 students
// $documentStudent = [];
// for($i = 1; $i <= 100;$i++)
// {
//   $documentStudent[] = [
//     'name' => 'Student ' . $i,
//     'email' => 'email000' . $i . '@gmail.com',
//     'class' => $collectionClass->aggregate([['$sample' => ['size' => 1]]])->toArray()[0]['_id']
//   ];
// }
// $collectionStudent->insertMany($documentStudent);

//Delete
if(isset($_POST['studentID']))
{
  $id = new MongoDB\BSON\ObjectID($_POST['studentID']);
  $collectionStudent->deleteOne(['_id' => $id]);
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
$totalPage = ceil($collectionStudent->count()/$limit);

//
//$id = new MongoDB\BSON\ObjectID("5f96dc42ba250000c1004d76");
$studentObject = $collectionStudent->aggregate(array(
  [
    '$lookup' => [
      'from' => 'classes',
      'localField' => 'class',
      'foreignField' => '_id',
      'as' => 'classObject'
      ]
  ],
  [
    '$unwind' => '$classObject'
  ],
  [
    '$skip' => $skip
  ],
  [
    '$limit' => $limit
  ],
))->toArray();
// var_dump($studentObject[0]['classObject']);
// die();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Student</title>
</head>
<body>
    <div class="container">
      <h1 class="title mt-5">Student List</h1>
      <a class="btn btn-success" href="add.php">Add</a>
      <header class="d-flex float-right mb-3">
        <form class="form-inline my-2 my-lg-0 float-right m-2" action="search.php">
          <input class="form-control mr-sm-2" type="text" name="keyword" placeholder="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </header>

      <table class="table">
          <thead>
            <tr>
              <th scope="col">Code</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Class</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($studentObject as $value) 
            {
            ?>
            <tr class="student">
              <th><?php echo $value['_id']?></th>
              <th><?php echo $value['name']?></th>
              <td><?php echo $value['email']?></td>
              <td><?php echo $value['classObject']['name']?></td>
              <td>
                  <a href="edit.php?id=<?php echo $value['_id']?>" class="btn btn-primary">Edit</a>
                    <form class="d-inline" action="index.php?page=<?php echo $page?>" method="POST" id="formSubmit">
                      <input type="hidden" value="<?php echo $value['_id']?>" name="studentID">
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
          echo createPageLinks($totalPage, $page);
          ?>
    </div>
</body>
</html>