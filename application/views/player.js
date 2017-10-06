				//PLAYER
				videoel.click(function() {
					vvideosrc = videoel[0].currentSrc;
					if (vvideosrc.match(vvideosrcsearch) == null) {
						if (videoel[0].paused) {
							$("#iplay").addClass('hidden');
							$("#ipause").removeClass('hidden');
							videoel[0].play();
						} else {
							$("#ipause").addClass('hidden');
							$("#iplay").removeClass('hidden');
							videoel[0].pause();
						}
					}
				});

				$("#btnplay").click(function() {
					if (videoel[0].paused) {
						$("#iplay").addClass('hidden');
						$("#ipause").removeClass('hidden');
						videoel[0].play();
					} else {
						$("#ipause").addClass('hidden');
						$("#iplay").removeClass('hidden');
						videoel[0].pause();
					}
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
					if (videoel[0].requestFullscreen) {
						videoel[0].requestFullscreen();
					} else if (videoel[0].mozRequestFullScreen) {
						videoel[0].mozRequestFullScreen();
					} else if (videoel[0].webkitRequestFullscreen) {
						videoel[0].webkitRequestFullscreen();
					}
				});

				videoel.on('loadedmetadata', function() {
					vvideosrc = videoel[0].currentSrc;
					if (vvideosrc.match(vvideosrcsearch) == null) {
						vdfilename = videotitle.text();
						sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
						vduration = videoel[0].duration;
						durationm = ('0' + Math.floor(vduration / 60)).slice(-2);
						durations = ('0' + Math.floor(vduration - durationm * 60)).slice(-2);
						vdurtime.text(durationm+':'+durations);
						maxthumb = Math.floor(videoel[0].duration);

						nimage = [];
						for (thumbn = 1 ; thumbn <= maxthumb; thumbn++) {
							nthumbn = ("00" + thumbn).slice(-3);
							nimage[thumbn] = new Image();
							imgsrc = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + nthumbn;
							nimage[thumbn].src = imgsrc;
						}
					}
				});

				videoel.on('timeupdate', function() {
					if (vvideosrc.match(vvideosrcsearch) == null) {
						var currentPos = videoel[0].currentTime;
						var maxduration = videoel[0].duration;
						var percentage = 100 * currentPos / maxduration;
						var currentPosm = ('0' + Math.floor(currentPos / 60)).slice(-2);
						var currentPoss = ('0' + Math.floor(currentPos - currentPosm * 60)).slice(-2);
						$('.timeBar').css('width', percentage+'%');
						vcurrtime.text(currentPosm+':'+currentPoss);
						//$('.tooltiptext').text(currentPosm+':'+currentPoss);
						videoelBuffer();
						if (currentPos == maxduration) {
							cbautoplay = $('#checkaplay').prop('checked');
							if (cbautoplay) {
								videolist = $('.list-group').children();
								activevideo = videotitle.text();
								activevideol = $('.list-group-item.active');
								// nactivevideoid = activevideol[0].nextElementSibling.lastChild.id
								nactivevideoid = activevideol[0].nextElementSibling.id
								// nextvideol = ($('#'+nactivevideoid);
								nextvideol = document.getElementById(nactivevideoid)
								// nextvideol = nactivevideoid[0].lastChild.innerText;
								nextvideoname = nextvideol.lastChild.innerText;
								nextvideosrc = nextvideol.lastChild.dataset.vsrc;
								videoel.attr({
									poster: '<?php echo base_url('assets/imgs/videoloading.gif')?>',
									src: '<?php echo str_replace("sim.", "video.", base_url())?>video/getvideo/' + nextvideosrc + '_' + nextvideoname
								});
								videotitle.text(nextvideoname);
								$('.list-group').children().removeClass('active');
								document.getElementById(nactivevideoid).className += ' active';
								joinvideos = false;
							}
						}
					}
				});

				var timeDrag = false;
				$('.progressBar').mousedown(function(e) {
					timeDrag = true;
					vfile = videotitle.text()
					vsourcefile = $( "span:contains('"+vfile+"')" ).data('vsrc');
					videoel[0].pause();
					if (vsourcefile != 'dvr00') {
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
						vsourcefile = $( "span:contains('"+vfile+"')" ).data('vsrc');
						videoel[0].pause();
						if (vsourcefile != 'dvr00') {
							videoelth.css('display', 'none');
							videoel.css('display', 'block');
						}
						timeDrag = false;
						videoel[0].play();
						$("#iplay").addClass('hidden');
						$("#ipause").removeClass('hidden');
						updatebar(e.pageX);
					}
				});
				$('.progressBar').mousemove(function(e) {
					if (timeDrag) {
						updatebar(e.pageX);
					}
				});

				function updatebar(x) {
					var maxduration = videoel[0].duration;
					var position = x - progressbar.offset().left;
					var percentage = (100 * position) / progressbar.width();

					if (percentage > 100) {
						percentage = 100;
					}
					if (percentage < 0) {
						percentage = 0;
					}

					vdfilename = videotitle.text();
					sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
					videotime = maxduration * percentage / 100;
					videotimesec = Math.floor(videotime);
					thumbnum = ('00' + videotimesec).slice(-3)
					videoel[0].currentTime = videotime.toFixed(3);

					$('.timeBar').css('width', percentage+'%');
					// videoelth.attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + thumbnum);
					uptadevThumb(sfilename, vdfilename, thumbnum);
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

					vdfilename = videotitle.text();
					sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
					// videotime = (maxduration * percentage) / 100;
					videotime = sec;
					videotimesec = Math.floor(videotime);
					thumbnum = ('00' + videotimesec).slice(-3)
					videoel[0].currentTime = videotime.toFixed(3);

					$('.timeBar').css('width', percentage+'%');
					// videoelth.attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + thumbnum);
					uptadevThumb(sfilename, vdfilename, thumbnum);
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
					currentPosm = ('0' + Math.floor(videotime / 60)).slice(-2);
					currentPoss = ('0' + Math.floor(videotime - currentPosm * 60)).slice(-2);
					thumbnum = ('0' + videotimesec).slice(-3)

					// $('#vthumb').attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + thumbnum);
					//videoelth.attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/' + sfilename +'_'+ vdfilename + '/' + thumbnum);
					$('.tooltiptime').text(currentPosm+':'+currentPoss);
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
					ttimeleft = event.pageX - 20;
					ttimetop = barPositionoff.top - barHeight + 10;
					$('.tooltiptime').css({
						'top': ttimetop+"px",
						'left':  ttimeleft + "px"
					});
					updateTimetooltip(event.pageX);
				});

				$(document).keypress(function(event) {
					if (event.which == 32) {
						if (videoel[0].paused) {
							$("#iplay").addClass('hidden');
							$("#ipause").removeClass('hidden');
							videoel[0].play();
						} else {
							$("#ipause").addClass('hidden');
							$("#iplay").removeClass('hidden');
							videoel[0].pause();
						}
					}
				});

				$(document).keydown(function(event) {
					// console.log(event);
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
						// videoel[0].currentTime = seektime;
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

				function videoelBuffer() {
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
				};

				$('.list-group').click(function(event) {
					cfileid = event.target.id;
					elclick = event.target.tagName;
					if (elclick == "SPAN") {
						cfilename = event.target.innerText;
						cfilevsource = event.target.dataset.vsrc;
						videoel.attr({
							poster: '<?php echo base_url('assets/imgs/videoloading.gif')?>',
							src: '<?php echo str_replace("sim.", "video.", base_url())?>video/getvideo/' + cfilevsource + '_' + cfilename
						});
						videotitle.text(cfilename);
						$('.list-group').children().removeClass('active');
						event.target.parentElement.className += ' active';
						joinvideos = false;
					} else if (elclick == "INPUT") {
						cvbtnid = event.originalEvent.path[2].id;
						cfilenamei = event.target.dataset.vfile;
						cfilevsourcei = event.target.dataset.vsrc;
						// vfilenamei = cfilevsourcei+'_'+cfilenamei;
						vfilenamei = cfilenamei+'.mp4';
						joinfiles(cfileid,cfilevsourcei,vfilenamei,cvbtnid);
					}
				});