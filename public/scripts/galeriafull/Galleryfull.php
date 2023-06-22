<?php
session_start();
?>
<?php require_once('../Connections/conexion.php'); ?>
<?php
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

$colname_rsfotos1 = "-1";
if (isset($_GET['imagen'])) {
  $colname_rsfotos1 = $_GET['imagen'];
}
mysql_select_db($database_conexion, $conexion);
$query_rsfotos1 = sprintf("SELECT * FROM fotos WHERE id_album = %s ORDER BY orden ASC", GetSQLValueString($colname_rsfotos1, "int"));
$rsfotos1 = mysql_query($query_rsfotos1, $conexion) or die(mysql_error());
$row_rsfotos1 = mysql_fetch_assoc($rsfotos1);
$totalRows_rsfotos1 = mysql_num_rows($rsfotos1);
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

		<title>Supersized - Full Screen Background Slideshow jQuery Plugin</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="css/supersized.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="theme/supersized.shutter.css" type="text/css" media="screen" />
        <link href="../css/plantilla.css" rel="stylesheet" type="text/css" />
		<link href="../css/estilos.css" rel="stylesheet" type="text/css" />
		<link href="../css/logo.css" rel="stylesheet" type="text/css" />
		
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
					slide_interval          :   3000,		// Length between transitions
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
					{image : '../imagenes/<?php echo $row_rsfotos1['foto']; ?>', title : '', thumb : '../imagenes/<?php echo $row_rsfotos1['foto']; ?>', url : ''},<?php } while ($row_rsfotos1 = mysql_fetch_assoc($rsfotos1)); ?>
											
												],
												
					// Theme Options			   
					progress_bar			:	1,			// Timer for each slide							
					mouse_scrub				:	0
					
				});
		    });
		    
		</script>
		
	</head>
	
	<style type="text/css">
	@import url(http://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700);
		ul#demo-block{ margin:0 15px 15px 15px; }
			ul#demo-block li{ margin:0 0 10px 0; padding:10px; display:inline; float:left; clear:both; color:#aaa; background:url('img/bg-black.png'); font:11px Helvetica, Arial, sans-serif;  }
			ul#demo-block li a{ color:#eee; font-weight:bold; }
			.botoneragaleria a{
			text-decoration:none;
			 height:19px;
			font-size:17px;
			color:#FFFFFF;
			font-family: 'Raleway', sans-serif;
			float:left;
			text-align:center;
			margin:10px;
			}
			.fondobotoneragaleria{
			background:url(../corte/header-naranja.jpg) repeat-x;
			width:100%;
			height:40px;
			right:0;
			position:absolute;
			z-index:-1;
			}
			.botonregresar2{
			 position:absolute; 
			 background:#FFFFFF;
	 		 right:0; 
	 		 font-family:'Raleway', sans-serif;
			 padding:5px;
			}

	</style>
<body>

	<!--Demo styles (you can delete this block)-->
	
        <div class="fondobotoneragaleria">
        
        	<div class="botoneragaleria top">
              	
                 <?php if($_SESSION['len']!=='ing' && $_SESSION['len']!=='por'){ ?> 
              	<a target="_top" href="../index.php" >Inicio</a>
              	<a target="_top" href="../hotel.php" >Hotel</a>
              	<a target="_top" href="../ofertas.php" >Ofertas</a>
              	<a target="_top" href="../contact.php" >Contacto</a>
                <div align="right">
                <a target="_top" class="botonregresar2" style="cursor:pointer; font-family:'Raleway', sans-serif; color:#000000;"  onclick="javascript:history.back()"> Regresar </a>
                </div>
                <?php }elseif($_SESSION['len']!=='es' && $_SESSION['len']!=='por'){?> 
                <a target="_top" href="../index.php">Home</a>
              	<a target="_top" href="../hotel.php">Hotel</a>
              	<a target="_top" href="../ofertas.php">Offers</a>
              	<a target="_top" href="../contact.php">Contact</a>
                <div align="right">
                <a target="_top" class="botonregresar2" style="cursor:pointer; font-family:'Raleway', sans-serif; color:#000000;"  onclick="javascript:history.back()"> Return</a></div>
                <?php }elseif($_SESSION['len']!=='ing' && $_SESSION['len']!=='es'){ ?> 
                <a target="_top" href="../index.php">iniciação</a>
              	<a target="_top" href="../hotel.php">Hotel</a>
              	<a target="_top" href="../ofertas.php">fornecimento</a>
              	<a target="_top" href="../contact.php">contato</a>
                <div align="right">
                <a target="_top" class="botonregresar2" style="cursor:pointer; font-family:'Raleway', sans-serif; color:#000000;"  onclick="javascript:history.back()"> Retorno </a></div>
                <?php } ?>
          	</div>
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

</body>
</html>
<?php
mysql_free_result($rsfotos1);
?>
