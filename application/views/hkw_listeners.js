var totalpanels, pstart, pcstart,
scmedia = '<?php echo $msc;?>', mediatype = '<?php echo $mtype;?>',
pagesrc = '<?php echo isset($pagesrc) ? $pagesrc : "null";?>',
id_source = '<?php echo isset($id_source) ? $id_source : 0;?>',
newdivid = 0, cksource = 0, totalpanelsd = 0,
joinfiles = false, sckeypress = false, filestojoin = [];
keyword = '<?php echo $keyword; ?>';
keywordarr = keyword.split(" ");
keywcount = keywordarr.length - 1;
rgx = new RegExp('\\b'+keyword+'\\b', 'ig');

<?php if (!is_null($pagesrc)) { ?>
$(document).ready(function() {
	totalpanels = $('div.panel.panel-default.collapse.in').length;

	scrolltokeyword(mediatype);

	if (scmedia == 'local' && mediatype == 'audio') {
		var sposturl = window.location.origin+'/pages/get_radio_keyword_texts';
	} else if (scmedia == 'novo' && mediatype == 'audio') {
		var sposturl = window.location.origin+'/pages/get_radio_novo_keyword_texts';
	} else if (scmedia == 'local' && mediatype == 'video') {
		var sposturl = window.location.origin+'/pages/get_tv_keyword_texts';
	} else if (scmedia == 'novo' && mediatype == 'video') {
		var sposturl = window.location.origin+'/pages/get_tv_novo_keyword_texts';
	} else {
		var sposturl = 'NO URL';
		console.log(sposturl);
	}

	pstart = <?php echo $start;?>;
	pcstart = <?php echo $rows;?>;
	pfound = <?php echo $ktfound;?>;
	$(window).scroll(function() {
		winscrollToph = ($(window).scrollTop() + $(window).height());
		winheight = $(document).height();
		if (winscrollToph == winheight) {
			pstart = pstart + pcstart;
			if (pstart < pfound) {
				$('#loadmore').animate({'opacity': 100}, 600);
				$.post(sposturl,
					{
						'id_keyword': <?php echo $id_keyword;?>,
						'id_client': <?php echo $id_client;?>,
						'keyword': '<?php echo $keyword;?>',
						'client_selected': '<?php echo $client_selected;?>',
						'id_source': id_source,
						'startdate': '<?php echo $startdate;?>',
						'enddate': '<?php echo $enddate;?>',
						'start': pstart,
						'rows': <?php echo $rows;?>,
						'pagesrc': pagesrc,
						'msc': scmedia,
						'mtype': mediatype
					},
					function(data, textStatus, xhr) {
						$('#loadmore').before(data);
						totalpanels = $('div.panel.panel-default.collapse.in').length;
						scrolltokeyword(mediatype);
						$('#loadmore').animate({'opacity': 0}, 600);

						loadedmedia();
				});
			}
		}
	});
});

loadedmedia();

$(document).on('click', 'audio, video', function() {
	idpmedia = $(this).attr('id');
	ptextid = 'ptext'+idpmedia.replace(/[a-zA-Z]/g, '');
	ptextspans = $('#'+ptextid).children('span.fkword');

	if ($(this)[0].paused) {
		console.log('media is paused');
		if (ptextspans.length == 0) {
			ptextspans = $('#'+ptextid).children('span');
		}

		spantime = $(ptextspans[0]).attr('data-begin');
		startread(idpmedia, ptextid, spantime, true);
	} else {
		console.log('media is playing');
	}
});
<?php } ?>

if ($('#back-to-top').length) {
	var scrollTrigger = 1000;
	backToTop();
	$(window).on('scroll', function() {
		backToTop();
	})
	$('#back-to-top').on('click', function (e) {
		e.preventDefault();
		$('html,body').animate({scrollTop: 0}, 700);
	})
};

