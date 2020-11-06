<?php 
function createPageLinks($totalPage, $page, $pageType = 'INDEX', $keyword = '')
{
    $link = '<ul class="pagination d-flex align-items-center justify-content-center">';

    if($pageType == 'INDEX')
    {
        $pageType = 'index.php?page=';
    }
    if($pageType == 'SEARCH')
    {
        $pageType = 'search.php?keyword=' . $keyword . '&page=';
    }
    if($pageType == 'TEACHER')
    {
        $pageType = 'teacher.php?page=';
    }

    $disable = '';
    if($page == 1)
    {
        $disable = ' disabled';
    }
    $link .= "<li class='page-item".$disable."'><a class='page-link' href=".$pageType.(1).">&laquo;&laquo;</a></li>";
    $link .= "<li class='page-item".$disable."'><a class='page-link' href=".$pageType.($page-1).">&laquo;</a></li>";
    for($i = 1; $i <= $totalPage; $i++)
    {
        $disable = '';
        if($page == $i)
        {
            $disable = ' disabled';
        }
        $link .= "<li class='page-item".$disable."'><a class='page-link' href='". $pageType . $i ."'>".$i."</a></li>";
    }

    $disable = '';
    if($page == $totalPage)
    {
        $disable = ' disabled';
    }
    $link .= "<li class='page-item".$disable."'><a class='page-link' href=".$pageType.($page+1).">&raquo;</a></li>";
    $link .= "<li class='page-item".$disable."'><a class='page-link' href=".$pageType.$totalPage.">&raquo;&raquo;</a></li>";

    $link .= '</ul>';
    return $link;
}   