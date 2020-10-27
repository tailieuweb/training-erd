<?php
require 'pagination.php';

$collectionClass = $db->classes;
$collectionStudent = $db->students;


$page = 1;
if(isset($_GET['page']))
{
  $page = $_GET['page'];
}

$limit = 20;
$skip = ($page - 1) * $limit;

$keyword = '';
$keyword2 = '';
if(isset($_GET['keyword']))
{
    $keyword = $_GET['keyword'];
    $keyword2 = new \MongoDB\BSON\Regex($keyword);
}

//dem so luong phan tu de phan trang
$totalPage = $collectionStudent->count(['$or' => [['name' => $keyword2], ['email' => $keyword2]]])/$limit;
$totalPage = ceil($totalPage);

$studentObject = $collectionStudent->aggregate(array(
    [
        // '$match' => ['$or' => [['name' => $keyword , ['email' => $keyword]]] ]
        '$match' => ['$or' => [['name' => $keyword2], ['email' => $keyword2]]]
    ],
    [
      '$lookup' => [
        'from' => 'classes',
        'localField' => 'class',
        'foreignField' => '_id',
        'as' => 'classObject'
        ]    
    ],
    [
      '$project' => ['class' => 0]
    ],
    [
      '$unwind' => '$classObject'
    ],
    [
        '$skip' => $skip
    ],
    [
        '$limit' => $limit
    ]
  ))->toArray();

//
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="/js/jquery-3.5.1.min.js"></script>
    <title>Student</title>
    <style>
      h1{
        margin-top : 50px;
      }
    </style>
</head>
<body>
    <div class="container">
      <h1 class="title">Student List</h1>
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
              <td>
            </tr>
            <?php }
            ?>
          </tbody>
        </table>
        <?php
        echo createPageLinks($totalPage, $page, "SEARCH", $keyword);
        ?>
    </div>
</body>
</html>