function loadedmedia() {
	$('audio, video').on('loadedmetadata', function() {
		mediaid = $(this).attr('id');
		fkwtime = $(this).attr('data-fkwtime');
		sc = $(this).attr('data-sc');
		type = $(this).attr('data-type');
		console.log('loaded metada of media '+mediaid);
		mediael = document.getElementById(mediaid);
		if (fkwtime) {
			if (mediael.readyState === 4){
				mediael.currentTime = fkwtime;
			} else {
				setTimeout(function() {
					mediael.currentTime = fkwtime;
				},500)
			}
		} else {
			if (sc == 'local' && type == 'video') {
				vsrc = mediael.src;
				vsrcarr = vsrc.split('/');
				mediael.poster = (window.location.origin).replace('sim','video')+'/video/getthumb/'+vsrcarr[5]+'/001'
			}
		}
	});
};

$(document).on('click', '.loadprevious', function(event) {
	var sc = $(this).attr('data-sc');
	var type = $(this).attr('data-type');
	loadpn('previous', $(this), sc, type);
});

$(document).on('click', '.loadnext', function(event) {
	var sc = $(this).attr('data-sc');
	var type = $(this).attr('data-type');
	loadpn('next', $(this), sc, type);
});

$(document).on('click', '.cbjoinfiles', function(event) {
	ciddoc = $(this).attr('data-iddoc');
	cidsource = $(this).attr('data-idsource');
	csource = $(this).attr('data-source');
	cstartdate = $(this).attr('data-startdate');
	cenddate = $(this).attr('data-enddate');
	cidclient = $(this).attr('data-idclient');
	cidkeyword = $(this).attr('data-idkeyword');

	checked = event.target.checked;
	if (checked) {
		if (cidsource == cksource || cksource == 0) {
			$('#wsource').text(csource);
			$('#fileslist').append(
				'<a id="acb'+ciddoc+'" class="list-group-item">'+
					cstartdate + ' - '+ cenddate +
				'</a>'
			);
			filestojoin.push(ciddoc);
			console.log(filestojoin);
			$('#joindiv').fadeIn('fast');
			cksource = cidsource;
			if (filestojoin.length >= 2) {
				$('#joinbtn').attr({
					'data-idclient': cidclient,
					'data-idkeyword': cidkeyword
				});
				$('#joinbtn').removeClass('disabled');
				$('#joinbtn').removeAttr('disabled');
				joinfiles = true;
			}
		} else {
			swal("Atenção!", "Os veículos devem ser iguais!", "error");
			$(this).prop("checked", false);
			$('#acb'+ciddoc).detach();
			cksource = 0;
		}
	} else {
		fileindex = filestojoin.indexOf(ciddoc);
		filestojoin.splice(fileindex,1);
		console.log(filestojoin);
		$('#acb'+ciddoc).detach();
		if (filestojoin.length == 1) {
			$('#joinbtn').addClass('disabled');
			$('#joinbtn').attr('disabled', true);
			joinfiles = false;
		} else if (filestojoin.length == 0) {
			$('#joinbtn').addClass('disabled');
			$('#joinbtn').attr('disabled', true);
			$('#fileslist').empty();
			$('#joindiv').fadeOut('fast');
			joinfiles = false;
		}
	}
});

$(document).on('click', '#joinbtn', function(event) {
	jbtn = $(this);
	jidclient = jbtn.attr('data-idclient');
	jidkeyword = jbtn.attr('data-idkeyword');

	$('#jids_doc').val(filestojoin);
	$('#jid_client').val(jidclient);
	$('#jid_keyword').val(jidkeyword);

	if (joinfiles) {
		$('#joindiv').fadeOut('fast');
		$('#joinbtn').addClass('disabled');
		$('#joinbtn').attr('disabled', true);
		$('#fileslist').empty();
		$('input[type="checkbox"]').prop("checked", false);
		$('.panel-info').detach();
		filestojoin = [];
		joinfiles = false;
		cksource = 0;
		// swal("Aguarde...");
		document.getElementById('joinform').submit();
	}
});

