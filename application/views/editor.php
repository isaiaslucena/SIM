				//EDITOR
				$('#btnjoin').click(function(event) {
					videoel[0].pause();
					// $('.joinmodal').modal('show');

					$('.joinmodal').modal({
						show: true,
						backdrop: 'static',
						keyboard: false
					})

					$.post('<?php echo base_url("pages/proxy")?>',
						{
							address: '<?php echo str_replace("sim.","video.",base_url("video/joinvideos"))?>',
							vsource: jvsource,
							files: filestojoin
						},
						function(data, textStatus, xhr) {
							console.log(data);
							fileid = data.id;
							filestotaltime = data.totaltime;
							jointimestart = new Date();
							var rjprogress = setInterval(function() {
									$.post('<?php echo base_url("pages/proxy")?>',
										{address: '<?php echo str_replace("sim.","video.",base_url("video/joinprogress/"))?>' + fileid + '/' + filestotaltime},
										function(dataj, textStatus, xhr) {
											console.log(dataj);
											joinpercent = dataj.percent;
											joinpcircle = joinpercent / 100;
											progressjbar.animate(joinpcircle);

											if (joinpercent >= 100) {
												clearInterval(rjprogress);

												videourlmjoin = '<?php echo str_replace("sim.","video.",base_url())?>video/getjoinvideo/' + data.joinfilename;
												// videovurlmjoin = '<?php echo str_replace("sim.","video.",base_url())?>video/verifyjoinvideo/' + data.joinfilename;
												// $.post('/pages/proxy', {address: videovurlmjoin}, function(data, textStatus, xhr) {
												// 	if (data == "OK") {
												// 		videoel.attr({src: videourlmjoin});
												// 		// videoel[0].play();
												// 	}
												// });

												videotitle.text(data.joinfilename)
												videoel.attr({
													poster: '<?php echo base_url("assets/imgs/videoloading.gif")?>',
													src: videourlmjoin
												});

												$('input').prop("checked", false);
												$('.list-group').children().removeClass('active');
												$.each(vbtnjoin, function(index, val) {
													document.getElementById(val).className += ' active';
												});

												filestojoin = [];
												vbtnjoin = [];
												joinvideos = true;

												jointimeend = new Date();
												croptimedifference = ((jointimeend.getTime() - jointimestart.getTime()) / 1200).toFixed(3);
												$('#cropvideoload').text("Tempo do corte: "+ croptimedifference + "s");

												$('.joinmodal').modal('hide');
												progressjbar.animate(0);
											}
										}
									);
							}, 1000);
						}
					);
				});

				$('#btncstart').click(function(event) {;
					time = $(this).text();
					if (time != '') {
						$(this).text(null);
						$(this).append('<i class="fa fa-hourglass-start"></i>');
					}
					$(this).removeClass('btn-default');
					$(this).addClass('btn-primary');
					$(this).append(' '+$('#currtime').text());
					cropstarts = (videoel[0].currentTime * 100 / 100).toFixed(3);
					cropstartms = cropstarts.split(".")
					cropstart = '00-'+$('#currtime').text().replace(":", "-")+'.'+cropstartms[1];
					cropstartt = '00-'+$('#currtime').text()+'.'+cropstartms[1];
					ccrops = true;
					console.log('crop endtime: '+cropstarts);
				});

				$('#btncend').click(function(event) {
					cropends = videoel[0].currentTime;
					if (ccrops) {
						if (cropends < cropstarts) {
							alert('O tempo final deve ser maior que o inicial!');
							$(this).text(null);
							$(this).append('<i class="fa fa-hourglass-end"></i>');
							$(this).removeClass('btn-primary');
							$(this).addClass('btn-default');
						} else {
							time = $(this).text();
							if (time != '') {
								$(this).text(null);
								$(this).append('<i class="fa fa-hourglass-end"></i>');
							}
							$('#btncrop').removeClass('disabled');
							$('#btncrop').removeAttr('disabled');
							// $('#btntran').removeClass('disabled');
							// $('#btntran').removeAttr('disabled');
							$(this).removeClass('btn-default');
							$(this).addClass('btn-primary');
							$(this).append(' '+$('#currtime').text());
							cropends = (videoel[0].currentTime * 100 / 100).toFixed(3);
							cropendms = cropends.split(".");
							cropendt = '00-'+$('#currtime').text()+'.'+cropendms[1];
							cropdurs = (cropends - cropstarts).toFixed(3);
							cropdurmm = ('0' + Math.floor(cropdurs / 60)).slice(-2);
							cropdurss = ('0' + Math.floor(cropdurs - cropdurmm * 60)).slice(-2);
							cropdur = '00-'+cropdurmm+'-'+cropdurss;
							ccrope = true;
							console.log('crop endtime: '+cropends);
						}
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
						console.log(cfilechann);

						cfilestate = cfilearr[3];
						cfilesource = "\"" + cfilechann + " - " + cfilestate + "\"";
						cfiledatetime = cfiledate + " " + cfiletime;
						cfilestimestamp = new Date(String(cfiledatetime));
						cfilesstimestamp = new Date(String(cfiledatetime));
						cfileetimestamp = new Date(String(cfiledatetime));
						cfiletimestampt = cfilestimestamp.getTime();
						cfiletstamp = cfileetimestamp.setMinutes(cfileetimestamp.getMinutes() + 10);
						// cropstartts = cropstarts - 20;
						// cropendts = cropends - 20;
						cropstartts = cropstarts;
						cropendts = cropends;
						cfiletstampst = cfilestimestamp.setSeconds(cfilestimestamp.getSeconds() + Number(cropstartts));
						cfiletstampet = cfilesstimestamp.setSeconds(cfilesstimestamp.getSeconds() + Number(cropendts));
						
						console.log('source: '+cfilesource);
						console.log('story startdate: '+cfiletimestampt);
						console.log('story enddate: '+cfiletstamp);
						console.log('word wordstart: '+cfiletstampst);
						console.log('word wordend: '+cfiletstampet);

						hstorydates = new Date(cfiletimestampt)
						hstorydatee = new Date(cfiletstamp)
						hwordtimes = new Date(cfiletstampst)
						hwordtimee = new Date(cfiletstampet)

						console.log('story startdate: '+hstorydates);
						console.log('story enddate: '+hstorydatee);
						console.log('word wordstart: '+hwordtimes);
						console.log('word wordend: '+hwordtimee);

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
								console.log(data);
								var textw = "";
								var wordbefore = "";
								$.each(data, function(index, val) {
									$.each(val.response.docs, function(indexd, vald) {
										starttimew = vald.starttime_l;
										endtimew = vald.endtime_l;
										// wordbefore = vdocs.response.docs[indexd - 1].word_s;
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

						if (joinvideos) {
							$.post('<?php echo base_url("pages/proxy")?>',
								{address: '<?php echo str_replace("sim.","video.",base_url("video/cropjoinvideos/"))?>' + cfile + '/' + cropstart + '/' + cropdurs},
								function(data, textStatus, xhr) {
									fileid = data.id;
									croptimestart = new Date();
									var rprogress = setInterval(function() {
											$.post('<?php echo base_url("pages/proxy")?>',
												{address: '<?php echo str_replace("sim.","video.",base_url("video/cropprogress/"))?>' + fileid + '/' + cropdurs},
												function(datac, textStatus, xhr) {
													crpercent = datac.percent;
													crpcircle = crpercent / 100;
													progresscbar.animate(crpcircle);

													if (crpercent >= 100) {
														clearInterval(rprogress);
														$('#progresscrop').css('display', 'none');
														$('#mdivvideo').css('display', 'block');

														videourlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/getcropvideo/' + data.cropfilename;
														videovurlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/verifycropvideo/' + data.cropfilename;
														$.post('/pages/proxy', {address: videovurlmcrop}, function(data, textStatus, xhr) {
															if (data == "OK") {
																videomel.attr({src: videourlmcrop});
																// videomel[0].play();
															}
														});

														filenamearr = data.cropfilename.split("_");
														datearr = filenamearr[1].split("-");
														cropfmonth = datearr[1];
														cropfday = datearr[2];
														cropfch = filenamearr[3];
														cropfst = filenamearr[4];
														$('#mbtnvdown').attr({href: videourlmcrop});
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
								{address: '<?php echo str_replace('sim.','video.',base_url('video/cropvideo/'))?>' + vsource + '_' + cfile + '/' + cropstart + '/' + cropdurs},
								function(data, textStatus, xhr) {
									fileid = data.id;
									croptimestart = new Date();
									var rprogress = setInterval(function() {
											$.post('<?php echo base_url("pages/proxy")?>',
												{address: '<?php echo str_replace("sim.","video.",base_url("video/cropprogress/"))?>' + fileid + '/' + cropdurs},
												function(datac, textStatus, xhr) {
													crpercent = datac.percent;
													crpcircle = crpercent / 100;
													progresscbar.animate(crpcircle);

													if (crpercent === 100) {
														clearInterval(rprogress);
														$('#progresscrop').css('display', 'none');
														$('#mdivvideo').css('display', 'block');

														videourlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/getcropvideo/' + data.cropfilename;
														videovurlmcrop = '<?php echo str_replace("sim.","video.",base_url())?>video/verifycropvideo/' + data.cropfilename;
														$.post('/pages/proxy', {address: videovurlmcrop}, function(data, textStatus, xhr) {
															if (data == "OK") {
																videomel.attr({	src: videourlmcrop});
																// videomel[0].play();
															}
														});

														filenamearr = data.cropfilename.split("_");
														datearr = filenamearr[1].split("-");
														cropfmonth = datearr[1];
														cropfday = datearr[2];
														cropfch = filenamearr[3];
														cropfst = filenamearr[4];
														$('#mbtnvdown').attr({href: videourlmcrop});
														croptimeend = new Date();
														croptimedifference = ((croptimeend.getTime() - croptimestart.getTime()) / 1200).toFixed(3);
														$('#cropvideoload').text("Tempo do corte: "+ croptimedifference + "s");
													}
												}
											);
									}, 1000);
								}
							);
						}
					}
				});

				$('.cropmodal').on('hide.bs.modal', function(event) {
					videomel[0].pause();
					videomel.removeAttr('src');
					$('#mdivvideo').css('display', 'none');
					$('#progresscrop').css('display', 'block');
					progresscbar.animate(0);
					cropstarts = null;
					cropends = null;
					ccrops = false;
					ccrope = false;
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
				});

				function joinfiles(cbid,jfilevsource,jfilename,jvbtn) {
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
						filestojoin.splice(fileindex,1);
						vbntindex = vbtnjoin.indexOf(jvbtn);
						vbtnjoin.splice(vbntindex,1);
						if (filestojoin.length < 2) {
							$('#btnjoin').addClass('disabled');
							$('#btnjoin').attr('disabled', true);
							joinvideos = false;
						}
					}
				}
			});
		</script>
	</body>
</html>