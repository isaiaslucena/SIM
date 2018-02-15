<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Resultado</title>
		<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-select/js/bootstrap-select.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sweetalert/dist/sweetalert.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js');?>"></script>
		<script src="<?php echo base_url('assets/progressbarjs/dist/progressbar.min.js');?>"></script>

		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-select/css/bootstrap-select.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert/dist/sweetalert.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bscheckbox/bscheckbox.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>

		<link rel="stylesheet" href="<?php echo base_url('assets/dataclip/crawler.css');?>"/>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row panel-heading" style="background-color: #f4f4f4">
				<div class="col-sm-1 col-md-1 col-lg-1">
					<a href="<?php echo base_url('pages/crawler')?>" title="Inicio">
						<img class="img-responsive" alt="Logo" src="<?php echo base_url('assets/imgs/dataclip_logo_only.png')?>">
					</a>
				</div>
				<div class="col-sm-7 col-md-7 col-lg-7">
					<div class="input-group input-group-lg" style="vertical-align: middle;">
						<form id="formsearch" action="<?php echo base_url('pages/crawler_result');?>" method="post" accept-charset="utf-8" style="display: none;">
							<input id="search_text" name="search_text" type="text" class="form-control" value="<?php echo $search_text;?>">
						</form>
						<input id="searchtext" type="text" class="form-control" form="formsearch" value="<?php echo $search_text;?>" autocomplete="off">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit" form="formsearch">
								<i class="fa fa-search fa-fw"></i>
							</button>
						</span>
					</div>
					<span class="text-muted">
						Encontrado <?php echo $search_result->response->numFound;?> resultados
						 (<?php echo $search_result->responseHeader->QTime;?> ms)
					</span>
				</div>
				<div class="col-sm-4 col-md-4 col-lg-4">
					<div class="btn-toolbar pull-right">
						<a href="<?php echo base_url('login/signout')?>" id="btnlogout" type="button" class="btn btn-danger pull-right" title="Sair"><i class="fa fa-sign-out"></i></a>
						<a href="<?php echo base_url('pages/index_print')?>" id="btnback" type="button" class="btn btn-default pull-right" title="Voltar"><i class="fa fa-arrow-left"></i></a>
					</div>
				</div>
			</div>

			<div class="row center-block text-center">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<?php
						$query = base64_encode($search_result->responseHeader->params->json);
						$jquery = json_decode($search_result->responseHeader->params->json)->query;

						$searchtime = (int)$search_result->responseHeader->QTime;
						$totalfound = (int)$search_result->response->numFound;
						$totalpages = ceil($totalfound/10);
						$firstpage = (int)$search_result->response->start;
						if ($totalpages >= 4 ) {
							$pageselectedend = $pageselected + 3;
						} else {
							$pageselectedend = $pageselected;
						}
					if ($totalfound > 10) { ?>
						<ul class="pagination">
							<?php if ($firstpage == 0) { ?>
								<li class="disabled">
									<a aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
								<?php } else {
									$totalpagesstart = $firstpage / 10;
									$startff = $firstpage;
									$startf = $startff - 10; ?>
								<li>
									<a href="<?php echo base_url('pages/crawler_result/'.$totalpagesstart.'/'.$query.'/'.$startf)?>" aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
							<?php } ?>
								</li>

							<?php if ($pageselected == $totalpages) { ?>
								<li>
									<a href="<?php echo base_url('pages/search_result/radio/1/'.$query.'/0')?>">1</a>
								</li>
								<li class="disabled">
									<a>...<span class="sr-only"></span></a>
								</li>
								<?php
								$pageselectedend = $pageselected - 1;
								$pageselectstart = $pageselected - 3;
								$startf = $firstpage;
								for ($page=$pageselectstart; $page <= $pageselectedend ; $page++) {
									$startff = $page.'0';
									$startf = $startff - 10;
									 if ($pageselected == $page) { ?>
										<li class="active">
											<a><?php echo $page; ?></a>
									<?php } else { ?>
										<li>
											<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf)?>"><?php echo $page; ?></a>
									<?php } ?>
										</li>
								<?php }
							} else {
								$startf = $firstpage;
								for ($page=$pageselected; $page <= $pageselectedend ; $page++) {
									if ($page == $totalpages) { ?>
										<li class="active">
										<?php break 1;
									} else {
										$startff = $page.'0';
										$startf = $startff - 10;
										if ($pageselected == $page) { ?>
											<li class="active">
												<a><?php echo $page; ?></a>
										<?php } else { ?>
											<li>
												<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf)?>"><?php echo $page; ?></a>
										<?php }
									} ?>
										</li>
								<?php }
							} ?>

							<?php if ($pageselected == $totalpages) { ?>
									<li class="active">
										<a><?php echo $totalpages?><span class="sr-only"></span></a>
							<?php } else {
									$totalpagesend = ($totalpages * 10) - 10; ?>
									<li class="disabled">
										<a>...<span class="sr-only"></span></a>
									</li>
									<li>
										<a href="<?php echo base_url('pages/crawler_result/'.$totalpages.'/'.$query.'/'.$totalpagesend)?>"><?php echo $totalpages?><span class="sr-only"></span></a>
							<?php } ?>
								</li>

							<?php if ($pageselected == $totalpages) { ?>
								<li class="disabled">
									<a aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
									<?php } else {
									$page = $pageselected + 1;
									$startff = $firstpage;
									$startf = $startff + 10; ?>
								<li>
								<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf)?>" aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
							<?php } ?>
							</li>
						</ul>
					<?php } ?>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<?php
						// var_dump($search_result);
						foreach ($search_result->response->docs as $sresult) { ?>
							<div class="well center-block" style="width: 50%">
								<span class="pull-left label label-default">
									Publicado: <?php echo str_replace('T', ' ', str_replace('Z', '', $sresult->published_dt));?>
								</span>
								<span class="pull-right label label-default">
									Inserido: <?php echo str_replace('T', ' ', str_replace('Z', '', $sresult->inserted_dt));?>
								</span>
								<br>
								<h4 class="ntitle text-primary" data-docid="<?php echo $sresult->id;?>">
									<?php echo $sresult->title_t[0];?>
								</h4>
								<p>
								<a class="text-info" href="<?php echo $sresult->url_s;?>" target="_blank">
									<?php echo $sresult->url_s;?>
								</a>
								</p>
								<div id="<?php echo 'f_'.$sresult->id;?>" class="fcontent text-justify" data-docid="<?php echo $sresult->id;?>" style="overflow-y: auto; max-height: 50px">
									<?php echo strip_tags($sresult->content_t[0], '<br><p>');?>
									<!-- <?php //echo $sresult->content_t[0];?> -->
								</div>
							</div>
						<?php }
					?>
				</div>
			</div>
			
			<div class="row center-block text-center">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<?php
						$query = base64_encode($search_result->responseHeader->params->json);
						$jquery = json_decode($search_result->responseHeader->params->json)->query;

						$searchtime = (int)$search_result->responseHeader->QTime;
						$totalfound = (int)$search_result->response->numFound;
						$totalpages = ceil($totalfound/10);
						$firstpage = (int)$search_result->response->start;
						if ($totalpages >= 4 ) {
							$pageselectedend = $pageselected + 3;
						} else {
							$pageselectedend = $pageselected;
						}
					if ($totalfound > 10) { ?>
						<ul class="pagination pull-right">
							<?php if ($firstpage == 0) { ?>
								<li class="disabled">
									<a aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
								<?php } else {
									$totalpagesstart = $firstpage / 10;
									$startff = $firstpage;
									$startf = $startff - 10; ?>
								<li>
									<a href="<?php echo base_url('pages/crawler_result/'.$totalpagesstart.'/'.$query.'/'.$startf)?>" aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
							<?php } ?>
								</li>

							<?php if ($pageselected == $totalpages) { ?>
								<li>
									<a href="<?php echo base_url('pages/search_result/radio/1/'.$query.'/0')?>">1</a>
								</li>
								<li class="disabled">
									<a>...<span class="sr-only"></span></a>
								</li>
								<?php
								$pageselectedend = $pageselected - 1;
								$pageselectstart = $pageselected - 3;
								$startf = $firstpage;
								for ($page=$pageselectstart; $page <= $pageselectedend ; $page++) {
									$startff = $page.'0';
									$startf = $startff - 10;
									 if ($pageselected == $page) { ?>
										<li class="active">
											<a><?php echo $page; ?></a>
									<?php } else { ?>
										<li>
											<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf)?>"><?php echo $page; ?></a>
									<?php } ?>
										</li>
								<?php }
							} else {
								$startf = $firstpage;
								for ($page=$pageselected; $page <= $pageselectedend ; $page++) {
									if ($page == $totalpages) { ?>
										<li class="active">
										<?php break 1;
									} else {
										$startff = $page.'0';
										$startf = $startff - 10;
										if ($pageselected == $page) { ?>
											<li class="active">
												<a><?php echo $page; ?></a>
										<?php } else { ?>
											<li>
												<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf)?>"><?php echo $page; ?></a>
										<?php }
									} ?>
										</li>
								<?php }
							} ?>

							<?php if ($pageselected == $totalpages) { ?>
									<li class="active">
										<a><?php echo $totalpages?><span class="sr-only"></span></a>
							<?php } else {
									$totalpagesend = ($totalpages * 10) - 10; ?>
									<li class="disabled">
										<a>...<span class="sr-only"></span></a>
									</li>
									<li>
										<a href="<?php echo base_url('pages/crawler_result/'.$totalpages.'/'.$query.'/'.$totalpagesend)?>"><?php echo $totalpages?><span class="sr-only"></span></a>
							<?php } ?>
								</li>

							<?php if ($pageselected == $totalpages) { ?>
								<li class="disabled">
									<a aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
									<?php } else {
									$page = $pageselected + 1;
									$startff = $firstpage;
									$startf = $startff + 10; ?>
								<li>
								<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf)?>" aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
							<?php } ?>
							</li>
						</ul>
					<?php } ?>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				jQuery.fn.scrollTo = function(elem) {
					$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
					return this;
				}

				$('#searchtext').keyup(function(event) {
					var stext = $(this).val();
					$('#search_text').val(stext);
				});

				var searchedtext = $('#searchtext').val();

				ptitles = $('.ntitle.text-primary');
				$.each(ptitles, function(index, val) {
					cpid = $(val).attr('id');
					scpid = '#'+cpid;
					qfound = '#'+cpid+' > .stext';

					ptitletext = $(val).text();
					// rgx = new RegExp ('\\b'+searchedtext+'\\b', 'ig');
					rgx = new RegExp (searchedtext, 'ig');
					pbodynewtext = ptitletext.replace(rgx, '<strong class="stext">'+searchedtext+'</strong>');
					$(scpid).html(null);
					$(scpid).html(pbodynewtext);
				});

				ptexts = $('.fcontent.text-justify');
				$.each(ptexts, function(index, val) {
					// console.log(event);
					cpid = $(val).attr('id');
					scpid = '#'+cpid;
					qfound = '#'+cpid+' > .stext';
					// idkeyword = event.target.dataset.idkeyword;
					// idpbodyt = event.target.dataset.pbodyt;

					pbodytext = $(val).text();
					// rgx = new RegExp ('\\b'+searchedtext+'\\b', 'ig');
					rgx = new RegExp (searchedtext, 'ig');
					pbodynewtext = pbodytext.replace(rgx, '<strong class="stext">'+searchedtext+'</strong>');
					$(scpid).html(null);
					$(scpid).html(pbodynewtext);

					qtkwf = $(qfound).length;
					// $(val)[0].parentElement.parentElement.parentElement.parentElement.children[0].children[1].children[1].innerText = qtkwf;
					$(val).scrollTo(qfound+':first-child');
					$(val).css('overflowY', 'hidden');
				});
				
				$('.fcontent.text-justify').click(function() {
					$(this).parent().animate({'width': '100%'}, 500);
					$(this).animate({
						'overflow-y': 'auto',
						'max-height': '600px'
					}, 500,
					function() {
						/* stuff to do after animation is complete */
					});
				});
				
				$('.fcontent.text-justify').hover(function() {
					/*do nothing*/
				}, function() {
					var ctextid = $(this).attr('id');
					$(this).parent().animate({'width': '50%'}, 500);
					$(this).animate({
						'max-height': '50px'
					}, 500,
					function() {
						$(this).scrollTo('.stext');
						$(this).css({'overflow-y': 'hidden'});
					});
				});
			});
		</script>
	</body>
</html>
