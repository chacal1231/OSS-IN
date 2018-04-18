<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /> 
<?php
session_start();
ob_start();
if (!$_SESSION['admin']){
    header("Location: login.php");
  }
include 'bagianHeader.php';
include 'inc/config.php';
include 'inc/class.coupon.php';
require 'inc/fpdf/fpdf.php';
//require_once 'inc/PEAR2/Autoload.php';
//require_once 'inc/routeros_api.class.php';


function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

if(isset($_GET['page'])){
	if(!file_exists('pages/'.$_GET['page'].'.php')){
		include('pages/404.php');
	}else{
		include('pages/'.$_GET['page'].'.php');
	}
}else{
	include('pages/home.php');
}

include 'bagianFooter.php';
?>