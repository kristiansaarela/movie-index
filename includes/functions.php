<?php

/**
 * @author Kristian
 * @copyright 2010
 */

function _sort($array, $separator)
{
    $_s = '';
    
    $x = 1;
    $c = count($array);
    foreach($array as $arr)
    {
        $line = ($x == $c) ? '' : $separator;
        $_s .= "<a href=\"index.php?sort=" . $arr . "\">" . $arr . "</a>" . $line;
        $x++;
    }
    return $_s;
}

function type($a, $reverse = false)
{
    if($reverse)
    {
        if($a == "m-HD") return 1;
        if($a == "720p") return 2;
        if($a == "480p") return 3;
        if($a == "Tv-720p") return 4;
        if($a == "Tv-480p") return 5;
        if($a == "Concerts") return 6;
        if($a == "Music-Videos") return 7;
    }
    else
    {
        if($a == 1) return "m-HD";
        if($a == 2) return "720p";
        if($a == 3) return "480p";
        if($a == 4) return "Tv-720p";
        if($a == 5) return "Tv-480p";
        if($a == 6) return "Concerts";
        if($a == 7) return "Music-Videos";
    }
}

function selected($key, $val)
{
    if(isset($_COOKIE[$key]) && $_COOKIE[$key] == $val) return ' selected="selected" ';
}

//TODO: values to template variables instead of the whole form.
function search_form($type, $years)
{
    $form = "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"GET\" id=\"search\">
        <input type=\"text\" name=\"q\" size=\"15\"/>
        <select name=\"t\">
            <option value=\"all\">All</option>";
    
    foreach($type as $id => $typ)
    {
        $form .= "<option value=\"" . $id . "\">" . $typ . "</option>";
    }
    
    $form .= "</select>
        <select name=\"y\">
            <option value=\"all\">All</option>";
    
    foreach($years as $year => $count)
    {
        $form .= "<option value=\"" . $year . "\">" . $year . " (" . $count . ")</option>";
    }
    
    $form .= "</select>
        <input type=\"submit\" name=\"search\" value=\"Search\">";
    
    $form .= "</form>";
    
    return $form;
}

function addbg($a)
{
    if($a == 1) $a = "mhd";
    if($a == 2) $a = "movieh";
    if($a == 3) $a = "moviel";
    if($a == 4) $a = "tvh";
    if($a == 5) $a = "tvl";
    if($a == 6) $a = "contcert";
    return "class=\"$a\"";
}

function log_error($msg)
{
    // Log error and display sorry page.
    error_log(date("[Y-m(M)-j G:i:s] ", time()) . $msg . "\r", 3, './errors.log');
    include_once('class.template.php');
    //TODO: use template specific error page.
    $t=new Template('./templates/default/');
    $t->set('link',BASE_URL);
    echo $t->fetch('error.tpl.php');
    exit();
}

?>