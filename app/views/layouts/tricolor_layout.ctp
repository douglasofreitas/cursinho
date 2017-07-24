<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('CPV: Curso Pré-Vestibular da UFSCar'); ?>
		<?php //echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->meta('icon');
		echo $html->css('tricolor');

		//echo '<script type="text/javascript" src="' . $html->url('/js/Visifire2.js') .'"></script>';
		//echo '<script type="text/javascript" src="' . $html->url('/js/visifire_chart.js') .'"></script>';
		//echo '<script type="text/javascript" src="' . $html->url('/js/jquery-1.3.2.js') . '"></script>';
		echo '<script type="text/javascript" src="' . $html->url('/js/jquery-1.5.1.min.js') . '"></script>';
        echo '<script type="text/javascript" src="' . $html->url('/js/jquery-validate.js') . '"></script>';
		echo '<script type="text/javascript" src="' . $html->url('/js/tooltips/jquery.poshytip.js') .'"></script>';
		echo '<script type="text/javascript" src="' . $html->url('/js/tooltips/tooltip.js') .'"></script>';
                echo '<script type="text/javascript" src="' . $html->url('/js/popupMsg.js') .'"></script>';
                echo '<script type="text/javascript" src="' . $html->url('/js/popupMsg-editable.js') .'"></script>';
	?>
	<!-- Tooltip classes -->
    <link rel="stylesheet" href="<?php echo $html->url('/js/tooltips/tip-twitter/tip-twitter.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo $html->url('/js/tooltips/tip-twitter/tip-alert.css') ?>" type="text/css" />

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-41595997-1', 'ufscar.br');
        ga('send', 'pageview');

    </script>

