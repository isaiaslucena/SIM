<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Resultado</title>

		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-select/css/bootstrap-select.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert/dist/sweetalert.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bscheckbox/bscheckbox.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/dataclip/crawler.css');?>"/>

		<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-select/js/bootstrap-select.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sweetalert/dist/sweetalert.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js');?>"></script>
		<script src="<?php echo base_url('assets/progressbarjs/dist/progressbar.min.js');?>"></script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row panel-heading" style="background-color: #f4f4f4">
				<div class="com-xs-1 col-sm-1 col-md-1 col-lg-1">
					<a href="<?php echo base_url('pages/crawler')?>" title="Inicio">
						<img class="img-responsive" alt="Logo" src="<?php echo base_url('assets/imgs/dataclip_logo_only.png')?>">
					</a>
				</div>

				<form id="formsearch" action="<?php echo base_url('pages/crawler_result');?>" method="post" accept-charset="utf-8">
					<div class="col-xs-8 col-sm-7 col-md-7 col-lg-7">
						<div class="input-group input-group-lg" style="vertical-align: middle;">
							<input id="search_text" name="search_text" type="text" class="form-control" form="formsearch" value="<?php echo $search_text;?>" autocomplete="off">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit">
									<i class="fa fa-search fa-fw"></i>
								</button>
							</span>
						</div>
						<span class="text-muted">
							Encontrado <?php echo $search_result->response->numFound;?> resultados
							 (<?php echo $search_result->responseHeader->QTime;?> ms)
						</span>
					</div>
				</form>

				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
					<div class="btn-toolbar pull-right">
						<a href="<?php echo base_url('login/signout')?>" id="btnlogout" type="button" class="btn btn-danger pull-right" title="Sair"><i class="fa fa-sign-out"></i></a>
						<a href="<?php echo base_url('pages/crawler')?>" id="btnback" type="button" class="btn btn-default pull-right" title="Voltar"><i class="fa fa-arrow-left"></i></a>
					</div>
				</div>
			</div>

			<div id="rpagination" class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<?php
						$query = base64_encode($search_result->responseHeader->params->json);
						$jquery = json_decode($search_result->responseHeader->params->json)->query;

						$searchtime = (int)$search_result->responseHeader->QTime;
						if (isset($search_result->responseHeader->params->rows)) {
							$rrows = (int)$search_result->responseHeader->params->rows;
						} else {
							$rrows = 10;
						}
						$totalfound = (int)$search_result->response->numFound;
						$totalpages = ceil($totalfound/$rrows);
						$firstpage = (int)$search_result->response->start;
						if ($totalpages >= 4 ) {
							$pageselectedend = $pageselected + 3;
						} else {
							$pageselectedend = $pageselected;
						}
					if ($totalfound > $rrows) { ?>
						<ul class="pagination">
							<?php if ($firstpage == 0) { ?>
								<li class="disabled">
									<a aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
								<?php } else {
									$totalpagesstart = $firstpage / $rrows;
									$startff = $firstpage;
									$startf = $startff - $rrows; ?>
								<li>
									<a href="<?php echo base_url('pages/crawler_result/'.$totalpagesstart.'/'.$query.'/'.$startf.'/'.$rrows)?>" aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
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
									$startf = $startff - $rrows;
									 if ($pageselected == $page) { ?>
										<li class="active">
											<a><?php echo $page; ?></a>
									<?php } else { ?>
										<li>
											<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf.'/'.$rrows)?>"><?php echo $page; ?></a>
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
										$startf = $startff - $rrows;
										if ($pageselected == $page) { ?>
											<li class="active">
												<a><?php echo $page; ?></a>
										<?php } else { ?>
											<li>
												<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf.'/'.$rrows)?>"><?php echo $page; ?></a>
										<?php }
									} ?>
										</li>
								<?php }
							} ?>

							<?php if ($pageselected == $totalpages) { ?>
									<li class="active">
										<a><?php echo $totalpages?><span class="sr-only"></span></a>
							<?php } else {
									$totalpagesend = ($totalpages * $rrows) - $rrows; ?>
									<li class="disabled">
										<a>...<span class="sr-only"></span></a>
									</li>
									<li>
										<a href="<?php echo base_url('pages/crawler_result/'.$totalpages.'/'.$query.'/'.$totalpagesend.'/'.$rrows)?>"><?php echo $totalpages?><span class="sr-only"></span></a>
							<?php } ?>
								</li>

							<?php if ($pageselected == $totalpages) { ?>
								<li class="disabled">
									<a aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
									<?php } else {
									$page = $pageselected + 1;
									$startff = $firstpage;
									$startf = $startff + $rrows; ?>
								<li>
								<a href="<?php echo base_url('pages/crawler_result/'.$page.'/'.$query.'/'.$startf.'/'.$rrows)?>" aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
							<?php } ?>
							</li>
						</ul>
					<?php } ?>
				</div>
			</div>

			<div id="rresult" class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<?php
						// var_dump($search_result);
						foreach ($search_result->response->docs as $sresult) { ?>
							<div class="well" style="width: 50%">
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
								<div id="<?php echo 'f_'.$sresult->id;?>" class="fcontent text-justify" data-docid="<?php echo $sresult->id;?>" style="overflow-x: hidden; overflow-y: auto; max-height: 80px">
									<?php echo strip_tags($sresult->content_t[0], '<br><p>');?>
									<!-- <?php //echo $sresult->content_t[0];?> -->
								</div>
							</div>
						<?php }
					?>
				</div>
			</div>

		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				jQuery.fn.scrollTo = function(elem) {
					$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
					return this;
				}

				window.mobilecheck = function() {
					var check = false;
					(function(a){
						if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;
					})(navigator.userAgent||navigator.vendor||window.opera);
					return check;
				};

				if (mobilecheck()) {
					console.log('Mobile Agent!');
					// url = "<?php echo base_url('login/mobile_index')?>";
					// $(location).attr("href", url);
					$('.well').css('width', '100%');
					$('img.img-responsive').css('display', 'none');
				} else {
					console.log('Desktop Agent!');
				}

				// $('#searchtext').keyup(function(event) {
				// 	var stext = $(this).val();
				// 	$('#search_text').val(stext);
				// });

				var searchedtext = $('#search_text').val();

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
					$(this).parent().animate({'width': '100%'}, 400);
					$(this).animate({
						'overflow-y': 'auto',
						'max-height': '600px'
					}, 400,
					function() {
						/* stuff to do after animation is complete */
					});
				});

				$('.fcontent.text-justify').hover(function() {
					// $(this).scrollTo('.stext');
				}, function() {
					var ctextid = $(this).attr('id');
					$(this).parent().animate({'width': '50%'}, 400);
					$(this).animate({
						'max-height': '80px'
					}, 400,
					function() {
						$(this).scrollTo('.stext');
						$(this).css({'overflow-y': 'hidden'});
					});
				});

				var pagclone = $('#rpagination').clone(true);
				$('#rresult').after(pagclone);
			});
		</script>
	</body>
</html>
