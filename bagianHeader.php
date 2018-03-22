<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">	
	<link rel="icon" href="backend/images/icono.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="">

    <title>Inventario OSS</title>

    <!--Core CSS -->
    <link href="backend/panel/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="backend/panel/css/bootstrap-reset.css" rel="stylesheet">
    <link href="backend/panel/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!--dynamic table-->
    <link href="backend/panel/js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="backend/panel/js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="backend/panel/js/data-tables/DT_bootstrap.css" />

    <!--datetimepicker table-->
    <link rel="stylesheet" href="backend/css/datepicker.min.css">
    <link href="backend/css/datepicker3.min.css" rel="stylesheet">
    <link href="backend/css/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="backend/plugin/fp/bootstrap-fileupload.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="backend/panel/css/style.css" rel="stylesheet">
    <link href="backend/panel/css/style-responsive.css" rel="stylesheet" />
    

    <link href="backend/panel/css/green-theme.css"rel="stylesheet">
    
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .sidebar-ed{color: white;border-radius: 4px;}
    </style>
</head>


<body>
    <section id="container" >
        <!--header start-->
        <header class="header fixed-top clearfix">
            <!--logo start-->
            <div class="brand">
                 <a href="index.php" class="logo">
                    <img src="backend/panel/images/logo.png" alt="" width="210" height="75">
                </a>
                <div class="sidebar-toggle-box">
                    <div class="fa fa-bars"></div>
                </div>
            </div>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- notification dropdown start-->
                    <li id="header_notification_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                            <i class="fa fa-bell-o"></i>
                            <span class="badge bg-warning">3</span>
                        </a>
                        <ul class="dropdown-menu extended notification">
                            <li>
                                <p>Notificaciones</p>
                            </li>
                            <li>
                                <div class="alert alert-info clearfix">
                                    <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                                    <div class="noti-info">
                                        <a href="#"> Server #1 overloaded.</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="alert alert-danger clearfix">
                                    <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                                    <div class="noti-info">
                                        <a href="#"> Server #2 overloaded.</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="alert alert-success clearfix">
                                    <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                                    <div class="noti-info">
                                        <a href="#"> Server #3 overloaded.</a>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </li>
                    <!-- notification dropdown end -->
                </ul>
                <!--  notification end -->
            </div>
            <div class="top-nav clearfix">
                <!--search & user info start-->
                <ul class="nav pull-right top-menu">
                    <li>
                        <input type="text" class="form-control search" placeholder=" Buscar">
                    </li>
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <img alt="" src="backend/panel/images/avatar1_small.png">
                            <span class="username"><?=$_SESSION['name'];?></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">          
                            <li><a href="index.php?page=logout"><i class="fa fa-key"></i>Salir</a></li>
                        </ul>
                    </li>
                </ul>
                <!--search & user info end-->
            </div>
            </header>
            <!--header end-->
            <aside>
                <div id="sidebar" class="nav-collapse">
                    <!-- sidebar menu start-->
                    <div class="leftside-navigation">
                        <ul class="sidebar-menu" id="nav-accordion">
                            <li>
                                <a href="index.php">
                                    <i class="fa fa-dashboard"></i>
                                    <span>OSS</span>
                                </a>
                            </li>
                            <li class="sub-menu">
                                <a href="javascript:;">
                                    <i class="fa fa-suitcase"></i>
                                    <span>Inventario</span>
                                </a>
                                <ul class="sub">
                                    <li><a href="?page=productos">Productos</a></li>
                                    <li><a href="?page=proyectos">Proyectos</a></li>
                                    <li><a href="?page=categorias">Categorias</a></li>
                                </ul> 
                            </li>
                            <li class="sub-menu">
                                <a href="javascript:;">
                                    <i class="fa fa-money"></i>
                                    <span>Mantenimiento</span>
                                </a>
                                <ul class="sub">
                                    <li><a href="?page=gen33">Generador 33</a></li>
                                    <li><a href="?page=gen34">Generador 34</a></li>
                                    <li><a href="?page=mpfm01">MPFM-01</a></li>
                                    <li><a href="?page=mpfm02">MPFM-02</a></li>
                                    <li><a href="?page=mpfm03">MPFM-03</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="?page=setting">
                                    <i class="fa fa-cogs"></i>
                                    <span>Configuraciones</span>
                                </a>
                                <ul class="sub">
                                    <li><a href="?page=setting">Cambiar mi contraseña</a></li>
									<li><a href="?page=correos">Añadir correos para avisos</a></li>
                                    li><a href="?page=correos">Agregar usuarios</a></li>
                                     </ul>
                            </li
                    </div>        
            <!-- sidebar menu end-->
                </div>
            </aside>
            <!--sidebar end-->
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                <!-- page start-->