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

$email = "jsiebert@uvm.edu";

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION 1d: Form error flags: Initalize ERROR flags, one for each form element
// we validate, in the order they appear in SECTION 1c

$emailERROR = false;

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
    
    $email = filter_var($_POST['txtEmail'], FILTER_SANITIZE_EMAIL);
    
    $dataRecord[] = $email;

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2c: Validation: Check each value for possible errors or empty.
    
    if ($email =="") {
        $errorMsg[] = "Please enter your email address.";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
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
    
        $message = "<h2>Thank you for providing much-needed information to GrowONE!</h2>";
        $message.= "<p>A copy of your submission appears below.</p>";
        
        foreach ($_POST as $key => $value) {
            $message.= "<p>";
            $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));
            
            foreach ($camelCase as $one) {
                $message.= $one . " ";
            }
            $message.= " = " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
        }

    // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //
    // SECTION 2g: Mail to user
    
        $to = $email; // the person who filled out form
        $cc = "";
        $bcc = "";
        $from = "GrowONE <noreply@w3.silk.uvm.edu>";
        
        // subject of mail should match form
        $todaysDate = strftime("%x");
        $subject = "Thanks for your interest in the ONE" . $todaysDate;
        
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
            <legend>Help Us Out!</legend>
            <p>Take a moment to fill out the following form. Your information is very valuable to us as we strive to continue the growth of the Old North End.</p>
            
            <fieldset class="wrapperTwo">
                <legend>Provide your information</legend>
                
                <fieldset class="contact">
                    <legend>Contact Info</legend>
                    <label for="txtEmail" class="required">Email
                        <input type="text" id="txtEmail" name="txtEmail"
                               value="<?php print $email; ?>"
                               tabindex="100" maxlength="45" placeholder="Enter a valid email address"
                               <?php if ($emailERROR) print 'class="mistake"'; ?>
                               onfocus="this.select()"
                               autofocus>
                    </label>
                </fieldset> <!-- end contact -->
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

</aside>

<?php
include '../footer.php';
?>
