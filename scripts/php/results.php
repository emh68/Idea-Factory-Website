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
    <link rel="stylesheet" href="../../styles/larger.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta property="og:title" content="The Idea Factory">
    <meta property="og:type" content="website">
    <meta property="og:image" content="">
    <meta property="og:url" content="https://www.ideafactoryrexburg.com">
    <title>The Idea Factory</title>
</head>

<body>
    <header>
        <a href="index.html"><img src="../../images/Screenshot 2024-09-24 133532.png" width="50px" height="50px" alt=""></a>
        <a href="index.html">
            <h1>IDEA FACTORY</h1>
        </a>
        <a id="menu" href="#" aria-label="Toggle Hamburger Menu"></a>
    </header>
    <nav>
        <ul class="navigation">
            <!-- <li><a href="#" class="active">HOME</a></li> -->
            <li><a href="classes.html">CLASSES</a></li>
            <li><a href="memberships.html">MEMBERSHIPS</a></li>
            <li><a href="reservations.html" onclick="checkLoginStatus()">RESERVATIONS</a></li>
            <li><a href="contact.html">CONTACT</a></li>
            <li><a href="signup.html">SIGN UP</a></li>
            <li><a href="login.html">LOG IN</a></li>
            <li><a href="FAQs.html">FAQs</a></li>
        </ul>
    </nav>

    <main>
    <?php
        session_start();
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $firstName = $_SESSION['first_name'] ?? 'Student';
            $class = $_SESSION['selected_class'] ?? 'Class';

            if ($message === 'registered') {
                echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>{$firstName} has been successfully registered for {$class}.</div>";
            } elseif ($message === 'waiting_list') {
                echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>The class is full. You have been added to the waiting list.</div>";
            }

            // Clear session variables
            unset($_SESSION['message']);
            unset($_SESSION['first_name']);
            unset($_SESSION['selected_class']);
        } else {
            echo "<div id='message' style='margin: 0; padding: 20px; text-align: center;'>No message to display.</div>";
        }
    ?>


    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3>
                    Services
                </h3>
                <ul>
                    <li><a href="/membership">Makerspace</a></li>
                    <li><a href="/classes">Classes</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="/faqs">FAQs</a></li>
                    <li><a href="/contact">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Company</h3>
                <ul>
                    <li><a href="/About">About Us</a></li>
                    <li><a href="/Become an Instructor">Careers</a></li>
                    <!-- <li><a href="/Investor Relations">Investor Relations</a></li> -->
                </ul>
            </div>
        </div>
        <br>
        <br>
        <div class="contact-info">
            <p>
                <strong>The Idea Factory</strong><br>343 E 4th N<br>Rexburg, ID 83440<br>Phone:
                208-356-0952<br>Email: info@ideafactoryusa.com<br><br>
            </p>
        </div>
        <div class="social">
            <a href="https://www.facebook.com/ideafactoryrexburg" target="_blank">
                <i class="fa-brands fa-square-facebook" style="color: #150E60; font-size: 36px;"></i>
            </a>
            <a href="https://www.instagram.com/ideafactoryrexburg" target="_blank">
                <i class="fa-brands fa-square-instagram" style="color: #150E60; font-size: 36px;"></i>
            </a>
            <a href="https://www.twitter.com/" target="_blank">
                <i class="fa-brands fa-square-x-twitter" style="color: #150E60; font-size: 36px;"></i>
            </a>
            <a href="https://www.youtube.com/" target="_blank"></a>
            <i class="fa-brands fa-square-youtube" style="color: #150E60; font-size: 36px;"></i>
            </a>
        </div>
        <div class="footer-content"><span>&copy; <span id="currentYear"></span> Idea Factory <span></span>
        </div>
    </footer>
    <script src="scripts/js/index.js"></script>
    <!-- <script src="https://js.stripe.com/v3/"></script>
    <script src="payment.js" defer></script> 
    <script src="success.js" defer></script> -->
</body>

</html>