    <nav>
        <ol>
        <?php
        // Two-dimensional array for relative URLS (index: 0) and navigation text )index: 1)
        $navLinks = array(
            array("index", "Home"),
            array("play", "Have Fun"),
            array("eat", "Eat Out"),
            array("create", "Make Art"),
            array("volunteer", "Give Back"),
            array("about", "Learn More"),
            array("contact", "Contact Us")
        );

        // Run through array to add links to nav bar.
        foreach ($navLinks as $link) {
            
            // If page is current page
            if ($path_parts['filename'] == $link[0]) {
                print '<li class="active-page">' . $link[1] . '</li>';
            } else {
                print '<li><a href="';
                
                // If current page is home page (link structure differs
                // for links from index page vs. other pages)
                if ($path_parts['filename'] == "index") {
                    print $link[0] . '/' . $link[0];
                } else {
                    print '../';
                    if ($link[0] != 'index') {
                        print $link[0] . '/';
                    }
                    print $link[0];
                }
                print '.php">' . $link[1] . '</a></li>';
            }
            print "\n";
        }
        ?>
        </ol> <!-- end nav menu -->
    </nav> <!-- end nav -->
    