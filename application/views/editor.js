				//EDITOR
				$('#btnjoin').click(function(event) {
					videoel[0].pause();
					// $('.joinmodal').modal('show');

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

													console.log(filesjoined);

													$('.vbutton').css('display', 'none');
													$('.vbutton').removeClass('paused');

													videourlmjoin = '<?php echo str_replace("sim.","video.",base_url())?>video/getjoinvideo/' + data.joinfilename;

													videotitle.text(data.joinfilename);
													vsrcarr = data.joinfilename.split('_');
													videotitle.attr('data-vsrc', vsrcarr[0]);
													videotitle.css('font-size', '18px');
													videoel.attr({
														poster: '<?php echo base_url("assets/imgs/videoloading.gif")?>',
														src: videourlmjoin
													});

													$('input').prop("checked", false);
													$('.list-group').children().removeClass('active');
													$.each(filestojoin, function(index, val) {
														var nval = val.replace('.mp4', '');
														$('span:contains('+nval+')').parent().addClass('active');
													});

													filestojoin = [];
													vbtnjoin = [];
													joinvideos = true;

													jointimeend = new Date();
													croptimedifference = ((jointimeend.getTime() - jointimestart.getTime()) / 1200).toFixed(3);
													$('#cropvideoload').text("Tempo do corte: "+ croptimedifference + "s");

													$('.joinmodal').modal('hide');
													progressjbar.animate(0);

													$('#btnjoin').addClass('disabled');
													$('#btnjoin').attr('disabled', true);
												}
											}
										);
								}, 1000);
							}
						);
					}
				});

				$('#btncstart').click(function(event) {
					cropstartss = videoel[0].currentTime;
					console.log()
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
						cropstart = $('#currtime').text().replace(":", "-");
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
				})

				$('#btncrop').click(function(event) {
					if (ccrops && ccrope) {
						videoel[0].pause();
						$("#ipause").addClass('hidden');
						$("#iplay").removeClass('hidden');
						croptimestart = new Date();
						$('#cropvideoload').text(null);
						$('.cropmodal').modal('show');

						cfile = videotitle.text();
						cfilearr = cfile.split("_");
						cfiledate = cfilearr[0];
						cfiletime = cfilearr[1].replace(new RegExp("-", 'g'), ":");
						cfilechannel = cfilearr[2];

						cfilechann = channelname(cfilechannel);
						if (cfilechann == "TV Cultura") {
							cfilestate = "SP";
						} else {
							cfilestate = cfilearr[3];
						}
						cfilesource = "\"" + cfilechann + " - " + cfilestate + "\"";
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

						$('#transct').val(null);
						$.post('/pages/get_tvwords',
							{
								source: cfilesource,
								ststartdate: cfiletimestampt,
								stenddate: cfiletstamp,
								wstartdate: cfiletstampst,
								wenddate: cfiletstampet
							},
							function(data, textStatus, xhr) {
								var textw = "";
								var wordbefore = "";
								$.each(data, function(index, val) {
									$.each(val.response.docs, function(indexd, vald) {
										starttimew = vald.starttime_l;
										endtimew = vald.endtime_l;
										if (vald.word_s != wordbefore) {
											textw += vald.word_s;
											textw += " ";
										}
										wordbefore = vald.word_s;
									});
								});
								$('#transct').val(textw);
							}
						);

						load_vihts();

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
							$.post('<?php echo base_url("pages/proxy")?>',
								{address: '<?php echo str_replace("sim.","video.",base_url("video/cropvideo/"))?>' + vsource + '_' + cfile + '/' + cropstart + '/' + cropdurs},
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
														// $('#progresscrop').css('display', 'none');
														// $('#mdivvideo').css('display', 'block');

														$('#progresscrop').fadeOut('fast', function() {
															$('#mdivvideo').fadeIn('fast');
														});

														videourlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/getcropvideo/'+filecname;
														videovurlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/verifycropvideo/'+filecname;
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

														filenamearr = filecname.split("_");
														datearr = filenamearr[1].split("-");
														cropfmonth = datearr[1];
														cropfday = datearr[2];
														cropfch = filenamearr[3];
														cropfst = filenamearr[4];
														// $('#mbtnvdown').attr({href: videourlmcrop});
														croptimeend = new Date();
														croptimedifference = ((croptimeend.getTime() - croptimestart.getTime()) / 1200).toFixed(3);
														$('#cropvideoload').text("ID: "+fileid+" | Tempo do corte: "+ croptimedifference + "s");
													}
												}
											);
									}, 1000);
								}
							);
						}
					}
				});

				$('#selvinheta').on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
					vintfile = $(this).val();
					console.log(vintfile);

					selvinheta = true;

					$('#mbtnv2down').removeClass('disabled');
					$('#mbtnv2down').removeAttr('disabled');
				})

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

				function joinfiles(cbid, jfilevsource, jfilename, jvbtn) {
					jvsource = jfilevsource;
					chbox = $('#'+cbid);
					verify = chbox.is(":checked");
					if (verify) {
						filestojoin.push(jfilename);
						vbtnjoin.push(jvbtn);
						if (filestojoin.length >= 2) {
							$('#btnjoin').removeClass('disabled');
							$('#btnjoin').removeAttr('disabled');
							joinvideos = true;
						}
					} else {
						fileindex = filestojoin.indexOf(jfilename);
						filestojoin.splice(fileindex, 1);
						vbntindex = vbtnjoin.indexOf(jvbtn);
						vbtnjoin.splice(vbntindex, 1);
						if (filestojoin.length <= 2) {
							$('#btnjoin').addClass('disabled');
							$('#btnjoin').attr('disabled', true);
							joinvideos = false;
						}
					}
				}

				function cropjoinfiles(cjfilename) {
					cropfilestojoin.push(cjfilename);
					if (cropfilestojoin.length >= 2) {
						$('#btnjoin').removeClass('disabled');
						$('#btnjoin').removeAttr('disabled');
						joincropvideos = true;
					}
				}
			});