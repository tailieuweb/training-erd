<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client();
$db = $client->StudentManagement;

$collectionClass = $db->admin;

//Use scss, compile and put scss into css
require_once "scssphp/scss.inc.php";
use ScssPhp\ScssPhp\Compiler;
$scss = new Compiler();
$scss_string = file_get_contents('css/style.scss');
file_put_contents('css/style.css', $scss->compile($scss_string));

//Insert 5 classes
// $documentClass = [];
// for($i = 1;$i <= 5;$i++)
// {
//     $documentClass[] = [
//       'username' => 'admin0' . $i,
//       'password' => md5('123456')
//     ];
// }
// $collectionClass->insertMany($documentClass);

session_start();
unset($_SESSION['login']);

$user = '';
$password = '';
$message = '';

if(isset($_POST['username']) && isset($_POST['password']))
{
    $user = $_POST['username'];
    $password = md5($_POST['password']);

    if($user = $collectionClass->findOne(['username' => $user]))
    {
        if($user['password'] == $password)
        {
            $_SESSION['login'] = true;
            header('Location: index.php');
            die();
        }
        else
        {
            $message = 'Username or password invalid! Please try again.';
        }
    }        
    else
    {
        $message = 'Username or password invalid! Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <form action="" method="post">
            <div class="form-group">
              <label for="username">Username :</label>
              <input type="text"
                class="form-control" name="username" id="username" aria-describedby="helpId" placeholder="">
            </div>
            <div class="form-group">
              <label for="password">Password :</label>
              <input type="password"
                class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="">
            </div>
            <div class="text-danger"><?php echo $message ?></div>
            <button type="submit" class="btn btn-primary">
                Login
            </button>
        </form>
    </div>
</body>
</html>