var totalpanels, pstart, pcstart,
scmedia = '<?php echo $msc;?>', mediatype = '<?php echo $mtype;?>',
newdivid = 0, cksource = 0, totalpanelsd = 0,
joinfiles = false, filestojoin = [];

$(document).ready(function() {
	keyword = '<?php echo $keyword_selected; ?>';
	keywordarr = keyword.split(" ");
	keywcount = keywordarr.length - 1;
	rgx = new RegExp('\\b'+keyword+'\\b', 'ig');

	totalpanels = $('div.panel.panel-default.collapse.in').length;

	scrolltokeyword(mediatype);

	if (scmedia == 'local' && mediatype == 'audio') {
		posturl = 'get_radio_keyword_texts';
	} else if (scmedia == 'novo' && mediatype == 'audio') {
		posturl = 'get_radio_novo_keyword_texts';
	} else if (scmedia == 'local' && mediatype == 'video') {
		posturl = 'get_tv_keyword_texts';
	} else if (scmedia == 'novo' && mediatype == 'video') {
		posturl = 'get_tv_novo_keyword_texts';
	}

	pstart = <?php echo $start;?>;
	pcstart = <?php echo $rows;?>;
	pfound = <?php echo $ktfound;?>;
	$(window).scroll(function() {
		winscrollToph = ($(window).scrollTop() + $(window).height());
		winheight = $(document).height();
		if (winscrollToph == winheight) {
			pstart = pstart + pcstart;
			if (pstart <= pfound) {
				$('#loadmore').animate({'opacity': 100}, 600);
				$.post(posturl,
					{
						'id_keyword': <?php echo $id_keyword;?>,
						'id_client': <?php echo $id_client;?>,
						'keyword_selected': '<?php echo $keyword_selected;?>',
						'client_selected': '<?php echo $client_selected;?>',
						'startdate': '<?php echo $startdate;?>',
						'enddate': '<?php echo $enddate;?>',
						'start': pstart,
						'rows': <?php echo $rows;?>
					},
					function(data, textStatus, xhr) {
						$('#loadmore').before(data);
						totalpanels = $('div.panel.panel-default.collapse.in').length;
						scrolltokeyword(mediatype);
						$('#loadmore').animate({'opacity': 0}, 600);
				});
			}
		}
	});
});

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

$(document).on('click', '.loadprevious', function(event) {
	sc = $(this).attr('data-sc');
	type = $(this).attr('data-type');
	loadpn('previous', $(this), sc, type);
});

$(document).on('click', '.loadnext', function(event) {
	sc = $(this).attr('data-sc');
	type = $(this).attr('data-type');
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
			swal("Atenção!", "A rádios devem ser iguais!", "error");
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
	discardbtn = $(this);
	discardbtn.children('i').css('display', 'inline-block');

	iddoc = discardbtn.attr('data-iddoc');
	iddiv = discardbtn.attr('data-iddiv');
	idkeyword = discardbtn.attr('data-idkeyword');
	idclient = discardbtn.attr('data-idclient');
	sc = discardbtn.attr('data-sc');
	type = discardbtn.attr('data-type');
	iduser = '<?php echo $this->session->userdata("id_user");?>';

	audioid = 'paudio'+iddiv.replace(/[a-zA-Z]/g, '');
	$('#'+audioid)[0].pause();

	$.post('<?php echo base_url("pages/discard_doc_radio")?>',
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

			totalpanelsd += 1;
			if (totalpanelsd == totalpanels) {
				console.log('no more panels!');
				window.location = '<?php echo base_url("pages/index_radio")?>';
			}
		}
	);
});

$(document).on('click', '.tndiscarddoc', function(event) {
	discardbtn = $(this);
	discardbtn.children('i').css('display', 'inline-block');

	iddoc = discardbtn.attr('data-iddoc');
	iddiv = discardbtn.attr('data-iddiv');
	idkeyword = discardbtn.attr('data-idkeyword');
	idclient = discardbtn.attr('data-idclient');
	iduser = '<?php echo $this->session->userdata("id_user");?>';

	$.post('<?php echo base_url("pages/discard_doc_tv_novo")?>',
		{
			'iddoc': iddoc,
			'idkeyword': idkeyword,
			'idclient': idclient,
			'iduser': iduser
		},
		function(data, textStatus, xhr) {
			// console.log(data);
			discardbtn.children('i').css('display', 'none');
			$('#'+iddiv).removeClass('panel-default');
			$('#'+iddiv).addClass('panel-danger');
			totalpanelsd += 1;

			if (totalpanelsd == totalpanels) {
				console.log('no more panels!');
				window.location = '<?php echo base_url("pages/index_tv")?>';
			}
		}
	);
});

$(document).on('click', '.desativado', function() {
	$(this).css('overflowY', 'auto');
})

$(document).on('click', 'span', function(){
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

$(document).on('click', 'audio, video', function() {
	if ($(this)[0].paused) {
		idpmedia = $(this).attr('id');
		ptextid = 'ptext'+idpmedia.replace(/[a-zA-Z]/g, '');

		ptextspans = $('#'+ptextid).children('span.fkword');
		if (ptextspans.length == 0) {
			ptextspans = $('#'+ptextid).children('span');
		}

		spantime = $(ptextspans[0]).attr('data-begin') - 0.3;
		startread(idpmedia, ptextid, spantime, true);
	}
});


$('window .pfaudio, .pfvideo').on('loadedmetadata', function() {
	mediaid = $(this).attr('id');
	fkwtime = $(this).attr('data-fkwtime');
	// jmediael = $('#'+mediaid);
	mediael = document.getElementById(mediaid);
	// console.log(mediael);
	if (mediael.readyState === 4){
		// mediael[0].currentTime = fkwtime;
		mediael.currentTime = fkwtime;
		console.log(mediaid);
		console.log(fkwtime);
	} else {
		setTimeout(function() {
			console.log('not ready! waiting 1.5s...');
			mediael.currentTime = fkwtime;
		},1500)
	}

});
// $(document).on('mouseleave', '.panel.panel-default.collapse.in', function() {
// 	ptextid = $(this).attr('id');
// 	pmedia = 'paudio'+ptextid.replace(/[a-zA-Z]/g, '');

// 	$('#'+pmedia)[0].pause();
// });