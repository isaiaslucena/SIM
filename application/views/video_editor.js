//EDITOR

//Variables
var progresscbar = new ProgressBar.Circle('#progresscrop', {
	color: '#aaa',
	// This has to be the same size as the maximum width to
	// prevent clipping
	strokeWidth: 4,
	trailWidth: 1,
	easing: 'easeInOut',
	duration: 200,
	text: { autoStyleContainer: false },
	from: { color: '#aaa', width: 2 },
	to: { color: '#333', width: 4 },
	// Set default step function for all animate calls
	step: function(state, circle) {
		circle.path.setAttribute('stroke', state.color);
		circle.path.setAttribute('stroke-width', state.width);

		var value = Math.round(circle.value() * 100);
		if (value === 0) {
			circle.setText('0%');
		} else {
			circle.setText(value+'%');
		}
	}
});
progresscbar.text.style.fontFamily = 'Helvetica';
progresscbar.text.style.fontSize = '4rem';

var progressjbar = new ProgressBar.Circle('#progressjoin', {
	color: '#aaa',
	// This has to be the same size as the maximum width to
	// prevent clipping
	strokeWidth: 4,
	trailWidth: 1,
	easing: 'easeInOut',
	duration: 200,
	text: { autoStyleContainer: false },
	from: { color: '#aaa', width: 2 },
	to: { color: '#333', width: 4 },
	// Set default step function for all animate calls
	step: function(state, circle) {
		circle.path.setAttribute('stroke', state.color);
		circle.path.setAttribute('stroke-width', state.width);

		var value = Math.round(circle.value() * 100);
		if (value === 0) {
			circle.setText('0%');
		} else {
			circle.setText(value+'%');
		}
	}
});
progressjbar.text.style.fontFamily = 'Helvetica';
progressjbar.text.style.fontSize = '4rem';

var progressjcbar = new ProgressBar.Circle('#progressjcrop', {
	color: '#aaa',
	// This has to be the same size as the maximum width to
	// prevent clipping
	strokeWidth: 4,
	trailWidth: 1,
	easing: 'easeInOut',
	duration: 200,
	text: { autoStyleContainer: false },
	from: { color: '#aaa', width: 2 },
	to: { color: '#333', width: 4 },
	// Set default step function for all animate calls
	step: function(state, circle) {
		circle.path.setAttribute('stroke', state.color);
		circle.path.setAttribute('stroke-width', state.width);

		var value = Math.round(circle.value() * 100);
		if (value === 0) {
			circle.setText('0%');
		} else {
			circle.setText(value+'%');
		}
	}
});
progressjcbar.text.style.fontFamily = 'Helvetica';
progressjcbar.text.style.fontSize = '4rem';

toastr.options = {
	"closeButton": false,
	"debug": false,
	"newestOnTop": false,
	"progressBar": true,
	"positionClass": "toast-top-right",
	"preventDuplicates": false,
	"onclick": function(e) {
		$('.cropqueuemodal').modal('show');
	},
	"showDuration": "300",
	"hideDuration": "2000",
	"timeOut": "5000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"
}

//Functions
function joinfiles(cbid, jfilevsource, jfilename, jvbtn) {
	jvsource = jfilevsource;
	chbox = $('#'+cbid);
	verify = chbox.is(":checked");
	if (verify) {
		filestojoin.push(jfilename);
		vbtnjoin.push(jvbtn);
		if (filestojoin.length == 1) {
			$('#btndownimgs').removeClass('disabled');
			$('#btndownimgs').removeAttr('disabled');
		} else if (filestojoin.length >= 2) {
			$('#btnjoin').removeClass('disabled');
			$('#btnjoin').removeAttr('disabled');
			joinvideos = true;
		}
	} else {
		fileindex = filestojoin.indexOf(jfilename);
		filestojoin.splice(fileindex, 1);
		vbntindex = vbtnjoin.indexOf(jvbtn);
		vbtnjoin.splice(vbntindex, 1);
		if (filestojoin.length == 0) {
			$('#btndownimgs').addClass('disabled');
			$('#btndownimgs').attr('disabled');
		} else if (filestojoin.length <= 2) {
			$('#btnjoin').addClass('disabled');
			$('#btnjoin').attr('disabled', true);
			joinvideos = false;
		}
	}
};

