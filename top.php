<!DOCTYPE HTML>
<html lang="en">
    <head>
        <?php
        // Parse the URL into htmlentities to remove any suspicious values that someone might try to pass in.
        
        $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
        
        // Break the URL up into an array, then pull out just the filename
        
        $path_parts = pathinfo($phpSelf);

        // Array for title content
        $pages = array (
            array("play", "Play - "),
            array("eat", "Eat - "),
            array("create", "Create - "),
            array("volunteer", "Volunteer - "),
            array("about", "About - "),
            array("contact", "Contact Us - ")
        );
        
        print '<title>';
        foreach ($pages as $title) {
            if ($path_parts['filename'] == $title[0]) {
                print $title[1];
            }
        }
        print 'Old North End | Burlington, VT</title>';
        ?>
        
        <meta charset="utf-8" />
        <meta name="author" content="Joe Siebert" />
        <meta name="description" content="The Old North End is a neighborhood in Burlington, VT, and it’s unlike any other part of Burlington. The ONE is becoming more vibrant and unique by the day." />
        <!--[if lt IE 9]>
            <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->
        
        <link rel="stylesheet"
              href="<?php if ($path_parts['filename'] != 'index') print "../"; ?>css/styles.css"
              type="text/css"
              media="screen" />
        
        <?php
        // Include slider script if current page is home page
        if ($path_parts['filename'] == 'index') {
            include 'nonglobal/slider-script.php';
        }
        // Include form features if current page is contact page
        if ($path_parts['filename'] == 'contact') {
            include 'nonglobal/form-features.php';
        }
        ?>

    </head> <!-- end head -->
    
    <?php
    print '<body id="' . $path_parts['filename'] . '">';
        
    include 'header.php';
    include 'nav.php';
    ?>
