
<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="view/dist/img/avatar5.png" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p> <?=$_SESSION['name']?> </p>
				<a href="#"><i class="fa fa-circle text-success"></i> En servicio</a>
			</div>
		</div>
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">PERFIL: <?=mb_strtoupper($_SESSION['perfil'],'utf-8')?></li>
			<?php if ( $_SESSION['perfil'] == 'QDP' || $_SESSION['perfil'] == 'QDNP' ): ?>
				<li id="option_1" class=" treeview">
					<a href="#">
						<i class="fa fa-dashboard"></i> <span>Quejas y Denuncias</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<?php if ( $_SESSION['nivel'] == 'ANALISTA'|| $_SESSION['nivel'] == 'JEFE' ): ?>
						<ul class="treeview-menu">
							<li id="option_1_2"><a href="index.php?menu=list_queja"><i class="fa fa-circle-o"></i> Listado </a></li>
						</ul>
					<?php else: ?>
						<ul class="treeview-menu">
							<li id="option_1_1" class=""><a href="index.php?menu=general"><i class="fa fa-circle-o"></i> Alta  </a></li>
							<li id="option_1_2"><a href="index.php?menu=list_queja"><i class="fa fa-circle-o"></i> Listado </a></li>
						</ul>
					<?php endif ?>
					
				</li>
				<?php if ( $_SESSION['nivel'] != 'ANALISTA' ): ?>
					<li id="option_2" class="">
						<a href="index.php?menu=reports">
							<i class="fa fa-file-excel-o"></i> <span>Reportes</span>
						</a>
					</li>
				<?php endif ?>
				
				<?php if ($_SESSION['nivel'] == 'SUBDIRECTOR'): ?>
					<li id="option_5" class="">
						<a href="index.php?menu=abogados">
							<i class="fa fa-user-secret"></i> <span>Abogados</span>
						</a>
					</li>
					<li id="option_4" class="">
						<a href="index.php?menu=devoluciones">
							<i class="fa  fa-exchange"></i> <span>Devoluciones</span>
						</a>
					</li>
					<li id="option_6" class="">
						<a href="index.php?menu=tablero">
							<i class="fa fa-table"></i> <span>Tablero de Ctrl.</span>
						</a>
					</li>
					<li id="option_7" class="">
						<a href="index.php?menu=migracion">
							<i class="fa fa-refresh"></i> <span>Transferencia multiple.</span>
						</a>
					</li>
				<?php endif ?>
			<?php elseif ( $_SESSION['perfil'] == 'DI'): ?>
				<?php if ($_SESSION['nivel'] == 'DIRECTOR'): ?>
				<li id="option_1" class="">
					<a href="index.php?menu=general">
						<i class="fa fa-table"></i> <span>Tablero de Ctrl.</span>
					</a>
				</li>	
				<li id="option_2" class="">
					<a href="index.php?menu=abogados">
						<i class="fa fa-user-secret"></i> <span>Abogados</span>
					</a>
				</li>
				<li id="option_7" class="">
					<a href="index.php?menu=migracion">
						<i class="fa fa-refresh"></i> <span>Transferencia multiple.</span>
					</a>
				</li>
				
				<li id="option_5" class="">
					<a href="index.php?menu=aviso">
						<i class="fa fa-file-pdf-o"></i> <span>Aviso de privacidad</span>
					</a>
				</li>
				<li id="option_6" class="">
					<a href="index.php?menu=manual">
						<i class="fa fa-file-pdf-o"></i> <span>Manual de usuario</span>
					</a>
				</li>
				<?php endif ?>
			<?php elseif ($_SESSION['perfil'] == 'SIRA'): ?>
				<li id="option_1" class=" treeview">
					<a href="#">
						<i class="fa fa-dashboard"></i> <span>Registro de Actas</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li id="option_1_1" class=""><a href="index.php?menu=general"><i class="fa fa-circle-o"></i> Alta </a></li>
						<li id="option_1_2"><a href="index.php?menu=list_acta"><i class="fa fa-circle-o"></i> Listado </a></li>
					</ul>
				</li>
				<li id="option_2" class="">
					<a href="index.php?menu=ordenes">
						<i class="fa fa-edit"></i> <span>Ordenes de trab.</span>
						<span class="pull-right-container">
						</span>
					</a>
				</li>
				<li id="option_3" class="">
					<a href="index.php?menu=reports">
						<i class="fa fa-search"></i> <span>Reportes</span>
						<span class="pull-right-container">
						</span>
					</a>
				</li>
				<li id="option_4" class="">
					<a href="index.php?menu=aviso">
						<i class="fa fa-file-pdf-o"></i> <span>Aviso de privacidad</span>
					</a>
				</li>
				<li id="option_5" class="">
					<a href="index.php?menu=manual">
						<i class="fa fa-file-pdf-o"></i> <span>Manual de usuario</span>
					</a>
				</li>
			<?php elseif ($_SESSION['perfil'] == 'DR'): ?>
				<?php if ($_SESSION['nivel'] == 'DIRECTOR'): ?>
				<li id="option_1" class="">
					<a href="index.php?menu=general">
						<i class="fa fa-table"></i> <span>Tablero de Ctrl.</span>
					</a>
				</li>	
				<li id="option_2" class="">
					<a href="index.php?menu=list_exp">
						<i class="fa fa-list"></i> <span>Listado de exp.</span>
					</a>
				</li>
				<li id="option_5" class="">
					<a href="index.php?menu=aviso">
						<i class="fa fa-file-pdf-o"></i> <span>Aviso de privacidad</span>
					</a>
				</li>
				<li id="option_6" class="">
					<a href="index.php?menu=manual">
						<i class="fa fa-file-pdf-o"></i> <span>Manual de usuario</span>
					</a>
				</li>
				<?php endif ?>
				<?php if ($_SESSION['nivel'] == 'ANALISTA'): ?>
				<li id="option_1" class="">
					<a href="index.php?menu=general">
						<i class="fa fa-table"></i> <span>Listado de exp.</span>
					</a>
				</li>	
				<li id="option_5" class="">
					<a href="index.php?menu=aviso">
						<i class="fa fa-file-pdf-o"></i> <span>Aviso de privacidad</span>
					</a>
				</li>
				<li id="option_6" class="">
					<a href="index.php?menu=manual">
						<i class="fa fa-file-pdf-o"></i> <span>Manual de usuario</span>
					</a>
				</li>
				<?php endif ?>
				
			<?php elseif ($_SESSION['perfil'] == 'SAPA'): ?>
				<?php if ($_SESSION['nivel'] == 'SECRETARIA'): ?>
					<li id="option_3" class="">
						<a href="index.php?menu=acuse">
							<i class="fa fa-upload"></i> <span>Subir acuse</span>
						</a>
					</li>	
				<?php elseif ($_SESSION['nivel'] == 'ANALISTA'): ?>
					<li id="option_1" class="">
						<a href="index.php?menu=general">
							<i class="fa fa-list"></i> <span>Listado de exp.</span>
						</a>
					</li>
				<?php elseif ($_SESSION['nivel'] == 'SUBDIRECTOR'): ?>
					<li id="option_1" class="">
						<a href="index.php?menu=general">
							<i class="fa fa-list"></i> <span>Listado de exp.</span>
						</a>
					</li>
					<li id="option_2" class="">
						<a href="index.php?menu=estadistica">
							<i class="fa fa-line-chart"></i> <span>Estadistica </span>
						</a>
					</li>
					<li id="option_3" class="">
						<a href="index.php?menu=acuse">
							<i class="fa fa-upload"></i> <span>Subir acuse</span>
						</a>
					</li>
				<?php endif ?>
			<?php elseif ($_SESSION['perfil'] == 'SC'): ?>
				<li id="option_1" class="">
					<a href="index.php?menu=general">
						<i class="fa fa-list"></i> <span>Listado de exp.</span>
					</a>
				</li>
				<li id="option_2" class="">
					<a href="index.php?menu=reportes">
						<i class="fa fa-line-chart"></i> <span>Reportes</span>
					</a>
				</li>
			<?php elseif ($_SESSION['perfil'] == 'TITULAR'): ?>
				<li id="option_1" class="">
					<a href="index.php?menu=general">
						<i class="fa fa-list"></i> <span>Listado de exp.</span>
					</a>
				</li>
			<?php endif ?>
		</ul>
	</section>
</aside>