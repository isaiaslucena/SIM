<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<body>
	<script type="text/javascript" src="<?php echo base_url('assets/jcrop/js/jquery.Jcrop.min.js')?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('assets/jcrop/css/jquery.Jcrop.min.css')?>">

	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					OCR Avulso
				</h1>
			</div>
		</div>

		<div class="row">
			<div id="divbigimg" class="col-lg-9">
				<div id="wellbigimg" class="well well-lg" style="min-height: 400px; padding-left: 40%; padding-top: 100px">
					<i id="iconimg" class="fa fa-file-image-o" style="font-size: 15em"></i>
					<!-- <img id="uplimg" class="img-responsive" style="display: none;"> -->
				</div>
			</div>
			<div class="col-lg-3">
				<div class="row" style="padding-bottom: 15px">
					<div class="col-lg-12">
						<label class="btn btn-sm btn-block btn-default">
							Selecionar Arquivo&hellip; 
							<input id="btnupload" type="file" style="display: none;">
						</label>
					</div>
				</div>				
				<div class="row" style="padding-bottom: 15px;">
					<div class="col-lg-12">
						<div class="btn-group">
							<button id="btnzoomin" type="button" class="btn btn-sm btn-default disabled" title="Aproximar" disabled><i class="fa fa-search-plus"></i></button>
							<button id="btnzoonout" type="button" class="btn btn-sm btn-default disabled" title="Afastar" disabled><i class="fa fa-search-minus"></i></button>
						</div>
						<div class="btn-group">
							<button id="btnclall" type="button" class="btn btn-sm btn-default disabled" title="Limpar tudo" disabled><i class="fa fa-eraser"></i></button>
							<button id="btncllast" type="button" class="btn btn-sm btn-default disabled" title="Limpar última" disabled><i class="fa fa-undo"></i></button>
						</div>
						<div class="btn-group">
							<button id="btnocr" type="button" class="btn btn-sm btn-default disabled"><i class="fa fa-pencil-square-o" disabled></i> OCR</button>
						</div>
					</div>
				</div>
				<div id="divbtnmultclipp" class="row" style="padding-bottom: 15px; display: none;">
					<div class="col-lg-12">
						<a id="btnmultclipp" href="http://www.multclipp.com.br/admin/cadastroNoticia.aspx" type="button" class="btn btn-sm btn-block btn-default" target="_blank">Abrir Multclipp</a>
					</div>
				</div>
				<div id="rowcropped" class="row">
					<div id="divcroppped" class="col-lg-12" style="max-height: 1500px; overflow-y: auto"></div>
				</div>
				<div id="rowtextresult" class="row">
					<div class="col-lg-12 text-center center-block">
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

		<script type="text/javascript">
			var jcropapi, imgobjctw, imgobjcth, timgwidth, timgheight;
			var jcropdestroy = false;

			$('#btnupload').change(function(event) {
				$('#iconimg').css('display', 'none');
				$('#wellbigimg').css({
					'padding-left': '0',
					'padding-top': '0'
				});
				// $('#uplimg').css('display', 'block');

				file = document.querySelector('input[type=file]').files[0];
				reader = new FileReader();

				reader.onload = function(e) {
					var objImage = new Image();
					objImage.src = e.srcElement.result;
					objImage.onload = function(event) {
						if (typeof jcropapi != 'undefined') {
							jcropapi.destroy();
							$('#uplimg').detach();
						}

						objImage.setAttribute('id', 'uplimg');
						document.getElementById('wellbigimg').appendChild(objImage);

						setTimeout(function() {console.log('wait 3s')}, 3000);

						// console.log(objImage);
						// insimg = document.getElementById('uplimg');
						imgobjctw = objImage.width;
						imgobjcth = objImage.height;
						// imgobjctw = $('#uplimg').width();
						// imgobjcth = $('#uplimg').height();
						// imgobjctw = insimg.width;
						// imgobjcth = insimg.height;

						if (imgobjctw > 2000) {
							timgwidth = imgobjctw / 3.5;
							timgheight = imgobjcth / 3.5;
						} else {
							timgwidth = imgobjctw / 2;
							timgheight = imgobjcth / 2;
						}

						console.log('img width: '+imgobjctw);
						console.log('img height: '+imgobjcth);

						console.log('timgwidth: '+timgwidth);
						console.log('timgheight: '+timgheight);

						$('#uplimg').Jcrop(
							{
								trueSize: [imgobjctw, imgobjcth],
								boxWidth: timgwidth,
								boxHeight: timgheight, 
								onSelect: showCoords,
							},
							function() {
								jcropdestroy = true;
								jcropapi = this;
						});

						function showCoords(c) {
							console.log(c);
							// variables can be accessed here as
							// c.x, c.y, c.x2, c.y2, c.w, c.h

							cropcanvas = document.createElement('canvas');
							// cropcanvas = document.getElementById('cropped');
							cropctx = cropcanvas.getContext('2d');

							cropctx.canvas.width = c.w;
							cropctx.canvas.height = c.h;

							cropctx.drawImage(objImage, c.x, c.y, c.w, c.h, 0, 0, c.w, c.h);

							cropcanvas.className = "img-responsive";
							document.getElementById('divcroppped').appendChild(cropcanvas);

							$('#btnocr').attr('disabled', false);
							$('#btnocr').removeClass('disabled');
						};
					}
				}
				
				if (file) {
					reader.readAsDataURL(file);
				}
				
				$('#btnclall').removeClass('disabled');
				$('#btnclall').removeAttr('disabled');
				croppedcvns = $('#divcroppped').children();	
				if (croppedcvns.length > 1) {
					$('#btncllast').removeClass('disabled');
					$('#btncllast').removeAttr('disabled');
				}
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
		</script>