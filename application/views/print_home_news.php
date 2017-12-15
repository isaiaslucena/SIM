<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<body>
	<script type="text/javascript" src="<?php echo base_url('assets/jcrop/js/jquery.Jcrop.min.js')?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('assets/jcrop/css/jquery.Jcrop.min.css')?>">

	<button href="#" id="back-to-top" class="btn btn-danger btn-circle btn-lg" title="<?php echo get_phrase('back_to_top')?>"><i class="fa fa-arrow-up"></i></button>

	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<?php echo $client_print_news->Noticias[0]->Veiculo; ?>
				</h1>
				<h2>
					<?php echo $client_print_news->Noticias[0]->Empresa; ?>
					<small><?php echo ' - '.count($client_print_news->Noticias).' '.get_phrase('news'); ?></small>		
				</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<?php
				if (is_null($client_print_news)) { ?>
					<h3>Não foi possível carregar as notícias...</h3>
				<?php } else {
					$divcount = 0;
					$icount = 0;
					$imgcount = 0;
					$nnidnews = null;
					foreach ($client_print_news->Noticias as $client_news) {
						$divcount++;
						$icount++;
						$nidvtype = $client_news->IdTVeiculo;
						$nvtype = $client_news->TVeiculo;
						$nidve = $client_news->IdVeiculo;
						$nve = $client_news->Veiculo;
						$nided = $client_news->IdEditoria;
						$ned = $client_news->Editoria;
						$nidemp = $client_news->IdEmpresa;
						$nemp = $client_news->Empresa;
						$nidnews = $client_news->Id;
						$ntitle = $client_news->Titulo;
						$nnews = $client_news->Noticia;
						$ndate = $client_news->Data;
						$ntime = $client_news->Hora;
						$nurl = $client_news->NoticiaURL; ?>

						<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
							<div class="panel-heading text-center">
								<strong>
									<i class="fa fa-file-text-o fa-fw"></i> 
									<?php echo $ned." | ".$ndate." - ".$ntime; ?>
								</strong>

								<div class="btn-group pull-right">
									<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<?php
										$keyquant =  count($client_news->PChaves);
										echo $keyquant;
										if ($keyquant == 0) {
										 	echo $keyquantm = " nenhuma palavra-chave";
										} else if ($keyquant == 1) {
										 	echo $keyquantm = " palavra-chave";
										} else {
											echo $keyquantm = " palavras-chave";
										} ?> 
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu dropup pull-right scrollable-menu" role="menu">
										<?php
										asort($client_news->PChaves);
										foreach ($client_news->PChaves as $keyword) { ?>
											<li>
												<a class="likeyword" href="#" data-pbodyt="<?php echo 'pbody'.$divcount;?>" data-idkeyword="<?php echo $keyword->IdPChave; ?>"><?php echo $keyword->PChave; ?></a>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>

							<div class="panel-body">
								<div class="row">
									<div class="col-lg-4">
										<?php 
										$imgq = count($client_news->Imagens);
										// var_dump($imgq);
										if ($imgq == 1) { 
											$imgcount++;
											if (!empty($client_news->Imagens[0]->Imagem)) {
											 	$imgsrc = $client_news->Imagens[0]->Imagem;
											} else if ($client_news->Imagens[0]->Imagem != " ") {
											 	$imgsrc = $client_news->Imagens[0]->Imagem;
											} else {
												//$imgsrc = base_url("assets/imgs/noimage.png");
												if (!empty($client_news->Imagens[0]->URLImagem)) {
												 	$imgsrc = $client_news->Imagens[0]->URLImagem;
												} else if ($client_news->Imagens[0]->URLImagem != " ") {
													$imgsrc = $client_news->Imagens[0]->URLImagem;
												} else {
													if ($client_news->NoticiaURL != " " or !empty($client_news->NoticiaURL)) {
														$imgsrc = $client_news->NoticiaURL;
													} else {
														$imgsrc = base_url("assets/imgs/noimage.png");
													}
												}
											} ?>
											<a class="thumbnail" data-idnews="<?php echo $nidnews ;?>" data-imgsrc="<?php echo base_url('pages/proxy/').base64_encode($imgsrc); ?>">
												<img id="<?php echo 'thumbimg'.$imgcount?>" class="img-responsive" src="<?php echo base_url('pages/proxy/').base64_encode($imgsrc); ?>" alt="Imagem">
											</a>
										<?php } else if ($imgq == 0) { 
											$imgcount++;
											// $except = array("png", "gif", "jpg", "bmp","com");
											$imp = "s3.amazonaws.com";
											// $imp = implode('|', $except);
											// if (preg_match('/^.*\.('.$imp.')$/i', $client_news->NoticiaURL)) {
											if (preg_match('/'.$imp.'/', $client_news->NoticiaURL)) {
												$imgsrc = $client_news->NoticiaURL; ?>
												<a class="thumbnail" data-idnews="<?php echo $nidnews ;?>" data-imgsrc="<?php echo base_url('pages/proxy/').base64_encode($imgsrc); ?>">
													<img id="<?php echo 'thumbimg'.$imgcount?>" class="img-responsive" src="<?php echo base_url('pages/proxy/').base64_encode($imgsrc); ?>" alt="Imagem">
												</a>
											<?php } else { 
												$imgsrc = base_url("assets/imgs/noimage.png"); ?>
												<a class="thumbnail" data-idnews="<?php echo $nidnews ;?>" data-imgsrc="<?php echo $imgsrc; ?>">
													<img id="<?php echo 'thumbimg'.$imgcount?>" class="img-responsive" src="<?php echo $imgsrc; ?>" alt="Imagem">
												</a>
											<?php } ?>

										<?php } else { ?>
											<div class="row">
												<?php 
												asort($client_news->Imagens);
												// var_dump($client_news->Imagens);
												$newidimg = null;
												foreach ($client_news->Imagens as $img) {
													if ($newidimg != $img->IdImagem) {
														$imgcount++;
														if (!empty($img->Imagem)) {
														 	$imgsrc = $img->Imagem;
														} else if ($img->Imagem != " ") {
														 	$imgsrc = $img->Imagem;
														} else if (!empty($img->URLImagem)) {
														 	$imgsrc = $img->URLImagem;
														} else if ($img->URLImagem != " ") {
															$imgsrc = $img->URLImagem;
														} else {
															$imgsrc = base_url("assets/imgs/noimage.png");
														} ?>
														<div class="col-xs-9 col-md-6">
															<a class="thumbnail" data-idnews="<?php echo $nidnews ;?>" data-imgsrc="<?php echo base_url('pages/proxy/').base64_encode($imgsrc); ?>">
																<img id="<?php echo 'thumbimg'.$imgcount?>" class="img-responsive" src="<?php echo base_url('pages/proxy/').base64_encode($imgsrc); ?>" alt="Imagem">
															</a>
														</div>
													<?php }
													$newidimg = $img->IdImagem;
												} ?>
											</div>
										<?php } ?>
									</div>

									<div class="col-lg-8 pbodyt" id="<?php echo 'pbody'.$divcount;?>" style="max-height: 550px; overflow-y: auto">
										<p class="lead"><?php echo $ntitle; ?></p>
										<p class="text-justify"><?php echo (string)$nnews;?></p>
									</div>
								</div>
							</div>
						</div>
					<?php }
				} ?>
			</div>
		</div>

		<div id="imgmodal" class="modal fade imgmodal" tabindex="-1" role="dialog" aria-labelledby="imgmodal">
			<div class="modal-dialog modal-lg" role="document" style="width: 95%;">
				<div class="modal-content">
					<div class="modal-body center-block text-center" style="min-height: 500px">
						<div class="row center-block text-center">
							<div id="divbigimg" class="col-lg-9">
							</div>
							<div class="col-lg-3">
								<div class="row" style="padding-bottom: 5px;">
									<div id="divbtns" class="col-lg-12">
										<div class="btn-group">
											<button id="btnzoomin" type="button" class="btn btn-sm btn-default disabled" title="Aproximar" disabled><i class="fa fa-search-plus"></i></button>
											<button id="btnzoonout" type="button" class="btn btn-sm btn-default disabled" title="Afastar" disabled><i class="fa fa-search-minus"></i></button>
										</div>
										<div class="btn-group">
											<button id="btnclall" type="button" class="btn btn-sm btn-default" title="Limpar tudo"><i class="fa fa-eraser"></i></button>
											<button id="btncllast" type="button" class="btn btn-sm btn-default" title="Limpar última"><i class="fa fa-undo"></i></button>
										</div>
										<div class="btn-group">
											<button id="btnocr" type="button" class="btn btn-sm btn-default disabled"><i class="fa fa-pencil-square-o" disabled></i> OCR</button>
										</div>
										<div class="btn-group">
											<button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Fechar</button>
										</div>
									</div>
								</div>
								<div id="divbtnmultclipp" class="row" style="padding-bottom: 15px; display: none;">
									<div class="col-lg-12">
										<a id="btnmultclipp" href="#" type="button" class="btn btn-large btn-block btn-default" target="_blank">Abrir no Multclipp</a>
									</div>
								</div>
								<div id="rowcropped" class="row">
									<div id="divcroppped" class="col-lg-12" style="max-height: 1500px; overflow-y: auto"></div>
								</div>
								<div id="rowtextresult" class="row">
									<div class="col-lg-12">
										<canvas id="joincanvas" class="img-responsive" style="display: none;"></canvas>
										<div id="waitimg" style="display: none;" style="padding-top: 15px">
											<img src="<?php echo base_url('assets/imgs/loading.gif')?>" alt="Carregando" width="60">
											<h3>Carregando...</h3>
										</div>
										<div id="divtextarea" class="form-group" style="display: none;">
											<textarea name="textresult" id="textresult" class="form-control text-justify js-copytextarea" rows="40"></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			var jcropapi;

			jQuery.fn.scrollTo = function(elem) {
				$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
				return this;
			}

			$('.pbodyt').css('overflowY', 'hidden');

			$('.pbodyt').click(function() {
				$(this).css('overflowY', 'auto');
			})

			$('.pbodyt').hover(function() {
				/*do nothing*/
			}, function() {
				$('.pbodyt').css('overflowY', 'hidden');
			});

			var qtkwf = $('.str').length;
			$('#qtkwfid').text(qtkwf);

			$('.likeyword').hover(function(event) {
				// console.log(event);
				keyword = event.target.text;
				idkeyword = event.target.dataset.idkeyword;
				idpbodyt = event.target.dataset.pbodyt;
				
				pbodytext = $('#'+idpbodyt+' .text-justify').text();
				rgx = new RegExp ('\\b'+keyword+'\\b', 'ig');
				pbodynewtext = pbodytext.replace(rgx, '<strong class="str" style="color: white; background-color: red; font-size: 110%;">'+keyword+'</strong>');
				$('#'+idpbodyt+' .text-justify').html(null);
				$('#'+idpbodyt+' .text-justify').html(pbodynewtext);
				
				if ($('#'+idpbodyt+ '> p > .str').length != 0) {
					$('#'+idpbodyt).scrollTo('.str');
				}
			}, function() {
				$('.pbodyt').css('overflowY', 'hidden');
			});

			// $('.likeyword').click(function(event) {
			// 	keyword = event.target.text;
			// 	idkeyword = event.target.dataset.idkeyword;
			// 	idpbodyt = event.target.dataset.pbodyt;
			// 	pbodytext = $('#'+idpbodyt+' .text-justify').text();
			// 	rgx = new RegExp (keyword, 'ig');
			// 	pbodynewtext = pbodytext.replace(rgx, '<strong class="str" style="color: white; background-color: red; font-size: 110%;">'+keyword+'</strong>');

			// 	$('#'+idpbodyt+' .text-justify').html(null);
			// 	$('#'+idpbodyt+' .text-justify').html(pbodynewtext);
				
			// 	if ($('.str').length != 0) {
			// 		$('.pbodyt').scrollTo('.str');
			// 	}
			// });

			$('.thumbnail').click(function(event) {
				// console.log(event);

				var imgobjctw, imgobjcth, timgwidth, timgheight;

				climgid = event.target.id;
				// climgsrc = event.target.attributes[2].value;
				climgsrc = $(this).attr('data-imgsrc');
				clidnews = $(this).attr('data-idnews');
				$('#btnmultclipp').attr('href', 'http://www.multclipp.com.br/admin/cadastroNoticia.aspx?id='+clidnews);

				// var canvas = document.getElementById('cbigimg');
				// var ctx = canvas.getContext('2d');

				divbiwidth = $('#divbigimg').width();
				divbiheigth = $('#divbigimg').height();

				var imageObj = new Image();
				imageObj.src = climgsrc;
				imgobjctw = imageObj.width;
				imgobjcth = imageObj.height;

				// console.log('image width: '+imgobjctw);

				if (imgobjctw > 2000) {
					timgwidth = imageObj.width / 3;
					timgheight = imageObj.height / 3;
				} else {
					timgwidth = imageObj.width / 2;
					timgheight = imageObj.height / 2;					
				}

				// console.log('timgwidth: '+timgwidth);
				// console.log('timgheight: '+timgheight);

				imageObj.setAttribute('id', 'bigimg');
				document.getElementById('divbigimg').appendChild(imageObj);

				$('#imgmodal').modal('show');

				$('#bigimg').Jcrop(
					{
						trueSize: [imageObj.width, imageObj.height],
						boxWidth: timgwidth,
						boxHeight: timgheight, 
						onSelect: showCoords,
					},
					function() { jcropapi = this; }
				);

				function showCoords(c) {
					// variables can be accessed here as
					// c.x, c.y, c.x2, c.y2, c.w, c.h

					cropcanvas = document.createElement('canvas');
					// cropcanvas = document.getElementById('cropped');
					cropctx = cropcanvas.getContext('2d');

					cropctx.canvas.width = c.w;
					cropctx.canvas.height = c.h;

					cropctx.drawImage(imageObj, c.x, c.y, c.w, c.h, 0, 0, c.w, c.h);

					cropcanvas.className = "img-responsive";
					document.getElementById('divcroppped').appendChild(cropcanvas);

					$('#btnocr').attr('disabled', false);
					$('#btnocr').removeClass('disabled');
				};
			});

			$('#imgmodal').on('hide.bs.modal', function (event) {
				// jcropapi = $.Jcrop('#bigimg');
				jcropapi.destroy();

				// jcropapi.disabled();
				// $('#divbigimg').empty();
				$('#bigimg').detach();
				$('#divcroppped').empty();

				$('#waitimg').css('display', 'none');
				$('#divbtnmultclipp').css('display', 'none');
				$('#divtextarea').css('display', 'none');
				$('#rowcropped').css('display', 'block');
			});

			$('#btnclall').click(function(event) {
				$('#divcroppped').empty();
				$('#waitimg').css('display', 'none');
				$('#divbtnmultclipp').css('display', 'none');
				$('#divtextarea').css('display', 'none');
				$('#rowcropped').css('display', 'block');
				$('#btnocr').attr('disabled', true);
				$('#btnocr').addClass('disabled');
			});

			$('#btncllast').click(function(event) {
				croppedcvns = $('#divcroppped').children();
				lastitem = croppedcvns[croppedcvns.length - 1];
				lastitem.remove();
			});

			$('#btnocr').click(function(event) {
				joincanvas = document.getElementById('joincanvas');
				joinctx = joincanvas.getContext('2d');

				croppedcvns = $('#divcroppped').children();
				tcanvasheight = 0;
				tcanvaswidth = 0;
				tcanvaslastwidth = 0;
				$.each(croppedcvns, function(index, val) {
					tcanvasheight += val.height;
					if (val.width > tcanvaslastwidth) {
						tcanvaswidth = val.width;
					}
					tcanvaslastwidth = val.width;
				});

				lastwidth = 0;
				lastheight = 0;
				joinctx.canvas.width = tcanvaswidth;
				joinctx.canvas.height = tcanvasheight;
				$.each(croppedcvns, function(index, val) {
					joinctx.drawImage(val, 0, 0, val.width, val.height, 0, lastheight, val.width, val.height);
					lastwidth = val.width;
					lastheight += val.height;
				});

				//localStorage.setItem( "savedImageData", joincanvas.toDataURL("image/png"));
			
				var djoincanvas = document.getElementById("joincanvas");

				// dimg = new Image();
				// dimg.src = djoincanvas.toDataURL();
				// console.log(dimg);
				
				dturl = djoincanvas.toDataURL();
				dimg = dturl.replace(/^data:image\/(png|jpg);base64,/, "");
				// console.log(dimg);

				var form = new FormData();
				form.append("address", "http://192.168.0.15:1688/upload");
				form.append("the_file", dimg);

				$('#rowcropped').css('display', 'none');
				$('#waitimg').css('display', 'block');

				var settings = {
					"async": true,
					"crossDomain": true,
					"url": "<?php echo base_url('pages/proxy')?>",
					"method": "POST",
					"headers": {
						"cache-control": "no-cache",
					},
					"processData": false,
					"contentType": false,
					"mimeType": "multipart/form-data",
					"data": form
				}

				$.ajax(settings)
				.done(function (response) {
					console.log(response);
					jresponse = JSON.parse(response);
					console.log(jresponse);
					$('#textresult').val(jresponse.result);

					$('#waitimg').css('display', 'none');
					$('#divbtnmultclipp').css('display', 'block');
					$('#divtextarea').css('display', 'block');

					// var copyTextarea = document.querySelector('.js-copytextarea');
					// copyTextarea.select();
				    	// $("#textresult").select();
    					// document.execCommand('copy');
					// copyTextarea.val();

					// try {
					// 	var successful = document.execCommand('copy');
					// 	var msg = successful ? 'successful' : 'unsuccessful';
					// 	console.log('Copying text command was ' + msg);
					// } catch (err) {
					// 	console.log('Oops, unable to copy');
					// 	console.log(err);
					// }
						
					$('#btnocr').attr('disabled', true);
					$('#btnocr').addClass('disabled');
				})
				.fail(function(err){
					console.log(err.responseText);
				});
			});

			if ($('#back-to-top').length) {
				var scrollTrigger = 1000,
				backToTop = function() {
					var scrollTop = $(window).scrollTop();
					if (scrollTop > scrollTrigger) {
						$('#back-to-top').addClass('show');
					} else {
						$('#back-to-top').removeClass('show');
					}
				}

				backToTop();
				$(window).on('scroll', function() {
					backToTop();
				});

				$('#back-to-top').on('click', function (e) {
					e.preventDefault();
					$('html,body').animate({scrollTop: 0}, 700);
				});
			}
		</script>