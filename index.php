<?php

/**
 * @author Kristian Saarela
 * @copyright 2011 
 * @version 2.0a
 */

require('./includes/config.php');
require('./includes/class.template.php');
require('./includes/class.pagination.php');
require('./includes/class.inputfilter.php');

if(isset($_GET['ajax'], $_GET['quote']))
{
    echo $quotes[rand(0, count($quotes) - 1)];
    exit();
}

$filter = new InputFilter();
$p = new Pagination();

$errors = array();

/** years and total movies released in that year **/
try {
    $years = $dbh->prepare('SELECT year, COUNT(id) AS total_movies FROM movies GROUP BY year ORDER BY year DESC');
    
    if($years->execute())
    {
        while($row = $years->fetch(PDO::FETCH_ASSOC))
        {
            $years_totalMovies[$row['year']] = $row['total_movies'];
        }
    }
    else
    {
        throw new PDOException('Error while fetching movie year and count.');
    }
}
catch(PDOException $e)
{
    log_error($e->getMessage());
}

/** how many results to display **/
if(isset($_COOKIE['HDUHowMany'])) $p->set_display($_COOKIE['HDUHowMany']);

$m = (isset($_POST['howMany'])) ? $filter->process($_POST['howMany']) : false;
if($m)
{
    $p->set_display($m);
    setcookie('HDUHowMany', $m, time() + 60*60*60*24*365);
    header("Location: index.php");
}