function cropjoinfiles(cjfilename) {
	cropfilestojoin.push(cjfilename);
	if (cropfilestojoin.length >= 2) {
		$('#btnjoin').removeClass('disabled');
		$('#btnjoin').removeAttr('disabled');
		joincropvideos = true;
	}
};

function getqueuecrop() {
	if ($('#queuecroplist').hasClass('noitems')) {
		$('#queuecroplist').html(null);
		$('#queuecroplist').removeClass('noitems');
	}

	$.get('<?php echo base_url("api/get_queue_crop");?>',
		function(data) {
		queuecroplenth = data.queue.length;

			if (queuecroplentha != queuecroplenth) {
				$('#queuecroplist').html(null);
				// console.log(data);
				$.each(data.queue, function(index, val) {
					var tsadddt = new Date(0);
					tsadddt.setUTCSeconds(val.ts_add);
					day = tsadddt.getDate();
					day = ('0' + day).slice(-2);
					month = (tsadddt.getMonth() + 1);
					month = ('0' + month).slice(-2);
					year = tsadddt.getFullYear();
					hour = tsadddt.getHours();
					hour = ('0'+hour).slice(-2);
					min = tsadddt.getMinutes();
					min = ('0'+min).slice(-2);
					sec = tsadddt.getSeconds();
					sec = ('0'+sec).slice(-2);
					tsaddtime = year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec;

					html = '<a class="list-group-item queuecropitem" href="#">'+
										'<h4 class="list-group-item-heading">'+
											index+' - '+val.filename+
										'</h4>'+
										'<p class="list-group-item-text">'+
											'Inclusão: '+tsaddtime+'<br>'+
											'Início do Corte: '+val.crop_start+'<br>'+
											'Fim do Corte: '+val.crop_end+
										'</p>'+
									'</a>';
					$('#queuecroplist').append(html);
				});
			}

			queuecroplentha = queuecroplenth;
		}
	);
}

//Listeners
$('#btncstart').click(function(event) {
	cropstartss = videoel[0].currentTime;
	console.log();
	if (parseInt(cropendss) < parseInt(cropstartss) || parseInt(cropendss) == parseInt(cropstartss)) {
		swal("Atenção!", "O tempo final deve ser maior que o inicial.", "error");
		$(this).text(null);
		$(this).append('<i class="fa fa-hourglass-end"></i>');
		$(this).removeClass('btn-primary');
		$(this).addClass('btn-default');
		ccrope = false;
	} else {
		$(this).text(null);
		$(this).append('<i class="fa fa-hourglass-start"></i>');
		$(this).removeClass('btn-default');
		$(this).addClass('btn-primary');
		$(this).append(' '+$('#currtime').text());
		// cropstarts = (videoel[0].currentTime * 100 / 100).toFixed(3);
		cropstarts = cropstartss.toFixed(3);
		cropstartms = cropstarts.split(".")
		// cropstart = '00-'+$('#currtime').text().replace(":", "-")+'.'+cropstartms[1];
		// cropstart = '00-'+$('#currtime').text().replace(":", "-");
		cropstart = $('#currtime').text().replace(/:/g, '-');
		// cropstartt = '00-'+$('#currtime').text()+'.'+cropstartms[1];
		cropstartt = $('#currtime').text();
		ccrops = true;
		// console.log('crop starttime: '+cropstartt);
		console.log('crop starttime: '+cropstarts);
	}
});

