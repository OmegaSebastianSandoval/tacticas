<?php
// Require the MXI classes
require_once ('includes/mxi/MXI.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>

<body onload="MM_preloadImages('../corte/botones2/inicio-2.png','../corte/botones2/galeria-vid-2.png','../corte/botones2/conoce-2.png','../corte/botones2/juego-2.png','../corte/botones2/galeria-ima-2.png')">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="botonera" >
  <tr>
    <td bgcolor="#000000"><div align="center"><table width="1024" border="0" align="center" cellpadding="0" cellspacing="0" style="display: inline-table;">
      <tr>
        <td><img name="index_r2_c1" src="../corte/img/index_r2_c1.png" width="315" height="52" id="index_r2_c1" alt="" /></td>
        <td><a href="index.php"><img src="../corte/img/index_r2_c2.png" alt="" name="index_r2_c2" width="76" height="52" border="0" id="index_r2_c" onmouseover="MM_swapImage('index_r2_c2','','../corte/botones2/inicio-2.png',1)" onmouseout="MM_swapImgRestore()" /></a></td>
        <td><img name="index_r2_c3" src="../corte/img/index_r2_c3.png" width="10" height="52" id="index_r2_c3" alt="" /></td>
        <td>
          <a href="galeria.php"><img src="../corte/img/index_r2_c4.png" alt="" name="galeri" width="135" height="52" border="0" id="galeri" onmouseover="MM_swapImage('galeri','','../corte/botones2/galeria-ima-2.png',1)" onmouseout="MM_swapImgRestore()" /></a>        </td>
        <td><img name="index_r2_c5" src="../corte/img/index_r2_c5.png" width="8" height="52" id="index_r2_c5" alt="" /></td>
        <td><a href="galeriaVideos.php"><img src="../corte/img/index_r2_c6.png" alt="" name="index_r2_c6" width="119" height="52" border="0" id="index_r2_c3" onmouseover="MM_swapImage('index_r2_c6','','../corte/botones2/galeria-vid-2.png',1)" onmouseout="MM_swapImgRestore()" /></a></td>
        <td><img name="index_r2_c7" src="../corte/img/index_r2_c7.png" width="11" height="52" id="index_r2_c4" alt="" /></td>
        <td><a href="conoceEldorado.php"><img src="../corte/img/index_r2_c8.png" alt="" name="index_r2_c8" width="143" height="52" border="0" id="index_r2_c5" onmouseover="MM_swapImage('index_r2_c8','','../corte/botones2/conoce-2.png',1)" onmouseout="MM_swapImgRestore()" /></a></td>
        <td><img name="index_r2_c9" src="../corte/img/index_r2_c9.png" width="9" height="52" id="index_r2_c11" alt="" /></td>
        <td><a href="http://201.245.85.180/puzzle/src/login.php" target="_blank"><img src="../corte/img/index_r2_c10.png" alt="" name="index_r2_c10" width="63" height="52" border="0" id="index_r2_c13" onmouseover="MM_swapImage('index_r2_c10','','../corte/botones2/juego-2.png',1)" onmouseout="MM_swapImgRestore()" /></a></td>
        <td><?php
  mxi_includes_start("redesSuperior.php");
  require(basename("redesSuperior.php"));
  mxi_includes_end();
?></td>
      </tr>
    </table></div></td>
  </tr>
</table>

</body>
</html>
