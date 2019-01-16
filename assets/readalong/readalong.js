var ReadAlong = {
	text_element: null,
	audio_element: null,
	autofocus_current_word: false,

	words: [],

	init: function (args) {
		var name;
		for (name in args) {
			this[name] = args[name];
		}
		this.generateWordList();
		this.addEventListeners();
		this.selectCurrentWord();
	},

	generateWordList: function () {
		var word_els = this.text_element.querySelectorAll('[data-begin]');
		this.words = Array.prototype.map.call(word_els, function (word_el, index) {
			var word = {
				'begin': parseFloat(word_el.dataset.begin),
				'dur': parseFloat(word_el.dataset.dur),
				'element': word_el
			};
			word_el.tabIndex = 0;
			word.index = index;
			word.end = word.begin + word.dur;
			word_el.dataset.index = word.index;
			return word;
		});
	},

	getCurrentWord: function () {
		var i;
		var len;
		var is_current_word;
		var word = null;
		for (i = 0, len = this.words.length; i < len; i += 1) {
			is_current_word = (
				(
					this.audio_element.currentTime >= this.words[i].begin
					&&
					this.audio_element.currentTime < this.words[i].end
				)
				||
				(this.audio_element.currentTime < this.words[i].begin)
			);
			if (is_current_word) {
				word = this.words[i];
				break;
			}
		}

		if (!word) {
			throw Error('Unable to find current word and we should always be able to.');
		}
		return word;
	},

	_current_end_select_timeout_id: null,
	_current_next_select_timeout_id: null,

	selectCurrentWord: function() {
		var that = this;
		var current_word = this.getCurrentWord();
		var is_playing = !this.audio_element.paused;

		if (!current_word.element.classList.contains('speaking')) {
			this.removeWordSelection();
			if (current_word.element.classList.contains('speaking')) {
				current_word.element.classList.remove('fkword');
				current_word.element.classList.remove('kword');
			}
			current_word.element.classList.add('speaking');
			if (this.autofocus_current_word) {
				current_word.element.focus();
			}
		}

		if (is_playing) {
			var seconds_until_this_word_ends = current_word.end - this.audio_element.currentTime;

			if (typeof this.audio_element === 'number' && !isNaN(this.audio_element)) {
				seconds_until_this_word_ends *= 1.0 /this.audio_element.playbackRate;
			}

			clearTimeout(this._current_end_select_timeout_id);
			this._current_end_select_timeout_id = setTimeout(function() {
				if (!that.audio_element.paused) {
					current_word.element.classList.remove('speaking');
				}
			}, Math.max(seconds_until_this_word_ends * 1000, 0));

			var next_word = this.words[current_word.index + 1];
			if (next_word) {
				var seconds_until_next_word_begins = next_word.begin - this.audio_element.currentTime;

				var orig_seconds_until_next_word_begins = seconds_until_next_word_begins;
				if (typeof this.audio_element === 'number' && !isNaN(this.audio_element)) {
					seconds_until_next_word_begins *= 1.0/this.audio_element.playbackRate;
				}
				clearTimeout(this._current_next_select_timeout_id);
				this._current_next_select_timeout_id = setTimeout(
					function () {
						that.selectCurrentWord();
					},
					Math.max(seconds_until_next_word_begins * 1000, 0)
				);
			}
		}
	},

	removeWordSelection: function() {
		var spoken_word_els = this.text_element.querySelectorAll('span[data-begin].speaking');
		Array.prototype.forEach.call(spoken_word_els, function (spoken_word_el) {
			spoken_word_el.classList.remove('speaking');
		});
	},

	addEventListeners: function () {
		var that = this;

		that.audio_element.addEventListener('play', function (e) {
			that.selectCurrentWord();
			that.text_element.classList.add('speaking');
			// console.log('event listening from readalong');
		}, false);

		that.audio_element.addEventListener('pause', function (e) {
			that.selectCurrentWord();
			that.text_element.classList.remove('speaking');
		}, false);

		function on_select_word_el(e) {
			if (!e.target.dataset.begin) {
				return;
			}
			e.preventDefault();

			console.log(e);

			var i = e.target.dataset.index;
			that.audio_element.currentTime = that.words[i].begin + 0.01;
			that.audio_element.play();
		}

		// that.text_element.addEventListener('click', on_select_word_el, false);
		that.text_element.addEventListener('keypress', function (e) {
			if ( (e.charCode || e.keyCode) === 13) {
				on_select_word_el.call(this, e);
			}
		}, false);

		// document.addEventListener('keypress', function (e) {
		// 	if ( (e.charCode || e.keyCode) === 32) {
		// 		e.preventDefault();
		// 		if (that.audio_element.paused) {
		// 			that.audio_element.play();
		// 		}
		// 		else {
		// 			that.audio_element.pause();
		// 		}
		// 	}
		// }, false);

		that.text_element.addEventListener('dblclick', function (e) {
			e.preventDefault();
			that.audio_element.play();
		}, false);

		that.audio_element.addEventListener('seeked', function (e) {
			that.selectCurrentWord();

			var audio_element = this;
			if (!audio_element.paused) {
				var previousTime = audio_element.currentTime;
				setTimeout(function () {
					if (!audio_element.paused && previousTime === audio_element.currentTime) {
						audio_element.currentTime += 0.01;
					}
				}, 500);
			}
		}, false);

		that.audio_element.addEventListener('ratechange', function (e) {
			that.selectCurrentWord();
		}, false);
	},

	playaudio: function() {
		var that = this;
		that.audio_element.play();
	}
};