</head>
<body>

	<div id="wrapper">
		<div id="header">
			<div id="logo">
				<h1><a href="#"><span class="style1">Curso Pré Vestibular - UFSCar </span></a></h1>
			</div>

			<?php if($login_group == null): ?>

				<!-- Barra de Login-->
				<div id="search">

					<?php
						echo $form->create('User', array('url' => array('controller' => 'users', 'action' =>'login')) );
					?>
						<fieldset>
							Usuário<input type="text" name="data[User][username]" id="PessoaLogin" size="12" /> 
							Senha<input type="password" name="data[User][password]" id="PessoaSenha" size="12" />
							<input type="submit" id="botaoLogin" value="Login" />
						</fieldset>
					</form>
				</div>

			<?php else: ?>
				<!-- Barra de Logoff-->
				<div id="search">
					<?php
						echo $form->create('User', array('url' => array('controller' => 'users', 'action' =>'logout')) );
					?>
						<fieldset>
						Bem Vindo Sr(a). <?php echo $login_nome;?>
						<input type="submit" id="botaoLogout" value="Logout" />
						</fieldset>
					</form>
				</div>
			<?php endif; ?>

		</div>

		<div id="menu">

		<?php if ($login_group == ''): ?>

			<ul>
				<li><?php //echo $html->link('Mapa do Site', '/pages/mapa_site') ?></li>
			</ul>

		<?php else: ?>

			<?php if ($login_group == 'coordenador'): ?>

				<ul>
					<li <?php if($moduloAtual == 'candidatos') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Candidatos', '/candidatos') ?></li>
					<li><?php echo $html->link('Estudantes', '/estudantes') ?></li>
                                        <li <?php if($moduloAtual == 'coordenadors') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Gestão', '/coordenadors') ?></li>
				</ul>
			<?php else: ?>

				<?php if ($login_group == 'comissao'): ?>

					<ul>
						<li <?php if($moduloAtual == 'candidatos') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Candidatos', '/candidatos') ?></li>
						<li <?php if($moduloAtual == 'estudantes') echo 'style="text-decoration: underline"'; ?> ><?php echo $html->link('Estudantes', '/estudantes') ?></li>
						<li <?php if($moduloAtual == 'comissaos') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Comissao', '/comissaos') ?></li>
						<li <?php if($moduloAtual == 'docentes') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Docente', '/docentes') ?></li>
						<li><?php echo $html->link('Ajuda', '/pages/mapa_site') ?></li>
					</ul>
				<?php else: ?>

					<?php if ($login_group == 'docente'): ?>

						<ul>
							<li <?php if($moduloAtual == 'candidatos') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Candidatos', '/candidatos') ?></li>
							<li <?php if($moduloAtual == 'estudantes') echo 'style="text-decoration: underline"'; ?> ><?php echo $html->link('Estudantes', '/estudantes') ?></li>
							<li <?php if($moduloAtual == 'docentes') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Docente', '/docentes') ?></li>
							<li><?php echo $html->link('Ajuda', '/pages/mapa_site') ?></li>
						</ul>
					<?php else: ?>

						<?php if ($login_group == 'funcionario'): ?>

							<ul>
								<li <?php if($moduloAtual == 'candidatos') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Candidatos', '/candidatos') ?></li>
								<li <?php if($moduloAtual == 'estudantes') echo 'style="text-decoration: underline"'; ?> ><?php echo $html->link('Estudantes', '/estudantes') ?></li>
									<li><?php echo $html->link('Ajuda', '/pages/mapa_site') ?></li>
							</ul>

						<?php else: //aqui é para o teste do sistema pelo CPV - TESTE - precisa ser apagado depois?>

                            <?php if ($login_group == 'estudante'): ?>

                                <ul>
                                    <li <?php if($moduloAtual == 'candidatos') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Candidatos', '/candidatos') ?></li>
                                </ul>

                            <?php else: //aqui é para o teste do sistema pelo CPV - TESTE - precisa ser apagado depois?>

                                <ul>
                                    <li <?php if($moduloAtual == 'candidatos') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Candidatos', '/candidatos') ?></li>
                                    <li <?php if($moduloAtual == 'estudantes') echo 'style="text-decoration: underline"'; ?> ><?php echo $html->link('Estudantes', '/#') ?></li>
                                    <li <?php if($moduloAtual == 'comissaos') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Comissao', '/#') ?></li>
                                    <li <?php if($moduloAtual == 'docentes') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Docente', '/#') ?></li>
                                    <li <?php if($moduloAtual == 'coordenadors') echo 'style="text-decoration: underline"'; ?>><?php echo $html->link('Gestão', '/coordenadors') ?></li>
                                    <li><?php echo $html->link('Ajuda', '/#') ?></li>
                                </ul>

                            <?php endif; ?>

						<?php endif; ?>

					<?php endif; ?>

				<?php endif; ?>

			<?php endif; ?>

		<?php endif; ?>

		</div>

		<div id="page">
		<div id="page-bgtop">
		<div id="page-bgbtm">

		<table width="100%">
			<tr>
				<td width="160px" valign="top">
					<?php echo $this->renderElement('side_bar', array('modulo' => $title_for_layout)); ?>
				</td>
				<td width="auto" valign="top">
					<div id="content">
						<div class="post">
							<h2 class="title"><?php echo $this->getVar('content_title'); ?></h2>

							<div class="entry">
								<?php $session->flash(); ?>
								<?php if(!empty($_SESSION['Auth']['User']['group_id'])) if($_SESSION['Auth']['User']['group_id'] == 1) $session->flash('auth'); ?>

								<?php echo $content_for_layout; ?>
							</div>
						</div>

						<div style="clear: both;">&nbsp;</div>
					</div>

				</td>
			</tr>
		</table>

			<div style="clear: both;">&nbsp;</div>

		</div>
		</div>
		</div>

		<div id="footer">
			<p style="text-align: right;margin-right: 12px;"> By <a href="http://www.grupodf.com/">GRUPO DF</a></p>
		</div>
	</div>

<!-- ini popupMsg -->
<div id="popupMsg">
    <a id="popupMsgClose">X</a>
    <h1 id="popupMsgTitulo"></h1>
    <div id="popupMsgCorpo" style="color:#000; "></div>
    <div style="height:20px;"></div>
</div>
<div id="backgroundPopup"></div> 
<!-- fim popupMsg -->

</body>
</html>