$(document).on('click', '.discarddoc', function(event) {
	btndiscardc = true;
	discardbtn = $(this);
	discardbtn.children('i').css('display', 'inline-block');

	iddoc = discardbtn.attr('data-iddoc');
	iddiv = discardbtn.attr('data-iddiv');
	idkeyword = discardbtn.attr('data-idkeyword');
	idclient = discardbtn.attr('data-idclient');
	mediasc = discardbtn.attr('data-sc');
	mediatype = discardbtn.attr('data-type');
	iduser = '<?php echo $this->session->userdata("id_user");?>';

	// if (iddoc.length > 0) {
		if (mediatype == 'audio') {
			mediaid = 'paudio'+iddiv.replace(/[a-zA-Z]/g, '');
			$('#'+mediaid)[0].pause();
		} else if (mediatype == 'video') {
			mediaid = 'pvideo'+iddiv.replace(/[a-zA-Z]/g, '');
			$('#'+mediaid)[0].pause();
		}

		if (mediasc == 'local' && mediatype == 'audio') {
			var dposturl = window.location.origin+'/pages/discard_doc_radio';
		} else if (mediasc == 'novo' && mediatype == 'audio') {
			var dposturl = window.location.origin+'/pages/discard_doc_radio_novo';
		} else if (mediasc == 'local' && mediatype == 'video') {
			var dposturl = window.location.origin+'/pages/discard_doc_tv';
		} else if (mediasc == 'novo' && mediatype == 'video') {
			var dposturl = window.location.origin+'/pages/discard_doc_tv_novo';
		} else {
			var dposturl = 'NO URL';
			console.log(dposturl);
		}

		$.post(dposturl,
			{
				'iddoc': iddoc,
				'idkeyword': idkeyword,
				'idclient': idclient,
				'iduser': iduser
			},
			function(data, textStatus, xhr) {
				discardbtn.children('i').css('display', 'none');
				$('#'+iddiv).removeClass('panel-default');
				$('#'+iddiv).addClass('panel-danger');
				$('#'+iddiv).slideUp('slow');
				// $('#'+iddiv).removeClass('in');
				// $('#'+iddiv).animate({'opacity': 0}, 'slow');

				totalpanelsd += 1;
				if (totalpanelsd == totalpanels) {
					console.log('no more panels!');
					window.location = '<?php echo base_url("pages/index_radio")?>';
				}
			}
		);
	// }
});

$(document).on('click', '.desativado', function() {
	$(this).css('overflowY', 'auto');
});

$(document).on('click', 'span[data-dur]', function(){
	ptextid = $(this).parent('.ptext').attr('id');
	if (mediatype == 'audio') {
		imedia = 'paudio';
	} else {
		imedia = 'pvideo';
	}
	pmedia = imedia+ptextid.replace(/[a-zA-Z]/g, '');
	spantime = $(this).attr('data-begin');

	startread(pmedia, ptextid, spantime, true);
	$('#'+pmedia)[0].play();
});

$(document).on('mouseover', '.ptext', function() {
	if (sckeypress) {
		$(this).css('overflow-y', 'auto');
	} else {
		$(this).css('overflow-y', 'hidden');
	}
});

$(document).on('mouseleave', '.ptext', function() {
	$(this).css('overflow-y', 'hidden');
});

$(document).keydown(function(event) {
	if(event.altKey) {
		sckeypress = true;
	}
});

$(document).keyup(function(event) {
	sckeypress = false;
});

// $(document).on('mouseleave', '.panel.panel-default.collapse.in', function() {
// 	ptextid = $(this).attr('id');
// 	pmedia = 'paudio'+ptextid.replace(/[a-zA-Z]/g, '');

// 	$('#'+pmedia)[0].pause();
// });