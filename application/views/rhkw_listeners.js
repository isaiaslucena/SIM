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
	loadpn('previous', $(this));
});

$(document).on('click', '.loadnext', function(event) {
	loadpn('next', $(this));
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

$(document).on('click', '.desativado', function() {
	$(this).css('overflowY', 'auto');
})

$(document).on('click', 'span', function(){
	ptextid = $(this).parent('.ptext').attr('id');
	paudioid = 'paudio'+ptextid.replace(/[a-zA-Z]/g, '');
	spantime = $(this).attr('data-begin');

	startread(paudioid, ptextid, spantime, true);
	$('#'+paudioid)[0].play();
});

$(document).on('click', 'audio', function(){
	if ($(this)[0].paused) {
		console.log('playing audio');

		paudioid = $(this).attr('id');
		ptextid = 'ptext'+paudioid.replace(/[a-zA-Z]/g, '');

		ptextspans = $('#'+ptextid).children('span.fkword');
		spantime = $(ptextspans[0]).attr('data-begin') - 0.3;

		startread(paudioid, ptextid, spantime, true);
		// $('#'+paudioid)[0].play();
	} else {
		console.log('paused audio');
	}
});

// $(document).on('mouseleave', '.panel.panel-default.collapse.in', function() {
// 	ptextid = $(this).attr('id');
// 	paudioid = 'paudio'+ptextid.replace(/[a-zA-Z]/g, '');

// 	$('#'+paudioid)[0].pause();
// });