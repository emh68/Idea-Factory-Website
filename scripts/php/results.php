<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Eli Hansen">
    <meta name="description" content="Website for makerspace Idea Factory">
    <link rel="icon" href="images/ideafactory.ico">
    <link rel="stylesheet" href="../../styles/fonts/stylesheet.css">
    <link rel="stylesheet" href="../../styles/normalize.css">
    <link rel="stylesheet" href="../../styles/base.css">
    <link rel="stylesheet" href="../../styles/medium.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta property="og:title" content="The Idea Factory">
    <meta property="og:type" content="website">
    <meta property="og:image" content="">
    <meta property="og:url" content="https://www.ideafactoryrexburg.com">
    <title>The Idea Factory</title>
</head>

<body>
<header>
        <div class="logo">
            <a href="index.html"><img src="../../images/Idea-Factory-logo-no-background.png" width="50" height="50"
                    alt=""></a>
        </div>
        <a href="index.html">
            <h1>IDEA FACTORY</h1>
        </a>
        <a id="menu" href="#" aria-label="Toggle Hamburger Menu"></a>

        <nav>
            <ul class="navigation">
                <li><a href="../../courses.html">COURSES</a></li>
                <li><a href="../../makerspace.html">MAKERSPACE</a></li>
                <li><a href="../../careers.html">CAREERS</a></li>
                <li><a href="../../contact.html">CONTACT</a></li>
                <li><a href="../../faqs.html">FAQs</a></li>
            </ul>
        </nav>
    </header>
    <main>
    <?php
    session_start();
    // Check if session messages are set and display them
    if (isset($_SESSION['message'])) {
        echo "<h3>" . htmlspecialchars($_SESSION['message']) . "</h3>";
        unset($_SESSION['message']); // Clear message after displaying it
    }

    if (isset($_SESSION['first_name']) && isset($_SESSION['selected_class'])) {
        echo "<h3 class='success-message'>" . htmlspecialchars($_SESSION['first_name']) . " has been successfully registered for " . htmlspecialchars($_SESSION['selected_class']) . ".</h3>";
        unset($_SESSION['first_name']);
        unset($_SESSION['selected_class']);
    }

    // Additional messages for waiting list and mailing list
    if (isset($_SESSION['waiting_list'])) {
        echo "<p>You have been added to the waiting list.</p>";
        unset($_SESSION['waiting_list']);
    }

    if (isset($_SESSION['mailing_list'])) {
        echo "<p>Your email has been added to the mailing list.</p>";
        unset($_SESSION['mailing_list']);
    }
    ?>

        <!-- <h2>Hello!</h2>

    session_start();
    
    // Check if there’s a message to display
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $firstName = $_SESSION['first_name'] ?? 'Student';
        $class = $_SESSION['selected_class'] ?? 'Class';
    
        // Display the message based on the status
        if ($message === 'registered') {
            echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>{$firstName} has been successfully registered for {$class}.</div>";
        } elseif ($message === 'waiting_list') {
            echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>The class is full. You have been added to the waiting list.</div>";
        } elseif ($message === "mailing_list") {
            echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>You have been successfully added to the mailing list. We will keep you updated on future courses and events.</div>";
        }
        // Clear the message after displaying
        unset($_SESSION['message'], $_SESSION['first_name'], $_SESSION['selected_class']);
    } -->

<!--     
        // session_start();
        // if (isset($_SESSION['message'])) {
        //     $message = $_SESSION['message'];
        //     $firstName = $_SESSION['first_name'] ?? 'Student';
        //     $class = $_SESSION['selected_class'] ?? 'Class';

        //     if ($message === 'registered') {
        //         echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>{$firstName} has been successfully registered for {$class}.</div>";
        //     } elseif ($message === 'waiting_list') {
        //         echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>The class is full. You have been added to the waiting list.</div>";
        //     }

        //     // Clear session variables
        //     unset($_SESSION['message']);
        //     unset($_SESSION['first_name']);
        //     unset($_SESSION['selected_class']);
        // } else {
        //     echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>No message to display.</div>";
        // }

        ///////////////////////////////////////////////////////
        // session_start();
        // if (isset($_SESSION['message'])) {
        //     $message = $_SESSION['message'];
        //     $firstName = $_SESSION['first_name'] ?? 'Student';
        //     $class = $_SESSION['selected_class'] ?? 'Class';
        
        //     if ($message === 'registered') {
        //         echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>{$firstName} has been successfully registered for {$class}.</div>";
        //     } elseif ($message === 'waiting_list') {
        //         echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>The class is full. You have been added to the waiting list.</div>";
        //     } elseif ($message === "You have been successfully added to the mailing list!") {
        //         echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>You have been successfully added to the mailing list. We will keep you updated on future courses and events.</div>";
        //     }
        
        //     // Clear session variables after displaying the message
        //     unset($_SESSION['message']);
        //     unset($_SESSION['first_name']);
        //     unset($_SESSION['selected_class']);
        // } else {
        //     echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>No message to display.</div>";
        // }
        // if (isset($_SESSION['message'])) {
        //     $message = $_SESSION['message'];
        //     $messageType = $_SESSION['message_type'] ?? '';
        
        //     if ($messageType === 'registered') {
        //         // Registration message
        //     } elseif ($messageType === 'waiting_list') {
        //         // Waiting list message
        //     } elseif ($messageType === 'mailing_list') {
        //         echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>$message</div>";
        //     }
        
        //     // Clear session variables
        //     unset($_SESSION['message']);
        //     unset($_SESSION['message_type']);
        // } -->



    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3>Services</h3>
                <ul>
                    <li><a href="../../membership.html">Makerspace</a></li>
                    <li><a href="../../courses.html">Courses</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="../../faqs.html">FAQs</a></li>
                    <li><a href="../../contact.html">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Company</h3>
                <ul>
                    <li><a href="../../careers.html">Careers</a></li>
                    <!-- <li><a href="/Investor Relations">Investor Relations</a></li> -->
                </ul>
            </div>
        </div>
        <br>
        <br>
        <div class="contact-info">
            <p>
                <strong>The Idea Factory</strong><br>343 E 4th N<br>Rexburg, ID 83440<br>Phone:
                208-656-1616<br>Email: contact@ideafactoryrexburg.com<br><br>
            </p>
        </div>
        <div class="social">
            <a href="https://www.facebook.com/ideafactoryrexburg" target="_blank">
                <i class="fa-brands fa-square-facebook" style="color: #CFEFFF; font-size: 36px;"></i>
            </a>
            <a href="https://www.instagram.com/ideafactoryrexburg" target="_blank">
                <i class="fa-brands fa-square-instagram" style="color: #CFEFFF; font-size: 36px;"></i>
            </a>
            <a href="https://www.twitter.com/" target="_blank">
                <i class="fa-brands fa-square-x-twitter" style="color: #CFEFFF; font-size: 36px;"></i>
            </a>
            <a href="https://www.youtube.com/" target="_blank">
                <i class="fa-brands fa-square-youtube" style="color: #CFEFFF; font-size: 36px;"></i>
            </a>
        </div>
        <div class="footer-content"><span>&copy; <span id="currentYear"></span><span> Idea Factory </span></span></div>
        <span id="siteseal">
            <script async
                src="https://seal.starfieldtech.com/getSeal?sealID=G8Xyj7E5YzenIjoR9l3vlmmyuTgHpjaYG8FSdRDxV7DHj57O3h1WFTISxkUB">
                </script>
        </span>
    </footer>
    <!-- <script src="/scripts/../js/index.js"></script> -->
</body>

</html>