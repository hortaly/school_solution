<?php
include("db_conn.php");
$sql = "SELECT * FROM settings LIMIT 1";
$result = mysqli_query($conn, $sql);
$settings = mysqli_fetch_assoc($result);
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <!--=============== basic  ===============-->
        <meta charset="UTF-8">
        <title>Joshtech Innovations Model Schools || Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        
       <!-- start of mega tag -->
<meta name="keywords" content="Wetin Dey Copper Platform Farmers Sensor, ODSFR"/>

<meta name="author" content="Wetin Dey Code Academy, Wetin Dey Inc., Wetin Dey">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://copper.com.ng">
<meta property="og:title" content="Wetin Dey Copper Platform || Home">
<meta property="og:description" content="Ondo State Forestry Anti Encroacment and Revenue Agent">
<meta property="og:image" content="https://copper.com.ng/bisola/icon.png">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://copper.com.ng">
<meta property="twitter:title" content="Wetin Dey Copper Platform || Home">
<meta name="twitter:site" content="@ondfaer" />
<meta property="twitter:description" content="Wetin Dey Copper Platform Farmers Sensor">
<meta property="twitter:image" content="https://copper.com.ng/icon.png">
    <!-- end of mega tag -->

        <!--=============== css  ===============-->	
        <link type="text/css" rel="stylesheet" href="css/reset.css">
        <link type="text/css" rel="stylesheet" href="css/plugins.css">
        <link type="text/css" rel="stylesheet" href="css/style.css">
        <link type="text/css" rel="stylesheet" href="css/color.css">
        <link type="text/css" rel="stylesheet" href="css/yourstyle.css">
        <!--=============== favicons ===============-->
        <link rel="shortcut icon" href="images/favicon.png">
    </head>
    <body>
        <!--================= loader ================-->
        <div class="loader-holder">
            <div class="loader-inner loader-vis">
                <div class="loader"></div>
            </div>
        </div>
        <!--================= loader end ================-->
        <!--================= main start ================-->
        <div id="main">
            <!--================= Header ================-->
            <header class="main-header">
                <a href="index.php" class="logo-holder"><img src="images/logo.png" alt=""></a>
                <!-- info-button -->
                <div class="nav-button" id="open-button">
                    <span class="menu-global menu-top"></span>
                    <span class="menu-global menu-middle"></span>
                    <span class="menu-global menu-bottom"></span>
                </div>
                <!-- info-button end-->
                <div class="show-share isShare"><img src="images/share.png" alt=""></div>
            </header>
            <!-- End header -->
            <!--================= menu ================-->
            <div class="menu-wrap">
                <div class="menu-inner">
                    <!-- menu logo-->
                    <a href="index.php" class="menu-logo"><img src="images/logo2.png" alt=""></a>
                    <!-- menu logo end -->
                    <div class="hid-men-wrap   alt">
                        <div id="hid-men">
                            <ul class="menu">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="new_admn_log/">Admin Login</a></li>
                                <li><a href="register/">Admin Register</a></li>
                                <li><a href="login/">Member Login</a></li>
                                <li><a href="register/member_reg.php">Member Register</a></li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="morph-shape" id="morph-shape" data-morph-open="M-7.312,0H15c0,0,66,113.339,66,399.5C81,664.006,15,800,15,800H-7.312V0z;M-7.312,0H100c0,0,0,113.839,0,400c0,264.506,0,400,0,400H-7.312V0z">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100 800" preserveAspectRatio="none">
                        <path d="M-7.312,0H0c0,0,0,113.839,0,400c0,264.506,0,400,0,400h-7.312V0z"/>
                    </svg>
                </div>
            </div>
            <!--menu end-->
            <!-- Hero section   -->
            <div class="hero-wrap fl-wrap full-height">
                <!-- hero-wrap-image-slider-holder  end -->
                <div class="hero-slider-holder fl-wrap full-height">
                    <div class="num-holder3"></div>
                    <!-- hero-wrap-image-slider  -->
                    <div class="hero-slider fscs fl-wrap full-height">
                        <!-- item  -->
                        <div class="item fl-wrap full-height">
                            <div class="bg"  data-bg="images/bg/2.JPG"></div>
                            <div class="overlay"></div>
                            <div class="hero-wrap-item left-her alt">
                                <div class="container">
                                    <div class="fl-wrap section-entry">
                                        <h3>Welcome to</h3>
                                        <h2><?php echo $settings['school_name']; ?></h2>
                                        <a href="new_admn_log/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Admin Login</b></span></a>  
                        
                                        <a href="parents_log/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Parent Login</b></span></a>  
                                        <a href="login/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Staff Login</b></span></a>  
                                         
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- item  end-->
                        <!-- item  -->
                        <div class="item fl-wrap full-height">
                            <div class="bg"  data-bg="images/bg/1.JPG"></div>
                            <div class="overlay"></div>
                            <div class="hero-wrap-item right-her alt">
                                <div class="container">
                                <div class="fl-wrap section-entry">
                                          <h3>Welcome to</h3>
                                        <h2><?php echo $settings['school_name']; ?></h2>
                                        <a href="new_admn_log/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Admin Login</b></span></a>  
                        
                                        <a href="parents_log/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Parent Login</b></span></a>  
                                        <a href="login/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Staff Login</b></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- item  end-->
                        <!-- item  -->
                        <div class="item fl-wrap full-height">
                            <div class="bg"  data-bg="images/bg/3.JPG"></div>
                            <div class="overlay"></div>
                            <div class="hero-wrap-item left-her alt">
                                <div class="container">
                                <div class="fl-wrap section-entry">
                                        <h3>Welcome to</h3>
                                        <h2><?php echo $settings['school_name']; ?></h2>
                                        <a href="new_admn_log/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Admin Login</b></span></a>  
                        
                                        <a href="parents_log/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Parent Login</b></span></a>  
                                        <a href="login/" class="btn hide-icon"  ><i class="fa fa-user"></i><span><b>Staff Login</b></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- item  end-->
                                                     
                    </div>
                    <!-- hero-wrap-image-slider  end -->
                    <!--  navigation -->
                    <div class="customNavigation gals">
                        <a class="prev-slide transition"> <i class="fa fa-angle-left"></i></a>
                        <a class="next-slide transition"><i class="fa fa-angle-right"></i></a>
                    </div>
                    <!--  navigation end-->
                </div>
                <!-- hero-wrap-image-slider-holder  end -->
            </div>
            <!-- Hero section   end -->
            <!-- fixed column  -->
            <div class="fixed-column">
                <div class="column-image fl-wrap full-height">
                    <div class="bg bg-scroll" ></div>
                    <div class="overlay"></div>
                </div>
                <div class="bg-title alt"><span></span></div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar"></div>
                </div>
            </div>
            <!-- fixed column  end -->

           
            <!-- arrow nav -->
            <div class="arrowpagenav"></div>
            <!-- arrow nav end-->
            <!-- footer-->
            <footer class="main-footer">
                <!-- <a href="../contact.php" class="mail-link"><i class="fa fa-envelope" aria-hidden="true"></i></a> -->
                <!-- header-social-->
                <!-- <div class="footer-social">
                    <ul >
                        <li><a href="#" target="_blank" ><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#" target="_blank" ><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-user"></i></a></li>
                    </ul>
                </div> -->
                <!-- header-social end-->           
                <div class="copyright">ODSTA &#169; <?php echo date('Y'); ?></div>
            </footer>
            <!-- footer end -->
            <!-- Share container  -->
            <div class="share-container  isShare"  data-share="['facebook','pinterest','googleplus','twitter','linkedin']"></div>
            <!-- Share container  end-->
        </div>

        <!-- Main end -->
        <!--=============== scripts  ===============-->
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/scripts.js"></script>
    </body>
</html>