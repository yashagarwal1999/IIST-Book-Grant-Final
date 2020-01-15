
<html>
    <head>
        <link rel="stylesheet" href="application/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body class="background">
        <div class="container" >
            <div class="row">
                <div class="col-sm-6 col-md-12 ">
                    <img class="logo" src="Logo.jpg">
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="container login-container">
                        <form name="Login-form"  action="Login.php" method="POST" autocomplete="off">
                        <div class="row margin-left">
                                
                                <div class="col-md-4">
                                <h6 class="margin-top">Username</h6>
                                <input name="Username" type="text" class="login-field" required>
                                
                                </div>
                        </div>
                        <div class="row margin-left">
                                
                                <div class="col-md-4">
                                <h6 class="padding">Password</h6>
                                <input name="Password" type="password" class="login-field" required>
                                
                                </div>
                        </div>

                        <div class="row margin-left">
                                <div class="col-md-6">
                            <a href="forgotpass.html" >Forgot Password?</a>
                            </div>
                        </div>
                        <?php
                        if(@$_GET['Empty']==true)
                        {
                            ?>
                            <div class="alert text-danger">
                                <?php echo $_GET['Empty']; ?>
                            </div>
                            <?php
                        }
                            ?>                        
                        <div class="row margin-left">
                                
                                <div class="col-md-4">
                                <input name="Login" type="submit" class="btn btn-primary login-button login-margin-top" value="Login">
                                </div>
                        </div>
                        </form>
                        
                        <div class="row margin-left">
                                
                                <div class="col-md-4">
                                <a href="register.html"><button class="btn btn-primary register-button" >Register</button></a>
                                </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>