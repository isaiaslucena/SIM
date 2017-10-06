(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory() :
  typeof define === 'function' && define.amd ? define(factory) :
  (factory());
}(this, function () { 'use strict';

  // ERROR=0, WARN=1, LOG=2, DEBUG=3
  const LogLevel = {
      Error: 0,
      Warn: 1,
      Log: 2,
      Debug: 3
  };

  let DEFAULT_LOG_LEVEL = LogLevel.Debug;

  function setDefaultLogLevel(level) {
      DEFAULT_LOG_LEVEL = level;
  }
  class Logger {
      constructor(level = DEFAULT_LOG_LEVEL, tag) {
          this.tag = tag;
          this.setLevel(level);
      }
      
      setLevel(level) {
          this.level = level;
      }
      
      static get level_map() { return {
          [LogLevel.Debug]:'log',
          [LogLevel.Log]:'log',
          [LogLevel.Warn]:'warn',
          [LogLevel.Error]:'error'
      }};

      _log(lvl, args) {
          args = Array.prototype.slice.call(args);
          if (this.tag) {
              args.unshift(`[${this.tag}]`);
          }
          if (this.level>=lvl) console[Logger.level_map[lvl]].apply(console, args);
      }
      log(){
          this._log(LogLevel.Log, arguments)
      }
      debug(){
          this._log(LogLevel.Debug, arguments)
      }
      error(){
          this._log(LogLevel.Error, arguments)
      }
      warn(){
          this._log(LogLevel.Warn, arguments)
      }
  }

  const taggedLoggers = new Map();
  function getTagged(tag) {
      if (!taggedLoggers.has(tag)) {
          taggedLoggers.set(tag, new Logger(DEFAULT_LOG_LEVEL, tag));
      }
      return taggedLoggers.get(tag);
  }
  const Log = new Logger();

  class Url {
      static parse(url) {
          var ret = {};

          var regex = /^([^:]+):\/\/([^\/]+)(.*)$/;  //protocol, login, urlpath
          var result = regex.exec(url);

          ret.full = url;
          ret.protocol = result[1];
          ret.urlpath = result[3];

          var parts = ret.urlpath.split('/');
          ret.basename = parts.pop().split(/\?|#/)[0];
          ret.basepath = parts.join('/');

          var loginSplit = result[2].split('@');
          var hostport = loginSplit[0].split(':');
          var userpass = [ null, null ];
          if (loginSplit.length === 2) {
              userpass = loginSplit[0].split(':');
              hostport = loginSplit[1].split(':');
          }

          ret.user = userpass[0];
          ret.pass = userpass[1];
          ret.host = hostport[0];
          ret.auth = (ret.user && ret.pass) ? `${ret.user}:${ret.pass}` : '';

          ret.port = (null == hostport[1]) ? Url.protocolDefaultPort(ret.protocol) : hostport[1];
          ret.portDefined = (null != hostport[1]);
          ret.location = `${ret.host}:${ret.port}`;

          if (ret.protocol == 'unix') {
              ret.socket = ret.port;
              ret.port = undefined;
          }

          return ret;
      }

      static full(parsed) {
          return `${parsed.protocol}://${parsed.auth?parsed.auth+'@':''}${parsed.location}/${parsed.urlpath}`;
      }

      static isAbsolute(url) {
          return /^[^:]+:\/\//.test(url);
      }

      static protocolDefaultPort(protocol) {
          switch (protocol) {
              case 'rtsp': return 554;
              case 'http': return 80;
              case 'https': return 443;
          }

          return 0;
      }
  }

  /**
   * Generate MP4 Box
   * got from: https://github.com/dailymotion/hls.js
   */

  class MP4 {
      static init() {
          MP4.types = {
              avc1: [], // codingname
              avcC: [],
              btrt: [],
              dinf: [],
              dref: [],
              esds: [],
              ftyp: [],
              hdlr: [],
              mdat: [],
              mdhd: [],
              mdia: [],
              mfhd: [],
              minf: [],
              moof: [],
              moov: [],
              mp4a: [],
              mvex: [],
              mvhd: [],
              sdtp: [],
              stbl: [],
              stco: [],
              stsc: [],
              stsd: [],
              stsz: [],
              stts: [],
              tfdt: [],
              tfhd: [],
              traf: [],
              trak: [],
              trun: [],
              trex: [],
              tkhd: [],
              vmhd: [],
              smhd: []
          };

          var i;
          for (i in MP4.types) {
              if (MP4.types.hasOwnProperty(i)) {
                  MP4.types[i] = [
                      i.charCodeAt(0),
                      i.charCodeAt(1),
                      i.charCodeAt(2),
                      i.charCodeAt(3)
                  ];
              }
          }

          var videoHdlr = new Uint8Array([
              0x00, // version 0
              0x00, 0x00, 0x00, // flags
              0x00, 0x00, 0x00, 0x00, // pre_defined
              0x76, 0x69, 0x64, 0x65, // handler_type: 'vide'
              0x00, 0x00, 0x00, 0x00, // reserved
              0x00, 0x00, 0x00, 0x00, // reserved
              0x00, 0x00, 0x00, 0x00, // reserved
              0x56, 0x69, 0x64, 0x65,
              0x6f, 0x48, 0x61, 0x6e,
              0x64, 0x6c, 0x65, 0x72, 0x00 // name: 'VideoHandler'
          ]);

          var audioHdlr = new Uint8Array([
              0x00, // version 0
              0x00, 0x00, 0x00, // flags
              0x00, 0x00, 0x00, 0x00, // pre_defined
              0x73, 0x6f, 0x75, 0x6e, // handler_type: 'soun'
              0x00, 0x00, 0x00, 0x00, // reserved
              0x00, 0x00, 0x00, 0x00, // reserved
              0x00, 0x00, 0x00, 0x00, // reserved
              0x53, 0x6f, 0x75, 0x6e,
              0x64, 0x48, 0x61, 0x6e,
              0x64, 0x6c, 0x65, 0x72, 0x00 // name: 'SoundHandler'
          ]);

          MP4.HDLR_TYPES = {
              'video': videoHdlr,
              'audio': audioHdlr
          };

          var dref = new Uint8Array([
              0x00, // version 0
              0x00, 0x00, 0x00, // flags
              0x00, 0x00, 0x00, 0x01, // entry_count
              0x00, 0x00, 0x00, 0x0c, // entry_size
              0x75, 0x72, 0x6c, 0x20, // 'url' type
              0x00, // version 0
              0x00, 0x00, 0x01 // entry_flags
          ]);

          var stco = new Uint8Array([
              0x00, // version
              0x00, 0x00, 0x00, // flags
              0x00, 0x00, 0x00, 0x00 // entry_count
          ]);

          MP4.STTS = MP4.STSC = MP4.STCO = stco;

          MP4.STSZ = new Uint8Array([
              0x00, // version
              0x00, 0x00, 0x00, // flags
              0x00, 0x00, 0x00, 0x00, // sample_size
              0x00, 0x00, 0x00, 0x00, // sample_count
          ]);
          MP4.VMHD = new Uint8Array([
              0x00, // version
              0x00, 0x00, 0x01, // flags
              0x00, 0x00, // graphicsmode
              0x00, 0x00,
              0x00, 0x00,
              0x00, 0x00 // opcolor
          ]);
          MP4.SMHD = new Uint8Array([
              0x00, // version
              0x00, 0x00, 0x00, // flags
              0x00, 0x00, // balance
              0x00, 0x00 // reserved
          ]);

          MP4.STSD = new Uint8Array([
              0x00, // version 0
              0x00, 0x00, 0x00, // flags
              0x00, 0x00, 0x00, 0x01]);// entry_count

          var majorBrand = new Uint8Array([105,115,111,109]); // isom
          var avc1Brand = new Uint8Array([97,118,99,49]); // avc1
          var minorVersion = new Uint8Array([0, 0, 0, 1]);

          MP4.FTYP = MP4.box(MP4.types.ftyp, majorBrand, minorVersion, majorBrand, avc1Brand);
          MP4.DINF = MP4.box(MP4.types.dinf, MP4.box(MP4.types.dref, dref));
      }

      static box(type, ...payload) {
          var size = 8,
              i = payload.length,
              len = i,
              result;
          // calculate the total size we need to allocate
          while (i--) {
              size += payload[i].byteLength;
          }
          result = new Uint8Array(size);
          result[0] = (size >> 24) & 0xff;
          result[1] = (size >> 16) & 0xff;
          result[2] = (size >> 8) & 0xff;
          result[3] = size  & 0xff;
          result.set(type, 4);
          // copy the payload into the result
          for (i = 0, size = 8; i < len; ++i) {
              // copy payload[i] array @ offset size
              result.set(payload[i], size);
              size += payload[i].byteLength;
          }
          return result;
      }

      static hdlr(type) {
          return MP4.box(MP4.types.hdlr, MP4.HDLR_TYPES[type]);
      }

      static mdat(data) {
          return MP4.box(MP4.types.mdat, data);
      }

      static mdhd(timescale, duration) {
          return MP4.box(MP4.types.mdhd, new Uint8Array([
              0x00, // version 0
              0x00, 0x00, 0x00, // flags
              0x00, 0x00, 0x00, 0x02, // creation_time
              0x00, 0x00, 0x00, 0x03, // modification_time
              (timescale >> 24) & 0xFF,
              (timescale >> 16) & 0xFF,
              (timescale >>  8) & 0xFF,
              timescale & 0xFF, // timescale
              (duration >> 24),
              (duration >> 16) & 0xFF,
              (duration >>  8) & 0xFF,
              duration & 0xFF, // duration
              0x55, 0xc4, // 'und' language (undetermined)
              0x00, 0x00
          ]));
      }

      static mdia(track) {
          return MP4.box(MP4.types.mdia, MP4.mdhd(track.timescale, track.duration), MP4.hdlr(track.type), MP4.minf(track));
      }

      static mfhd(sequenceNumber) {
          return MP4.box(MP4.types.mfhd, new Uint8Array([
              0x00,
              0x00, 0x00, 0x00, // flags
              (sequenceNumber >> 24),
              (sequenceNumber >> 16) & 0xFF,
              (sequenceNumber >>  8) & 0xFF,
              sequenceNumber & 0xFF, // sequence_number
          ]));
      }

      static minf(track) {
          if (track.type === 'audio') {
              return MP4.box(MP4.types.minf, MP4.box(MP4.types.smhd, MP4.SMHD), MP4.DINF, MP4.stbl(track));
          } else {
              return MP4.box(MP4.types.minf, MP4.box(MP4.types.vmhd, MP4.VMHD), MP4.DINF, MP4.stbl(track));
          }
      }

      static moof(sn, baseMediaDecodeTime, track) {
          return MP4.box(MP4.types.moof, MP4.mfhd(sn), MP4.traf(track,baseMediaDecodeTime));
      }
      /**
       * @param tracks... (optional) {array} the tracks associated with this movie
       */
      static moov(tracks, duration, timescale) {
          var
              i = tracks.length,
              boxes = [];

          while (i--) {
              boxes[i] = MP4.trak(tracks[i]);
          }

          return MP4.box.apply(null, [MP4.types.moov, MP4.mvhd(timescale, duration)].concat(boxes).concat(MP4.mvex(tracks)));
      }

      static mvex(tracks) {
          var
              i = tracks.length,
              boxes = [];

          while (i--) {
              boxes[i] = MP4.trex(tracks[i]);
          }
          return MP4.box.apply(null, [MP4.types.mvex].concat(boxes));
      }

      static mvhd(timescale,duration) {
          var
              bytes = new Uint8Array([
                  0x00, // version 0
                  0x00, 0x00, 0x00, // flags
                  0x00, 0x00, 0x00, 0x01, // creation_time
                  0x00, 0x00, 0x00, 0x02, // modification_time
                  (timescale >> 24) & 0xFF,
                  (timescale >> 16) & 0xFF,
                  (timescale >>  8) & 0xFF,
                  timescale & 0xFF, // timescale
                  (duration >> 24) & 0xFF,
                  (duration >> 16) & 0xFF,
                  (duration >>  8) & 0xFF,
                  duration & 0xFF, // duration
                  0x00, 0x01, 0x00, 0x00, // 1.0 rate
                  0x01, 0x00, // 1.0 volume
                  0x00, 0x00, // reserved
                  0x00, 0x00, 0x00, 0x00, // reserved
                  0x00, 0x00, 0x00, 0x00, // reserved
                  0x00, 0x01, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x01, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x40, 0x00, 0x00, 0x00, // transformation: unity matrix
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00, // pre_defined
                  0xff, 0xff, 0xff, 0xff // next_track_ID
              ]);
          return MP4.box(MP4.types.mvhd, bytes);
      }

      static sdtp(track) {
          var
              samples = track.samples || [],
              bytes = new Uint8Array(4 + samples.length),
              flags,
              i;
          // leave the full box header (4 bytes) all zero
          // write the sample table
          for (i = 0; i < samples.length; i++) {
              flags = samples[i].flags;
              bytes[i + 4] = (flags.dependsOn << 4) |
                  (flags.isDependedOn << 2) |
                  (flags.hasRedundancy);
          }

          return MP4.box(MP4.types.sdtp, bytes);
      }

      static stbl(track) {
          return MP4.box(MP4.types.stbl, MP4.stsd(track), MP4.box(MP4.types.stts, MP4.STTS), MP4.box(MP4.types.stsc, MP4.STSC), MP4.box(MP4.types.stsz, MP4.STSZ), MP4.box(MP4.types.stco, MP4.STCO));
      }

      static avc1(track) {
          var sps = [], pps = [], i, data, len;
          // assemble the SPSs

          for (i = 0; i < track.sps.length; i++) {
              data = track.sps[i];
              len = data.byteLength;
              sps.push((len >>> 8) & 0xFF);
              sps.push((len & 0xFF));
              sps = sps.concat(Array.prototype.slice.call(data)); // SPS
          }

          // assemble the PPSs
          for (i = 0; i < track.pps.length; i++) {
              data = track.pps[i];
              len = data.byteLength;
              pps.push((len >>> 8) & 0xFF);
              pps.push((len & 0xFF));
              pps = pps.concat(Array.prototype.slice.call(data));
          }

          var avcc = MP4.box(MP4.types.avcC, new Uint8Array([
                  0x01,   // version
                  sps[3], // profile
                  sps[4], // profile compat
                  sps[5], // level
                  0xfc | 3, // lengthSizeMinusOne, hard-coded to 4 bytes
                  0xE0 | track.sps.length // 3bit reserved (111) + numOfSequenceParameterSets
              ].concat(sps).concat([
                  track.pps.length // numOfPictureParameterSets
              ]).concat(pps))), // "PPS"
              width = track.width,
              height = track.height;
          //console.log('avcc:' + Hex.hexDump(avcc));
          return MP4.box(MP4.types.avc1, new Uint8Array([
                  0x00, 0x00, 0x00, // reserved
                  0x00, 0x00, 0x00, // reserved
                  0x00, 0x01, // data_reference_index
                  0x00, 0x00, // pre_defined
                  0x00, 0x00, // reserved
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00, // pre_defined
                  (width >> 8) & 0xFF,
                  width & 0xff, // width
                  (height >> 8) & 0xFF,
                  height & 0xff, // height
                  0x00, 0x48, 0x00, 0x00, // horizresolution
                  0x00, 0x48, 0x00, 0x00, // vertresolution
                  0x00, 0x00, 0x00, 0x00, // reserved
                  0x00, 0x01, // frame_count
                  0x12,
                  0x62, 0x69, 0x6E, 0x65, //binelpro.ru
                  0x6C, 0x70, 0x72, 0x6F,
                  0x2E, 0x72, 0x75, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, // compressorname
                  0x00, 0x18,   // depth = 24
                  0x11, 0x11]), // pre_defined = -1
              avcc,
              MP4.box(MP4.types.btrt, new Uint8Array([
                  0x00, 0x1c, 0x9c, 0x80, // bufferSizeDB
                  0x00, 0x2d, 0xc6, 0xc0, // maxBitrate
                  0x00, 0x2d, 0xc6, 0xc0])) // avgBitrate
          );
      }

      static esds(track) {
          var configlen = track.config.byteLength;
          let data = new Uint8Array(26+configlen+3);
          data.set([
              0x00, // version 0
              0x00, 0x00, 0x00, // flags

              0x03, // descriptor_type
              0x17+configlen, // length
              0x00, 0x01, //es_id
              0x00, // stream_priority

              0x04, // descriptor_type
              0x0f+configlen, // length
              0x40, //codec : mpeg4_audio
              0x15, // stream_type
              0x00, 0x00, 0x00, // buffer_size
              0x00, 0x00, 0x00, 0x00, // maxBitrate
              0x00, 0x00, 0x00, 0x00, // avgBitrate

              0x05, // descriptor_type
              configlen
          ]);
          data.set(track.config, 26);
          data.set([0x06, 0x01, 0x02], 26+configlen);
          // return new Uint8Array([
          //     0x00, // version 0
          //     0x00, 0x00, 0x00, // flags
          //
          //     0x03, // descriptor_type
          //     0x17+configlen, // length
          //     0x00, 0x01, //es_id
          //     0x00, // stream_priority
          //
          //     0x04, // descriptor_type
          //     0x0f+configlen, // length
          //     0x40, //codec : mpeg4_audio
          //     0x15, // stream_type
          //     0x00, 0x00, 0x00, // buffer_size
          //     0x00, 0x00, 0x00, 0x00, // maxBitrate
          //     0x00, 0x00, 0x00, 0x00, // avgBitrate
          //
          //     0x05 // descriptor_type
          // ].concat([configlen]).concat(track.config).concat([0x06, 0x01, 0x02])); // GASpecificConfig)); // length + audio config descriptor
          return data;
      }

      static mp4a(track) {
          var audiosamplerate = track.audiosamplerate;
          return MP4.box(MP4.types.mp4a, new Uint8Array([
                  0x00, 0x00, 0x00, // reserved
                  0x00, 0x00, 0x00, // reserved
                  0x00, 0x01, // data_reference_index
                  0x00, 0x00, 0x00, 0x00,
                  0x00, 0x00, 0x00, 0x00, // reserved
                  0x00, track.channelCount, // channelcount
                  0x00, 0x10, // sampleSize:16bits
                  0x00, 0x00, // pre_defined
                  0x00, 0x00, // reserved2
                  (audiosamplerate >> 8) & 0xFF,
                  audiosamplerate & 0xff, //
                  0x00, 0x00]),
              MP4.box(MP4.types.esds, MP4.esds(track)));
      }

      static stsd(track) {
          if (track.type === 'audio') {
              return MP4.box(MP4.types.stsd, MP4.STSD, MP4.mp4a(track));
          } else {
              return MP4.box(MP4.types.stsd, MP4.STSD, MP4.avc1(track));
          }
      }

      static tkhd(track) {
          var id = track.id,
              duration = track.duration,
              width = track.width,
              height = track.height,
              volume = track.volume;
          return MP4.box(MP4.types.tkhd, new Uint8Array([
              0x00, // version 0
              0x00, 0x00, 0x07, // flags
              0x00, 0x00, 0x00, 0x00, // creation_time
              0x00, 0x00, 0x00, 0x00, // modification_time
              (id >> 24) & 0xFF,
              (id >> 16) & 0xFF,
              (id >> 8) & 0xFF,
              id & 0xFF, // track_ID
              0x00, 0x00, 0x00, 0x00, // reserved
              (duration >> 24),
              (duration >> 16) & 0xFF,
              (duration >>  8) & 0xFF,
              duration & 0xFF, // duration
              0x00, 0x00, 0x00, 0x00,
              0x00, 0x00, 0x00, 0x00, // reserved
              0x00, 0x00, // layer
              0x00, 0x00, // alternate_group
              (volume>>0)&0xff, (((volume%1)*10)>>0)&0xff, // track volume // FIXME
              0x00, 0x00, // reserved
              0x00, 0x01, 0x00, 0x00,
              0x00, 0x00, 0x00, 0x00,
              0x00, 0x00, 0x00, 0x00,
              0x00, 0x00, 0x00, 0x00,
              0x00, 0x01, 0x00, 0x00,
              0x00, 0x00, 0x00, 0x00,
              0x00, 0x00, 0x00, 0x00,
              0x00, 0x00, 0x00, 0x00,
              0x40, 0x00, 0x00, 0x00, // transformation: unity matrix
              (width >> 8) & 0xFF,
              width & 0xFF,
              0x00, 0x00, // width
              (height >> 8) & 0xFF,
              height & 0xFF,
              0x00, 0x00 // height
          ]));
      }

      static traf(track,baseMediaDecodeTime) {
          var sampleDependencyTable = MP4.sdtp(track),
              id = track.id;
          return MP4.box(MP4.types.traf,
              MP4.box(MP4.types.tfhd, new Uint8Array([
                  0x00, // version 0
                  0x00, 0x00, 0x00, // flags
                  (id >> 24),
                  (id >> 16) & 0XFF,
                  (id >> 8) & 0XFF,
                  (id & 0xFF) // track_ID
              ])),
              MP4.box(MP4.types.tfdt, new Uint8Array([
                  0x00, // version 0
                  0x00, 0x00, 0x00, // flags
                  (baseMediaDecodeTime >>24),
                  (baseMediaDecodeTime >> 16) & 0XFF,
                  (baseMediaDecodeTime >> 8) & 0XFF,
                  (baseMediaDecodeTime & 0xFF) // baseMediaDecodeTime
              ])),
              MP4.trun(track,
                  sampleDependencyTable.length +
                  16 + // tfhd
                  16 + // tfdt
                  8 +  // traf header
                  16 + // mfhd
                  8 +  // moof header
                  8),  // mdat header
              sampleDependencyTable);
      }

      /**
       * Generate a track box.
       * @param track {object} a track definition
       * @return {Uint8Array} the track box
       */
      static trak(track) {
          track.duration = track.duration || 0xffffffff;
          return MP4.box(MP4.types.trak, MP4.tkhd(track), MP4.mdia(track));
      }

      static trex(track) {
          var id = track.id;
          return MP4.box(MP4.types.trex, new Uint8Array([
              0x00, // version 0
              0x00, 0x00, 0x00, // flags
              (id >> 24),
              (id >> 16) & 0XFF,
              (id >> 8) & 0XFF,
              (id & 0xFF), // track_ID
              0x00, 0x00, 0x00, 0x01, // default_sample_description_index
              0x00, 0x00, 0x00, 0x00, // default_sample_duration
              0x00, 0x00, 0x00, 0x00, // default_sample_size
              0x00, 0x01, 0x00, 0x01 // default_sample_flags
          ]));
      }

      static trun(track, offset) {
          var samples= track.samples || [],
              len = samples.length,
              arraylen = 12 + (16 * len),
              array = new Uint8Array(arraylen),
              i,sample,duration,size,flags,cts;
          offset += 8 + arraylen;
          array.set([
              0x00, // version 0
              0x00, 0x0f, 0x01, // flags
              (len >>> 24) & 0xFF,
              (len >>> 16) & 0xFF,
              (len >>> 8) & 0xFF,
              len & 0xFF, // sample_count
              (offset >>> 24) & 0xFF,
              (offset >>> 16) & 0xFF,
              (offset >>> 8) & 0xFF,
              offset & 0xFF // data_offset
          ],0);
          for (i = 0; i < len; i++) {
              sample = samples[i];
              duration = sample.duration;
              size = sample.size;
              flags = sample.flags;
              cts = sample.cts;
              array.set([
                  (duration >>> 24) & 0xFF,
                  (duration >>> 16) & 0xFF,
                  (duration >>> 8) & 0xFF,
                  duration & 0xFF, // sample_duration
                  (size >>> 24) & 0xFF,
                  (size >>> 16) & 0xFF,
                  (size >>> 8) & 0xFF,
                  size & 0xFF, // sample_size
                  (flags.isLeading << 2) | flags.dependsOn,
                  (flags.isDependedOn << 6) |
                  (flags.hasRedundancy << 4) |
                  (flags.paddingValue << 1) |
                  flags.isNonSync,
                  flags.degradPrio & 0xF0 << 8,
                  flags.degradPrio & 0x0F, // sample_flags
                  (cts >>> 24) & 0xFF,
                  (cts >>> 16) & 0xFF,
                  (cts >>> 8) & 0xFF,
                  cts & 0xFF // sample_composition_time_offset
              ],12+16*i);
          }
          return MP4.box(MP4.types.trun, array);
      }

      static initSegment(tracks, duration, timescale) {
          if (!MP4.types) {
              MP4.init();
          }
          var movie = MP4.moov(tracks, duration, timescale), result;
          result = new Uint8Array(MP4.FTYP.byteLength + movie.byteLength);
          result.set(MP4.FTYP);
          result.set(movie, MP4.FTYP.byteLength);
          return result;
      }
  }

  class AACFrame {

      constructor(data, dts, pts) {
          this.dts = dts;
          this.pts = pts ? pts : this.dts;

          this.data=data;//.subarray(offset);
      }

      getData() {
          return this.data;
      }

      getSize() {
          return this.data.byteLength;
      }
  }

  // TODO: asm.js
  class AACAsm {
      constructor() {
          this.config = null;
      }

      onAACFragment(pkt) {
          let rawData = pkt.getPayload();
          if (!pkt.media) {
              return null;
          }
          let data = new DataView(rawData.buffer, rawData.byteOffset, rawData.byteLength);

          let sizeLength = pkt.media.fmtp['sizelength'] || 0;
          let indexLength = pkt.media.fmtp['indexlength'] || 0;
          let indexDeltaLength = pkt.media.fmtp['indexdeltalength'] || 0;
          let CTSDeltaLength = pkt.media.fmtp['ctsdeltalength'] || 0;
          let DTSDeltaLength = pkt.media.fmtp['dtsdeltalength'] || 0;
          let RandomAccessIndication = pkt.media.fmtp['randomaccessindication'] || 0;
          let StreamStateIndication = pkt.media.fmtp['streamstateindication'] || 0;
          let AuxiliaryDataSizeLength = pkt.media.fmtp['auxiliarydatasizelength'] || 0;

          let configHeaderLength =
              sizeLength + Math.max(indexLength, indexDeltaLength) + CTSDeltaLength + DTSDeltaLength +
              RandomAccessIndication + StreamStateIndication + AuxiliaryDataSizeLength;


          let auHeadersLengthPadded = 0;
          if (0 !== configHeaderLength) {
              /* The AU header section is not empty, read it from payload */
              let auHeadersLengthInBits = data.getUint16(0); // Always 2 octets, without padding
              auHeadersLengthPadded = 2 + (auHeadersLengthInBits + auHeadersLengthInBits & 0x7) >>> 3; // Add padding

              this.config = new Uint8Array(rawData, 0 , auHeadersLengthPadded);
          }

          let aacData = rawData.subarray(auHeadersLengthPadded);
          let offset = 0;
          while (true) {
              if (aacData[offset] !=255) break;
              ++offset;
          }

          ++offset;
          let ts = (Math.round(pkt.getTimestampMS()/1024) << 10) * 90000 / this.config.samplerate;
          return new AACFrame(rawData.subarray(auHeadersLengthPadded+offset), ts);
      }
  }

  const listener = Symbol("event_listener");
  const listeners = Symbol("event_listeners");

  class DestructibleEventListener {
      constructor(eventListener) {
          this[listener] = eventListener;
          this[listeners] = new Map();
      }

      destroy() {
          this[listeners].forEach((listener_set, event)=>{
              listener_set.forEach((fn)=>{
                  this[listener].removeEventListener(event, fn);
              });
              listener_set = null;
          });
          this[listeners] = null;
      }

      addEventListener(event, fn) {
          if (!this[listeners].has(event)) {
              this[listeners].set(event, new Set());
          }
          this[listeners].get(event).add(fn);
          this[listener].addEventListener(event, fn, false);
      }

      removeEventListener(event, fn) {
          this[listener].removeEventListener(event, fn, false);
          if (this[listeners].has(event)) {
              this[listeners].set(event, new Set());
              let ev = this[listeners].get(event);
              ev.delete(fn);
              if (!ev.size) {
                  this[listeners].delete(event);
              }
          }
      }

      dispatchEvent(event) {
          this[listener].dispatchEvent(event);
      }
  }

  class EventEmitter {
      constructor() {
          this[listener] = new DestructibleEventListener(document.createElement('div'));
      }

      destroy() {
          this[listener].destroy();
          this[listener] = null;
      }

      addEventListener(event, fn) {
          this[listener].addEventListener(event, fn, false);
      }

      removeEventListener(event, fn) {
          this[listener].removeEventListener(event, fn, false);
      }

      dispatchEvent(event, data) {
          this[listener].dispatchEvent(new CustomEvent(event, {detail: data}))
      }
  }

  const LOG_TAG$1 = "mse";
  const Log$4 = getTagged(LOG_TAG$1);

  class Buffer {
      constructor(parent, codec) {
          this.mediaSource = parent.mediaSource;
          this.players = parent.players;
          this.cleaning = false;
          this.parent = parent;
          this.queue = [];
          this.cleanResolvers = [];
          this.codec = codec;

          Log$4.debug(`Use codec: ${codec}`);

          this.sourceBuffer = this.mediaSource.addSourceBuffer(codec);

          this.sourceBuffer.addEventListener('updatestart', (e)=> {
              // this.updating = true;
              // Log.debug('update start');
              if (this.cleaning) {
                  Log$4.debug(`${this.codec} cleaning start`);
              }
          });

          this.sourceBuffer.addEventListener('update', (e)=> {
              // this.updating = true;
              if (this.cleaning) {
                  Log$4.debug(`${this.codec} cleaning update`);
              }
          });

          this.sourceBuffer.addEventListener('updateend', (e)=> {
              // Log.debug('update end');
              // this.updating = false;
              if (this.cleaning) {
                  Log$4.debug(`${this.codec} cleaning end`);
                  if (this.sourceBuffer.buffered.length && this.players[0].currentTime < this.sourceBuffer.buffered.start(0)) {
                      this.players[0].currentTime = this.sourceBuffer.buffered.start(0);
                  }
                  while (this.cleanResolvers.length) {
                      let resolver = this.cleanResolvers.shift();
                      resolver();
                  }
                  this.cleaning = false;
              } else {
                  // Log.debug(`buffered: ${this.sourceBuffer.buffered.end(0)}, current ${this.players[0].currentTime}`);
              }
              this.feedNext();
          });

          this.sourceBuffer.addEventListener('error', (e)=> {
              Log$4.debug(`Source buffer error: ${this.mediaSource.readyState}`);
              if (this.mediaSource.sourceBuffers.length) {
                  this.mediaSource.removeSourceBuffer(this.sourceBuffer);
              }
              this.parent.eventSource.dispatchEvent('error');
          });

          this.sourceBuffer.addEventListener('abort', (e)=> {
              Log$4.debug(`Source buffer aborted: ${this.mediaSource.readyState}`);
              if (this.mediaSource.sourceBuffers.length) {
                  this.mediaSource.removeSourceBuffer(this.sourceBuffer);
              }
              this.parent.eventSource.dispatchEvent('error');
          });

          if (!this.sourceBuffer.updating) {
              this.feedNext();
          }
          // TODO: cleanup every hour for live streams
      }

      destroy() {
          this.clear();
          this.mediaSource.removeSourceBuffer(this.sourceBuffer);
      }

      clear() {
          this.queue = [];
          let promises = [];
          for (let i=0; i< this.sourceBuffer.buffered.length; ++i) {
              // TODO: await remove
              this.cleaning = true;
              promises.push(new Promise((resolve, reject)=>{
                  this.cleanResolvers.push(resolve);
                  this.sourceBuffer.remove(this.sourceBuffer.buffered.start(i), this.sourceBuffer.buffered.end(i));
              }));
          }
          return Promise.all(promises);
      }

      feedNext() {
          // Log.debug("feed next ", this.sourceBuffer.updating);
          if (!this.sourceBuffer.updating && !this.cleaning && this.queue.length) {
              this.doAppend(this.queue.shift());
          }
      }

      doCleanup() {
          if (this.sourceBuffer.buffered.length && !this.sourceBuffer.updating && !this.cleaning) {
              Log$4.debug(`${this.codec} cleanup`);
              let bufferStart = this.sourceBuffer.buffered.start(0);
              let removeEnd = this.sourceBuffer.buffered.start(0) + (this.sourceBuffer.buffered.end(0) - this.sourceBuffer.buffered.start(0))/2;
              if (this.players[0].currentTime < removeEnd) {
                  this.players[0].currentTime = removeEnd;
              }
              // let removeEnd = Math.max(this.players[0].currentTime - 3, this.sourceBuffer.buffered.end(0) - 3);
              //
              // if (removeEnd < bufferStart) {
              //     removeEnd = this.sourceBuffer.buffered.start(0) + (this.sourceBuffer.buffered.end(0) - this.sourceBuffer.buffered.start(0))/2;
              //     if (this.players[0].currentTime < removeEnd) {
              //         this.players[0].currentTime = removeEnd;
              //     }
              // }

              if (removeEnd > bufferStart && (removeEnd - bufferStart > 0.5 )) {
                  // try {
                      Log$4.debug(`${this.codec} remove range [${bufferStart} - ${removeEnd}). 
                    \nBuffered end: ${this.sourceBuffer.buffered.end(0)}
                    \nUpdating: ${this.sourceBuffer.updating}
                    `);
                      this.cleaning = true;
                      this.sourceBuffer.remove(bufferStart, removeEnd);
                  // } catch (e) {
                  //     // TODO: implement
                  //     Log.error(e);
                  // }
              } else {
                  this.feedNext();
              }
          } else {
              this.feedNext();
          }
      }

      doAppend(data) {
          // console.log(MP4Inspect.mp4toJSON(data));
          let err = this.players[0].error;
          if (err) {
              Log$4.error(`Error occured: ${MSE.ErrorNotes[err.code]}`);
              try {
                  this.players.forEach((video)=>{video.stop();});
                  this.mediaSource.endOfStream();
              } catch (e){

              }
              this.parent.eventSource.dispatchEvent('error');
          } else {
              try {
                  this.sourceBuffer.appendBuffer(data);
              } catch (e) {
                  if (e.name === 'QuotaExceededError') {
                      Log$4.debug(`${this.codec} quota fail`);
                      this.queue.unshift(data);
                      this.doCleanup();
                      return;
                  }

                  // reconnect on fail
                  Log$4.error(`Error occured while appending buffer. ${e.name}: ${e.message}`);
                  this.parent.eventSource.dispatchEvent('error');
              }
          }

      }

      feed(data) {
          this.queue = this.queue.concat(data);
          // Log.debug(this.sourceBuffer.updating, this.updating, this.queue.length);
          if (this.sourceBuffer && !this.sourceBuffer.updating && !this.cleaning) {
              // Log.debug('enq feed');
              this.feedNext();
          }
      }
  }

  class MSE {
      // static CODEC_AVC_BASELINE = "avc1.42E01E";
      // static CODEC_AVC_MAIN = "avc1.4D401E";
      // static CODEC_AVC_HIGH = "avc1.64001E";
      // static CODEC_VP8 = "vp8";
      // static CODEC_AAC = "mp4a.40.2";
      // static CODEC_VORBIS = "vorbis";
      // static CODEC_THEORA = "theora";

      static get ErrorNotes() {return  {
          [MediaError.MEDIA_ERR_ABORTED]: 'fetching process aborted by user',
          [MediaError.MEDIA_ERR_NETWORK]: 'error occurred when downloading',
          [MediaError.MEDIA_ERR_DECODE]: 'error occurred when decoding',
          [MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED]: 'audio/video not supported'
      }};

      static isSupported(codecs) {
          return (window.MediaSource && window.MediaSource.isTypeSupported(`video/mp4; codecs="${codecs.join(',')}"`));
      }

      constructor (players) {
          this.players = players;
          this.eventSource = new EventEmitter();
          this.reset();
      }

      destroy() {
          this.clear();
          this.eventSource.destroy();
      }

      play() {
          this.players.forEach((video)=>{video.play();});
      }

      resetBuffers() {
          this.players.forEach((video)=>{
              video.pause();
              video.currentTime=0;
          });

          let promises = [];
          for (let buffer of this.buffers.values()) {
              promises.push(buffer.clear());
          }
          return Promise.all(promises).then(()=>{
              this.mediaSource.endOfStream();
              this.mediaSource.duration = 0;
              this.mediaSource.clearLiveSeekableRange();
              this.play();
          });
      }

      clear() {
          for (let track in this.buffers) {
              this.buffers[track].destroy();
              delete this.buffers[track];
          }
      }

      reset() {
          this.updating = false;
          this.resolved = false;
          this.buffers = {};
          this.mediaSource = new MediaSource();
          this.players.forEach((video)=>{video.src = URL.createObjectURL(this.mediaSource)});
          // TODO: remove event listeners for existing media source
          this.mediaReady = new Promise((resolve, reject)=>{
              this.mediaSource.addEventListener('sourceopen', ()=>{
                  Log$4.debug(`Media source opened: ${this.mediaSource.readyState}`);
                  if (!this.resolved) {
                      this.resolved = true;
                      resolve();
                  }
              });
              this.mediaSource.addEventListener('sourceended', ()=>{
                  Log$4.debug(`Media source ended: ${this.mediaSource.readyState}`);
              });
              this.mediaSource.addEventListener('sourceclose', ()=>{
                  Log$4.debug(`Media source closed: ${this.mediaSource.readyState}`);
                  this.eventSource.dispatchEvent('sourceclose');
              });
          });
          // this.clear();
      }

      setCodec(track, mimeCodec) {
          return this.mediaReady.then(()=>{
              this.buffers[track] = new Buffer(this, mimeCodec);
          });
      }

      feed(track, data) {
          if (this.buffers[track]) {
              this.buffers[track].feed(data);
          }
      }
  }

  const Log$5 = getTagged('remuxer:base');
  let track_id = 1;
  class BaseRemuxer {

      static get MP4_TIMESCALE() { return 90000;}

      // TODO: move to ts parser
      // static PTSNormalize(value, reference) {
      //
      //     let offset;
      //     if (reference === undefined) {
      //         return value;
      //     }
      //     if (reference < value) {
      //         // - 2^33
      //         offset = -8589934592;
      //     } else {
      //         // + 2^33
      //         offset = 8589934592;
      //     }
      //     /* PTS is 33bit (from 0 to 2^33 -1)
      //      if diff between value and reference is bigger than half of the amplitude (2^32) then it means that
      //      PTS looping occured. fill the gap */
      //     while (Math.abs(value - reference) > 4294967296) {
      //         value += offset;
      //     }
      //     return value;
      // }

      static getTrackID() {
          return track_id++;
      }

      constructor(timescale, scaleFactor, params) {
          this.timeOffset = 0;
          this.timescale = timescale;
          this.scaleFactor = scaleFactor;
          this.readyToDecode = false;
          this.samples = [];
          this.seq = 1;
          this.tsAlign = 1;
      }

      shifted(timestamp) {
          return timestamp - this.timeOffset;
      }

      scaled(timestamp) {
          return timestamp / this.scaleFactor;
      }

      unscaled(timestamp) {
          return timestamp * this.scaleFactor;
      }

      remux(unit) {
          if (unit && this.timeOffset >= 0) {
              this.samples.push({
                  unit: unit,
                  pts: this.shifted(unit.pts),
                  dts: this.shifted(unit.dts)
              });
              return true;
          }
          return false;
      }

      static toMS(timestamp) {
          return timestamp/90;
      }
      
      setConfig(config) {
          
      }

      insertDscontinuity() {
          this.samples.push(null);
      }

      init(initPTS, initDTS, shouldInitialize=true) {
          this.initPTS = Math.min(initPTS, this.samples[0].dts - this.unscaled(this.timeOffset));
          this.initDTS = Math.min(initDTS, this.samples[0].dts - this.unscaled(this.timeOffset));
          Log$5.debug(`Initial pts=${this.initPTS} dts=${this.initDTS}`);
          this.initialized = shouldInitialize;
      }

      flush() {
          this.seq++;
          this.mp4track.len = 0;
          this.mp4track.samples = [];
      }

      getPayloadBase(sampleFunction, setupSample) {
          if (!this.readyToDecode || !this.initialized || !this.samples.length) return null;
          this.samples.sort(function(a, b) {
              return (a.dts-b.dts);
          });
          return true;

          let payload = new Uint8Array(this.mp4track.len);
          let offset = 0;
          let samples=this.mp4track.samples;
          let mp4Sample, lastDTS, pts, dts;

          while (this.samples.length) {
              let sample = this.samples.shift();
              if (sample === null) {
                  // discontinuity
                  this.nextDts = undefined;
                  break;
              }

              let unit = sample.unit;

              pts = Math.round((sample.pts - this.initDTS)/this.tsAlign)*this.tsAlign;
              dts = Math.round((sample.dts - this.initDTS)/this.tsAlign)*this.tsAlign;
              // ensure DTS is not bigger than PTS
              dts = Math.min(pts, dts);

              // sampleFunction(pts, dts);   // TODO:

              // mp4Sample = setupSample(unit, pts, dts);    // TODO:

              payload.set(unit.getData(), offset);
              offset += unit.getSize();

              samples.push(mp4Sample);
              lastDTS = dts;
          }
          if (!samples.length) return null;

          // samplesPostFunction(samples); // TODO:

          return new Uint8Array(payload.buffer, 0, this.mp4track.len);
      }
  }

  // TODO: asm.js

  function appendByteArray(buffer1, buffer2) {
      let tmp = new Uint8Array((buffer1.byteLength|0) + (buffer2.byteLength|0));
      tmp.set(buffer1, 0);
      tmp.set(buffer2, buffer1.byteLength|0);
      return tmp;
  }

  function base64ToArrayBuffer(base64) {
      var binary_string =  window.atob(base64);
      var len = binary_string.length;
      var bytes = new Uint8Array( len );
      for (var i = 0; i < len; i++)        {
          bytes[i] = binary_string.charCodeAt(i);
      }
      return bytes.buffer;
  }

  function hexToByteArray(hex) {
      let len = hex.length >> 1;
      var bufView = new Uint8Array(len);
      for (var i = 0; i < len; i++) {
          bufView[i] = parseInt(hex.substr(i<<1,2),16);
      }
      return bufView;
  }

  function bitSlice(bytearray, start=0, end=bytearray.byteLength*8) {
      let byteLen = Math.ceil((end-start)/8);
      let res = new Uint8Array(byteLen);
      let startByte = start >>> 3;   // /8
      let endByte = (end>>>3) - 1;    // /8
      let bitOffset = start & 0x7;     // %8
      let nBitOffset = 8 - bitOffset;
      let endOffset = 8 - end & 0x7;   // %8
      for (let i=0; i<byteLen; ++i) {
          let tail = 0;
          if (i<endByte) {
              tail = bytearray[startByte+i+1] >> nBitOffset;
              if (i == endByte-1 && endOffset < 8) {
                  tail >>= endOffset;
                  tail <<= endOffset;
              }
          }
          res[i]=(bytearray[startByte+i]<<bitOffset) | tail;
      }
      return res;
  }

  class BitArray {

      constructor(src) {
          this.src    = new DataView(src.buffer, src.byteOffset, src.byteLength);
          this.bitpos = 0;
          this.byte   = this.src.getUint8(0); /* This should really be undefined, uint wont allow it though */
          this.bytepos = 0;
      }

      readBits(length) {
          if (32 < (length|0) || 0 === (length|0)) {
              /* To big for an uint */
              throw new Error("too big");
          }

          let result = 0;
          for (let i = length; i > 0; --i) {

              /* Shift result one left to make room for another bit,
               then add the next bit on the stream. */
              result = ((result|0) << 1) | (((this.byte|0) >> (8 - (++this.bitpos))) & 0x01);
              if ((this.bitpos|0)>=8) {
                  this.byte = this.src.getUint8(++this.bytepos);
                  this.bitpos &= 0x7;
              }
          }

          return result;
      }
      skipBits(length) {
          this.bitpos += (length|0) & 0x7; // %8
          this.bytepos += (length|0) >>> 3;  // *8
          if (this.bitpos > 7) {
              this.bitpos &= 0x7;
              ++this.bytepos;
          }

          if (!this.finished()) {
              this.byte = this.src.getUint8(this.bytepos);
              return 0;
          } else {
              return this.bytepos-this.src.byteLength-this.src.bitpos;
          }
      }
      
      finished() {
          return this.bytepos >= this.src.byteLength;
      }
  }

  const Log$3 = getTagged("remuxer:aac");
  // TODO: asm.js
  class AACRemuxer extends BaseRemuxer {

      constructor(timescale, scaleFactor = 1, params={}) {
          super(timescale, scaleFactor);

          this.codecstring=MSE.CODEC_AAC;
          this.units = [];
          this.initDTS = undefined;
          this.nextAacPts = undefined;
          this.lastPts = 0;
          this.firstDTS = 0;
          this.firstPTS = 0;
          this.duration = params.duration || 1;
          this.initialized = false;

          this.mp4track={
              id:BaseRemuxer.getTrackID(),
              type: 'audio',
              fragmented:true,
              channelCount:0,
              audiosamplerate: this.timescale,
              duration: 0,
              timescale: this.timescale,
              volume: 1,
              samples: [],
              config: '',
              len: 0
          };
          if (params.config) {
              this.setConfig(params.config);
          }
      }

      setConfig(config) {
          this.mp4track.channelCount = config.channels;
          this.mp4track.audiosamplerate = config.samplerate;
          if (!this.mp4track.duration) {
              this.mp4track.duration = (this.duration?this.duration:1)*config.samplerate;
          }
          this.mp4track.timescale = config.samplerate;
          this.mp4track.config = config.config;
          this.mp4track.codec = config.codec;
          this.timescale = config.samplerate;
          this.scaleFactor = BaseRemuxer.MP4_TIMESCALE / config.samplerate;
          this.expectedSampleDuration = 1024 * this.scaleFactor;
          this.readyToDecode = true;
      }

      remux(aac) {
          if (super.remux.call(this, aac)) {
              this.mp4track.len += aac.getSize();
          }
      }
      
      getPayload() {
          if (!this.readyToDecode || !this.samples.length) return null;
          this.samples.sort(function(a, b) {
              return (a.dts-b.dts);
          });

          let payload = new Uint8Array(this.mp4track.len);
          let offset = 0;
          let samples=this.mp4track.samples;
          let mp4Sample, lastDTS, pts, dts;

          while (this.samples.length) {
              let sample = this.samples.shift();
              if (sample === null) {
                  // discontinuity
                  this.nextDts = undefined;
                  break;
              }
              let unit = sample.unit;
              pts = sample.pts - this.initDTS;
              dts = sample.dts - this.initDTS;

              if (lastDTS === undefined) {
                  if (this.nextDts) {
                      let delta = Math.round(this.scaled(pts - this.nextAacPts));
                      // if fragment are contiguous, or delta less than 600ms, ensure there is no overlap/hole between fragments
                      if (/*contiguous || */Math.abs(delta) < 600) {
                          // log delta
                          if (delta) {
                              if (delta > 0) {
                                  Log$3.log(`${delta} ms hole between AAC samples detected,filling it`);
                                  // if we have frame overlap, overlapping for more than half a frame duraion
                              } else if (delta < -12) {
                                  // drop overlapping audio frames... browser will deal with it
                                  Log$3.log(`${(-delta)} ms overlapping between AAC samples detected, drop frame`);
                                  this.mp4track.len -= unit.getSize();
                                  continue;
                              }
                              // set DTS to next DTS
                              pts = dts = this.nextAacPts;
                          }
                      }
                  }
                  // remember first PTS of our aacSamples, ensure value is positive
                  this.firstDTS = Math.max(0, dts);
              }

              mp4Sample = {
                  size: unit.getSize(),
                  cts: 0,
                  duration:1024,
                  flags: {
                      isLeading: 0,
                      isDependedOn: 0,
                      hasRedundancy: 0,
                      degradPrio: 0,
                      dependsOn: 1
                  }
              };

              payload.set(unit.getData(), offset);
              offset += unit.getSize();
              samples.push(mp4Sample);
              lastDTS = dts;
          }
          if (!samples.length) return null;
          this.nextDts =pts+this.expectedSampleDuration;
          return new Uint8Array(payload.buffer, 0, this.mp4track.len);
      }
  }

  class NALU {

      static get NDR() {return 1;}
      static get IDR() {return 5;}
      static get SEI() {return 6;}
      static get SPS() {return 7;}
      static get PPS() {return 8;}

      static get TYPES() {return {
          [NALU.IDR]: 'IDR',
          [NALU.SEI]: 'SEI',
          [NALU.SPS]: 'SPS',
          [NALU.PPS]: 'PPS',
          [NALU.NDR]: 'NDR'
      }};

      static type(nalu) {
          if (nalu.ntype in NALU.TYPES) {
              return NALU.TYPES[nalu.ntype];
          } else {
              return 'UNKNOWN';
          }
      }

      constructor(ntype, nri, data, dts, pts) {

          this.data = data;
          this.ntype = ntype;
          this.nri = nri;
          this.dts = dts;
          this.pts = pts ? pts : this.dts;

      }

      appendData(idata) {
          this.data = appendByteArray(this.data, idata);
      }

      type() {
          return this.ntype;
      }

      isKeyframe() {
          return this.ntype == NALU.IDR;
      }

      getSize() {
          return 4 + 1 + this.data.byteLength;
      }

      getData() {
          let header = new Uint8Array(5 + this.data.byteLength);
          let view = new DataView(header.buffer);
          view.setUint32(0, this.data.byteLength + 1);
          view.setUint8(4, (0x0 & 0x80) | (this.nri & 0x60) | (this.ntype & 0x1F));
          header.set(this.data, 5);
          return header;
      }
  }

  // TODO: asm.js
  class NALUAsm {
      static get NALTYPE_FU_A() {return 28;}
      static get NALTYPE_FU_B() {return 29;}

      constructor() {
          this.nalu = null;
      }

      onNALUFragment(rawData, dts, pts) {

          var data = new DataView(rawData.buffer, rawData.byteOffset, rawData.byteLength);

          var nalhdr = data.getUint8(0);

          var nri = nalhdr & 0x60;
          var naltype = nalhdr & 0x1F;
          var nal_start_idx = 1;

          if (27 >= naltype && 0 < naltype) {
              /* This RTP package is a single NALU, dispatch and forget, 0 is undefined */
              return new NALU(naltype, nri, rawData.subarray(nal_start_idx), dts, pts);
              //return;
          }

          if (NALUAsm.NALTYPE_FU_A !== naltype && NALUAsm.NALTYPE_FU_B !== naltype) {
              /* 30 - 31 is undefined, ignore those (RFC3984). */
              Log.log('Undefined NAL unit, type: ' + naltype);
              return null;
          }
          nal_start_idx++;

          var nalfrag = data.getUint8(1);
          var nfstart = (nalfrag & 0x80) >>> 7;
          var nfend = (nalfrag & 0x40) >>> 6;
          var nftype = nalfrag & 0x1F;

          var nfdon = 0;
          if (NALUAsm.NALTYPE_FU_B === naltype) {
              nfdon = data.getUint16(2);
              nal_start_idx+=2;
          }

          if (null === this.nalu || nfstart) {
              if (!nfstart) {
                  console.log('broken chunk (continuation of lost frame)');
                  return null;
              }  // Ignore broken chunks

              /* Create a new NAL unit from multiple fragmented NAL units */
              this.nalu = new NALU(nftype, nri, rawData.subarray(nal_start_idx), dts, pts);
          } else {
              if (this.nalu.dts != dts) {
                  // Frames lost
                  console.log('broken chunk (continuation of frame with other timestamp)');
                  this.nalu = null;
                  return null;
              }
              /* We've already created the NAL unit, append current data */
              this.nalu.appendData(rawData.subarray(nal_start_idx));
          }

          if (1 === nfend) {
              let ret = this.nalu;
              this.nalu = null;
              return ret;
          }
      }
  }

  class ExpGolomb {

    constructor(data) {
      this.data = data;
      // the number of bytes left to examine in this.data
      this.bytesAvailable = this.data.byteLength;
      // the current word being examined
      this.word = 0; // :uint
      // the number of bits left to examine in the current word
      this.bitsAvailable = 0; // :uint
    }

    // ():void
    loadWord() {
      var
        position = this.data.byteLength - this.bytesAvailable,
        workingBytes = new Uint8Array(4),
        availableBytes = Math.min(4, this.bytesAvailable);
      if (availableBytes === 0) {
        throw new Error('no bytes available');
      }
      workingBytes.set(this.data.subarray(position, position + availableBytes));
      this.word = new DataView(workingBytes.buffer, workingBytes.byteOffset, workingBytes.byteLength).getUint32(0);
      // track the amount of this.data that has been processed
      this.bitsAvailable = availableBytes * 8;
      this.bytesAvailable -= availableBytes;
    }

    // (count:int):void
    skipBits(count) {
      var skipBytes; // :int
      if (this.bitsAvailable > count) {
        this.word <<= count;
        this.bitsAvailable -= count;
      } else {
        count -= this.bitsAvailable;
        skipBytes = count >> 3;
        count -= (skipBytes >> 3);
        this.bytesAvailable -= skipBytes;
        this.loadWord();
        this.word <<= count;
        this.bitsAvailable -= count;
      }
    }

    // (size:int):uint
    readBits(size) {
      var
        bits = Math.min(this.bitsAvailable, size), // :uint
        valu = this.word >>> (32 - bits); // :uint
      if (size > 32) {
        Log.error('Cannot read more than 32 bits at a time');
      }
      this.bitsAvailable -= bits;
      if (this.bitsAvailable > 0) {
        this.word <<= bits;
      } else if (this.bytesAvailable > 0) {
        this.loadWord();
      }
      bits = size - bits;
      if (bits > 0) {
        return valu << bits | this.readBits(bits);
      } else {
        return valu;
      }
    }

    // ():uint
    skipLZ() {
      var leadingZeroCount; // :uint
      for (leadingZeroCount = 0; leadingZeroCount < this.bitsAvailable; ++leadingZeroCount) {
        if (0 !== (this.word & (0x80000000 >>> leadingZeroCount))) {
          // the first bit of working word is 1
          this.word <<= leadingZeroCount;
          this.bitsAvailable -= leadingZeroCount;
          return leadingZeroCount;
        }
      }
      // we exhausted word and still have not found a 1
      this.loadWord();
      return leadingZeroCount + this.skipLZ();
    }

    // ():void
    skipUEG() {
      this.skipBits(1 + this.skipLZ());
    }

    // ():void
    skipEG() {
      this.skipBits(1 + this.skipLZ());
    }

    // ():uint
    readUEG() {
      var clz = this.skipLZ(); // :uint
      return this.readBits(clz + 1) - 1;
    }

    // ():int
    readEG() {
      var valu = this.readUEG(); // :int
      if (0x01 & valu) {
        // the number is odd if the low order bit is set
        return (1 + valu) >>> 1; // add 1 to make it even, and divide by 2
      } else {
        return -1 * (valu >>> 1); // divide by two then make it negative
      }
    }

    // Some convenience functions
    // :Boolean
    readBoolean() {
      return 1 === this.readBits(1);
    }

    // ():int
    readUByte() {
      return this.readBits(8);
    }

    // ():int
    readUShort() {
      return this.readBits(16);
    }
      // ():int
    readUInt() {
      return this.readBits(32);
    }  
  }

  class H264Parser {

      constructor(remuxer) {
          this.remuxer = remuxer;
          this.track = remuxer.mp4track;
      }

      msToScaled(timestamp) {
          return (timestamp - this.remuxer.timeOffset) * this.remuxer.scaleFactor;
      }

      parseSPS(sps) {
          var config = H264Parser.readSPS(new Uint8Array(sps));

          this.track.width = config.width;
          this.track.height = config.height;
          this.track.sps = [new Uint8Array(sps)];
          // this.track.timescale = this.remuxer.timescale;
          // this.track.duration = this.remuxer.timescale; // TODO: extract duration for non-live client
          this.track.codec = 'avc1.';

          let codecarray = new DataView(sps.buffer, sps.byteOffset+1, 4);
          for (let i = 0; i < 3; ++i) {
              var h = codecarray.getUint8(i).toString(16);
              if (h.length < 2) {
                  h = '0' + h;
              }
              this.track.codec  += h;
          }
      }

      parsePPS(pps) {
          this.track.pps = [new Uint8Array(pps)];
      }

      parseNAL(unit) {
          if (!unit) return false;
          
          let push = false;
          switch (unit.type()) {
              case NALU.NDR:
                  push = true;
                  break;
              case NALU.IDR:
                  push = true;
                  break;
              case NALU.PPS:
                  if (!this.track.pps) {
                      this.parsePPS(unit.getData().subarray(4));
                      if (!this.remuxer.readyToDecode && this.track.pps && this.track.sps) {
                          this.remuxer.readyToDecode = true;
                      }
                  }
                  break;
              case NALU.SPS:
                  if(!this.track.sps) {
                      this.parseSPS(unit.getData().subarray(4));
                      if (!this.remuxer.readyToDecode && this.track.pps && this.track.sps) {
                          this.remuxer.readyToDecode = true;
                      }
                  }
                  break;
              case NALU.SEI:
                  // console.log('SEI');
                  break;
              default:
          }
          return push;
      }

      /**
       * Advance the ExpGolomb decoder past a scaling list. The scaling
       * list is optionally transmitted as part of a sequence parameter
       * set and is not relevant to transmuxing.
       * @param decoder {ExpGolomb} exp golomb decoder
       * @param count {number} the number of entries in this scaling list
       * @see Recommendation ITU-T H.264, Section 7.3.2.1.1.1
       */
      static skipScalingList(decoder, count) {
          let lastScale = 8,
              nextScale = 8,
              deltaScale;
          for (let j = 0; j < count; j++) {
              if (nextScale !== 0) {
                  deltaScale = decoder.readEG();
                  nextScale = (lastScale + deltaScale + 256) % 256;
              }
              lastScale = (nextScale === 0) ? lastScale : nextScale;
          }
      }

      /**
       * Read a sequence parameter set and return some interesting video
       * properties. A sequence parameter set is the H264 metadata that
       * describes the properties of upcoming video frames.
       * @param data {Uint8Array} the bytes of a sequence parameter set
       * @return {object} an object with configuration parsed from the
       * sequence parameter set, including the dimensions of the
       * associated video frames.
       */
      static readSPS(data) {
          let decoder = new ExpGolomb(data);
          let frameCropLeftOffset = 0,
              frameCropRightOffset = 0,
              frameCropTopOffset = 0,
              frameCropBottomOffset = 0,
              sarScale = 1,
              profileIdc,profileCompat,levelIdc,
              numRefFramesInPicOrderCntCycle, picWidthInMbsMinus1,
              picHeightInMapUnitsMinus1,
              frameMbsOnlyFlag,
              scalingListCount;
          decoder.readUByte();
          profileIdc = decoder.readUByte(); // profile_idc
          profileCompat = decoder.readBits(5); // constraint_set[0-4]_flag, u(5)
          decoder.skipBits(3); // reserved_zero_3bits u(3),
          levelIdc = decoder.readUByte(); //level_idc u(8)
          decoder.skipUEG(); // seq_parameter_set_id
          // some profiles have more optional data we don't need
          if (profileIdc === 100 ||
              profileIdc === 110 ||
              profileIdc === 122 ||
              profileIdc === 244 ||
              profileIdc === 44  ||
              profileIdc === 83  ||
              profileIdc === 86  ||
              profileIdc === 118 ||
              profileIdc === 128) {
              var chromaFormatIdc = decoder.readUEG();
              if (chromaFormatIdc === 3) {
                  decoder.skipBits(1); // separate_colour_plane_flag
              }
              decoder.skipUEG(); // bit_depth_luma_minus8
              decoder.skipUEG(); // bit_depth_chroma_minus8
              decoder.skipBits(1); // qpprime_y_zero_transform_bypass_flag
              if (decoder.readBoolean()) { // seq_scaling_matrix_present_flag
                  scalingListCount = (chromaFormatIdc !== 3) ? 8 : 12;
                  for (let i = 0; i < scalingListCount; ++i) {
                      if (decoder.readBoolean()) { // seq_scaling_list_present_flag[ i ]
                          if (i < 6) {
                              H264Parser.skipScalingList(decoder, 16);
                          } else {
                              H264Parser.skipScalingList(decoder, 64);
                          }
                      }
                  }
              }
          }
          decoder.skipUEG(); // log2_max_frame_num_minus4
          var picOrderCntType = decoder.readUEG();
          if (picOrderCntType === 0) {
              decoder.readUEG(); //log2_max_pic_order_cnt_lsb_minus4
          } else if (picOrderCntType === 1) {
              decoder.skipBits(1); // delta_pic_order_always_zero_flag
              decoder.skipEG(); // offset_for_non_ref_pic
              decoder.skipEG(); // offset_for_top_to_bottom_field
              numRefFramesInPicOrderCntCycle = decoder.readUEG();
              for(let i = 0; i < numRefFramesInPicOrderCntCycle; ++i) {
                  decoder.skipEG(); // offset_for_ref_frame[ i ]
              }
          }
          decoder.skipUEG(); // max_num_ref_frames
          decoder.skipBits(1); // gaps_in_frame_num_value_allowed_flag
          picWidthInMbsMinus1 = decoder.readUEG();
          picHeightInMapUnitsMinus1 = decoder.readUEG();
          frameMbsOnlyFlag = decoder.readBits(1);
          if (frameMbsOnlyFlag === 0) {
              decoder.skipBits(1); // mb_adaptive_frame_field_flag
          }
          decoder.skipBits(1); // direct_8x8_inference_flag
          if (decoder.readBoolean()) { // frame_cropping_flag
              frameCropLeftOffset = decoder.readUEG();
              frameCropRightOffset = decoder.readUEG();
              frameCropTopOffset = decoder.readUEG();
              frameCropBottomOffset = decoder.readUEG();
          }
          if (decoder.readBoolean()) {
              // vui_parameters_present_flag
              if (decoder.readBoolean()) {
                  // aspect_ratio_info_present_flag
                  let sarRatio;
                  const aspectRatioIdc = decoder.readUByte();
                  switch (aspectRatioIdc) {
                      case 1: sarRatio = [1,1]; break;
                      case 2: sarRatio = [12,11]; break;
                      case 3: sarRatio = [10,11]; break;
                      case 4: sarRatio = [16,11]; break;
                      case 5: sarRatio = [40,33]; break;
                      case 6: sarRatio = [24,11]; break;
                      case 7: sarRatio = [20,11]; break;
                      case 8: sarRatio = [32,11]; break;
                      case 9: sarRatio = [80,33]; break;
                      case 10: sarRatio = [18,11]; break;
                      case 11: sarRatio = [15,11]; break;
                      case 12: sarRatio = [64,33]; break;
                      case 13: sarRatio = [160,99]; break;
                      case 14: sarRatio = [4,3]; break;
                      case 15: sarRatio = [3,2]; break;
                      case 16: sarRatio = [2,1]; break;
                      case 255: {
                          sarRatio = [decoder.readUByte() << 8 | decoder.readUByte(), decoder.readUByte() << 8 | decoder.readUByte()];
                          break;
                      }
                  }
                  if (sarRatio) {
                      sarScale = sarRatio[0] / sarRatio[1];
                  }
              }
              if (decoder.readBoolean()) {decoder.skipBits(1);}

              if (decoder.readBoolean()) {
                  decoder.skipBits(4);
                  if (decoder.readBoolean()) {
                      decoder.skipBits(24);
                  }
              }
              if (decoder.readBoolean()) {
                  decoder.skipUEG();
                  decoder.skipUEG();
              }
              if (decoder.readBoolean()) {
                  let unitsInTick = decoder.readUInt();
                  let timeScale = decoder.readUInt();
                  let fixedFrameRate = decoder.readBoolean();
                  let frameDuration = timeScale/(2*unitsInTick);
                  console.log(`timescale: ${timeScale}; unitsInTick: ${unitsInTick}; fixedFramerate: ${fixedFrameRate}; avgFrameDuration: ${frameDuration}`);
              }
          }
          return {
              width: Math.ceil((((picWidthInMbsMinus1 + 1) * 16) - frameCropLeftOffset * 2 - frameCropRightOffset * 2) * sarScale),
              height: ((2 - frameMbsOnlyFlag) * (picHeightInMapUnitsMinus1 + 1) * 16) - ((frameMbsOnlyFlag? 2 : 4) * (frameCropTopOffset + frameCropBottomOffset))
          };
      }

      static readSliceType(decoder) {
          // skip NALu type
          decoder.readUByte();
          // discard first_mb_in_slice
          decoder.readUEG();
          // return slice_type
          return decoder.readUEG();
      }
  }

  const Log$6 = getTagged("remuxer:h264"); 
  // TODO: asm.js
  class H264Remuxer extends BaseRemuxer {

      constructor(timescale, scaleFactor=1, params={}) {
          super(timescale, scaleFactor);

          this.nextDts = undefined;
          this.readyToDecode = false;
          this.initialized = false;

          this.firstDTS=0;
          this.firstPTS=0;
          this.lastDTS=undefined;
          this.lastSampleDuration = 0;
          // this.timescale = 90000;
          this.tsAlign = Math.round(this.timescale/60);

          this.mp4track={
              id:BaseRemuxer.getTrackID(),
              type: 'video',
              len:0,
              fragmented:true,
              sps:'',
              pps:'',
              width:0,
              height:0,
              timescale: timescale,
              duration: timescale,
              samples: []
          };
          this.samples = [];

          this.h264 = new H264Parser(this);

          if (params.sps) {
              this.setSPS(new Uint8Array(params.sps));
          }
          if (params.pps) {
              this.setPPS(new Uint8Array(params.pps));
          }

          if (this.mp4track.pps && this.mp4track.sps) {
              this.readyToDecode = true;
          }
      }

      _scaled(timestamp) {
          return timestamp >>> this.scaleFactor;
      }

      _unscaled(timestamp) {
          return timestamp << this.scaleFactor;
      }

      setSPS(sps) {
          this.h264.parseSPS(sps);
      }

      setPPS(pps) {
          this.h264.parsePPS(pps);
      }

      remux(nalu) {
          if (this.h264.parseNAL(nalu) && super.remux.call(this, nalu)) {
              this.mp4track.len += nalu.getSize();
          }
      }

      getPayload() {
          if (!this.getPayloadBase()) {
              return null;
          }

          let payload = new Uint8Array(this.mp4track.len);
          let offset = 0;
          let samples=this.mp4track.samples;
          let mp4Sample, lastDTS, pts, dts;


          // Log.debug(this.samples.map((e)=>{
          //     return Math.round((e.dts - this.initDTS)/this.tsAlign)*this.tsAlign;
          // }));

          let minDuration = Number.MAX_SAFE_INTEGER;
          while (this.samples.length) {
              let sample = this.samples.shift();
              if (sample === null) {
                  // discontinuity
                  this.nextDts = undefined;
                  break;
              }

              let unit = sample.unit;
              
              pts = /*Math.round(*/(sample.pts - this.initDTS)/*/this.tsAlign)*this.tsAlign*/;
              dts = /*Math.round(*/(sample.dts - this.initDTS)/*/this.tsAlign)*this.tsAlign*/;
              // ensure DTS is not bigger than PTS
              dts = Math.min(pts,dts);

              // if not first AVC sample of video track, normalize PTS/DTS with previous sample value
              // and ensure that sample duration is positive
              if (lastDTS !== undefined) {
                  let sampleDuration = this.scaled(dts - lastDTS);
                  minDuration = Math.min(sampleDuration, minDuration);
                  // Log.debug(`Sample duration: ${sampleDuration}`);
                  if (sampleDuration <= 0) {
                      Log$6.log(`invalid AVC sample duration at PTS/DTS: ${pts}/${dts}|lastDTS: ${lastDTS}:${sampleDuration}`);
                      this.mp4track.len -= unit.getSize();
                      continue;
                  }
                  mp4Sample.duration = sampleDuration;
              } else {
                  if (this.nextDts) {
                      let delta = dts - this.nextDts;
                      // if fragment are contiguous, or delta less than 600ms, ensure there is no overlap/hole between fragments
                      if (/*contiguous ||*/ Math.abs(Math.round(BaseRemuxer.toMS(delta))) < 600) {

                          if (delta) {
                              // set DTS to next DTS
                              // Log.debug(`Video/PTS/DTS adjusted: ${pts}->${Math.max(pts - delta, this.nextDts)}/${dts}->${this.nextDts},delta:${delta}`);
                              dts = this.nextDts;
                              // offset PTS as well, ensure that PTS is smaller or equal than new DTS
                              pts = Math.max(pts - delta, dts);
                          }
                      } else {
                          if (delta < 0) {
                              Log$6.log(`skip frame from the past at DTS=${dts} with expected DTS=${this.nextDts}`);
                              this.mp4track.len -= unit.getSize();
                              continue;
                          }
                      }
                  }
                  // remember first DTS of our avcSamples, ensure value is positive
                  this.firstDTS = Math.max(0, dts);
              }

              mp4Sample = {
                  size: unit.getSize(),
                  duration: 0,
                  cts: this.scaled(pts - dts),
                  flags: {
                      isLeading: 0,
                      isDependedOn: 0,
                      hasRedundancy: 0,
                      degradPrio: 0
                  }
              };
              let flags = mp4Sample.flags;
              if (sample.unit.isKeyframe() === true) {
                  // the current sample is a key frame
                  flags.dependsOn = 2;
                  flags.isNonSync = 0;
              } else {
                  flags.dependsOn = 1;
                  flags.isNonSync = 1;
              }

              payload.set(unit.getData(), offset);
              offset += unit.getSize();

              samples.push(mp4Sample);
              lastDTS = dts;
          }

          if (!samples.length) return null;

          if (samples.length >= 2) {
              this.lastSampleDuration = minDuration;
              mp4Sample.duration = minDuration;
          } else {
              mp4Sample.duration = this.lastSampleDuration;
          }

          if(samples.length && (!this.nextDts /*|| navigator.userAgent.toLowerCase().indexOf('chrome') > -1*/)) {
              let flags = samples[0].flags;
              // chrome workaround, mark first sample as being a Random Access Point to avoid sourcebuffer append issue
              // https://code.google.com/p/chromium/issues/detail?id=229412
              flags.dependsOn = 2;
              flags.isNonSync = 0;
          }

          // next AVC sample DTS should be equal to last sample DTS + last sample duration
          this.nextDts = dts + this.unscaled(this.lastSampleDuration);

          return new Uint8Array(payload.buffer, 0, this.mp4track.len);
      }
  }

  class StreamType$1 {
      static get VIDEO() {return 1;}
      static get AUDIO() {return 2;}

      static get map() {return {
          [StreamType$1.VIDEO]: 'video',
          [StreamType$1.AUDIO]: 'audio'
      }};
  }

  class PayloadType {
      static get H264() {return 1;}
      static get AAC() {return 2;}

      static get map() {return {
          [PayloadType.H264]: 'video',
          [PayloadType.AAC]: 'audio'
      }};

      static get string_map() {return  {
          H264: PayloadType.H264,
          AAC: PayloadType.AAC,
          'MP4A-LATM': PayloadType.AAC
      }}
  }

  const LOG_TAG = "remuxer";
  const Log$2 = getTagged(LOG_TAG);

  class Remuxer {
      static get TrackConverters() {return {
          [PayloadType.H264]: H264Remuxer,
          [PayloadType.AAC]:  AACRemuxer
      }};

      static get TrackScaleFactor() {return {
          [PayloadType.H264]: 1,//4,
          [PayloadType.AAC]:  0
      }};

      static get TrackTimescale() {return {
          [PayloadType.H264]: 90000,//22500,
          [PayloadType.AAC]:  0
      }};

      constructor(mediaElement) {
          this.mse = new MSE([mediaElement]);
          this.eventSource = new EventEmitter();
          this.mse_ready = true;
          this.transport = null;

          this.reset();

          this.errorListener = this.mseClose.bind(this);
          this.closeListener = this.mseClose.bind(this);
          this.samplesListener = this.onSamples.bind(this);
          this.audioConfigListener = this.onAudioConfig.bind(this);

          this.mse.eventSource.addEventListener('error', this.errorListener);
          this.mse.eventSource.addEventListener('sourceclose', this.closeListener);
          
          this.eventSource.addEventListener('ready', this.init.bind(this));
      }

      reset() {
          this.tracks = {};
          this.initialized = false;
          this.initSegments = {};
          this.codecs = [];
          this.streams = {};
          this.enabled = false;
      }

      destroy() {
          this.mse.destroy();
          this.mse = null;

          this.detachClient();

          this.eventSource.destroy();
      }

      onTracks(tracks) {
          Log$2.debug(tracks.detail);
          // store available track types
          for (let track of tracks.detail) {
              this.tracks[track.type] = new Remuxer.TrackConverters[track.type](Remuxer.TrackTimescale[track.type], Remuxer.TrackScaleFactor[track.type], track.params);
              if (track.offset) {
                  this.tracks[track.type].timeOffset = track.offset;
              }
              if (track.duration) {
                  this.tracks[track.type].mp4track.duration = track.duration*(this.tracks[track.type].timescale || Remuxer.TrackTimescale[track.type]);
                  this.tracks[track.type].duration = track.duration;
              } else {
                  this.tracks[track.type].duration = 1;
              }

              // this.tracks[track.type].duration
          }
      }

      setTimeOffset(timeOffset, track) {
          if (this.tracks[track.type]) {
              this.tracks[track.type].timeOffset = timeOffset;///this.tracks[track.type].scaleFactor;
          }
      }

      init() {
          let tracks = [];
          this.codecs = [];
          let initmse = [];
          let initPts = Infinity;
          let initDts = Infinity;
          for (let track_type in this.tracks) {
              let track = this.tracks[track_type];
              if (!MSE.isSupported([track.mp4track.codec])) {
                  throw new Error(`${track.mp4track.type} codec ${track.mp4track.codec} is not supported`);
              }
              tracks.push(track.mp4track);
              this.codecs.push(track.mp4track.codec);
              track.init(initPts, initDts/*, false*/);
              // initPts = Math.min(track.initPTS, initPts);
              // initDts = Math.min(track.initDTS, initDts);
          }

          for (let track_type in this.tracks) {
              let track = this.tracks[track_type];
              //track.init(initPts, initDts);
              this.initSegments[track_type] = MP4.initSegment([track.mp4track], track.duration*track.timescale, track.timescale);
              initmse.push(this.initMSE(track_type, track.mp4track.codec));
          }
          this.initialized = true;
          Promise.all(initmse).then(()=>{
              this.mse.play();
              this.enabled = true;
          });
          
      }

      initMSE(track_type, codec) {
          if (MSE.isSupported(this.codecs)) {
              return this.mse.setCodec(track_type, `${PayloadType.map[track_type]}/mp4; codecs="${codec}"`).then(()=>{
                  this.mse.feed(track_type, this.initSegments[track_type]);
                  // this.mse.play();
                  // this.enabled = true;
              });
          } else {
              throw new Error('Codecs are not supported');
          }
      }

      mseClose() {
          this.mse.clear();
          this.eventSource.dispatchEvent('stopped');
      }

      flush() {
          this.onSamples();
          if (!this.initialized) {
              for (let track_type in this.tracks) {
                  if (!this.tracks[track_type].readyToDecode || !this.tracks[track_type].samples.length) return;
              }
              this.eventSource.dispatchEvent('ready');
          } else {
              for (let track_type in this.tracks) {
                  let track = this.tracks[track_type];
                  let pay = track.getPayload();
                  if (pay && pay.byteLength) {
                      this.mse.feed(track_type, [MP4.moof(track.seq, track.scaled(track.firstDTS), track.mp4track), MP4.mdat(pay)]);
                      track.flush();
                  }
              }
          }
      }

      onSamples(ev) {
          // TODO: check format
          // let data = ev.detail;
          // if (this.tracks[data.pay] && this.client.sampleQueues[data.pay].length) {
              // console.log(`video ${data.units[0].dts}`);
          for (let qidx in this.client.sampleQueues) {
              let queue = this.client.sampleQueues[qidx];
              while (queue.length) {
                  let units = queue.shift();
                  for (let chunk of units) {
                      this.tracks[qidx].remux(chunk);
                  }
              }
          }
          // }
      }

      onAudioConfig(ev) {
          if (this.tracks[ev.detail.pay]) {
              this.tracks[ev.detail.pay].setConfig(ev.detail.config);
          }
      }

      attachClient(client) {
          this.detachClient();
          this.client = client;
          this.client.eventSource.addEventListener('samples', this.samplesListener);
          this.client.eventSource.addEventListener('audio_config', this.audioConfigListener);
          this.client.eventSource.addEventListener('tracks', this.onTracks.bind(this));
          this.client.eventSource.addEventListener('flush', this.flush.bind(this));
          this.client.eventSource.addEventListener('clear', ()=>{
              this.reset();
              this.mse.clear();
              this.mse.play();
          });
      }

      detachClient() {
          if (this.client) {
              this.client.eventSource.removeEventListener('samples', this.samplesListener);
              this.client.eventSource.removeEventListener('audio_config', this.audioConfigListener);
              // TODO: clear other listeners
              // this.client.eventSource.removeEventListener('clear');
              this.client = null;
          }
      }
  }

  class State {
      constructor(name, stateMachine) {
          this.stateMachine = stateMachine;
          this.transitions = new Set();
          this.name = name;
      }


      activate() {
          return Promise.resolve(null);
      }

      finishTransition() {}

      deactivate() {
          return Promise.resolve(null);
      }
  }

  class StateMachine {
      constructor() {
          this.storage = {};
          this.currentState = null;
          this.states = new Map();
      }

      addState(name, {activate, finishTransition, deactivate}) {
          let state = new State(name, this);
          if (activate) state.activate = activate;
          if (finishTransition) state.finishTransition = finishTransition;
          if (deactivate) state.deactivate = deactivate;
          this.states.set(name, state);
          return this;
      }

      addTransition(fromName, toName){
          if (!this.states.has(fromName)) {
              throw ReferenceError(`No such state: ${fromName} while connecting to ${toName}`);
          }
          if (!this.states.has(toName)) {
              throw ReferenceError(`No such state: ${toName} while connecting from ${fromName}`);
          }
          this.states.get(fromName).transitions.add(toName);
          return this;
      }

      _promisify(res) {
          let promise;
          try {
              promise = res;
              if (!promise.then) {
                  promise = Promise.resolve(promise);
              }
          } catch (e) {
              promise = Promise.reject(e);
          }
          return promise;
      }

      transitionTo(stateName) {
          if (this.currentState == null) {
              let state = this.states.get(stateName);
              return this._promisify(state.activate.call(this))
                  .then((data)=> {
                      this.currentState = state;
                      return data;
                  }).then(state.finishTransition.bind(this));
          }
          if (this.currentState.transitions.has(stateName)) {
              let state = this.states.get(stateName);
              return this._promisify(state.deactivate.call(this))
                  .then(state.activate.bind(this)).then((data)=> {
                      this.currentState = state;
                      return data;
                  }).then(state.finishTransition.bind(this));
          } else {
              return Promise.reject(`No such transition: ${this.currentState.name} to ${stateName}`);
          }
      }

  }

  const Log$8 = getTagged("parser:sdp");

  class SDPParser {
      constructor(){
          this.version = -1;
          this.origin = null;
          this.sessionName = null;
          this.timing = null;
          this.sessionBlock = {};
          this.media = {};
          this.tracks = {};
          this.mediaMap = {};
      }

      parse(content) {
          return new Promise((resolve, reject)=>{
              var dataString = content;
              var success = true;
              var currentMediaBlock = this.sessionBlock;

              // TODO: multiple audio/video tracks

              for (let line of dataString.split("\n")) {
                  line = line.replace(/\r/, '');
                  if (0 === line.length) {
                      /* Empty row (last row perhaps?), skip to next */
                      continue;
                  }

                  switch (line.charAt(0)) {
                      case 'v':
                          if (-1 !== this.version) {
                              Log$8.log('Version present multiple times in SDP');
                              reject();
                              return false;
                          }
                          success = success && this._parseVersion(line);
                          break;

                      case 'o':
                          if (null !== this.origin) {
                              Log$8.log('Origin present multiple times in SDP');
                              reject();
                              return false;
                          }
                          success = success && this._parseOrigin(line);
                          break;

                      case 's':
                          if (null !== this.sessionName) {
                              Log$8.log('Session Name present multiple times in SDP');
                              reject();
                              return false;
                          }
                          success = success && this._parseSessionName(line);
                          break;

                      case 't':
                          if (null !== this.timing) {
                              Log$8.log('Timing present multiple times in SDP');
                              reject();
                              return false;
                          }
                          success = success && this._parseTiming(line);
                          break;

                      case 'm':
                          if (null !== currentMediaBlock && this.sessionBlock !== currentMediaBlock) {
                              /* Complete previous block and store it */
                              this.media[currentMediaBlock.type] = currentMediaBlock;
                          }

                          /* A wild media block appears */
                          currentMediaBlock = {};
                          currentMediaBlock.rtpmap = {};
                          this._parseMediaDescription(line, currentMediaBlock);
                          break;

                      case 'a':
                          SDPParser._parseAttribute(line, currentMediaBlock);
                          break;

                      default:
                          Log$8.log('Ignored unknown SDP directive: ' + line);
                          break;
                  }

                  if (!success) {
                      reject();
                      return;
                  }
              }

              this.media[currentMediaBlock.type] = currentMediaBlock;

              success?resolve():reject();
          });
      }

      _parseVersion(line) {
          var matches = line.match(/^v=([0-9]+)$/);
          if (0 === matches.length) {
              Log$8.log('\'v=\' (Version) formatted incorrectly: ' + line);
              return false;
          }

          this.version = matches[1];
          if (0 != this.version) {
              Log$8.log('Unsupported SDP version:' + this.version);
              return false;
          }

          return true;
      }

      _parseOrigin(line) {
          var matches = line.match(/^o=([^ ]+) ([0-9]+) ([0-9]+) (IN) (IP4|IP6) ([^ ]+)$/);
          if (0 === matches.length) {
              Log$8.log('\'o=\' (Origin) formatted incorrectly: ' + line);
              return false;
          }

          this.origin = {};
          this.origin.username       = matches[1];
          this.origin.sessionid      = matches[2];
          this.origin.sessionversion = matches[3];
          this.origin.nettype        = matches[4];
          this.origin.addresstype    = matches[5];
          this.origin.unicastaddress = matches[6];

          return true;
      }

      _parseSessionName(line) {
          var matches = line.match(/^s=([^\r\n]+)$/);
          if (0 === matches.length) {
              Log$8.log('\'s=\' (Session Name) formatted incorrectly: ' + line);
              return false;
          }

          this.sessionName = matches[1];

          return true;
      }

      _parseTiming(line) {
          var matches = line.match(/^t=([0-9]+) ([0-9]+)$/);
          if (0 === matches.length) {
              Log$8.log('\'t=\' (Timing) formatted incorrectly: ' + line);
              return false;
          }

          this.timing = {};
          this.timing.start = matches[1];
          this.timing.stop  = matches[2];

          return true;
      }

      _parseMediaDescription(line, media) {
          var matches = line.match(/^m=([^ ]+) ([^ ]+) ([^ ]+)[ ]/);
          if (0 === matches.length) {
              Log$8.log('\'m=\' (Media) formatted incorrectly: ' + line);
              return false;
          }

          media.type  = matches[1];
          media.port  = matches[2];
          media.proto = matches[3];
          media.fmt   = line.substr(matches[0].length).split(' ').map(function(fmt, index, array) {
              return parseInt(fmt);
          });

          for (let fmt of media.fmt) {
              this.mediaMap[fmt] = media;
          }

          return true;
      }

      static _parseAttribute(line, media) {
          if (null === media) {
              /* Not in a media block, can't be bothered parsing attributes for session */
              return true;
          }

          var matches; /* Used for some cases of below switch-case */
          var separator = line.indexOf(':');
          var attribute = line.substr(0, (-1 === separator) ? 0x7FFFFFFF : separator); /* 0x7FF.. is default */

          switch (attribute) {
              case 'a=recvonly':
              case 'a=sendrecv':
              case 'a=sendonly':
              case 'a=inactive':
                  media.mode = line.substr('a='.length);
                  break;
              case 'a=range':
                  matches = line.match(/^a=range:\s*([a-zA-Z-]+)=([0-9.]+|now)-([0-9.]*)$/);
                  media.range= [Number(matches[2]=="now"?-1:matches[2]), Number(matches[3]), matches[1]];
                  break;
              case 'a=control':
                  media.control = line.substr('a=control:'.length);
                  break;

              case 'a=rtpmap':
                  matches = line.match(/^a=rtpmap:(\d+) (.*)$/);
                  if (null === matches) {
                      Log$8.log('Could not parse \'rtpmap\' of \'a=\'');
                      return false;
                  }

                  var payload = parseInt(matches[1]);
                  media.rtpmap[payload] = {};

                  var attrs = matches[2].split('/');
                  media.rtpmap[payload].name  = attrs[0];
                  media.rtpmap[payload].clock = attrs[1];
                  if (undefined !== attrs[2]) {
                      media.rtpmap[payload].encparams = attrs[2];
                  }
                  media.ptype = PayloadType.string_map[attrs[0]];

                  break;

              case 'a=fmtp':
                  matches = line.match(/^a=fmtp:(\d+) (.*)$/);
                  if (0 === matches.length) {
                      Log$8.log('Could not parse \'fmtp\'  of \'a=\'');
                      return false;
                  }

                  media.fmtp = {};
                  for (var param of matches[2].split(';')) {
                      var idx = param.indexOf('=');
                      media.fmtp[param.substr(0, idx).toLowerCase().trim()] = param.substr(idx + 1).trim();
                  }
                  break;
          }

          return true;
      }

      getSessionBlock() {
          return this.sessionBlock;
      }

      hasMedia(mediaType) {
          return this.media[mediaType] != undefined;
      }

      getMediaBlock(mediaType) {
          return this.media[mediaType];
      }

      getMediaBlockByPayloadType(pt) {
          // for (var m in this.media) {
          //     if (-1 !== this.media[m].fmt.indexOf(pt)) {
          //         return this.media[m];
          //     }
          // }
          return this.mediaMap[pt] || null;

          //ErrorManager.dispatchError(826, [pt], true);
          // Log.error(`failed to find media with payload type ${pt}`);
          //
          // return null;
      }

      getMediaBlockList() {
          var res = [];
          for (var m in this.media) {
              res.push(m);
          }

          return res;
      }
  }

  const LOG_TAG$3 = "rtsp:stream";
  const Log$9 = getTagged(LOG_TAG$3);

  class RTSPStream {

      constructor(client, track) {
          this.state = null;
          this.client = client;
          this.track = track;
          this.rtpChannel = 1;

          this.stopKeepAlive();
          this.keepaliveInterval = null;
      }

      reset() {
          this.stopKeepAlive();
          this.client.forgetRTPChannel(this.rtpChannel);
          this.client = null;
          this.track = null;
      }

      start() {
          return this.sendSetup().then(this.sendPlay.bind(this));
      }

      stop() {
          return this.sendTeardown();
      }

      getSetupURL(track) {
          var sessionBlock = this.client.sdp.getSessionBlock();
          if (Url.isAbsolute(track.control)) {
              return track.control;
          } else if (Url.isAbsolute(`${sessionBlock.control}${track.control}`)) {
              return `${sessionBlock.control}${track.control}`;
          } else if (Url.isAbsolute(`${this.client.contentBase}${track.control}`)) {
              /* Should probably check session level control before this */
              return `${this.client.contentBase}${track.control}`;
          }

          Log$9.error('Can\'t determine track URL from ' +
              'block.control:' + track.control + ', ' +
              'session.control:' + sessionBlock.control + ', and ' +
              'content-base:' + this.client.contentBase);
      }

      getControlURL() {
          let ctrl = this.client.sdp.getSessionBlock().control;
          if (Url.isAbsolute(ctrl)) {
              return ctrl;
          } else if (!ctrl || '*' === ctrl) {
              return this.client.contentBase;
          } else {
              return `${this.client.contentBase}${ctrl}`;
          }
      }

      sendKeepalive() {
          return this.client.sendRequest('GET_PARAMETER', this.getSetupURL(this.track), {
              'Session': this.session
          });
      }

      stopKeepAlive() {
          clearInterval(this.keepaliveInterval);
      }

      startKeepAlive() {
          this.keepaliveInterval = setInterval(()=>{
              this.sendKeepalive().catch((e)=>{
                  Log$9.error(e);
                  this.client.reconnect();
              });
          }, 30000);
      }

      sendRequest(_cmd, _params={}) {
          let params = {};
          if (this.session) {
              params['Session'] = this.session;
          }
          Object.assign(params, _params);
          return this.client.sendRequest(_cmd, this.getControlURL(), params);
      }

      sendSetup() {
          this.state = RTSPClient$1.STATE_SETUP;
          this.rtpChannel = this.client.interleaveChannelIndex;
          let interleavedChannels = this.client.interleaveChannelIndex++ + "-" + this.client.interleaveChannelIndex++;
          return this.client.sendRequest('SETUP', this.getSetupURL(this.track), {
              'Transport': `RTP/AVP/TCP;unicast;interleaved=${interleavedChannels}`,
              'Date': new Date().toUTCString()
          }).then((_data)=>{
              this.session = _data.headers['session'];
              /*if (!/RTP\/AVP\/TCP;unicast;interleaved=/.test(_data.headers["transport"])) {
                  // TODO: disconnect stream and notify client
                  throw new Error("Connection broken");
              }*/
              this.startKeepAlive();
          });
      }

      sendPlay(pos=0) {
          this.state = RTSPStream.STATE_PLAY;
          let params = {};
          let range = this.client.sdp.sessionBlock.range;
          if (range) {
              // TODO: seekable
              if (range[0]==-1) {
                  range[0]=0;// Do not handle now at the moment
              }
              // params['Range'] = `${range[2]}=${range[0]}-`;
          }
          return this.sendRequest('PLAY', params).then((_data)=>{
              this.client.useRTPChannel(this.rtpChannel);
              this.state = RTSPClient$1.STATE_PLAYING;
              return {track:this.track, data: _data};
          });
      }

      sendPause() {
          if (!this.client.supports("PAUSE")) {
              return;
          }
          this.state = RTSPClient$1.STATE_PAUSE;
          return this.sendRequest("PAUSE").then((_data)=>{
              this.state = RTSPClient$1.STATE_PAUSED;
          });
      }

      sendTeardown() {
          if (this.state != RTSPClient$1.STATE_TEARDOWN) {
              this.client.forgetRTPChannel(this.rtpChannel);
              this.state = RTSPClient$1.STATE_TEARDOWN;
              this.stopKeepAlive();
              return this.sendRequest("TEARDOWN").then(()=> {
                  Log$9.log('RTSPClient: STATE_TEARDOWN');
                  ///this.client.connection.disconnect();
                  // TODO: Notify client
              });
          }
      }
  }

  class RTP {
      constructor(pkt/*uint8array*/, sdp) {
          let bytes = new DataView(pkt.buffer, pkt.byteOffset, pkt.byteLength);

          this.version   = bytes.getUint8(0) >>> 6;
          this.padding   = bytes.getUint8(0) & 0x20 >>> 5;
          this.has_extension = bytes.getUint8(0) & 0x10 >>> 4;
          this.csrc      = bytes.getUint8(0) & 0x0F;
          this.marker    = bytes.getUint8(1) >>> 7;
          this.pt        = bytes.getUint8(1) & 0x7F;
          this.sequence  = bytes.getUint16(2) ;
          this.timestamp = bytes.getUint32(4);
          this.ssrc      = bytes.getUint32(8);
          this.csrcs     = [];

          let pktIndex=12;
          if (this.csrc>0) {
              this.csrcs.push(bytes.getUint32(pktIndex));
              pktIndex+=4;
          }
          if (this.has_extension==1) {
              this.extension = bytes.getUint16(pktIndex);
              this.ehl = bytes.getUint16(pktIndex+2);
              pktIndex+=4;
              this.header_data = pkt.slice(pktIndex, this.ehl);
              pktIndex += this.ehl;
          }

          this.headerLength = pktIndex;
          let padLength = 0;
          if (this.padding) {
              padLength = bytes.getUint8(pkt.byteLength-1);
          }

          // this.bodyLength   = pkt.byteLength-this.headerLength-padLength;

          this.media = sdp.getMediaBlockByPayloadType(this.pt);
          if (null === this.media) {
              Log.log(`Media description for payload type: ${this.pt} not provided.`);
          }
          this.type = this.media.ptype;//PayloadType.string_map[this.media.rtpmap[this.media.fmt[0]].name];

          this.data = pkt.subarray(pktIndex);
          // this.timestamp = 1000 * (this.timestamp / this.media.rtpmap[this.pt].clock);
          // console.log(this);
      }
      getPayload() {
          return this.data;
      }

      getTimestampMS() {
          return this.timestamp; //1000 * (this.timestamp / this.media.rtpmap[this.pt].clock);
      }

      toString() {
          return "RTP(" +
              "version:"   + this.version   + ", " +
              "padding:"   + this.padding   + ", " +
              "has_extension:" + this.has_extension + ", " +
              "csrc:"      + this.csrc      + ", " +
              "marker:"    + this.marker    + ", " +
              "pt:"        + this.pt        + ", " +
              "sequence:"  + this.sequence  + ", " +
              "timestamp:" + this.timestamp + ", " +
              "ssrc:"      + this.ssrc      + ")";
      }

      isVideo(){return this.media.type == 'video';}
      isAudio(){return this.media.type == 'audio';}

      
  }

  class RTPFactory {
      constructor(sdp) {
          this.tsOffsets={};
          for (let pay in sdp.media) {
              for (let pt of sdp.media[pay].fmt) {
                  this.tsOffsets[pt] = {last: 0, overflow: 0};
              }
          }
      }

      build(pkt/*uint8array*/, sdp) {
          let rtp = new RTP(pkt, sdp);

          let tsOffset = this.tsOffsets[rtp.pt];
          if (tsOffset) {
              rtp.timestamp += tsOffset.overflow;
              if (Math.abs(rtp.timestamp - tsOffset.last) > 0x7fffffff) {
                  tsOffset.overflow += 0xffffffff;
                  rtp.timestamp += tsOffset.overflow;
              }
              tsOffset.last = rtp.timestamp;
          }

          return rtp;
      }
  }

  class RTSPMessage {
      static get RTSP_1_0() {return  "RTSP/1.0";}

      constructor(_rtsp_version) {
          this.version = _rtsp_version;
      }

      build(_cmd, _host, _params={}, _payload=null) {
          let requestString = `${_cmd} ${_host} ${this.version}\r\n`;
          for (let param in _params) {
              requestString+=`${param}: ${_params[param]}\r\n`
          }
          // TODO: binary payload
          if (_payload) {
              requestString+=`Content-Length: ${_payload.length}\r\n`
          }
          requestString+='\r\n';
          if (_payload) {
              requestString+=_payload;
          }
          return requestString;
      }

      parse(_data) {
          let lines = _data.split('\r\n');
          let parsed = {
              headers:{},
              body:null,
              code: 0,
              statusLine: ''
          };

          let match;
          [match, parsed.code, parsed.statusLine] = lines[0].match(new RegExp(`${this.version}[ ]+([0-9]{3})[ ]+(.*)`));
          parsed.code = Number(parsed.code);
          let lineIdx = 1;

          while (lines[lineIdx]) {
              let [k,v] = lines[lineIdx].split(/:(.+)/);
              parsed.headers[k.toLowerCase()] = v.trim();
              lineIdx++;
          }

          parsed.body = lines.slice(lineIdx).join('\n\r');

          return parsed;
      }

  }

  const MessageBuilder = new RTSPMessage(RTSPMessage.RTSP_1_0);

  class RTPPayloadParser {

      constructor() {
          this.h264parser = new RTPH264Parser();
          this.aacparser = new RTPAACParser();
      }

      parse(rtp) {
          if (rtp.media.type=='video') {
              return this.h264parser.parse(rtp);
          } else if (rtp.media.type == 'audio') {
              return this.aacparser.parse(rtp);
          }
          return null;
      }
  }

  class RTPH264Parser {
      constructor() {
          this.naluasm = new NALUAsm();
      }

      parse(rtp) {
          return this.naluasm.onNALUFragment(rtp.getPayload(), rtp.getTimestampMS());
      }
  }

  class RTPAACParser {

      constructor() {
          this.scale = 1;
          this.asm = new AACAsm();
      }

      setConfig(conf) {
          this.asm.config = conf;
      }

      parse(rtp) {
          return this.asm.onAACFragment(rtp);
      }
  }

  class BaseClient {
      constructor(transport, options={flush: 100}) {
          this.options = options;
          this.eventSource = new EventEmitter();

          Object.defineProperties(this, {
              sourceUrl: {value: null, writable: true},   // TODO: getter with validator
              paused: {value: true, writable: true},
              seekable: {value: false, writable: true}
          });

          this._onData = ()=>{
              if (this.transport.dataQueue.length) {
                  this.onData(this.transport.dataQueue.pop());
              }
          };
          this._onConnect = this.onConnected.bind(this);
          this._onDisconnect = this.onDisconnected.bind(this);
          this.attachTransport(transport);
      }

      static streamType() {
          return null;    
      }
      
      destroy() {
          this.detachTransport();
      }

      attachTransport(transport) {
          this.detachTransport();
          this.transport = transport;
          this.transport.eventSource.addEventListener('data', this._onData);
          this.transport.eventSource.addEventListener('connected', this._onConnect);
          this.transport.eventSource.addEventListener('disconnected', this._onDisconnect);
      }

      detachTransport() {
          if (this.transport) {
              this.transport.eventSource.removeEventListener('data', this._onData);
              this.transport.eventSource.removeEventListener('connected', this._onConnect);
              this.transport.eventSource.removeEventListener('disconnected', this._onDisconnect);
          }
      }

      start() {
          Log.log('Client started');
          this.paused = false;
          // this.startStreamFlush();
      }

      stop() {
          Log.log('Client paused');
          this.paused = true;
          // this.stopStreamFlush();
      }

      seek(timeOffset) {

      }

      setSource(source) {
          this.stop();
          this.endpoint = source;
          this.sourceUrl = source.urlpath;
      }

      startStreamFlush() {
          this.flushInterval = setInterval(()=>{
              if (!this.paused) {
                  this.eventSource.dispatchEvent('flush');
              }
          }, this.options.flush);
      }

      stopStreamFlush() {
          clearInterval(this.flushInterval);
      }

      onData(data) {

      }

      onConnected() {

      }

      onDisconnected() {

      }
  }

  class AACParser {
      static get SampleRates() {return  [
          96000, 88200,
          64000, 48000,
          44100, 32000,
          24000, 22050,
          16000, 12000,
          11025, 8000,
          7350];}

      // static Profile = [
      //     0: Null
      //     1: AAC Main
      //     2: AAC LC (Low Complexity)
      //     3: AAC SSR (Scalable Sample Rate)
      //     4: AAC LTP (Long Term Prediction)
      //     5: SBR (Spectral Band Replication)
      //     6: AAC Scalable
      // ]

      static parseAudioSpecificConfig(bytesOrBits) {
          let config;
          if (bytesOrBits.byteLength) { // is byteArray
              config = new BitArray(bytesOrBits);
          } else {
              config = bytesOrBits;
          }

          let bitpos = config.bitpos+(config.src.byteOffset+config.bytepos)*8;
          let prof = config.readBits(5);
          this.codec = `mp4a.40.${prof}`;
          let sfi = config.readBits(4);
          if (sfi == 0xf) config.skipBits(24);
          let channels = config.readBits(4);

          return {
              config: bitSlice(new Uint8Array(config.src.buffer), bitpos, bitpos+16),
              codec: `mp4a.40.${prof}`,
              samplerate: AACParser.SampleRates[sfi],
              channels: channels
          }
      }

      static parseStreamMuxConfig(bytes) {
          // ISO_IEC_14496-3 Part 3 Audio. StreamMuxConfig
          let config = new BitArray(bytes);

          if (!config.readBits(1)) {
              config.skipBits(14);
              return AACParser.parseAudioSpecificConfig(config);
          }
      }
  }

  const LOG_TAG$2 = "client:rtsp";
  const Log$7 = getTagged(LOG_TAG$2);

  class RTSPClient extends BaseClient {
      constructor(transport, options={flush: 200}) {
          super(transport, options);
          this.clientSM = new RTSPClient$1(this, transport);
          this.clientSM.ontracks = (tracks) => {
              this.eventSource.dispatchEvent('tracks', tracks);
              this.startStreamFlush();
          };
          this.sampleQueues={};
      }
      
      static streamType() {
          return 'rtsp';
      }

      setSource(url) {
          super.setSource(url);
          this.clientSM.setSource(url.urlpath);
      }

      destroy() {
          this.clientSM.destroy();
          return super.destroy();
      }

      start() {
          super.start();
          this.transport.ready.then(()=> {
              this.clientSM.start();
          });
      }

      onData(data) {
          this.clientSM.onData(data);
      }
  }

  class RTSPClient$1 extends StateMachine {
      static get USER_AGENT() {return 'SFRtsp 0.3';}
      static get STATE_INITIAL() {return  1 << 0;}
      static get STATE_OPTIONS() {return 1 << 1;}
      static get STATE_DESCRIBE () {return  1 << 2;}
      static get STATE_SETUP() {return  1 << 3;}
      static get STATE_STREAMS() {return 1 << 4;}
      static get STATE_TEARDOWN() {return  1 << 5;}
      // static STATE_PAUSED = 1 << 6;

      constructor(parent, transport) {
          super();

          this.parent = parent;
          this.transport = transport;
          this.payParser = new RTPPayloadParser();
          this.rtp_channels = new Set();
          this.ontracks = null;

          this.reset();

          this.addState(RTSPClient$1.STATE_INITIAL,{
          }).addState(RTSPClient$1.STATE_OPTIONS, {
              activate: this.sendOptions,
              finishTransition: this.onOptions
          }).addState(RTSPClient$1.STATE_DESCRIBE, {
              activate: this.sendDescribe,
              finishTransition: this.onDescribe
          }).addState(RTSPClient$1.STATE_SETUP, {
              activate: this.sendSetup,
              finishTransition: this.onSetup
          }).addState(RTSPClient$1.STATE_STREAMS, {

          }).addState(RTSPClient$1.STATE_TEARDOWN, {
              activate: ()=>{
                  this.started = false;
                  let promises = [];
                  for (let stream in this.streams) {
                      promises.push(this.streams[stream].mseClose())
                  }
                  return Promise.all(promises);
              },
              finishTransition: ()=>{
                  return this.transitionTo(RTSPClient$1.STATE_INITIAL)
              }
          }).addTransition(RTSPClient$1.STATE_INITIAL, RTSPClient$1.STATE_OPTIONS)
              .addTransition(RTSPClient$1.STATE_OPTIONS, RTSPClient$1.STATE_DESCRIBE)
              .addTransition(RTSPClient$1.STATE_DESCRIBE, RTSPClient$1.STATE_SETUP)
              .addTransition(RTSPClient$1.STATE_SETUP, RTSPClient$1.STATE_STREAMS)
              .addTransition(RTSPClient$1.STATE_TEARDOWN, RTSPClient$1.STATE_INITIAL)
              // .addTransition(RTSPClientSM.STATE_STREAMS, RTSPClientSM.STATE_PAUSED)
              // .addTransition(RTSPClientSM.STATE_PAUSED, RTSPClientSM.STATE_STREAMS)
              .addTransition(RTSPClient$1.STATE_STREAMS, RTSPClient$1.STATE_TEARDOWN)
              // .addTransition(RTSPClientSM.STATE_PAUSED, RTSPClientSM.STATE_TEARDOWN)
              .addTransition(RTSPClient$1.STATE_SETUP, RTSPClient$1.STATE_TEARDOWN)
              .addTransition(RTSPClient$1.STATE_DESCRIBE, RTSPClient$1.STATE_TEARDOWN)
              .addTransition(RTSPClient$1.STATE_OPTIONS, RTSPClient$1.STATE_TEARDOWN);

          this.transitionTo(RTSPClient$1.STATE_INITIAL);

          this.shouldReconnect = false;

          // TODO: remove listeners
          // this.connection.eventSource.addEventListener('connected', ()=>{
          //     if (this.shouldReconnect) {
          //         this.reconnect();
          //     }
          // });
          // this.connection.eventSource.addEventListener('disconnected', ()=>{
          //     if (this.started) {
          //         this.shouldReconnect = true;
          //     }
          // });
          // this.connection.eventSource.addEventListener('data', (data)=>{
          //     let channel = new DataView(data).getUint8(1);
          //     if (this.rtp_channels.has(channel)) {
          //         this.onRTP({packet: new Uint8Array(data, 4), type: channel});
          //     }
          //
          // });
      }

      destroy() {
          this.parent = null;
      }

      setSource(url) {
          this.url = url;
      }

      start() {
          if (this.state != RTSPClient$1.STATE_STREAMS) {
              this.transitionTo(RTSPClient$1.STATE_OPTIONS);
          } else {
              // TODO: seekable
          }
      }

      onData(data) {
          let channel = data[1];
          if (this.rtp_channels.has(channel)) {
              this.onRTP({packet: data.subarray(4), type: channel});
          }
      }

      useRTPChannel(channel) {
          this.rtp_channels.add(channel);
      }

      forgetRTPChannel(channel) {
          this.rtp_channels.delete(channel);
      }

      stop() {
          this.started = false;
          this.shouldReconnect = false;
          // this.mse = null;
      }

      reset() {
          this.methods = [];
          this.tracks = [];
          for (let stream in this.streams) {
              this.streams[stream].reset();
          }
          this.streams={};
          this.contentBase = "";
          this.state = null;
          this.sdp = null;
          this.interleaveChannelIndex = 0;
          this.session = null;
          this.timeOffset = {};
      }

      reconnect() {
          //this.parent.eventSource.dispatchEvent('clear');
          this.reset();
          if (this.currentState.name != RTSPClient$1.STATE_INITIAL) {
              this.transitionTo(RTSPClient$1.STATE_TEARDOWN).then(()=> {
                  this.transitionTo(RTSPClient$1.STATE_OPTIONS);
              });
          } else {
              this.transitionTo(RTSPClient$1.STATE_OPTIONS);
          }
      }

      supports(method) {
          return this.methods.includes(method)
      }

      parse(_data) {
          Log$7.debug(_data.payload);
          let d=_data.payload.split('\r\n\r\n');
          let parsed =  MessageBuilder.parse(d[0]);
          let len = Number(parsed.headers['content-length']);
          if (len) {
              let d=_data.payload.split('\r\n\r\n');
              parsed.body = d[1];
          } else {
              parsed.body="";
          }
          return parsed
      }

      sendRequest(_cmd, _host, _params={}, _payload=null) {
          this.cSeq++;
          Object.assign(_params, {
              CSeq: this.cSeq,
              'User-Agent': RTSPClient$1.USER_AGENT
          });
          if (_host != '*' && this.parent.endpoint.auth) {
              // TODO: DIGEST authentication
              _params['Authorization'] = `Basic ${btoa(this.parent.endpoint.auth)}`;
          }
          return this.send(MessageBuilder.build(_cmd, _host, _params, _payload));
      }

      send(_data) {
          return this.transport.ready.then(()=> {
              Log$7.debug(_data);
              return this.transport.send(_data).then(this.parse.bind(this)).then((parsed)=> {
                  // TODO: parse status codes
                  if (parsed.code>=300) {
                      Log$7.error(parsed.statusLine);
                      throw new Error(`RTSP error: ${parsed.code} ${parsed.message}`);
                  }
                  return parsed;
              });
          });
      }

      sendOptions() {
          this.reset();
          this.started = true;
          this.cSeq = 0;
          return this.sendRequest('OPTIONS', '*', {});
      }

      onOptions(data) {
          this.methods = data.headers['public'].split(',').map((e)=>e.trim());
          this.transitionTo(RTSPClient$1.STATE_DESCRIBE);
      }

      sendDescribe() {
          return this.sendRequest('DESCRIBE', this.url, {
              'Accept': 'application/sdp'
          }).then((data)=>{
              this.sdp = new SDPParser();
              return this.sdp.parse(data.body).catch(()=>{
                  throw new Error("Failed to parse SDP");
              }).then(()=>{return data;});
          });
      }

      onDescribe(data) {
          this.contentBase = data.headers['content-base'];
          this.tracks = this.sdp.getMediaBlockList();
          this.rtpFactory = new RTPFactory(this.sdp);

          Log$7.log('SDP contained ' + this.tracks.length + ' track(s). Calling SETUP for each.');

          if (data.headers['session']) {
              this.session = data.headers['session'];
          }

          if (!this.tracks.length) {
              throw new Error("No tracks in SDP");
          }

          this.transitionTo(RTSPClient$1.STATE_SETUP);
      }

      sendSetup() {
          let streams=[];

          // TODO: select first video and first audio tracks
          for (let track_type of this.tracks) {
              Log$7.log("setup track: "+track_type);
              // if (track_type=='audio') continue;
              // if (track_type=='video') continue;
              let track = this.sdp.getMediaBlock(track_type);
              this.streams[track_type] = new RTSPStream(this, track);
              let playPromise = this.streams[track_type].start();
              streams.push(playPromise.then(({track, data})=>{
                  let timeOffset = 0;
                  try {
                      let rtp_info = data.headers["rtp-info"].split(';');
                      this.timeOffset[track.fmt[0]] = Number(rtp_info[rtp_info.length - 1].split("=")[1]) ;
                  } catch (e) {
                      this.timeOffset[track.fmt[0]] = new Date().getTime();
                  }

                  let params = {
                      timescale: 0,
                      scaleFactor: 0
                  };
                  if (track.fmtp['sprop-parameter-sets']) {
                      let sps_pps = track.fmtp['sprop-parameter-sets'].split(',');
                      params = {
                          sps:base64ToArrayBuffer(sps_pps[0]),
                          pps:base64ToArrayBuffer(sps_pps[1])
                      };
                  } else if (track.fmtp['config']) {
                      let config = track.fmtp['config'];
                      this.has_config = track.fmtp['cpresent']!='0';
                      if (config) {
                          params={config:
                              AACParser.parseStreamMuxConfig(hexToByteArray(config))
                          };
                          this.payParser.aacparser.setConfig(params.config);
                      }
                  }
                  params.duration = this.sdp.sessionBlock.range?this.sdp.sessionBlock.range[1]-this.sdp.sessionBlock.range[0]:1;
                  this.parent.seekable = (params.duration != 1);
                  let res = {
                      track: track,
                      offset: timeOffset,
                      type: PayloadType.string_map[track.rtpmap[track.fmt[0]].name],
                      params: params,
                      duration: params.duration
                  };
                  this.parent.sampleQueues[res.type]=[];
                  return res;
              }));
          }
          return Promise.all(streams).then((tracks)=>{

              if (this.ontracks) {
                  this.ontracks(tracks);
              }
          });
      }

      onSetup() {
          this.transitionTo(RTSPClient$1.STATE_STREAMS);
      }

      onRTP(_data) {
          if (!this.rtpFactory) return;

          let rtp = this.rtpFactory.build(_data.packet, this.sdp);
          rtp.timestamp -= this.timeOffset[rtp.pt];
          if (rtp.media) {
              let pay = this.payParser.parse(rtp);
              if (pay) {
                  this.parent.sampleQueues[rtp.type].push([pay]);
              }
          }

          // this.remuxer.feedRTP();
      }
  }

  class BaseTransport {
      constructor(endpoint, stream_type, config={}) {
          this.stream_type = stream_type;
          this.endpoint = endpoint;
          this.eventSource = new EventEmitter();
          this.dataQueue = [];
      }

      static canTransfer(stream_type) {
          return BaseTransport.streamTypes().includes(stream_type);
      }
      
      static streamTypes() {
          return [];
      }

      destroy() {
          this.eventSource.destroy();
      }

      connect() {
          // TO be impemented
      }

      disconnect() {
          // TO be impemented
      }

      reconnect() {
          return this.disconnect().then(()=>{
              return this.connect();
          });
      }

      setEndpoint(endpoint) {
          this.endpoint = endpoint;
          return this.reconnect();
      }

      send(data) {
          // TO be impemented
          // return this.prepare(data).send();
      }

      prepare(data) {
          // TO be impemented
          // return new Request(data);
      }

      // onData(type, data) {
      //     this.eventSource.dispatchEvent(type, data);
      // }
  }

  const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

  const CPU_CORES = 1;//navigator.hardwareConcurrency || 3;

  const LOG_TAG$4 = "transport:ws";
  const Log$10 = getTagged(LOG_TAG$4);
  const WORKER_COUNT = CPU_CORES;

  class WebsocketTransport extends BaseTransport {
      constructor(endpoint, stream_type, options={
          socket:`${location.protocol.replace('http', 'ws')}//${location.host}/ws/`
      }) {
          super(endpoint, stream_type);
          this.proxies = [];
          this.currentProxy = 0;
          this.socket_url = options.socket;
          this.ready = this.connect();
      }

      static canTransfer(stream_type) {
          return WebsocketTransport.streamTypes().includes(stream_type);
      }

      static streamTypes() {
          return ['hls', 'rtsp'];
      }

      connect() {
          return this.disconnect().then(()=>{
              let promises = [];
              // TODO: get mirror list
              for (let i=0; i<WORKER_COUNT; ++i) {
                  let proxy = new WebSocketProxy(this.socket_url, this.endpoint, this.stream_type);

                  proxy.set_disconnect_handler((p)=> {
                      this.eventSource.dispatchEvent('disconnected');
                      setTimeout(()=> {
                          if (this.ready && this.ready.reject) {
                              this.ready.reject();
                          }
                          this.ready = this.connect();
                      }, 3000);
                  });

                  proxy.set_data_handler((data)=> {
                      this.dataQueue.push(new Uint8Array(data));
                      this.eventSource.dispatchEvent('data');
                  });

                  promises.push(proxy.connect().then(()=> {
                      this.eventSource.dispatchEvent('connected');
                  }).catch((e)=> {
                      this.eventSource.dispatchEvent('error');
                      throw new Error(e);
                  }));
                  this.proxies.push(proxy);
              }
              return Promise.all(promises);
          });
      }

      disconnect() {
          let promises = [];
          for (let i=0; i<this.proxies.length; ++i) {
              this.proxies[i].close();
          }
          this.proxies= [];
          if (this.proxies.length) {
              return Promise.all(promises);
          } else {
              return Promise.resolve();
          }
      }

      socket() {
          return this.proxies[(this.currentProxy++)%this.proxies.length];
      }

      send(_data, fn) {
          let res = this.socket().send(_data);
          if (fn) {
              fn(res.seq);
          }
          return res.promise;
      }
  }

  class WSPProtocol {
      static get PROTO() {return  'WSP';}

      static get V1_1() {return '1.1';}

      static get CMD_INIT() {return 'INIT';}
      static get CMD_JOIN() {return  'JOIN';}
      static get CMD_WRAP() {return  'WRAP';}


      constructor(ver){
          this.ver = ver;
      }

      build(cmd, data, payload=''){
          let data_str='';
          if (!data.seq) {
              data.seq = ++WSPProtocol.seq;
          }
          for (let k in data) {
              data_str += `${k}: ${data[k]}\r\n`;
          }
          return `${WSPProtocol.PROTO}/${this.ver} ${cmd}\r\n${data_str}\r\n${payload}`;
      }

      static parse(data) {
          let payIdx = data.indexOf('\r\n\r\n');
          let lines = data.substr(0, payIdx).split('\r\n');
          let hdr = lines.shift().match(new RegExp(`${WSPProtocol.PROTO}/${WSPProtocol.V1_1}\\s+(\\d+)\\s+(.+)`));
          if (hdr) {
              let res = {
                  code: Number(hdr[1]),
                  msg:  hdr[2],
                  data: {},
                  payload: ''
              };
              while (lines.length) {
                  let line = lines.shift();
                  if (line) {
                      let [k,v] = line.split(':');
                      res.data[k.trim()] = v.trim();
                  } else {
                      break;
                  }
              }
              res.payload = data.substr(payIdx+4);
              return res;
          }
          return null;
      }
  }
  WSPProtocol.seq = 0;

  class WebSocketProxy {
      static get CHN_CONTROL() {return 'control';}
      static get CHN_DATA() {return  'data';}

      constructor(wsurl, endpoint, stream_type) {
          this.url = wsurl;
          this.stream_type = stream_type;
          this.endpoint = endpoint;
          this.data_handler = ()=>{};
          this.disconnect_handler = ()=>{};
          this.builder = new WSPProtocol(WSPProtocol.V1_1);
          this.awaitingPromises = {};
          this.seq = 0;
      }

      set_data_handler(handler) {
          this.data_handler = handler;
      }

      set_disconnect_handler(handler) {
          this.disconnect_handler = handler;
      }

      close() {
          Log$10.log('closing connection');
          return new Promise((resolve)=>{
              this.ctrlChannel.onclose = ()=>{
                  if (this.dataChannel) {
                      this.dataChannel.onclose = ()=>{
                          Log$10.log('closed');
                          resolve();
                      };
                      this.dataChannel.close();
                  } else {
                      Log$10.log('closed');
                      resolve();
                  }
              };
              this.ctrlChannel.close();
          });
      }

      onDisconnect(){
          this.ctrlChannel.onclose=null;
          this.ctrlChannel.close();
          if (this.dataChannel) {
              this.dataChannel.onclose = null;
              this.dataChannel.close();
          }
          this.disconnect_handler(this);
      }

      initDataChannel(channel_id) {
          return new Promise((resolve, reject)=>{
              this.dataChannel = new WebSocket(this.url, WebSocketProxy.CHN_DATA);
              this.dataChannel.binaryType = 'arraybuffer';
              this.dataChannel.onopen = ()=>{
                  let msg = this.builder.build(WSPProtocol.CMD_JOIN, {
                      channel: channel_id
                  });
                  Log$10.debug(msg);
                  this.dataChannel.send(msg);
              };
              this.dataChannel.onmessage = (ev)=>{
                  Log$10.debug(`[data]\r\n${ev.data}`);
                  let res = WSPProtocol.parse(ev.data);
                  if (!res) {
                      return reject();
                  }

                  this.dataChannel.onmessage=(e)=>{
                      Log$10.debug('got data');
                      if (this.data_handler) {
                          this.data_handler(e.data);
                      }
                  };
                  resolve();
              };
              this.dataChannel.onerror = (e)=>{
                  Log$10.error(`[data] ${e.type}`);
                  this.dataChannel.close();
              };
              this.dataChannel.onclose = (e)=>{
                  Log$10.error(`[data] ${e.type}. code: ${e.code}`);
                  this.onDisconnect();
              };
          });
      }

      connect() {
          return new Promise((resolve, reject)=>{
              this.ctrlChannel = new WebSocket(this.url, WebSocketProxy.CHN_CONTROL);

              this.connected = false;

              this.ctrlChannel.onopen = ()=>{
                  let headers = {
                      proto: this.stream_type
                  };
                  if (this.endpoint.socket) {
                      headers.socket = this.endpoint.socket;
                  } else {
                      Object.assign(headers, {
                          host:  this.endpoint.host,
                          port:  this.endpoint.port
                      })
                  }
                  let msg = this.builder.build(WSPProtocol.CMD_INIT, headers);
                  Log$10.debug(msg);
                  this.ctrlChannel.send(msg);
              };

              this.ctrlChannel.onmessage = (ev)=>{
                  Log$10.debug(`[ctrl]\r\n${ev.data}`);

                  let res = WSPProtocol.parse(ev.data);
                  if (!res) {
                      return reject();
                  }

                  if (res.code >= 300) {
                      Log$10.error(`[ctrl]\r\n${res.code}: ${res.msg}`);
                      return reject();
                  }
                  this.ctrlChannel.onmessage = (e)=> {
                      let res = WSPProtocol.parse(e.data);
                      Log$10.debug(`[ctrl]\r\n${e.data}`);
                      if (res.data.seq in this.awaitingPromises) {
                          if (res.code < 300) {
                              this.awaitingPromises[res.data.seq].resolve(res);
                          } else {
                              this.awaitingPromises[res.data.seq].reject(res);
                          }
                          delete this.awaitingPromises[res.data.seq];
                      }
                  };

                  this.initDataChannel(res.data.channel).then(resolve).catch(reject);
              };

              this.ctrlChannel.onerror = (e)=>{
                  Log$10.error(`[ctrl] ${e.type}`);
                  this.ctrlChannel.close();
              };
              this.ctrlChannel.onclose = (e)=>{
                  Log$10.error(`[ctrl] ${e.type}. code: ${e.code}`);
                  this.onDisconnect();
              };
          });
      }

      send(payload) {
          if (this.ctrlChannel.readyState != WebSocket.OPEN) {
              this.close();
              // .then(this.connect.bind(this));
              // return;
              throw new Error('disconnected');
          }
          // Log.debug(payload);
          let data = {
              contentLength: payload.length,
              seq: ++WSPProtocol.seq
          };
          return {
              seq:data.seq,
              promise: new Promise((resolve, reject)=>{
                  this.awaitingPromises[data.seq] = {resolve, reject};
                  let msg = this.builder.build(WSPProtocol.CMD_WRAP, data, payload);
                  Log$10.debug(msg);
                  this.ctrlChannel.send(msg);
              })};
      }
  }

  const Log$1 = getTagged('wsp');

  class StreamType {
      static get HLS() {return 'hls';}
      static get RTSP() {return 'rtsp';}

      static isSupported(type) {
          return [StreamType.HLS, StreamType.RTSP].includes(type);
      }

      static fromUrl(url) {
          let parsed = Url.parse(url);
          switch (parsed.protocol) {
              case 'rtsp':
                  return StreamType.RTSP;
              case 'http':
              case 'https':
                  if (url.indexOf('.m3u8')>=0) {
                      return StreamType.HLS;
                  } else {
                      return null;
                  }
              default:
                  return null;
          }
      }

      static fromMime(mime) {
          switch (mime) {
              case 'application/x-rtsp':
                  return StreamType.RTSP;
              case 'application/vnd.apple.mpegurl':
              case 'application/x-mpegurl':
                  return StreamType.HLS;
              default:
                  return null;
          }
      }
  }

  class WSPlayer {

      constructor(node, opts) {
          if (typeof node == typeof '') {
              this.player = document.getElementById(node);
          } else {
              this.player = node;
          }

          let modules = opts.modules || {
              client: RTSPClient,
              transport: {
                  constructor: WebsocketTransport
              }
          };

          this.modules = {};
          for (let module of modules) {
              let transport = module.transport || WebsocketTransport;
              let client = module.client || RTSPClient;
              if (transport.constructor.canTransfer(client.streamType())) {
                  this.modules[client.streamType()] = {
                      client: client,
                      transport: transport
                  }
              } else {
                  Log$1.warn(`Client stream type ${client.streamType()} is incompatible with transport types [${transport.streamTypes().join(', ')}]. Skip`)
              }
          }
          
          this.type = StreamType.RTSP;
          this.url = null;
          if (opts.url && opts.type) {
              this.url = opts.url;
              this.type = opts.type;
          } else {
              if (!this._checkSource(this.player)) {
                  for (let i=0; i<this.player.children.length; ++i) {
                      if (this._checkSource(this.player.children[i])) {
                          break;
                      }
                  }
              }
              if (!this.url) {
                   throw new Error('No playable endpoint found');
              }
          }

          this.setSource(this.url, this.type);

          this.player.addEventListener('play', ()=>{
              if (!this.isPlaying()) {
                  this.client.start();
              }
          }, false);

          this.player.addEventListener('pause', ()=>{
              this.client.stop();
          }, false);
      }

      // TODO: check native support

      isPlaying() {
          return !(this.player.paused || this.client.paused);
      }

      static canPlay(resource) {
          return StreamType.fromMime(resource.type) || StreamType.fromUrl(resource.src);
      }

      _checkSource(src) {
          if (!src.dataset['ignore'] && src.src && !this.player.canPlayType(src.type) && (StreamType.fromMime(src.type) || StreamType.fromUrl(src.src))) {
              this.url = src.src;
              this.type = src.type ? StreamType.fromMime(src.type) : StreamType.fromUrl(src.src);
              return true;
          }
          return false;
      }

      setSource(url, type) {
          if (this.transport) {
              this.transport.destroy();
          }
          this.endpoint = Url.parse(url);
          this.url = url;
          let transport = this.modules[type].transport;
          this.transport = new transport.constructor(this.endpoint, this.type, transport.options);


          let lastType = this.type;
          this.type = (StreamType.isSupported(type)?type:false) || StreamType.fromMime(type);
          if (!this.type) {
              throw new Error("Bad stream type");
          }

          if (lastType!=this.type || !this.client) {
              if (this.client) {
                  this.client.destroy();
              }
              let client = this.modules[type].client;
              this.client = new client(this.transport);
              if (!this.remuxer) {
                  this.remuxer = new Remuxer(this.player);
              }
              this.remuxer.attachClient(this.client);
          }
          this.client.attachTransport(this.transport);
          this.client.setSource(this.endpoint);

          if (this.player.autoplay) {
              this.client.start();
          }
      }

      start() {
          this.client.start();
      }

      stop() {
          this.client.stop();
      }

  }

  class PESAsm {

      constructor() {
          this.fragments = [];
          this.pesLength=0;
          this.pesPkt = null;
      }

      parse(frag) {

          if (this.extPresent) {
              let ext = this.parseExtension(frag);
              ext.data = frag.subarray(ext.offset);
          } else {
              return null;
          }
      }

      parseHeader() {
          let hdr = this.fragments[0];
          let pesPrefix = (hdr[0] << 16) + (hdr[1] << 8) + hdr[2];
          this.extPresent = ![0xbe, 0xbf].includes(hdr[3]);
          if (pesPrefix === 1) {
              let pesLength = (hdr[4] << 8) + hdr[5];
              if (pesLength) {
                  this.pesLength = pesLength;
                  this.hasLength = true;
              } else {
                  this.hasLength = false;
                  this.pesPkt = null;
              }
              return true;
          }
          return false;
      }

      static PTSNormalize(value, reference) {

          let offset;
          if (reference === undefined) {
              return value;
          }
          if (reference < value) {
              // - 2^33
              offset = -8589934592;
          } else {
              // + 2^33
              offset = 8589934592;
          }
          /* PTS is 33bit (from 0 to 2^33 -1)
           if diff between value and reference is bigger than half of the amplitude (2^32) then it means that
           PTS looping occured. fill the gap */
          while (Math.abs(value - reference) > 4294967296) {
              value += offset;
          }
          return value;
      }

      parseExtension(frag) {
          let  pesFlags, pesPrefix, pesLen, pesHdrLen, pesPts, pesDts, payloadStartOffset;
              pesFlags = frag[1];
              if (pesFlags & 0xC0) {
                  /* PES header described here : http://dvd.sourceforge.net/dvdinfo/pes-hdr.html
                   as PTS / DTS is 33 bit we cannot use bitwise operator in JS,
                   as Bitwise operators treat their operands as a sequence of 32 bits */
                  pesPts = (frag[3] & 0x0E) * 536870912 +// 1 << 29
                      (frag[4] & 0xFF) * 4194304 +// 1 << 22
                      (frag[5] & 0xFE) * 16384 +// 1 << 14
                      (frag[6] & 0xFF) * 128 +// 1 << 7
                      (frag[7] & 0xFE) / 2;
                  // check if greater than 2^32 -1
                  if (pesPts > 4294967295) {
                      // decrement 2^33
                      pesPts -= 8589934592;
                  }
                  if (pesFlags & 0x40) {
                      pesDts = (frag[8] & 0x0E ) * 536870912 +// 1 << 29
                          (frag[9] & 0xFF ) * 4194304 +// 1 << 22
                          (frag[10] & 0xFE ) * 16384 +// 1 << 14
                          (frag[11] & 0xFF ) * 128 +// 1 << 7
                          (frag[12] & 0xFE ) / 2;
                      // check if greater than 2^32 -1
                      if (pesDts > 4294967295) {
                          // decrement 2^33
                          pesDts -= 8589934592;
                      }
                  } else {
                      pesDts = pesPts;
                  }

              pesHdrLen = frag[2];
              payloadStartOffset = pesHdrLen + 9;

              // TODO: normalize pts/dts
              return {offset: payloadStartOffset, pts: pesPts, dts: pesDts};
          } else {
              return null;
          }
      }
      
      feed(frag, shouldParse) {

          let res = null;
          if (shouldParse && this.fragments.length) {
              if (!this.parseHeader()) {
                  throw new Error("Invalid PES packet");
              }

              let offset = 6;
              let parsed = {};
              if (this.extPresent) {
                  // TODO: make sure fragment have necessary length
                  parsed = this.parseExtension(this.fragments[0].subarray(6));
                  offset = parsed.offset;
              }
              if (!this.pesPkt) {
                  this.pesPkt = new Uint8Array(this.pesLength);
              }

              let poffset = 0;
              while (this.pesLength && this.fragments.length) {
                  let data = this.fragments.shift();
                  if (offset) {
                      if (data.byteLength < offset) {
                          offset -= data.byteLength;
                          continue;
                      } else {
                          data = data.subarray(offset);
                          this.pesLength -= offset - (this.hasLength?6:0);
                          offset = 0;
                      }
                  }
                  this.pesPkt.set(data, poffset);
                  poffset += data.byteLength;
                  this.pesLength -= data.byteLength;
              }
              res = {data:this.pesPkt, pts: parsed.pts, dts: parsed.dts};
          } else {
              this.pesPkt = null;
          }
          this.pesLength += frag.byteLength;

          if (this.fragments.length && this.fragments[this.fragments.length-1].byteLength < 6) {
              this.fragments[this.fragments.length-1] = appendByteArray(this.fragments[0], frag);
          } else {
              this.fragments.push(frag);
          }

          return res;
      }
  }

  class PESType {
      static get AAC() {return  0x0f;}  // ISO/IEC 13818-7 ADTS AAC (MPEG-2 lower bit-rate audio)
      static get ID3() {return  0x15;}  // Packetized metadata (ID3)
      static get H264() {return  0x1b;}  // ITU-T Rec. H.264 and ISO/IEC 14496-10 (lower bit-rate video)
  }

  class TSParser {
      static get PACKET_LENGTH() {return  188;}

      constructor() {
          this.pmtParsed = false;
          this.pesParserTypes = new Map();
          this.pesParsers = new Map();
          this.pesAsms = {};
          this.ontracks = null;
          this.toSkip = 0;
      }

      addPesParser(pesType, constructor) {
          this.pesParserTypes.set(pesType, constructor);
      }

      parse(packet) {
          let bits = new BitArray(packet);
          if (packet[0] === 0x47) {
              bits.skipBits(9);
              let payStart = bits.readBits(1);
              bits.skipBits(1);
              let pid = bits.readBits(13);
              bits.skipBits(2);
              let adaptFlag = bits.readBits(1);
              let payFlag = bits.readBits(1);
              bits.skipBits(4);
              if (adaptFlag) {
                  let adaptSize = bits.readBits(8);
                  this.toSkip = bits.skipBits(adaptSize*8);
                  if (bits.finished()) {
                      return;
                  }
              }
              if (!payFlag) return;

              let payload = packet.subarray(bits.bytepos);//bitSlice(packet, bits.bitpos+bits.bytepos*8);

              if (this.pmtParsed && this.pesParsers.has(pid)) {
                  let pes = this.pesAsms[pid].feed(payload, payStart);
                  if (pes) {
                      return this.pesParsers.get(pid).parse(pes);
                  }
              } else {
                  if (pid === 0) {
                      this.pmtId = this.parsePAT(payload);
                  } else if (pid === this.pmtId) {
                      this.parsePMT(payload);
                      this.pmtParsed = true;
                  }
              }
          }
          return null;
      }

      parsePAT(data) {
          let bits = new BitArray(data);
          let ptr = bits.readBits(8);
          bits.skipBits(8*ptr+83);
          return bits.readBits(13);
      }

      parsePMT(data) {
          let bits = new BitArray(data);
          let ptr = bits.readBits(8);
          bits.skipBits(8*ptr + 8);
          bits.skipBits(6);
          let secLen = bits.readBits(10);
          bits.skipBits(62);
          let pil = bits.readBits(10);
          bits.skipBits(pil*8);

          let tracks = new Set();
          let readLen = secLen-13-pil;
          while (readLen>0) {
              let pesType = bits.readBits(8);
              bits.skipBits(3);
              let pid = bits.readBits(13);
              bits.skipBits(6);
              let il = bits.readBits(10);
              bits.skipBits(il*8);
              if ([PESType.AAC, PESType.H264].includes(pesType)) {
                  if (this.pesParserTypes.has(pesType) && !this.pesParsers.has(pid)) {
                      this.pesParsers.set(pid, new (this.pesParserTypes.get(pesType)));
                      this.pesAsms[pid] = new PESAsm();
                      switch (pesType) {
                          case PESType.H264: tracks.add({type: PayloadType.H264, offset: 0});break;
                          case PESType.AAC: tracks.add({type: PayloadType.AAC, offset: 0});break;
                      }
                  }
              }
              readLen -= 5+il;
          }
          // TODO: notify about tracks
          if (this.ontracks) {
              this.ontracks(tracks);
          }
      }
  }

  class AVCPES {
      constructor() {
          this.naluasm = new NALUAsm();
          this.lastUnit = null;
      }

      parse(pes) {
          let array = pes.data;
          let i = 0, len = array.byteLength, value, overflow, state = 0;
          let units = [], lastUnitStart;
          while (i < len) {
              value = array[i++];
              // finding 3 or 4-byte start codes (00 00 01 OR 00 00 00 01)
              switch (state) {
                  case 0:
                      if (value === 0) {
                          state = 1;
                      }
                      break;
                  case 1:
                      if( value === 0) {
                          state = 2;
                      } else {
                          state = 0;
                      }
                      break;
                  case 2:
                  case 3:
                      if( value === 0) {
                          state = 3;
                      } else if (value === 1 && i < len) {
                          if (lastUnitStart) {
                              let nalu = this.naluasm.onNALUFragment(array.subarray(lastUnitStart, i - state - 1), pes.dts);
                              if (nalu && (nalu.type() in NALU.TYPES)) {
                                  units.push(nalu);
                              }
                          } else {
                              // If NAL units are not starting right at the beginning of the PES packet, push preceding data into previous NAL unit.
                              overflow  = i - state - 1;
                              if (overflow) {
                                  if (this.lastUnit) {
                                      this.lastUnit.data = appendByteArray(this.lastUnit.data.byteLength, array.subarray(0, overflow));
                                  }
                              }
                          }
                          lastUnitStart = i;
                          state = 0;
                      } else {
                          state = 0;
                      }
                      break;
                  default:
                      break;
              }
          }
          if (lastUnitStart) {
              let nalu = this.naluasm.onNALUFragment(array.subarray(lastUnitStart, len), pes.dts, pes.pts);
              if (nalu) {
                  units.push(nalu);
              }
          }
          this.lastUnit = units[units.length-1];
          return {units: units, type: StreamType$1.VIDEO, pay: PayloadType.H264};
      }
  }

  class ADTS {

      static parseHeader(data) {
          let bits = new BitArray(data);
          bits.skipBits(15);
          let protectionAbs = bits.readBits(1);
          bits.skipBits(14);
          let len = bits.readBits(13);
          bits.skipBits(11);
          let cnt = bits.readBits(2);
          if (!protectionAbs) {
              bits.skipBits(16);
          }
          return {size: len-bits.bytepos, frameCount: cnt, offset: bits.bytepos}
      }

      static parseHeaderConfig(data) {
          let bits = new BitArray(data);
          bits.skipBits(15);
          let protectionAbs = bits.readBits(1);
          let profile = bits.readBits(2) + 1;
          let freq = bits.readBits(4);
          bits.skipBits(1);
          let channels = bits.readBits(3);
          bits.skipBits(4);
          let len = bits.readBits(13);
          bits.skipBits(11);
          let cnt = bits.readBits(2);
          if (!protectionAbs) {
              bits.skipBits(16);
          }

          let userAgent = navigator.userAgent.toLowerCase();
          let configLen = 4;
          let extSamplingIdx;

          // firefox: freq less than 24kHz = AAC SBR (HE-AAC)
          if (userAgent.indexOf('firefox') !== -1) {
              if (freq >= 6) {
                  profile = 5;
                  configLen = 4;
                  // HE-AAC uses SBR (Spectral Band Replication) , high frequencies are constructed from low frequencies
                  // there is a factor 2 between frame sample rate and output sample rate
                  // multiply frequency by 2 (see table below, equivalent to substract 3)
                  extSamplingIdx = freq - 3;
              } else {
                  profile = 2;
                  configLen = 2;
                  extSamplingIdx = freq;
              }
              // Android : always use AAC
          } else if (userAgent.indexOf('android') !== -1) {
              profile = 2;
              configLen = 2;
              extSamplingIdx = freq;
          } else {
              /*  for other browsers (chrome ...)
               always force audio type to be HE-AAC SBR, as some browsers do not support audio codec switch properly (like Chrome ...)
               */
              profile = 5;
              configLen = 4;
              // if (manifest codec is HE-AAC or HE-AACv2) OR (manifest codec not specified AND frequency less than 24kHz)
              if (freq >= 6) {
                  // HE-AAC uses SBR (Spectral Band Replication) , high frequencies are constructed from low frequencies
                  // there is a factor 2 between frame sample rate and output sample rate
                  // multiply frequency by 2 (see table below, equivalent to substract 3)
                  extSamplingIdx = freq - 3;
              } else {
                  // if (manifest codec is AAC) AND (frequency less than 24kHz OR nb channel is 1) OR (manifest codec not specified and mono audio)
                  // Chrome fails to play back with AAC LC mono when initialized with HE-AAC.  This is not a problem with stereo.
                  if (channels === 1) {
                      profile = 2;
                      configLen = 2;
                  }
                  extSamplingIdx = freq;
              }
          }


          let config = new Uint8Array(configLen);

          config[0] = profile << 3;
          // samplingFrequencyIndex
          config[0] |= (freq & 0x0E) >> 1;
          config[1] |= (freq & 0x01) << 7;
          // channelConfiguration
          config[1] |= channels << 3;
          if (profile === 5) {
              // adtsExtensionSampleingIndex
              config[1] |= (extSamplingIdx & 0x0E) >> 1;
              config[2] = (extSamplingIdx & 0x01) << 7;
              // adtsObjectType (force to 2, chrome is checking that object type is less than 5 ???
              //    https://chromium.googlesource.com/chromium/src.git/+/master/media/formats/mp4/aac.cc
              config[2] |= 2 << 2;
              config[3] = 0;
          }
          return {
              config: {
                  config: config,
                  codec: `mp4a.40.${profile}`,
                  samplerate: AACParser.SampleRates[freq],
                  channels: channels,
              },
              size: len-bits.bytepos,
              frameCount: cnt,
              offset: bits.bytepos
          };
      }
  }

  class AACPES {
      constructor() {
          this.aacOverFlow = null;
          this.lastAacPTS = null;
          this.track = {};
          this.config = null;
      }

      parse(pes) {
              let data = pes.data;
              let pts = pes.pts;
              let startOffset = 0;
              let aacOverFlow = this.aacOverFlow;
              let lastAacPTS = this.lastAacPTS;
              var config, frameDuration, frameIndex, offset, stamp, len;

          if (aacOverFlow) {
              var tmp = new Uint8Array(aacOverFlow.byteLength + data.byteLength);
              tmp.set(aacOverFlow, 0);
              tmp.set(data, aacOverFlow.byteLength);
              Log.debug(`AAC: append overflowing ${aacOverFlow.byteLength} bytes to beginning of new PES`);
              data = tmp;
          }

          // look for ADTS header (0xFFFx)
          for (offset = startOffset, len = data.length; offset < len - 1; offset++) {
              if ((data[offset] === 0xff) && (data[offset+1] & 0xf0) === 0xf0) {
                  break;
              }
          }
          // if ADTS header does not start straight from the beginning of the PES payload, raise an error
          if (offset) {
              var reason, fatal;
              if (offset < len - 1) {
                  reason = `AAC PES did not start with ADTS header,offset:${offset}`;
                  fatal = false;
              } else {
                  reason = 'no ADTS header found in AAC PES';
                  fatal = true;
              }
              Log.error(reason);
              if (fatal) {
                  return;
              }
          }

          let hdr = null;
          let res = {units:[], type: StreamType$1.AUDIO, pay: PayloadType.AAC};
          if (!this.config) {
              hdr = ADTS.parseHeaderConfig(data.subarray(offset));
              this.config = hdr.config;
              res.config = hdr.config;
              hdr.config = null;
              Log.debug(`parsed codec:${this.config.codec},rate:${this.config.samplerate},nb channel:${this.config.channels}`);
          }
          frameIndex = 0;
          frameDuration = 1024 * 90000 / this.config.samplerate;

          // if last AAC frame is overflowing, we should ensure timestamps are contiguous:
          // first sample PTS should be equal to last sample PTS + frameDuration
          if(aacOverFlow && lastAacPTS) {
              var newPTS = lastAacPTS+frameDuration;
              if(Math.abs(newPTS-pts) > 1) {
                  Log.debug(`AAC: align PTS for overlapping frames by ${Math.round((newPTS-pts)/90)}`);
                  pts=newPTS;
              }
          }

          while ((offset + 5) < len) {
              if (!hdr) {
                  hdr = ADTS.parseHeader(data.subarray(offset));
              }
              if ((hdr.size > 0) && ((offset + hdr.offset + hdr.size) <= len)) {
                  stamp = pts + frameIndex * frameDuration;
                  res.units.push(new AACFrame(data.subarray(offset + hdr.offset, offset + hdr.offset + hdr.size), stamp));
                  offset += hdr.offset + hdr.size;
                  frameIndex++;
                  // look for ADTS header (0xFFFx)
                  for ( ; offset < (len - 1); offset++) {
                      if ((data[offset] === 0xff) && ((data[offset + 1] & 0xf0) === 0xf0)) {
                          break;
                      }
                  }
              } else {
                  break;
              }
              hdr = null;
          }
          if ((offset < len) && (data[offset]==0xff)) {   // TODO: check it
              aacOverFlow = data.subarray(offset, len);
              //logger.log(`AAC: overflow detected:${len-offset}`);
          } else {
              aacOverFlow = null;
          }
          this.aacOverFlow = aacOverFlow;
          this.lastAacPTS = stamp;

          return res;
      }
  }

  // adapted from https://github.com/kanongil/node-m3u8parse/blob/master/attrlist.js
  class AttrList {

      constructor(attrs) {
          if (typeof attrs === 'string') {
              attrs = AttrList.parseAttrList(attrs);
          }
          for(var attr in attrs){
              if(attrs.hasOwnProperty(attr)) {
                  this[attr] = attrs[attr];
              }
          }
          this.attrs = attrs;
      }

      decimalInteger(attrName) {
          const intValue = parseInt(this[attrName], 10);
          if (intValue > Number.MAX_SAFE_INTEGER) {
              return Infinity;
          }
          return intValue;
      }

      hexadecimalInteger(attrName) {
          if(this[attrName]) {
              let stringValue = (this[attrName] || '0x').slice(2);
              stringValue = ((stringValue.length & 1) ? '0' : '') + stringValue;

              const value = new Uint8Array(stringValue.length / 2);
              for (let i = 0; i < stringValue.length / 2; i++) {
                  value[i] = parseInt(stringValue.slice(i * 2, i * 2 + 2), 16);
              }
              return value;
          } else {
              return null;
          }
      }

      hexadecimalIntegerAsNumber(attrName) {
          const intValue = parseInt(this[attrName], 16);
          if (intValue > Number.MAX_SAFE_INTEGER) {
              return Infinity;
          }
          return intValue;
      }

      decimalFloatingPoint(attrName) {
          return parseFloat(this[attrName]);
      }

      enumeratedString(attrName) {
          return this[attrName];
      }

      decimalResolution(attrName) {
          const res = /^(\d+)x(\d+)$/.exec(this[attrName]);
          if (res === null) {
              return undefined;
          }
          return {
              width: parseInt(res[1], 10),
              height: parseInt(res[2], 10)
          };
      }

      static parseAttrList(input) {
          const re = /\s*(.+?)\s*=((?:\".*?\")|.*?)(?:,|$)/g;
          var match, attrs = {};
          while ((match = re.exec(input)) !== null) {
              var value = match[2], quote = '"';

              if (value.indexOf(quote) === 0 &&
                  value.lastIndexOf(quote) === (value.length-1)) {
                  value = value.slice(1, -1);
              }
              attrs[match[1]] = value;
          }
          return attrs;
      }

  }

  class M3U8Parser {
      static get TYPE_CHUNK() {return  0;}
      static get TYPE_PLAYLIST() {return  1;}
      // TODO: parse master playlists
      static parse(playlist, baseUrl='') {
          playlist = playlist.replace(/\r/, '').split('\n');
          if (playlist.shift().indexOf('#EXTM3U') < 0) {
              throw new Error("Bad playlist");
          }
          let chunkList = [];
          let playlistList = [];
          let expectedUrl = false;
          let cont = {};
          let urlType=null;
          while (playlist.length) {
              let entry = playlist.shift().replace(/\r/, '');
              if (expectedUrl) {
                  if (entry) {
                      cont.url = entry.startsWith('http')?entry:`${baseUrl}/${entry}`;
                      switch (urlType) {
                          case M3U8Parser.TYPE_CHUNK:
                              chunkList.push(cont);
                              break;
                          case M3U8Parser.TYPE_PLAYLIST:
                              playlistList.push(cont);
                              break;
                      }
                      cont={};
                      expectedUrl = false;
                  }
              } else {
                  if (entry.startsWith('#EXTINF')) {
                      // TODO: it's unsafe
                      cont.duration = Number(entry.split(':')[1].split(',')[0]);
                      urlType = M3U8Parser.TYPE_CHUNK;
                      expectedUrl = true;
                  } else if (entry.startsWith('#EXT-X-STREAM-INF')){
                      let props = entry.split(':')[1];
                      let al = new AttrList(props);
                      for (let prop in al.attrs) {
                          cont[prop.toLowerCase()]= al.attrs[prop];
                      }
                      urlType = M3U8Parser.TYPE_PLAYLIST;
                      expectedUrl = true;
                  }
              }
          }
          return {chunks: chunkList, playlists: playlistList}
      }
  }

  const LOG_TAG$5 = "client:hls";
  const Log$11 = getTagged(LOG_TAG$5);


  class HLSClient extends BaseClient {
      static get CHUNKS_TO_LOAD() {return  CPU_CORES;}

      constructor(transport, options) {
          super(transport, options);

          this.parser = new TSParser();
          this.parser.addPesParser(PESType.H264, AVCPES);
          this.parser.addPesParser(PESType.AAC, AACPES);
          this.parser.ontracks = (tracks)=>{
              let duration = 0;
              for (let chunk of this.chunks) {
                  duration+=chunk.duration;
              }
              for (let track of tracks) {
                  track.duration = duration;
                  this.sampleQueues[track.type]=[];
              }

              this.eventSource.dispatchEvent('tracks', tracks);
              this.startStreamFlush();
          };

          this.playlists = [];
          this.chunks = [];
          this.loadedChunks = [];
          this.pendingChunks = new Set();
          this.chunkIdx = 0;
          this.seqMap = new Map();
          this.sampleQueues = {};
          this.chunkSeq = new Map();

          this.eventSource.addEventListener('needData', this.loadChunks.bind(this, HLSClient.CHUNKS_TO_LOAD));
          this.resume = false;

          this.parsing = false;
      }

      static streamType() {
          return 'hls';
      }

      parse(data) {
          let seq = new DataView(data.buffer, data.byteOffset, 4).getUint32(0);
          // console.log(`data for ${seq}`);
          // console.log('begin parse');
          let offset = 4;
          while (offset < data.byteLength) {
              let parsed = this.parser.parse(data.subarray(offset, offset + TSParser.PACKET_LENGTH));
              if (parsed) {
                  if (parsed.config) {
                      this.eventSource.dispatchEvent(`${StreamType$1.map[parsed.type]}_config`, {
                          config: parsed.config,
                          pay: parsed.pay
                      });
                  }
                  this.sampleQueues[parsed.type].push(parsed.units);
                  this.eventSource.dispatchEvent('samples', {pay: parsed.pay});
              }
              offset += TSParser.PACKET_LENGTH;
          }
          // this.eventSource.dispatchEvent('flush');
          // console.log('end parse');
          this.parsing = false;
          if (this.loadedChunks.length) {
              this.onData(this.loadedChunks.shift());
          }
      }

      checkSeq(seq, data) {
          seq = Number(seq);
          if (this.seqMap.has(seq)) {
              // parse data
              let cs = this.chunkSeq.get(seq);
              this.pendingChunks.delete(cs.idx);
              this.chunkSeq.delete(seq);
              this.parse(data?data:this.seqMap.get(seq));
              this.seqMap.delete(seq);
              cs.promise.resolve();
          } else {
              this.seqMap.set(seq, data);
          }
      }

      onData(data) {
          if (this.parsing) {
              this.loadedChunks.push(data);
          } else {
              this.parsing = true;
              let seq = new DataView(data.buffer, data.byteOffset, 4).getUint32(0);
              // TODO: store seq & check if data ready
              this.checkSeq(seq, data);
          }
      }

      onConnected() {
          Log$11.debug('HLS connected');
          if (!this.paused) {
              this.start();
          }
      }

      onDisconnected() {
          Log$11.debug('HLS disconnected');
          this.parsing = false;
          this.seqMap.clear();
          this.chunkSeq.clear();
          // this.stop();
      }

      start() {
          super.start();
          if (this.resume) {
              this.eventSource.dispatchEvent('needData');
          } else {
              this.resume = true;
              this.eventSource.dispatchEvent('clear');
              Log$11.debug('waiting for transport ready');
              this.transport.ready.then(()=> {
                  Log$11.debug('loading playlist');
                  this.loadPlaylist();
              }).catch((e)=>{
                  Log$11.debug('reject transport', e);
              });
          }
      }

      stop() {
          super.stop();
      }

      setSource(source) {
          this.resume = false;
          super.setSource(source);
      }

      send(request, beforeSend) {
          try {
              return this.transport.send(request, beforeSend);
          } catch (e) {
              Log$11.error(e);
              this.onDisconnected();
              this.transport.connect();
              return Promise.reject();
          }
      }

      loadPlaylist(playlist=null) {
          let url = playlist?playlist.url:this.sourceUrl;
          this.send(`GET ${url} HTTP/1.1\r\nHost: ${this.transport.endpoint.host}\r\n\r\n`).then((data)=>{
              let entries = data.payload.split('\r\n\r\n');
              let http = entries[0].split('\r\n');
              let status = http[0].match(new RegExp(`HTTP/\\d\\.\\d\\s+(\\d+)\\s+(\\w+)`));
              if (!status || status[1]>=400) {
                  Log$11.error('bad playlist response');
                  return;
              }
              Log$11.debug(status);
              let baseUrl = url.substr(0, url.lastIndexOf('/'));
              let playlist = M3U8Parser.parse(entries[1], baseUrl);
              if (playlist.chunks.length) {
                  this.chunks = playlist.chunks;
                  this.resume = true;
                  // Load first chunk to detect initial timestamps
                  this.loadChunks(1);
              } else if (playlist.playlists) {
                  this.playlists = playlist.playlists;
                  // TODO: check playlist support
                  this.loadPlaylist(this.playlists[0])
              }
              // this.chunks = entries[1].split('\n').map((e)=>{e.replace(/\r/, ''); return e;});
              // this.loadChunk();
          }).catch((e)=>{
              this.resume = false;
          });
      }

      loadChunk(chunk, idx) {
          return new Promise((resolve, reject)=>{
              this.send(`GET ${chunk.url}?${Math.random()} HTTP/1.1\r\nHost: ${this.transport.endpoint.host}\r\n\r\n`, (seq)=>{
                  this.chunkSeq.set(seq, {
                      idx: idx,
                      promise: {resolve, reject}
                  });
              }).then((res)=>{
                  let lines = res.payload.split('\r\n');
                  let [version, code, msg] = lines[0].split(' ');
                  if (Number(code) >= 300) {
                      throw new Error(`Load error: ${code} ${msg}`);
                  }

                  // TODO: store seq & check if data ready
                  this.checkSeq(res.data.seq, null);
              });
          });
      }

      loadChunks(count) {
          if (this.chunkIdx>= this.chunks.length) return;
          let promises = [];
          if (this.pendingChunks.size) {
              Log$11.debug(`Reload chunks: ${Array.from(this.pendingChunks)}`);
              for (let i of this.pendingChunks) {
                  let chunk = this.chunks[i];
                  Log$11.log(`Loading ${chunk.url} (${i+1} of ${this.chunks.length})`);
                  promises.push(this.loadChunk(chunk, i));
              }
          } else {
              for (let i = this.chunkIdx; i < this.chunkIdx+count; ++i) {
                  let chunk = this.chunks[i];//.shift();
                  Log$11.log(`Loading ${chunk.url} (${i+1} of ${this.chunks.length})`);
                  this.pendingChunks.add(i);

                  promises.push(this.loadChunk(chunk, i));
                  if (i+1 >= this.chunks.length) return;
              }
          }
          return Promise.all(promises).then(()=>{
              Log$11.log('chunk loaded');
              // TODO: check queue overflow
              this.chunkIdx+=HLSClient.CHUNKS_TO_LOAD;
              if (!this.paused) {
                  this.eventSource.dispatchEvent('needData');
              }
          }).catch((e)=>{
              Log$11.error(e);
              // this.stop();
          });
      }
  }

  setDefaultLogLevel(LogLevel.Debug);
  getTagged("transport:ws").setLevel(LogLevel.Error);
  getTagged("client:rtsp").setLevel(LogLevel.Error);

  window.Streamedian = {
      init(el_id, options) {
          let wsTransport = {
              constructor: WebsocketTransport,
              options: {
                  socket: options.socket
              }
          };

          let p = new WSPlayer(el_id, {
              // url: `${STREAM_UNIX}${STREAM_URL}`,
              // type: wsp.StreamType.HLS,
              modules: [
                  {
                      client: RTSPClient,
                      transport: wsTransport
                  },
              ]
          });
      }
  }

}));
//# sourceMappingURL=test.bundle.js.map