$('#btncend').click(function(event) {
	cropendss = videoel[0].currentTime;
	if (ccrops) {
		if (parseInt(cropendss) < parseInt(cropstartss) || parseInt(cropendss) == parseInt(cropstartss)) {
			swal("Atenção!", "O tempo final deve ser maior que o inicial.", "error");
			$(this).text(null);
			$(this).append('<i class="fa fa-hourglass-end"></i>');
			$(this).removeClass('btn-primary');
			$(this).addClass('btn-default');
			ccrope = false;
		} else {
			time = $(this).text();
			if (time != '') {
				$(this).text(null);
				$(this).append('<i class="fa fa-hourglass-end"></i>');
			}
			$('#btncrop').removeClass('disabled');
			$('#btncrop').removeAttr('disabled');
			$(this).removeClass('btn-default');
			$(this).addClass('btn-primary');
			$(this).append(' '+$('#currtime').text());
			// cropends = (videoel[0].currentTime * 100 / 100).toFixed(3);
			cropends = cropendss.toFixed(3);
			cropendms = cropends.split(".");
			cropendt = '00-'+$('#currtime').text()+'.'+cropendms[1];
			// cropendt = '00-'+$('#currtime').text();
			cropdurs = (cropends - cropstarts).toFixed(3);
			cropdurmm = ('0' + Math.floor(cropdurs / 60)).slice(-2);
			cropdurss = ('0' + Math.floor(cropdurs - cropdurmm * 60)).slice(-2);
			cropdur = '00-'+cropdurmm+'-'+cropdurss;
			ccrope = true;
			// console.log('crop starttime: '+cropstarts);
			console.log('crop end: '+cropends);
			console.log('crop duration: '+cropdurs);
		}
	} else {
		swal("Atenção!", "Você deve marcar primeiro o tempo inicial.", "error");
	}
});