if(isset($_GET['sort']) && in_array($_GET['sort'], $alphabet))
{
    $letter = $filter->process($_GET['sort']);
    
    try
    {
        $count = $dbh->prepare('SELECT COUNT(id) AS total FROM movies WHERE LEFT(title, 1) = :letter');
        $count->bindParam(':letter', $letter, PDO::PARAM_STR, 1);
        
        if($count->execute())
        {
            $total = $count->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            throw new PDOException('Error while getting letter ' . $letter . ' movies from database.');
        }
    }
    catch(PDOException $e)
    {
        log_error($e->getMessage());
    }
    
    $p->set_link(BASE_URL . 'index.php?sort=' . $letter . '&page=');
    $p->set_total($total[0]['total']);
    
    $movies = $dbh->prepare('SELECT * FROM movies WHERE LEFT(title, 1) = :letter LIMIT :limit1, :limit2');
    $movies->bindParam(':letter', $letter, PDO::PARAM_STR, 1);
    $movies->bindParam(':limit1', $p->limit['limit1'], PDO::PARAM_INT);
    $movies->bindParam(':limit2', $p->limit['limit2'], PDO::PARAM_INT);
}
else if(isset($_GET['sort']) && in_array($_GET['sort'], $generes))
{
    $genre = $filter->process($_GET['sort']);
    $like = "%" . $genre . "%";
    
    try
    {
        $count = $dbh->prepare('SELECT COUNT(id) AS total FROM movies WHERE genre LIKE :genre');
        $count->bindParam(':genre', $like, PDO::PARAM_STR);
        
        if($count->execute())
        {
            $total = $count->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            throw new PDOException('Error while getting ' . $genre . ' movies from database.');
        }
    }
    catch(PDOException $e)
    {
        log_error($e->getMessage());
    }
    
    $p->set_link(BASE_URL . 'index.php?sort=' . $genre . '&page=');
    $p->set_total($total[0]['total']);

    $movies = $dbh->prepare('SELECT * FROM movies WHERE genre LIKE :genre LIMIT :limit1, :limit2');
    $movies->bindParam(':genre', $like, PDO::PARAM_STR);
    $movies->bindParam(':limit1', $p->limit['limit1'], PDO::PARAM_INT);
    $movies->bindParam(':limit2', $p->limit['limit2'], PDO::PARAM_INT);
}
else if(isset($_GET['sort']) && in_array($_GET['sort'], $type))
{
    $type = $filter->process($_GET['sort']);
    
    try
    {
        $count = $dbh->prepare('SELECT COUNT(id) AS total FROM movies WHERE type = :type');
        $count->bindParam(':type', $type, PDO::PARAM_INT, 1);
        
        if($count->execute())
        {
            $total = $count->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            throw new PDOException('Error while getting ' . $type . ' movies from database.');
        }
    }
    catch(PDOException $e)
    {
        log_error($e->getMessage());
    }
    
    $p->set_link(BASE_URL . 'index.php?sort=' . $type . '&page=');
    $p->set_total($total[0]['total']);
    
    $movies = $dbh->prepare('SELECT * FROM movies WHERE type = :type LIMIT :limit1, :limit2');
    $movies->bindParam(':type', $type, PDO::PARAM_INT, 1);
    $movies->bindParam(':limit1', $p->limit['limit1'], PDO::PARAM_INT);
    $movies->bindParam(':limit2', $p->limit['limit2'], PDO::PARAM_INT);
}
else if(isset($_GET['search']))
{ /** search. form comes from functions.php search_form function -.- **/
    $query = $filter->process($_GET['q']);
    $type  = $filter->process($_GET['t']);
    $year  = $filter->process($_GET['y']);
    
    /** checks all three and if everything checks out, add to src array **/
    
    /** check query **/
    if(isset($query) AND !empty($query))
    {
        if(strlen($query) > 2 && strlen($query) < 64)
        {
            $squery = '%' . $query . '%';
            $src['title'] = ' title LIKE :squery';
        }
    }
    
    /** check type **/
    if(isset($type) AND !empty($type) AND $type !== 'all')
    {
        if(strlen($type) == 1)
        {
            $stype = $type;
            $src['type'] = ' type = :type';
        }
    }
    
    /** check year **/
    if(isset($year) AND !empty($year) AND $year !== 'all')
    {
        if(strlen($year) == 4)
        {
            $syear = $year;
            $src['year'] = ' year = :year';
        }
    }
    
    if(count($src) == 0)
    { /** if nothing is searched, just redirect to index. need better handling **/
        header("Location: " . BASE_URL . 'index.php');
    }
    
    $sql = 'SELECT * FROM movies WHERE';
       
    if(count($src) == 1)
    {
        $temp = array_values($src);
        $sql1 = $temp[0];
    }
    else if(count($src) == 2)
    {
        $temp = array_values($src);
        $sql1 = $temp[0] . ' AND' . $temp[1];
    }
    else if(count($src) == 3)
    {
        $temp = array_values($src);
        $sql1 = $temp[0] . ' AND' . $temp[1] . ' AND' . $temp[2];
    }
    
    try
    {
        $searchsql = 'SELECT COUNT(id) AS total FROM movies WHERE' . $sql1;
        $count = $dbh->prepare($searchsql);
        
        if(isset($src['title']))
        {
            $count->bindParam(':squery', $squery, PDO::PARAM_STR, 64);
        }
        
        if(isset($src['type']))
        {
            $count->bindParam(':type', $stype, PDO::PARAM_INT, 1);
        }
        
        if(isset($src['year']))
        {
            $count->bindParam(':year', $syear, PDO::PARAM_INT, 4);
        }
        
        if($count->execute())
        {
            $total = $count->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            throw new PDOException('Error with search query.');
        }
    }
    catch(PDOException $e)
    {
        log_error($e->getMessage());
    }
    
    $p->set_link(BASE_URL . 'index.php?search=search&q='.$query.'&t='.$type.'&y='.$year.'&page=');
    $p->set_total($total[0]['total']);
    
    $sqlend = ' ORDER BY add_time DESC LIMIT :limit1, :limit2';

    $movies = $dbh->prepare($sql . $sql1 . $sqlend);
    
    if(isset($src['title']))
    {
        $movies->bindParam(':squery', $squery, PDO::PARAM_STR, 64);
    }
    
    if(isset($src['type']))
    {
        $movies->bindParam(':type', $stype, PDO::PARAM_INT, 1);
    }
    
    if(isset($src['year']))
    {
        $movies->bindParam(':year', $syear, PDO::PARAM_INT, 4);
    }
    
    $movies->bindParam(':limit1', $p->limit['limit1'], PDO::PARAM_INT);
    $movies->bindParam(':limit2', $p->limit['limit2'], PDO::PARAM_INT);
}
else if(!isset($_GET['sort'])
        || !isset($_GET['search'])
        || !in_array($_GET['sort'], $alphabet, $generes, $types))
{
    try
    {
        $count = $dbh->prepare('SELECT COUNT(id) AS total FROM movies');
        
        if($count->execute())
        {
            $total = $count->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            throw new PDOException('Error while getting movies from database.');
        }
    }
    catch(PDOException $e)
    {
        log_error($e->getMessage());
    }
    
    $p->set_link('index.php?page=');
    $p->set_total($total[0]['total']);
    
    $movies = $dbh->prepare('SELECT * FROM movies LIMIT :limit1, :limit2');
    $movies->bindParam(':limit1', $p->limit['limit1'], PDO::PARAM_INT);
    $movies->bindParam(':limit2', $p->limit['limit2'], PDO::PARAM_INT);
}

