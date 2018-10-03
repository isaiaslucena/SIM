				//PLAYER
				videoel.click(function(event) {
					videoelid = event.target.id;
					vvideosrc = event.target.src;
					if (vvideosrc.length != 0) {
						playpausevideo(videoelid);
					}
				});

				videomel.click(function(event) {
					videoelid = event.target.id;
					vvideosrc = event.target.src;
					if (vvideosrc.length != 0) {
						playpausevideo(videoelid);
					}
				});

				videojcmel.click(function(event) {
					videoelid = event.target.id;
					vvideosrc = event.target.src;
					if (vvideosrc.length != 0) {
						playpausevideo(videoelid);
					}
				});

				videoel.dblclick(function(event) {
					videoelid = event.target.id;
					// videofulls = document.webkitFullscreenEnabled;
					// videofullsel = document.webkitFullscreenElement;
					vvideosrc = event.target.src;
					if (vvideosrc.length != 0) {
						vfullscreen(videoelid);
					}
				});

				videomel.dblclick(function(event) {
					videoelid = event.target.id;
					// videofulls = document.webkitFullscreenEnabled;
					vvideosrc = event.target.src;
					if (vvideosrc.length != 0) {
						vfullscreen(videoelid);
					}
				});

				videojcmel.dblclick(function(event) {
					videoelid = event.target.id;
					// videofulls = document.webkitFullscreenEnabled;
					vvideosrc = event.target.src;
					if (vvideosrc.length != 0) {
						vfullscreen(videoelid);
					}
				});

				$('.vbutton').click(function(event) {
					playpausevideo('vvideo');
				});

				$("#btnplay").click(function() {
					playpausevideo('vvideo');
				});

				$("#btnstop").click(function() {
					videoel[0].pause();
					videoel[0].currentTime = 0;
					$("#ipause").addClass('hidden');
					$("#iplay").removeClass('hidden');
				});

				$("#btnrn").click(function() {
					videoel[0].playbackRate=1;
				});

				$("#btnrs").click(function() {
					videoel[0].playbackRate-=0.1;
				});

				$("#btnrf").click(function() {
					videoel[0].playbackRate+=0.65;
				});

				$("#btnvol").click(function() {
					if (videoel[0].muted) {
						$("#btnvol").removeClass('btn-danger');
						$("#btnvol").addClass('btn-default');
						videoel[0].muted = false;
					} else {
						$("#btnvol").removeClass('btn-default');
						$("#btnvol").addClass('btn-danger');
						videoel[0].muted = true;
					}
				});

				$("#btnvolm").click(function() {
					videoel[0].volume-=0.5;
				});

				$("#btnvolp").click(function() {
					videoel[0].volume+=0.5;
				});

				$('#btnfull').click(function(event) {
					vfullscreen('vvideo');
				});

				videoel.on('loadedmetadata', function() {
					var vvideosrc = videoel[0].currentSrc;
					if (vvideosrc.match(vvideosrcsearch) == null) {
						var vduration = videoel[0].duration;

						vdurtime.text(sectostring(vduration));

						var nimage = [];
						if (joinvideosclk) {
							var vdfilename = videotitle.text();
							var srcarr = vdfilename.split("_");
							var srcfilename = srcarr[0];
							var channel = srcarr[6];
							if (channel != 'AVULSO') {
								if (srcfilename.replace(/[0-9]/g, '') != 'cagiva') {
									fjoinedquant = filesjoined.length;
									fjoinedcount = 0;
									$.each(filesjoined, function(index, file) {
										fjoinedcount++;
										maxthumb = file.time;
										vdfilename = file.file;
										for (thumbn = 1 ; thumbn <= maxthumb; thumbn++) {
											nthumbn = ("00" + thumbn).slice(-3);
											nimage[thumbn] = new Image();
											imgsrc = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+vdfilename+'/'+nthumbn;
											nimage[thumbn].src = imgsrc;

											if (fjoinedquant === fjoinedcount) {
												nimage[thumbn].onload = function(e) {
													if (navigator.vendor == 'Google Inc.') {
														loadedsrc = e.path[0].src;
													} else {
														loadedsrc = e.target.src;
													}

													urlload = window.location.origin;
													urlload = urlload.replace('sim.', 'video.');
													ldtmbnarr = loadedsrc.replace(urlload+'/video/getthumb/', '').split('/');
													ldtmbn = parseInt(ldtmbnarr[1]);
													if (ldtmbn === maxthumb) {
														closeloadingthumbs();
														videoel[0].play();
													}
												};

												nimage[thumbn].onerror = function(e) {
													if (navigator.vendor == 'Google Inc.') {
														loadedsrc = e.path[0].src;
													} else {
														loadedsrc = e.target.src;
													}

													urlload = window.location.origin;
													urlload = urlload.replace('sim.', 'video.');
													ldtmbnarr = loadedsrc.replace(urlload+'/video/getthumb/', '').split('/');
													ldtmbn = parseInt(ldtmbnarr[1]);
													if (ldtmbn === maxthumb) {
														closeloadingthumbs();
														videoel[0].play();
													}
												};
											}
										}
									});
								}
							}
						} else {
							var vdfilename = videotitle.text();
							var arr = vdfilename.split('_');
							var channel = arr[2];
							if (channel != 'AVULSO') {
								sfilename = $("span:contains('"+vdfilename+"')").data('vsrc');
								if (sfilename.replace(/[0-9]/g, '') != 'cagiva') {
									maxthumb = Math.floor(videoel[0].duration);
									for (thumbn = 1 ; thumbn <= maxthumb; thumbn++) {
									nthumbn = ("00" + thumbn).slice(-3);
									nimage[thumbn] = new Image();
									imgsrc = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + nthumbn;
									nimage[thumbn].src = imgsrc;
									nimage[thumbn].onload = function(e) {
										if (navigator.vendor == 'Google Inc.') {
											loadedsrc = e.path[0].src;
										} else {
											loadedsrc = e.target.src;
										}

										urlload = window.location.origin;
										urlload = urlload.replace('sim.', 'video.');
										ldtmbnarr = loadedsrc.replace(urlload+'/video/getthumb/', '').split('/');
										ldtmbn = parseInt(ldtmbnarr[1]);
										if (ldtmbn === maxthumb) {
											closeloadingthumbs();
											videoel[0].play();
										}
									};

									nimage[thumbn].onerror = function(e) {
										if (navigator.vendor == 'Google Inc.') {
											loadedsrc = e.path[0].src;
										} else {
											loadedsrc = e.target.src;
										}

										urlload = window.location.origin;
										urlload = urlload.replace('sim.', 'video.');
										ldtmbnarr = loadedsrc.replace(urlload+'/video/getthumb/', '').split('/');
										ldtmbn = parseInt(ldtmbnarr[1]);
										if (ldtmbn === maxthumb) {
											closeloadingthumbs();
											videoel[0].play();
										}
									};
									}
								}
								// clearInterval(loadthumbs);
							}
						}
					}
				});

				videoel.on('timeupdate', function() {
					if (vvideosrc.match(vvideosrcsearch) == null) {
						currentPos = videoel[0].currentTime;
						maxduration = videoel[0].duration;
						percentage = 100 * currentPos / maxduration;

						currentPosh = ('0' + Math.floor(currentPos / 60 / 60)).slice(-2);
						currentPosm = ('0' + Math.floor(currentPos - currentPosh * 60)).slice(-2);
						currentPoss = ('0' + Math.floor(currentPos - currentPosm * 60)).slice(-2);
						currentPossmss = (currentPos * 100 / 100).toFixed(3);
						currentPossms = currentPossmss.split(".");

						$('.timeBar').css('width', percentage+'%');
						vcurrtime.text(sectostring(currentPos));

						videoelBuffer();
						if (currentPos == maxduration) {
							cbautoplay = $('#checkaplay').prop('checked');
							if (cbautoplay) {
								videolist = $('.list-group').children();
								activevideo = videotitle.text();
								activevideol = $('.list-group-item.active');
								nactivevideoid = activevideol[0].nextElementSibling.id
								nextvideol = document.getElementById(nactivevideoid)
								nextvideoname = nextvideol.lastChild.innerText;
								nextvideosrc = nextvideol.lastChild.dataset.vsrc;

								videoel.attr({
									poster: '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+nextvideosrc+'_'+nextvideoname+'/001',
									src: '<?php echo str_replace("sim.", "video.", base_url())?>video/getvideo/' + nextvideosrc + '_' + nextvideoname
								});
								videotitle.text(nextvideoname);

								$('.list-group').children().removeClass('active');
								document.getElementById(nactivevideoid).className += ' active';
								// joinvideos = false;
							}
						}
					}
				});

				var timeDrag = false;
				$('.progressBar').mousedown(function(e) {
					timeDrag = true;

					if (joinvideos) {
						filenarr = filesjoined[0].file.split("_");
						vsourcefile = filenarr[0];
					} else {
						vfile = videotitle.text()
						vsourcefile = $("span:contains('"+vfile+"')").data('vsrc');
					}

					videoel[0].pause();
					if (vsourcefile.replace(/[0-9]/g, '') != 'cagiva') {
						videoel.css('display', 'none');
						videoelth.css('display', 'block');
					}

					$("#ipause").addClass('hidden');
					$("#iplay").removeClass('hidden');
					updatebar(e.pageX);
				});
				$(document).mouseup(function(e) {
					if (timeDrag) {
						vfile = videotitle.text()
						// vsourcefile = $("span:contains('"+vfile+"')").data('vsrc');
						vsourcefile = videotitle.attr('data-vsrc');
						//videoel[0].pause();
						if (vsourcefile.replace(/[0-9]/g, '') != 'cagiva') {
							videoelth.css('display', 'none');
							videoel.css('display', 'block');
						}
						timeDrag = false;
						$('.vbutton').css('dusplay', 'block');
						$('.vbutton').removeClass('paused');
						setTimeout(function() {$('.vbutton').fadeOut('fast')}, 1500);
						$("#iplay").addClass('hidden');
						$("#ipause").removeClass('hidden');
						updatebar(e.pageX);
						videoel[0].play();
					}
				});
				$(document).mousemove(function(e) {
					if (timeDrag) {
						updatebar(e.pageX);
					}
				});

				function updatebar(x) {
					maxduration = videoel[0].duration;
					position = x - progressbar.offset().left;
					percentage = (100 * position) / progressbar.width();

					if (percentage > 100) {
						percentage = 100;
					}
					if (percentage < 0) {
						percentage = 0;
					}

					videotime = (maxduration * percentage) / 100;
					videotimesec = Math.floor(videotime);
					videoel[0].currentTime = videotime.toFixed(3);
					thumbnum = ('00' + videotimesec).slice(-3);
					$('.timeBar').css('width', percentage+'%');

					if (joinvideos) {
						filenarr = filesjoined[0].file.split("_");
						thnsfilename = filenarr[0];
						if (thnsfilename.replace(/[0-9]/g, '') != 'cagiva') {
							var ttime = 0;
							var thumbnnf;
							var thnvdfilename;

							$.each(filesjoined, function(index, filer) {
								ttime = ttime + filer.time;
								if (videotimesec <= ttime) {
									timedif = ttime - videotimesec;
									thumbnnf = ('00' + (filer.time - timedif)).slice(-3);
									thnvdfilename = filer.file.replace(thnsfilename+"_","");
									uptadevThumb(thnsfilename, thnvdfilename, thumbnnf);
									return false;
								}
							});
						}
					} else {
						vdfilename = videotitle.text();
						sfilename = $("span:contains('"+vdfilename+"')").data('vsrc');
						if (sfilename.replace(/[0-9]/g, '') != 'cagiva') {
							uptadevThumb(sfilename, vdfilename, thumbnum);
						}
					}
				};

				function updatebarkeyb(sec) {
					var maxduration = videoel[0].duration;
					// var position = sec - time.offset().left;
					var position = sec;
					var percentage = (position * 100) / maxduration;

					if (percentage > 100) {
						percentage = 100;
					}
					if (percentage < 0) {
						percentage = 0;
					}

					// vdfilename = videotitle.text();
					// sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
					// videotime = (maxduration * percentage) / 100;
					videotime = sec;
					videotimesec = Math.floor(videotime);
					thumbnum = ('00' + videotimesec).slice(-3)
					videoel[0].currentTime = videotime.toFixed(3);

					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// videotime = ((maxduration * percentage) / 100).toFixed(3);
					// currentPosh = ('0' + Math.floor(videotime / 60 / 60)).slice(-2);
					// // currentPosm = ('0' + Math.floor(videotime / 60)).slice(-2);
					// currentPosm = ('0' + Math.floor(videotime - currentPosh * 60)).slice(-2);
					// currentPoss = ('0' + Math.floor(videotime - currentPosm * 60)).slice(-2);
					// currentPossmss = (videotime * 100 / 100).toFixed(3);
					// currentPossms = currentPossmss.split(".");

					vcurrtime.text(sectostring(videotime));
					// vcurrtime.text(currentPosm+':'+currentPoss+'.'+currentPossms[1]);

					$('.timeBar').css('width', percentage+'%');
					videoelBuffer();
					// videoelth.attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + thumbnum);
					// uptadevThumb(sfilename, vdfilename, thumbnum);

					if (joinvideos) {
						filenarr = filesjoined[0].file.split("_");
						thnsfilename = filenarr[0];

						var ttime = 0;
						var thumbnnf;
						var thnvdfilename;

						$.each(filesjoined, function(index, filer) {
							ttime = ttime + filer.time;
							if (videotimesec <= ttime) {
								timedif = ttime - videotimesec;
								thumbnnf = ('00' + (filer.time - timedif)).slice(-3);
								thnvdfilename = filer.file.replace(thnsfilename+"_","");
								uptadevThumb(thnsfilename, thnvdfilename, thumbnnf);
								return false;
							}
						});
					} else {
						vdfilename = videotitle.text();
						sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
						uptadevThumb(sfilename, vdfilename, thumbnum);
					}
				};

				function uptadevThumb(utsfilename, utvdfilename, utthumbnum) {
					videoelth.attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + utsfilename +'_'+ utvdfilename + '/' + utthumbnum);
				}

				function updateTimetooltip(x) {
					vdfilename = videotitle.text();
					sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
					var maxduration = videoel[0].duration;
					var position = x - progressbar.offset().left;
					var percentage = (100 * position) / progressbar.width();

					if (percentage > 100) {
						percentage = 100;
					}
					if (percentage < 0) {
						percentage = 0;
					}

					videotime = ((maxduration * percentage) / 100).toFixed(3);
					videotimesec = Math.floor(videotime);
					// currentPosh = ('0' + Math.floor(maxduration / 60 / 60)).slice(-2);
					// // currentPosm = ('0' + Math.floor(videotime / 60)).slice(-2);
					// currentPosm = ('0' + Math.floor(maxduration - currentPosh * 60)).slice(-2);
					// currentPoss = ('0' + Math.floor(maxduration - currentPosm * 60)).slice(-2);
					// currentPossmss = (maxduration * 100 / 100).toFixed(3);
					// currentPossms = currentPossmss.split(".");
					thumbnum = ('0' + videotimesec).slice(-3)

					// $('#vthumb').attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + thumbnum);
					//videoelth.attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + thumbnum);
					// vtooltiptime.text(currentPosh+':'+currentPosm+':'+currentPoss+'.'+currentPossms[1]);
					vtooltiptime.text(sectostring(videotime));
					videoelBuffer();
				};

				progressbar.hover(function(event) {
					$('.tooltiptime').fadeIn("fast");
				}, function() {
					$('.tooltiptime').fadeOut("fast");
				})
				.mousemove(function(event) {
					var barHeight = progressbar.height();
					var barPosition = progressbar.position();
					var barPositionoff = progressbar.offset();
					var maxduration = videoel[0].duration;
					// thumbleft = event.pageX - 106;
					// thumbtop = barPositionoff.top - 155;
					ttimeleft = event.pageX - 52;
					ttimetop = barPositionoff.top - barHeight + 10;
					$('.tooltiptime').css({
						'top': ttimetop+"px",
						'left':  ttimeleft + "px"
					});
					updateTimetooltip(event.pageX);
				});

				$(document).keypress(function(event) {
					if (event.which == 32) {
						playpausevideo('vvideo');
					}
				});

				$(document).keydown(function(event) {
					if(event.ctrlKey && event.which == 37) {
						// console.log("Control+left pressed!");
						// videoel[0].currentTime-=1;
						seektime = videoel[0].currentTime-1;
						// seektime = videoel[0].currentTime-0.04;
						videoel[0].currentTime = seektime;
						videoel.css('display', 'none');
						videoelth.css('display', 'block');
						$("#ipause").addClass('hidden');
						$("#iplay").removeClass('hidden');
						updatebarkeyb(seektime);
					} else if(event.ctrlKey && event.which == 39){
						// console.log("Control+right pressed!");
						seektime = videoel[0].currentTime+1;
						videoel[0].currentTime = seektime;
						videoel.css('display', 'none');
						videoelth.css('display', 'block');
						$("#ipause").addClass('hidden');
						$("#iplay").removeClass('hidden');
						updatebarkeyb(seektime);
					} else if (event.which == 37) {
						// console.log("Left pressed!");
						// videoel[0].currentTime-=1;
						// seektime = videoel[0].currentTime-1;
						seektime = videoel[0].currentTime-0.04;
						videoel[0].currentTime = seektime;
						// videoel.css('display', 'none');
						// videoelth.css('display', 'block');
						// $("#ipause").addClass('hidden');
						// $("#iplay").removeClass('hidden');
						updatebarkeyb(seektime);
					} else if (event.which == 39) {
						// console.log("Right pressed!");
						// videoel[0].currentTime+=1;
						// seektime = videoel[0].currentTime+1;
						seektime = videoel[0].currentTime+0.04;
						videoel[0].currentTime = seektime;
						// videoel.css('display', 'none');
						// videoelth.css('display', 'block');
						// $("#ipause").addClass('hidden');
						// $("#iplay").removeClass('hidden');
						updatebarkeyb(seektime);
					}
				});

				$(document).keyup(function(event) {
					if (event.ctrlKey && event.which == 37 || event.ctrlKey && event.which == 39) {
						videoelth.css('display', 'none');
						videoel.css('display', 'block');
						// videoel[0].play();
						$("#iplay").addClass('hidden');
						$("#ipause").removeClass('hidden');
					}
				});

				function sectostring(secs) {
					var sec_num = parseInt(secs, 10);
					var hours   = Math.floor(sec_num / 3600);
					// var hours = (sec_num / 3600).toFixed(2);
					var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
					// var minutes = ((sec_num - (hours * 3600)) / 60).toFixed(2);
					var seconds = sec_num - (hours * 3600) - (minutes * 60);
					var mseconds = String(secs);
					var milliseconds =  mseconds.slice(-3);

					if (hours  < 10) {hours = "0" + hours;}
					if (minutes < 10) {minutes = "0" + minutes;}
					if (seconds < 10) {seconds = "0" + seconds;}
					return hours+':'+minutes+':'+seconds+'.'+milliseconds;
				}

				function videoelBuffer() {
					// var startBuffer = videoel[0].buffered.start(0);
					if (videoel[0].buffered.length > 0) {
						var maxduration = videoel[0].duration;
						var startBuffer = videoel[0].buffered.start(0);
						var endBuffer = videoel[0].buffered.end(0);
						// var percentageBuffer = (100 * endBuffer) / maxduration;
						var percentageBuffer = (endBuffer / maxduration) * 100;
						$('.bufferBar').css('width', percentageBuffer+'%');

						// if (endBuffer < maxduration) {
						// 	// setTimeout(startBuffer(), 500);
							// setTimeout(videoelBuffer(),500);
						// }
					}
				};

				function playpausevideo(videoelt) {
					vvideoelmt = $('#'+videoelt);
					if (vvideoelmt[0].paused) {
						$("#iplay").addClass('hidden');
						$("#ipause").removeClass('hidden');
						$('.vbutton').removeClass('paused');
						$('.vbutton').css('display', 'block');
						setTimeout(function() {$('.vbutton').fadeOut('fast')}, 1500);
						vvideoelmt[0].play();
					} else {
						$("#ipause").addClass('hidden');
						$("#iplay").removeClass('hidden');
						$('.vbutton').addClass('paused');
						$('.vbutton').css('display', 'block');
						vvideoelmt[0].pause();
					}
				}

				function vfullscreen(videoelt) {
					var elem = document.getElementById(videoelt);
					// var elem = document.querySelector(videoelt);
					if (elem.requestFullscreen) {
						elem.requestFullscreen();
					} else if (elem.msRequestFullscreen) {
						elem.msRequestFullscreen();
					} else if (elem.mozRequestFullScreen) {
						if (document.fullscreenElement) {
							elem.exitFullscreen();
						} else {
							elem.mozRequestFullScreen();
						}
					} else if (elem.webkitRequestFullscreen) {
						if (document.webkitFullscreenElement) {
							document.webkitExitFullscreen();
						} else {
							elem.webkitRequestFullscreen();
						}
					}
				}

				$('.list-group').click(function(event) {
					cfileid = event.target.id;
					elclick = event.target.tagName;
					aid = $(event.target).attr('data-aid');
					disclass = $('#'+aid).hasClass('disabled');
					if (disclass == false) {
						if (elclick == "SPAN" || elclick == "H4") {
							joinvideosclk = false;
							cfilename = event.target.innerText;
							cfilevsource = event.target.dataset.vsrc;

							$('.vbutton').css('display', 'none');
							$('.vbutton').removeClass('paused');

							videoel.attr({
								poster: '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+cfilevsource+'_'+cfilename+'/001',
								src: '<?php echo str_replace("sim.", "video.", base_url())?>video/getvideo/' + cfilevsource + '_' + cfilename
							});

							arr = lastvideo.split('_');
							channel = arr[2];
							if (channel != 'AVULSO') {
								if (cfilevsource.replace(/[0-9]/g, '') != 'cagiva') {
									videoel[0].pause();

									loadingthumbs();
								} else {
									videoel[0].play();
								}
							} else {
								videoel[0].play();
							}

							videotitle.text(cfilename);
							videotitle.attr('data-vsrc', cfilevsource);
							videotitle.css('font-size', '30px');
							mobileconf();
							$('.list-group').children().removeClass('active');
							event.target.parentElement.className += ' active';
							joinvideos = false;
						} else if (elclick == "INPUT") {
							ccbjoincrop = $('#checkjoincrop').prop('checked');
							if (ccbjoincrop) {
								swal({
									title: 'Escolha somente uma opção!',
									closeOnClickOutside: false,
									closeOnEsc: false,
									buttons: {
										cancel: false,
										confirm: true,
									}
								});
								$('#'+cfileid).prop('checked', false);
								$('#checkjoincrop').bootstrapToggle('off');
							} else {
								cvbtnid = $(this).parent().attr('id');
								cfilenamei = event.target.dataset.vfile;
								cfilevsourcei = event.target.dataset.vsrc;
								vfilenamei = cfilenamei+'.mp4';

								joinfiles(cfileid, cfilevsourcei, vfilenamei, cvbtnid);
								// console.log(joinvideos);
							}
						}
					}
				});

				function loadingthumbs() {
					$('body').css('cursor', 'progress');

					swal({
						onOpen: () => {
							swal.showLoading()
						},
						title: "Carregando imagens...",
						animation: true,
						allowEscapeKey: false,
						allowOutsideClick: false,
						showCancelButton: false,
						showConfirmButton: false,
					});
				}

				function closeloadingthumbs() {
					$('body').css('cursor', 'default');
					$('#vnext').scrollTo('.active');

					swal.close();
				}

				$('#checkjoincrop').change(function() {
					joinchkbx = $('.list-group input:checked').length > 0;
					cgcbjoincrop = $('#checkjoincrop').prop('checked');
					if (joinchkbx) {
						swal({
							title: 'Escolha somente uma opção!',
							closeOnClickOutside: false,
							closeOnEsc: false,
							buttons: {
								cancel: false,
								confirm: true,
							}
						});
						$('#checkjoincrop').bootstrapToggle('off');
						$('input').prop("checked", false);
					} else if (!cgcbjoincrop) {
						joincropvideos = false;
						cropfilestojoin = [];
					} else if (cgcbjoincrop) {
						swal({
							title: 'Atenção!',
							text: 'A partir de agora os cortes serão armazenados.',
							buttons: {
								cancel: false,
								confirm: true,
							}
						});
					}
				})
