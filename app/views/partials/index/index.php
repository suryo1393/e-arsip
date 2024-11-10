        <?php 
        $page_id = null;
        $comp_model = new SharedController;
        ?>
        <div  class=" py-5">
            <div class="container">
                <div class="row ">
                    <div class="col-md-8 comp-grid">
                        <div class=""><!DOCTYPE html>
                            <html lang="en">
                                <head>
                                    <meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                            <title>Background Image Example</title>
                                            <style>
                                                body {
                                                background-image: url('assets/images/bg2.jpg');
                                                background-size: cover;
                                                background-position: center;
                                                background-repeat: no-repeat;
                                                height: 100vh;
                                                margin: 0; /* Remove default margin */
                                                }
                                                .content {
                                                display: flex;
                                                justify-content: center;
                                                align-items: center;
                                                height: 100%;
                                                color: white;
                                                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Optional text shadow */
                                                font-size: 2em;
                                                font-family: Arial, sans-serif;
                                                }
                                            </style>
                                        </head>
                                        <body>
                                            <div class="content">
                                                <h1>Welcome to E-Arsip</h1>
                                            </div>
                                        </body>
                                    </html>
                                </div>
                            </div>
                            <div class="col-md-4 comp-grid">
                                <div class=""><link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
                                    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
                                        <link rel="stylesheet" href="assets/css/style.css">
                                        </div>
                                        <?php $this :: display_page_errors(); ?>
                                        
                                        <div  class="bg-light p-5 animated bounce page-content">
                                            <div>
                                                <h4><i class="fa fa-key"></i> User Login</h4>
                                                <hr />
                                                <?php 
                                                $this :: display_page_errors(); 
                                                ?>
                                                <form name="loginForm" action="<?php print_link('index/login/?csrf_token=' . Csrf::$token); ?>" class="needs-validation form page-form" method="post">
                                                    <div class="input-group form-group">
                                                        <input placeholder="Username Or Email" name="username"  required="required" class="form-control" type="text"  />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="form-control-feedback fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="input-group form-group">
                                                        <input  placeholder="Password" required="required" v-model="user.password" name="password" class="form-control " type="password" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="form-control-feedback fa fa-key"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="row clearfix mt-3 mb-3">
                                                        <div class="col-6">
                                                            <label class="">
                                                                <input value="true" type="checkbox" name="rememberme" />
                                                                Remember Me
                                                            </label>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="<?php print_link('passwordmanager') ?>" class="text-danger"> Reset Password?</a>
                                                        </div>
                                                    </div>
                                                    <div class="form-group text-center">
                                                        <button class="btn btn-primary btn-block btn-md" type="submit"> 
                                                            <i class="load-indicator">
                                                                <clip-loader :loading="loading" color="#fff" size="20px"></clip-loader> 
                                                            </i>
                                                            Login <i class="fa fa-key"></i>
                                                        </button>
                                                    </div>
                                                    <hr />
                                                    <div class="text-center">
                                                        Don't Have an Account? <a href="<?php print_link("index/register") ?>" class="btn btn-success">Register
                                                        <i class="fa fa-user"></i></a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        