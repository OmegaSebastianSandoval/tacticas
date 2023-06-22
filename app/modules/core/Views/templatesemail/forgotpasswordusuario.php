<div style="padding: 40px; text-align: center; background: #333333;">
	<div style="display: inline-block; width:700px;">
		<table border="0" cellpadding="10" cellspacing="0" width="100%">
			<thead>
				<tr bgcolor="#FFFFFF">
					<td><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/skins/page/images/logotacticas.png" alt="Logo tacticas Panamá" height="100";></td>
					<td><h2 style="color:#19A9C9;">Recuperación de contraseña</h2></td>
				</tr>
			</thead>
			<tr bgcolor="#19A9C9">
				<td colspan="2" style="color: #FFFFFF; text-align: left;">
					<div><h2>Hola, <?php echo $this->nombre; ?></h2></div>
					<div>Usted recibió este correo debido a que olvidó su usuario o contraseña.</div><br />
					<div>Su usuario es: <?php echo $this->usuario; ?> </div><br />
					<div>Haga clic en este link para cambiar su contraseña :</div><br/>
					<div align="center"><a href="<?php echo $this->url; ?>" target="_blank" style="padding:10px; display:block; color:#19A9C9; background:#FFF; text-transform:uppercase; text-decoration:none; font-weight: bold;" >Restablecer Contrase&ntilde;a</a></div> 
				</td>
			</tr>
		</table>
	</div>
</div>


