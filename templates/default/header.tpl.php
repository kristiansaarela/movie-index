<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta name="author" content="Kristian" />

    <title><?php echo $title; ?></title>
    
    <link rel="stylesheet" type="text/css" href="<?php echo $p; ?>style.css" />
    <script type="text/javascript" src="<?php echo $p; ?>js/jquery-1.5.2.min.js"></script>
    <script>
    $(document).ready(function() {
        // ajax pagination.
        $('#pagination a').live('click', function(e) {
            e.preventDefault();
            //$("#encodes").fadeOut().delay(400);
            $.ajax({
                type: "GET",
                url: this + "&pagination=true&ajax=true",
                dataType: "json",
                success: function(ms) {
                    $("#encodes").empty().append(ms.content);
                    $("#pagination").empty().append(ms.pagination);
                    //$("#encodes").fadeIn();
                }
            });
        });
        
        // ajax sorting
        $('#header div a').live('click', function(e) {
            e.preventDefault();
            //$("#encodes").fadeOut().delay(400);
            $.ajax({
                type: "GET",
                url: this + "&ajax=true",
                dataType: "json",
                success: function(so) {
                    $("#encodes").empty().append(so.content);
                    $("#pagination").empty().append(so.pagination);
                    $(".total").empty().append(so.total);
                    //$("#encodes").fadeIn();
                }
            });
        });
        
        // quote.
        var refreshQuotes = setInterval(function() {
            $(".quote").fadeOut("slow", function() {
                $(this).load('index.php?quote=true&ajax=true').fadeIn("slow");
            });
        }, 10000);
    });
    </script>
</head>

<body>

<div id="container">
    <div id="header">
        <a href="<?php echo BASE_URL; ?>index.php" class="fl cet"><img src="<?php echo $p; ?>img/logo.png" alt="movie index logo" /></a>
        <p class="fr"><a href="#">Back to forum</a> &plusmn; <a href="<?php echo BASE_URL; ?>index.php">Index</a></p>
        <div class="cl genre cap">
            <?php echo _sort($generes, ' - '); ?>
        </div>
        <div class="fl lol cap">
            <?php echo _sort($alphabet, ' '); ?>
        </div>
        <div class="fr asda">
            <?php echo $types; ?>
        </div>
        <div class="cl fl lol">
            <form action="index.php" method="GET" id="search">
                <input type="text" name="q" size="15"/>
                <select name="t">
                    <option value="all">All</option>
                    <?php foreach($search_form_data['types'] as $id => $typ): ?>
                    <option value="<?php echo $id; ?>"><?php echo $typ; ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="y">
                    <option value="all">All</option>
                    <?php foreach($search_form_data['years'] as $year => $count): ?>
                    <option value="<?php echo $year ?>"><?php echo $year . '(' . $count . ')'; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" name="search" value="Search">
            </form>
        </div>
        <div class="fr asda">
            <form method="POST" action="index.php">
                <select name="style" onchange="submit()">
                    <?php echo $templateSelect; ?>
                </select>
            </form>
            <form method="POST" action="index.php">
                Display <select name="howMany" onchange="submit();">
                    <?php
                    for($i = 15; $i <= 50; $i = $i + 5)
                        echo "<option value=\"$i\"" . selected('HDUHowMany', $i) . ">$i</option>\n";
                    ?>
                </select> per page.
            </form>
            <?php #echo $select_template; ?>
        </div>
        <br class="cl" />
    </div>