<?php 
require 'vendor/autoload.php';

//Tạo client
$client = new MongoDB\Client();

$db = $client->StudentManagement;

function createPageLinks($totalPage, $page, $payType = 'INDEX', $keyword = '')
{
    $link = '<ul class="pagination d-flex align-items-center justify-content-center">';

    if($payType == 'INDEX')
    {
        $payType = 'index.php?page=';
    }
    if($payType == 'SEARCH')
    {
        $payType = 'search.php?keyword=' . $keyword . '&page=';
    }

    $disable = '';
    if($page == 1)
    {
        $disable = ' disabled';
    }
    $link .= "<li class='page-item".$disable."'><a class='page-link' href=".$payType.(1).">&laquo;&laquo;</a></li>";
    $link .= "<li class='page-item".$disable."'><a class='page-link' href=".$payType.($page-1).">&laquo;</a></li>";
    for($i = 1; $i <= $totalPage; $i++)
    {
        $disable = '';
        if($page == $i)
        {
            $disable = ' disabled';
        }
        $link .= "<li class='page-item".$disable."'><a class='page-link' href='". $payType . $i ."'>".$i."</a></li>";
    }

    $disable = '';
    if($page == $totalPage)
    {
        $disable = ' disabled';
    }
    $link .= "<li class='page-item".$disable."'><a class='page-link' href=".$payType.($page+1).">&raquo;</a></li>";
    $link .= "<li class='page-item".$disable."'><a class='page-link' href=".$payType.$totalPage.">&raquo;&raquo;</a></li>";

    $link .= '</ul>';
    return $link;
}   