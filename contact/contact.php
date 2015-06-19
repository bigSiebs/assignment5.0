<?php
include '../top.php';

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1: Initalize variables
//
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// 
// SECTION 1a: Variable for classroom purposes to help find errors

$debug = false;

if (isset($_GET['debug'])) {
    $debug = true;
}

if ($debug) {
    print "<p>DEBUG MODE IS ON!</p>";
}

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1b: Securtity

// define security variable to be used in SECTION 2a
$yourURL = $domain . $phpSelf;

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1c: Form variables: Initalize variables, one for each form element,
// in the order they appear on the form

// contact
$firstName = "";
$lastName = "";
$email = "";


// personal
$relation = "Resident";
$neighborhood = "Old North End";
$comments = "";

// feedback
$visit = false;
$newsletter = false;
$website = false;
$feedback = "";

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1d: Form error flags: Initalize ERROR flags, one for each form element
// we validate, in the order they appear in SECTION 1c

$firstNameERROR = false;
$emailERROR = false;

$commentsERROR = false;

$feedbackERROR = false;

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1e: Misc. variables

// Array to hold error messages
$errorMsg = array();

// Array to hold form values to be written to CSV file
$dataRecord = array();

$mailed = false; // Not mailed yet

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 2: Process for when the form is submitted

if (isset($_POST['btnSubmit'])) {

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2a: Security
    
    if (!securityCheck(true)) {
        $msg = '<p>Sorry, you cannot access this page. ';
        $msg.= 'Security breach detected and reported.';
        die($msg);
    }

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2b: Sanitize data
    
    // Remove any potential JS or HTML code from users input on the form.
    // Follow same order as declared in SECTION 1c.
    
    $firstName = htmlentities($_POST['txtFirstName'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;
    
    $lastName = htmlentities($_POST['txtLastName'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastName;
    
    $email = filter_var($_POST['txtEmail'], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;
    
    $relation = htmlentities($_POST['radRelation'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $relation;
    
    $neighborhood = htmlentities($_POST['lstNeighborhood'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $neighborhood;
    
    $comments = htmlentities($_POST['txtComments'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $comments;
    
    if (isset($_POST['chkVisit'])) {
        $visit = true;
    } else {
        $visit = false;
    }
    $dataRecord[] = $visit;
    
    if (isset($_POST['chkNewsletter'])) {
        $newsletter = true;
    } else {
        $newsletter = false;
    }
    $dataRecord[] = $newsletter;
    
    if (isset($_POST['chkWebsite'])) {
        $website = true;
    } else {
        $website = false;
    }
    $dataRecord[] = $website;
    
    $feedback = htmlentities($_POST['txtFeedback'], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $feedback;

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2c: Validation: Check each value for possible errors or empty.
    
    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name.";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have invalid characters.";
        $firstNameERROR = true;
    }
    
    if ($lastName == "") {
        $errorMsg[] = "Please enter your first name.";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your first name appears to have invalid characters.";
        $lastNameERROR = true;
    }
    
    if ($email == "") {
        $errorMsg[] = "Please enter your email address.";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }
    
    if ($comments == "") {
        $errorMsg[] = "Please provide a comment explaining your neighborhood preference.";
        $commentsERROR = true;
    } elseif (!verifyAlphaNum($comments)) {
        $errorMsg[] = "Your response in the first comments field contains invalid characters.";
        $commentsERROR = true;
    }
    
    if ($feedback == "") {
        $errorMsg[] = "Please provide a feedback in the second comments field.";
        $feedbackERROR = true;
    } elseif (!verifyAlphaNum($feedback)) {
        $errorMsg[] = "Your response in the second comments field contains invalid characters.";
        $feedbackERROR = true;
    }

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2d: Process form - passed validation (errorMsg is empty)
    
    if (!$errorMsg) {
        if ($debug) {
            print "<p>Form is valid.</p>";
        }
    
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2e: Save data: Save data to CSV file
    
        $fileExt = ".csv";
        
        $myFilename = "registration";
        
        $filename = $myFilename . $fileExt;
        
        if ($debug) {
            print "\n\n<p>Filename is " . $filename . ".</p>";
        }
        
        // Open file for append
        $file = fopen('data/' . $filename, 'a');
        
        // Write to file
        fputcsv($file, $dataRecord);
        
        // Close file
        fclose($file);

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2f: Create message
    
        $message = "<h2>Thank you for providing much-needed information to Grow ONE!</h2>";
        $message.= "<p>A copy of your submission appears below.</p>";
        
        foreach ($_POST as $key => $value) {
            $message.= "<p>";
            $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));
            
            foreach ($camelCase as $one) {
                $message.= $one . " ";
            }
            $message.= ": " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
        }

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2g: Mail to user
    
        $to = $email; // the person who filled out form
        $cc = "";
        $bcc = "";
        $from = "Grow ONE <jsiebert@uvm.edu>";
        
        // subject of mail should match form
        $todaysDate = strftime("%x");
        $subject = "Thanks for your interest in the ONE, " . $todaysDate;
        
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // ends form is valid
} // ends if form was submitted

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 3: Display form
//
?>

<article id="main">
    <h2>Form</h2>
    
    <?php
    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 3a
    
    // If its the first time coming to form or there are errors, display form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing marked with 'end body submit'
        print "<h2>Your request has ";
        
        if (!$mailed) {
            print 'not ';
        }
        
        print "been processed.</h2>";
        
        if ($mailed) {
            print "<p>A copy of this message has been sent to: " . $email . ".</p>";
            print "<p>Mail message:</p>";
            print $message;
        }
        
    } else {
        
    
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // SECTION 3b: Error messages: Display any error message before we print form
    
        if ($errorMsg) {
            print '<div class="errors">';
            print "<ol>\n";
            foreach ($errorMsg as $err) {
                print "\t<li>" . $err . "</li>\n";
            }
            print "</ol>\n";
            print "</div>";
        }
    
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // SECTION 3c: HTML form: Display HTML form
        // Action is to this same page. $phpSelf is defined in top.php
        /* Note lines like: value="<?php print $email; ?> 
         * These make the form sticky by displaying the default value or
         * the value that was typed in previously.
         * Also note lines like <?php if ($emailERROR) print 'class="mistake"'; ?> 
         * These allow us to use CSS to identify errors with style. */
    ?>
    
    <form action="<?php print $phpSelf; ?>"
          method="post"
          id="frmRegister">
          
        <fieldset class="wrapper">
            <legend></legend>
            <p>Take a moment to fill out the following form. Your information is very valuable to us as we strive to continue the growth of the Old North End.</p>
            
            <fieldset class="wrapperTwo">
                <legend></legend>
                
                <fieldset class="contact">
                    <legend>Contact Info</legend>
                    <?php
                    // Array for unique values of each block (index: 0, id/name;
                    // 1, label text; 2, PHP variable; 3, placeholder text)
                    $textBoxes = array(
                        array("txtFirstName", "First Name", $firstName, $firstNameERROR, "Enter your first name"),
                        array("txtLastName", "Last Name", $lastName, $lastNameERROR, "Enter your last name"),
                        array("txtEmail", "Email", $email, $emailERROR, "Enter a valid email address")
                    );
                    
                    // Variable for tabindex, to be incremented as loop iterates
                    $tabIndex = 100;
                    
                    foreach ($textBoxes as $box) {
                        print "\n\t\t" . '<label for="' . $box[0] . '" class="required">';
                        print $box[1];
                        print "\n\t\t\t" . '<input type="text" id="' . $box[0] . '" name="' . $box[0] . '"';
                        print "\n\t\t\t\t" . 'value="' . $box[2] . '"';
                        print "\n\t\t\t\t" . 'tabindex="' . strval($tabIndex) . '" ';
                        if ($box[3]) print 'class="mistake" ';
                        print 'maxlength="45" placeholder="' . $box[4] . '"';
                        print "\n\t\t\t\t" . 'onfocus="this.select"';
                        // Change if new box is used first
                        if ($box[0] == "txtFirstName") {
                            print "\n\t\t\t\t" . "autofocus";
                        }
                        print ">";
                        print "\n\t\t</label>";
                        
                        // Increment tabIndex by 10
                        $tabIndex+= 10;
                    }
                    ?>
                </fieldset> <!-- end contact -->
                <fieldset class="personal">
                    <legend>Personal Info</legend>
                    <fieldset class="radio">
                        <legend>Which of the following best describes your relation to Burlington?</legend>
                        <?php
                        // Array for unique values in each block (index: 0, id; 1, value/label
                        $radioButtons = array(
                            array("radRelationResident", "Resident"),
                            array("radRelationVisitor", "Visitor"),
                            array("radRelationNone", "No relation")
                        );
                        
                        // Variable for tabIndex
                        $tabIndex = 200;
                        
                        foreach ($radioButtons as $button) {
                            print "\n\t\t" . '<label><input type="radio"';
                            print "\n\t\t\t" . 'id="' . $button[0] . '"';
                            print "\n\t\t\t" . 'name="radRelation"';
                            print "\n\t\t\t" . 'value="' . $button[1] . '"';
                            if ($relation == $button[1]) {
                                print "\n\t\t\tchecked";
                            }
                            print "\n\t\t\t" . 'tabindex="' . strval($tabIndex) . '">';
                            print $button[1];
                            print "</label>";
                            
                            $tabIndex+= 10;
                        }
                        ?>
                    </fieldset> <!-- end radio -->
                    <fieldset class="listbox">
                        <label for="lstNeighborhood">Favorite Burlington Neighborhood</label>
                        <select id="lstNeighborhood" name="lstNeighborhood"
                                tabIndex="300">
                        <?php
                        // Array for listbox options
                        $listOptions = array("Downtown", "Hill Section", "New North End", "Old North End", "South End", "I don't know");
                        
                        foreach ($listOptions as $option) {
                            print "\n\t\t\t" . "<option ";
                            if ($neighborhood == $option) {
                                print 'selected ';
                            }
                            print 'value="' . $option . '">' . $option . "</option>";
                            print "\n";
                        }
                        ?>
                    </select>
                    </fieldset> <!-- end listbox -->
                    <fieldset class="comments">
                        <label for="txtComments" class="required">Please provide at least one reason for your choice.</label>
                        <textarea id="txtComments"
                                  name="txtComments"
                                  tabindex="400"
                                  <?php if ($commentsERROR) print 'class="mistake"'; ?>
                                  onfocus="this.select()"><?php print $comments; ?></textarea>
                    </fieldset> <!-- end comments -->
                </fieldset> <!-- end personal -->
                <fieldset class="feedback">
                    <fieldset class="checkbox">
                        <legend>Of the following, please check all that apply.</legend>
                        <?php
                        // Array for unique values in each block (index: 0, id/name; 1, value; 2, variable; 3, label text
                        $checkboxes = array(
                            array("chkVisit", "Visit", $visit, "I plan to visit the Old North End in the future."),
                            array("chkNewsletter", "Newsletter", $newsletter, "I am interested in your newsletter."),
                            array("chkWebsite", "Website", $website, "This site is useful to me.")
                        );
                        
                        $tabIndex = 500;
                        
                        foreach ($checkboxes as $checkbox) {
                            print "\n\t\t<label><input" . ' type="checkbox"';
                            print "\n\t\t\t" . 'id="' . $checkbox[0] . '" name="' . $checkbox[0] . '"';
                            print "\n\t\t\t" . 'value="' . $checkbox[1] . '"';
                            if ($checkbox[2]) print " checked";
                            print "\n\t\t\t" . 'tabindex="' . strval($tabIndex) . '">';
                            print $checkbox[3];
                            print "</label>";
                            
                            $tabIndex+= 10;
                        }
                        ?>
                    </fieldset> <!-- end checkbox -->
                    <fieldset class="comments">
                            <label for="txtFeedback" class="required">Please provide additional feedback. If you don't have any, please write, "No comment."</label>
                            <textarea id="txtFeedback"
                                      name="txtFeedback"
                                      tabindex="600"
                                      <?php if ($feedbackERROR) print 'class="mistake"'; ?>
                                      onfocus="this.select()"><?php print $feedback; ?></textarea>
                    </fieldset> <!-- end comments -->
                </fieldset> <!-- end feedback -->
            </fieldset> <!-- end wrapperTwo -->
            <fieldset class="buttons">
                <legend></legend>
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" tabindex="900" class="button">
            </fieldset> <!-- end buttons -->
        </fieldset> <!-- end wrapper -->
    </form> <!-- end form -->
    <?php 
    } // end body submit
    ?>
</article>

<aside>
    <p>Looking for more information? Are you a local individual, group, business, etc. looking for representation on this site? Did you notice something wrong with our site?</p>
    <p>If the answer to any of these questions is yes, or you have other questions you'd like answered, or comments you want us to hear, fill out the form. It will only take a second for you to provide the information, and this information will greatly help us on our mission to encourage social, artistic, and economic growth in the Old North End. We promise we won’t spam your account or give your personal information to anyone else.</p>
    <p>One last thing: We're contemplating starting a newsletter to be released periodically. We're not exactly sure the form it will take, but it will include, but not be limited to, news about upcoming events, new projects, etc. in the ONE. If you're interested in receiving this newsletter in the event it comes to fruition, please note your interest by checking the box at the end of the form!</p>
</aside>

<?php
include '../footer.php';
?>