$('#btncrop').click(function(event) {
	if (ccrops && ccrope) {
		videoel[0].pause();
		$("#ipause").addClass('hidden');
		$("#iplay").removeClass('hidden');
		croptimestart = new Date();
		$('#cropvideoload').text(null);
		// $('.cropmodal').modal('show');

		cfile = videotitle.text();
		cfilearr = cfile.split('_');
		cfiledate = cfilearr[0];
		cfiletime = cfilearr[1].replace(new RegExp("-", 'g'), ":");
		cfilechannel = cfilearr[2];
		cfilestate = cfilearr[3];
		cfilesource = "\""+cfilechannel+"-"+cfilestate+"\"";
		cfiledatetime = cfiledate + " " + cfiletime;

		cfilestimestamp = new Date(String(cfiledatetime));
		cfilesstimestamp = new Date(String(cfiledatetime));
		cfileetimestamp = new Date(String(cfiledatetime));
		cfiletimestampt = cfilestimestamp.getTime();
		// cfiletimestampt = cfileetimestamp.setMinutes(cfileetimestamp.getMinutes() - 10);
		cfiletstamp = cfileetimestamp.setMinutes(cfileetimestamp.getMinutes() + 10);
		// cropstartts = cropstarts - 20;
		// cropendts = cropends - 20;
		cropstartts = cropstarts;
		cropendts = cropends;
		cfiletstampst = cfilestimestamp.setSeconds(cfilestimestamp.getSeconds() + Number(cropstartts));
		cfiletstampet = cfilesstimestamp.setSeconds(cfilesstimestamp.getSeconds() + Number(cropendts));

		hstorydates = new Date(cfiletimestampt)
		hstorydatee = new Date(cfiletstamp)
		hwordtimes = new Date(cfiletstampst)
		hwordtimee = new Date(cfiletstampet)

		if (joinvideos) {
			$.post('<?php echo base_url("pages/proxy")?>',
				{address: '<?php echo str_replace("sim.","video.",base_url("video/cropjoinvideos/"))?>' + cfile + '/' + cropstart + '/' + cropdurs},
				function(data, textStatus, xhr) {
					console.log(data);
					fileid = data.id;
					filecname = data.cropfilename;
					croptimestart = new Date();
					var rprogress = setInterval(function() {
							$.post('<?php echo base_url("pages/proxy")?>',
								{address: '<?php echo str_replace("sim.","video.",base_url("video/cropprogress/"))?>' + fileid + '/' + cropdurs},
								function(datac, textStatus, xhr) {
									// console.log(datac);
									crpercent = datac.percent;
									crpcircle = crpercent / 100;
									progresscbar.animate(crpcircle);

									if (crpercent >= 99) {
										clearInterval(rprogress);
										$('#progresscrop').css('display', 'none');
										$('#mdivvideo').css('display', 'block');

										videourlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/getcropvideo/' + filecname;
										videovurlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/verifycropvideo/' + filecname;
										$.post('/pages/proxy', {address: videovurlmcrop}, function(data, textStatus, xhr) {
											if (data == "OK") {
												videomel.attr({src: videourlmcrop});
												// videomel[0].play();
											}
										});

										cbjoincrop = $('#checkjoincrop').prop('checked');
										if (cbjoincrop) {
											cropjoinfiles(filecname);
											joincropvideos = true;
											console.log(cropfilestojoin);
										}

										filenamearr = data.cropfilename.split("_");
										datearr = filenamearr[1].split("-");
										cropfmonth = datearr[1];
										cropfday = datearr[2];
										cropfch = filenamearr[3];
										cropfst = filenamearr[4];
										// $('#mbtnvdown').attr({href: videourlmcrop});
										croptimeend = new Date();
										croptimedifference = ((croptimeend.getTime() - croptimestart.getTime()) / 1200).toFixed(3);
										$('#cropvideoload').text("Tempo do corte: "+ croptimedifference + "s");
									}
								}
							);
					}, 1000);
				}
			);
		} else {
			$.ajax({
				url: '<?php echo base_url("api/add_queue_crop")?>',
				type: 'POST',
				dataType: 'json',
				contentType: 'application/json; charset=utf-8',
				data: JSON.stringify(
					{
						"id_user": <?php echo $this->session->userdata('id_user');?>,
						"filename": cfile,
						"source": vsource,
						"crop_start": cropstarts,
						"crop_end": cropends
					}
				)
			})
			.done(function(data) {
				// console.log(data);
				toastr.success(cfile+' inserido na fila de corte com ID '+data.queue_crop_id);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

			// $.post('<?php echo str_replace("sim.","video.",base_url("video/cropvideo/"))?>'+vsource+'_'+cfile+'/'+cropstart+'/'+cropdurs,
			// 	function(data, textStatus, xhr) {
			// 		console.log(data);
			// 		fileid = data.id;
			// 		filecname = data.cropfilename;
			// 		croptimestart = new Date();
			// 		var rprogress = setInterval(function() {
			// 				$.post('<?php echo base_url("pages/proxy")?>',
			// 					{address: '<?php echo str_replace("sim.","video.",base_url("video/cropprogress/"))?>' + fileid + '/' + cropdurs},
			// 					function(datac, textStatus, xhr) {
			// 						// console.log(datac);
			// 						crpercent = datac.percent;
			// 						crpcircle = crpercent / 100;
			// 						progresscbar.animate(crpcircle);

			// 						if (crpercent >= 99) {
			// 							clearInterval(rprogress);
			// 							// $('#progresscrop').css('display', 'none');
			// 							// $('#mdivvideo').css('display', 'block');

			// 							$('#progresscrop').fadeOut('fast', function() {
			// 								$('#mdivvideo').fadeIn('fast');
			// 							});

			// 							videourlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/getcropvideo/'+filecname;
			// 							videovurlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/verifycropvideo/'+filecname;
			// 							$.post('/pages/proxy', {address: videovurlmcrop}, function(data, textStatus, xhr) {
			// 								if (data == "OK") {
			// 									videomel.attr({src: videourlmcrop});
			// 									// videomel[0].play();
			// 								}
			// 							});

			// 							cbjoincrop = $('#checkjoincrop').prop('checked');
			// 							if (cbjoincrop) {
			// 								cropjoinfiles(filecname);
			// 								joincropvideos = true;
			// 								console.log(cropfilestojoin);
			// 							}

			// 							filenamearr = filecname.split("_");
			// 							datearr = filenamearr[1].split("-");
			// 							cropfmonth = datearr[1];
			// 							cropfday = datearr[2];
			// 							cropfch = filenamearr[3];
			// 							cropfst = filenamearr[4];
			// 							croptimeend = new Date();
			// 							croptimedifference = ((croptimeend.getTime() - croptimestart.getTime()) / 1200).toFixed(3);
			// 							$('#cropvideoload').text("ID: "+fileid+" | Tempo do corte: "+ croptimedifference + "s");
			// 						}
			// 					}
			// 				);
			// 		}, 1000);
			// 	}
			// );
		}
	}
});

$('#btnjoin').click(function(event) {
	videoel[0].pause();

	if (joincropvideos) {
		$('.jcropmodal').modal('show');

		$.post('<?php echo base_url("pages/proxy")?>',
			{
				address: '<?php echo str_replace("sim.","video.",base_url("video/joincropvideos"))?>',
				vsource: vsource,
				files: cropfilestojoin
			},
			function(data, textStatus, xhr) {
				console.log(data);
				fileid = data.id;
				filestotaltime = data.totaltime;
				joinctimestart = new Date();
				var rcjprogress = setInterval(function() {
						$.post('<?php echo base_url("pages/proxy")?>',
							{address: '<?php echo str_replace("sim.","video.",base_url("video/joinprogress/"))?>' + fileid + '/' + filestotaltime},
							function(dataj, textStatus, xhr) {
								// console.log(dataj);
								joinpercent = dataj.percent;
								joinpcircle = joinpercent / 100;
								progressjcbar.animate(joinpcircle);

								if (joinpercent >= 99) {
									clearInterval(rcjprogress);

									$('#progressjcrop').css('display', 'none');
									$('#mcjdivvideo').css('display', 'block');

									$('.vbutton').css('display', 'none');
									$('.vbutton').removeClass('paused');

									videourlmcjoin = '<?php echo str_replace("sim.","video.",base_url())?>video/getjoinvideo/' + data.joinfilename;
									$('#mcjvvideo').attr({
										poster: '<?php echo base_url("assets/imgs/videoloading.gif")?>',
										src: videourlmcjoin
									});

									$('#mbtnjcdown').attr('href', videourlmcjoin);
									$('#checkjoincrop').bootstrapToggle('off');
									joinctimeend = new Date();
									joinctimedifference = ((joinctimeend.getTime() - joinctimestart.getTime()) / 1200).toFixed(3);
									$('#joincropvideoload').text("Tempo da junção: "+ joinctimedifference + "s");
									joinvideosclk = false;
								}
							}
						);
				}, 100);
			}
		);
	} else {
		$('.joinmodal').modal({
			show: true,
			backdrop: 'static',
			keyboard: false
		})

		filesjoined = [];
		$.post('<?php echo base_url("pages/proxy")?>',
			{
				address: '<?php echo str_replace("sim.","video.",base_url("video/joinvideos"))?>',
				vsource: jvsource,
				files: filestojoin
			},
			function(data, textStatus, xhr) {
				console.log(data);
				filesjoined = data.files;
				fileid = data.id;
				filestotaltime = data.totaltime;
				jointimestart = new Date();
				var rjprogress = setInterval(function() {
						$.post('<?php echo base_url("pages/proxy")?>',
							{address: '<?php echo str_replace("sim.","video.",base_url("video/joinprogress/"))?>' + fileid + '/' + filestotaltime},
							function(dataj, textStatus, xhr) {
								joinpercent = dataj.percent;
								joinpcircle = joinpercent / 100;
								progressjbar.animate(joinpcircle);

								if (joinpercent >= 99) {
									clearInterval(rjprogress);
									$('.vbutton').css('display', 'none');
									$('.vbutton').removeClass('paused');

									videotitle.text(data.joinfilename);
									vsrcarr = data.joinfilename.split('_');
									videotitle.attr('data-vsrc', vsrcarr[0]);
									videotitle.css('font-size', '18px');

									firstfile = filesjoined[0].file;
									firstfilename = firstfile.replace(jvsource+'_', '');
									videourlmjoin = '<?php echo str_replace("sim.","video.",base_url())?>video/getjoinvideo/'+data.joinfilename;

									var waitf = setTimeout(function() {
										videoel.attr({
											poster: srcposter,
											src: videourlmjoin
										});

										videoel[0].pause();

										$('.joinmodal').modal('hide');
										progressjbar.animate(0);

										if (channel != 'AVULSO') {
											if (jvsource.replace(/[0-9]/g, '') != 'cagiva') {
												loadingthumbs();
											} else {
												videoel[0].play();
											}
										} else {
											videoel[0].play();
										}
									}, 5000);

									arr = firstfile.split('_');
									channel = arr[3];

									if (channel != 'AVULSO') {
										if (jvsource.replace(/[0-9]/g, '') != 'cagiva') {
											var srcposter = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+jvsource+'_'+firstfilename+'/001';
										} else {
											var srcposter = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
										}
									} else {
										var srcposter = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
									}

									$('input').prop("checked", false);
									$('.list-group').children().removeClass('active');
									$.each(filestojoin, function(index, val) {
										var nval = val.replace('.mp4', '');
										$('span:contains('+nval+')').parent().addClass('active');
									});

									jointimeend = new Date();
									croptimedifference = ((jointimeend.getTime() - jointimestart.getTime()) / 1200).toFixed(3);
									$('#cropvideoload').text("Tempo do corte: "+ croptimedifference + "s");

									$('#btnjoin').addClass('disabled');
									$('#btnjoin').attr('disabled', true);

									filestojoin = [];
									vbtnjoin = [];
									joinvideos = true;
									joinvideosclk = true;
								}
							}
						);
				}, 1000);
			}
		);
	}
});

$('#btnqueuecrop').click(function(event) {
	$('.queuecropmodal').modal('show');
});

$('#selvinheta').on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
	vintfile = $(this).val();
	console.log(vintfile);

	selvinheta = true;

	$('#mbtnv2down').removeClass('disabled');
	$('#mbtnv2down').removeAttr('disabled');
});

