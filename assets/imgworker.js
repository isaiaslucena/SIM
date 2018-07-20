console.log('imgworker loaded!')

self.onmessage = function(e) {
	file = e.data.file;
	thumbn = e.data.thumbn;
	imgsrc = e.data.imgsrc;
	vmethod = 'GET';
	// imgarr = []
	// nimg = 0;
	http_req(vmethod, imgsrc, function(resp){
		// postMessage({
		// 	'response': resp
		// });
		// console.log('Thumb '+thumbn+' of file '+file+' loaded');
	});
}

function http_req(method, url, callback) {
	var xhr;

	if (typeof XMLHttpRequest !== 'undefined') {
		xhr = new XMLHttpRequest();
	} else {
		var versions = [
			"MSXML2.XmlHttp.5.0",
			"MSXML2.XmlHttp.4.0",
			"MSXML2.XmlHttp.3.0",
			"MSXML2.XmlHttp.2.0",
			"Microsoft.XmlHttp"
		]

		for(var i = 0, len = versions.length; i < len; i++) {
			try {
				xhr = new ActiveXObject(versions[i]);
				break;
			}
			catch(e){}
		}
	}

	// xhr.setRequestHeader('Authorization', 'Access-Control-Allow-Headers');
	xhr.onreadystatechange = ensureReadiness;

	function ensureReadiness() {
		if(xhr.readyState < 4) {
			return;
		}

		if(xhr.status !== 200) {
			return;
		}

		if(xhr.readyState === 4) {
			callback(xhr);
		}
	}

	xhr.open(method, url, false);
	xhr.send(null);
}