try
{
    if($movies->execute())
    {
        $content = $movies->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
        throw new PDOexception('Error while generating pagination');
    }
}
catch(PDOexception $e)
{
    log_error($e->getMessage());
}

$pagesystem = $p->paginate();

/** templating shit ... **/
if(isset($_POST['style']))
{
    if($key = array_search($_POST['style'], $templates))
    { // match found!
        $path = './templates/' . $templates[$key] . '/';
        setcookie('HDUTemplate', $templates[$key], time() + 60*60*60*24*365);
        header("Location: index.php");
    }
}

if(isset($_COOKIE['HDUTemplate']))
{
    if($key = array_search($_COOKIE['HDUTemplate'], $templates))
    { // match found!
        $path = './templates/' . $templates[$key] . '/';
    }
    else $path = './templates/default/';
}
else $path = './templates/default/';

$index = new Template($path);
$index->set('p', $path);

    $header = new Template($path);
    $header->set('title', 'Movie Index');
    $header->set('p', $path);
    $header->set('generes', $generes);
    $header->set('alphabet', $alphabet);
    
    $t = "";
    foreach($types as $val => $link)
        $t .= "<a href=\"index.php?sort=".$val."\" ".addbg($val).">".$link."</a>&nbsp;\r";
    $header->set('types', $t);
    $header->set('search_form', search_form($types, $years_totalMovies));
    
    $templateSelect = '';
    foreach($templates AS $key => $val)
        $templateSelect .= '<option value="' . $key . '"' . selected('HDUTemplate', $key) . '>' . $val . '</option>';
    $header->set('templateSelect', $templateSelect);

$index->set('header', $header->fetch('header.tpl.php'));
$index->set('total', $total[0]['total']);
$index->set('quote', $quotes[rand(0,count($quotes) - 1)]);

    $movies = new Template($path);
    $movies->set('m', $content);

if(isset($_GET['pagination'], $_GET['ajax']))
{
    echo json_encode(array('content' => $movies->fetch('movies.tpl.php'), 'pagination' => $pagesystem));
    exit();
}
if(isset($_GET['sort'], $_GET['ajax']))
{
    echo json_encode(array('content' => $movies->fetch('movies.tpl.php'), 'pagination' => $pagesystem, 'total' => 'Total movies found: ' . $total[0]['total']));
    exit();
}

$index->set('movies', $movies->fetch('movies.tpl.php'));
$index->set('pagination', $pagesystem);

    $footer = new Template($path);
    $footer->set('copy', date('Y') . ' @ Kristian Saarela');

$index->set('footer', $footer->fetch('footer.tpl.php'));
echo $index->fetch('index.tpl.php');