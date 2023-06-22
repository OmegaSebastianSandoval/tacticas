<?php require_once('../Connections/maoev.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_rsFotos = "-1";
if (isset($_GET['album'])) {
  $colname_rsFotos = $_GET['album'];
}
mysql_select_db($database_maoev, $maoev);
$query_rsFotos = sprintf("SELECT * FROM fotos WHERE album = %s", GetSQLValueString($colname_rsFotos, "int"));
$rsFotos = mysql_query($query_rsFotos, $maoev) or die(mysql_error());
$row_rsFotos = mysql_fetch_assoc($rsFotos);
$totalRows_rsFotos = mysql_num_rows($rsFotos);

$colname_rsAlbumes = "-1";
if (isset($_GET['album'])) {
  $colname_rsAlbumes = $_GET['album'];
}
mysql_select_db($database_maoev, $maoev);
$query_rsAlbumes = sprintf("SELECT * FROM album WHERE id = %s", GetSQLValueString($colname_rsAlbumes, "int"));
$rsAlbumes = mysql_query($query_rsAlbumes, $maoev) or die(mysql_error());
$row_rsAlbumes = mysql_fetch_assoc($rsAlbumes);
$totalRows_rsAlbumes = mysql_num_rows($rsAlbumes);

// Show Dynamic Thumbnail
$objDynamicThumb1 = new tNG_DynamicThumbnail("../", "KT_thumbnail1");
$objDynamicThumb1->setFolder("../imagenes/");
$objDynamicThumb1->setRenameRule("{rsFotos.Foto}");
$objDynamicThumb1->setResize(200, 150, true);
$objDynamicThumb1->setWatermark(false);

// Require the MXI classes
require_once ('../includes/mxi/MXI.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<!--
		Supersized - Fullscreen Slideshow jQuery Plugin
		Version : 3.2.7
		Site	: www.buildinternet.com/project/supersized
		
		Author	: Sam Dunn
		Company : One Mighty Roar (www.onemightyroar.com)
		License : MIT License / GPL License
	-->

	<head>
		<title>Galeria Terminal 2 Eldorado</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="image_src" href="http://201.245.85.180/t2eldorado/corte/logodorado.png" />
		<link rel="stylesheet" href="css/supersized.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="theme/supersized.shutter.css" type="text/css" media="screen" />
		
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.easing.min.js"></script>
		
		<script type="text/javascript" src="js/supersized.3.2.7.min.js"></script>
		<script type="text/javascript" src="theme/supersized.shutter.min.js"></script>
		
		<script type="text/javascript">
jQuery(function($){
				
				$.supersized({
				
					// Functionality
					slideshow               :   1,			// Slideshow on/off
					autoplay				:	1,			// Slideshow starts playing automatically
					start_slide             :   1,			// Start slide (0 is random)
					stop_loop				:	0,			// Pauses slideshow on last slide
					random					: 	0,			// Randomize slide order (Ignores start slide)
					slide_interval          :   5000,		// Length between transitions
					transition              :   6, 			// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
					transition_speed		:	1000,		// Speed of transition
					new_window				:	1,			// Image links open in new window/tab
					pause_hover             :   0,			// Pause slideshow on hover
					keyboard_nav            :   1,			// Keyboard navigation on/off
					performance				:	1,			// 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
					image_protect			:	1,			// Disables image dragging and right click with Javascript
															   
					// Size & Position						   
					min_width		        :   0,			// Min width allowed (in pixels)
					min_height		        :   0,			// Min height allowed (in pixels)
					vertical_center         :   1,			// Vertically center background
					horizontal_center       :   1,			// Horizontally center background
					fit_always				:	0,			// Image will never exceed browser width or height (Ignores min. dimensions)
					fit_portrait         	:   1,			// Portrait images will not exceed browser height
					fit_landscape			:   0,			// Landscape images will not exceed browser width
															   
					// Components							
					slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
					thumb_links				:	1,			// Individual thumb links for each slide
					thumbnail_navigation    :   0,			// Thumbnail navigation
					slides 					:  	[			// Slideshow Images
												 <?php do { ?>		
														
														{image : '../imagenes/<?php echo $row_rsFotos['imagen']; ?>', title : '<?php echo $row_rsFotos['tituloFoto']; ?>', thumb : '<?php echo $objDynamicThumb1->Execute(); ?>', url : ''},
														 <?php } while ($row_rsFotos = mysql_fetch_assoc($rsFotos)); ?>
												],
												
					// Theme Options			   
					progress_bar			:	1,			// Timer for each slide							
					mouse_scrub				:	0
					
				});
		    });
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
</script>
	    <script src="../Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
	
	<style type="text/css">
		ul#demo-block{ margin:0 15px 15px 15px; }
			ul#demo-block li{ margin:0 0 10px 0; padding:10px; display:inline; float:left; clear:both; color:#aaa; background:url('img/bg-black.png'); font:11px Helvetica, Arial, sans-serif; }
			ul#demo-block li a{ color:#eee; font-weight:bold; }
	</style>

<body>

	<!--Demo styles (you can delete this block)-->
   <div  align="center"> 
   <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td height="41"><script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','584','height','41','src','../flash/Botonera-maoeventos','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','wmode','transparent','movie','../flash/Botonera-maoeventos' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="584" height="41">
      <param name="movie" value="../flash/Botonera-maoeventos.swf" />
      <param name="quality" value="high" />
      <param name="wmode" value="transparent" />
      
      <embed src="../flash/Botonera-maoeventos.swf" width="584" height="41" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"></embed>
    </object>
</noscript></td>
    <td width="150">
      <?php
  mxi_includes_start("../menucss/menuAlbum.php");
  require(basename("../menucss/menuAlbum.php"));
  mxi_includes_end();
?></td>
  </tr>
</table>
</div>
<!--End of styles-->

	<!--Thumbnail Navigation-->
<div id="prevthumb"></div>
	<div id="nextthumb"></div>
	
	<!--Arrow Navigation-->
<a id="prevslide" class="load-item"></a>
	<a id="nextslide" class="load-item"></a>
	
	<div id="thumb-tray" class="load-item">
		<div id="thumb-back"></div>
		<div id="thumb-forward"></div>
	</div>
	
	<!--Time Bar-->
	<div id="progress-back" class="load-item">
		<div id="progress-bar"></div>
	</div>
	
	<!--Control Bar-->
	<div id="controls-wrapper" class="load-item">
  <div id="controls">
			
			<a id="play-button"><img id="pauseplay" src="img/pause.png"/></a>
		
			<!--Slide counter-->
			<div id="slidecounter">
				<span class="slidenumber"></span> / <span class="totalslides"></span>
			</div>
			
			<!--Slide captions displayed here-->
			<div id="slidecaption"></div>
			
			<!--Thumb Tray button-->
			<a id="tray-button"><img id="tray-arrow" src="img/button-tray-up.png"/></a>
			
			<!--Navigation-->
			<ul id="slide-list"></ul>
			
		</div>
	</div>
		<table width="500px" border="0" cellspacing="5" cellpadding="0"  style="background:#000; opacity:0.7; color:#FFFFFF;">
  <tr height="100px">
    <td valign="top">
    	<div align="left">
   <?php echo $row_rsAlbumes['tituloAlbum']; ?>
    </div>
   	<div align="justify" style="font-size:11px;">
    <?php echo $row_rsAlbumes['descripcion']; ?>
    </div>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsFotos);

mysql_free_result($rsAlbumes);

?>
