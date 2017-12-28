<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	<div class="row page-header">
		<div class="col-lg-12">
			<div class="col-lg-4">
				<h1><?php echo get_phrase('print');?></h1>
			</div>
			<div class="col-lg-5">
			</div>
			<div class="col-lg-3" >
				<small><span class="pull-right text-muted pageload"></span></small>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
				<?php
				//var_dump($print_newspapers);
				$npapercount = 0;
				$invert = 0; ?>
				<ul class="timeline" id="newspaper-ul">
				<?php foreach ($print_newspapers as $newspaper) {
					$npidveiculo = $newspaper->IdVeiculo;
					$npveiculo = $newspaper->Veiculo;
					$npapercount++; ?>
						<?php if ($invert % 2 == 0) { ?>
							<li id="<?php echo 'li-'.$npapercount; ?>">
						<?php } else { ?>
							<li class="timeline-inverted" id="<?php echo 'li-'.$npapercount; ?>">
						<?php } ?>
								<div class="timeline-badge"><i class="fa fa-newspaper-o"></i></div>
								<div class="timeline-panel">
									<div class="timeline-heading">
										<h4 class="timeline-title">
											<a href="<?php echo 'http://www.multclipp.com.br/admin/cadastroVeiculo.aspx?id='.$npidveiculo; ?>" title="Editar veículo no Multclipp" target="_blank">
												<?php echo $npveiculo; ?>
											</a>
										</h4>
									</div>
									<div class="timeline-body">
										<?php
										foreach ($newspaper->Empresas as $empresa) {
											$eidemp = $empresa->IdEmpresa;
											$eemp = $empresa->Empresa; ?>
											<form style="all: unset;" action="<?php echo base_url('pages/home_print_clientnews') ?>" method="post" accept-charset="utf-8">
												<input type="hidden" name="jsonres" value="false">
												<input type="hidden" name="idnpaper" value="<?php echo $npidveiculo; ?>">
												<input type="hidden" name="idclient" value="<?php echo $eidemp; ?>">
												<input type="hidden" name="offset" value="0">
												<button type="submit" class="btn btn-info btn-sm btnclient">
													<?php $qtnews = count($empresa->Noticias); ?>
													<span class="badge"><?php echo $qtnews ;?> </span>
													<?php echo $eemp; ?>
												</button>
											</form>
										<?php } ?>
									</div>
								</div>
							</li>
						<?php $invert++;
				} ?>
				</ul>

			<span class="text-muted center-block text-center" id="loadmore" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i> Carregando...
			</span>
			<button id="btnloadmore" type="button" class="btn btn-primary btn-sm center-block" style="display: none;">Carregar mais</button>
		</div>
	</div>

	<?php
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $start), 4);
		//echo 'Page generated in '.$total_time.' seconds.';
	?>

	<script type="text/javascript">
		$('#searchclient').on('keyup click input', function (event) {
			if (this.value.length > 0) {
				$('#newspaper-ul > li').hide().filter(function (element) {
					return $(this).text().toLowerCase().indexOf($('#searchclient').val().toLowerCase()) != -1;
				}).show();
			} else {
				$('#newspaper-ul > li').show();
				$('.btnclient').show();
			}
		});

		if ($('#back-to-top').length) {
			var scrollTrigger = 1500, // px
			backToTop = function () {
				var scrollTop = $(window).scrollTop();
				if (scrollTop > scrollTrigger) {
					$('#back-to-top').addClass('show')
				} else {
					$('#back-to-top').removeClass('show')
				}
			}
			backToTop();
			$(window).on('scroll', function () {
				backToTop();
			})
			$('#back-to-top').on('click', function (e) {
				e.preventDefault();
				$('html,body').animate({scrollTop: 0}, 700)
			})
		};
	</script>