$('#mbtnvdown').click(function(event) {
	event.preventDefault();
	// window.location.href = videourlmcrop+'/'+vintfile;
	window.location.href = videourlmcrop;
});

$('#mbtnv2down').click(function(event) {
	event.preventDefault();
	window.location.href = videourlmcrop+'/'+vintfile;
});

$('.cropmodal').on('hide.bs.modal', function(event) {
	progresscbar.animate(0);
	videomel[0].pause();
	videomel.removeAttr('src');

	$('#mdivvideo').css('display', 'none');
	$('#progresscrop').css('display', 'block');
	$('#cropvideoload').text(null);
	$('#btncrop').addClass('disabled');
	$('#btncrop').attr('disabled');
	$('#btncend').text(null);
	$('#btncend').append('<i class="fa fa-hourglass-end"></i>');
	$('#btncend').removeClass('btn-primary');
	$('#btncend').addClass('btn-default');
	$('#btncstart').text(null);
	$('#btncstart').append('<i class="fa fa-hourglass-end"></i>');
	$('#btncstart').removeClass('btn-primary');
	$('#btncstart').addClass('btn-default');

	$('#mbtnv2down').addClass('disabled');
	$('#mbtnv2down').attr('disabled', true);

	cropstartss = null;
	cropendss = null;
	cropstarts = null;
	cropends = null;
	ccrops = false;
	ccrope = false;
	selvinheta = false;
	vintfile = null;
});

$('.jcropmodal').on('hide.bs.modal', function(event) {
	progressjcbar.animate(0);
	videojcmel[0].pause();
	videojcmel.removeAttr('src');
	$('#mcjdivvideo').css('display', 'none');
	$('#progressjcrop').css('display', 'block');
	$('#joincropvideoload').text(null);
	$('#toggle-trigger').bootstrapToggle('off')
	$('#btnjoin').addClass('disabled');
	$('#btnjoin').attr('disabled', true);
	cropfilestojoin = [];
	joincropvideos = false;
});