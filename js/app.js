
var create_email = false;
var final_transcript = '';
var recognizing = false;
var ignore_onend;
var start_img = $('#start_img');
var start_timestamp;
if (!('webkitSpeechRecognition' in window)) {
  upgrade();
} else {

  var recognition = new webkitSpeechRecognition();
  recognition.continuous = true;
  recognition.interimResults = true;
  recognition.onstart = function() {
    recognizing = true;
    showInfo('info_speak_now');
    start_img.addClass('listening');
  };
  recognition.onerror = function(event) {
    if (event.error == 'no-speech') {
      start_img.removeClass('listening');
      showInfo('info_no_speech');
      ignore_onend = true;
    }
    if (event.error == 'audio-capture') {
      start_img.removeClass('listening');
      showInfo('info_no_microphone');
      ignore_onend = true;
    }
    if (event.error == 'not-allowed') {
      if (event.timeStamp - start_timestamp < 100) {
        showInfo('info_blocked');
      } else {
        showInfo('info_denied');
      }
      ignore_onend = true;
    }
  };
  recognition.onend = function() {
    recognizing = false;
    if (ignore_onend) {
      return;
    }
    start_img.removeClass('listening');
    if (!final_transcript) {
      showInfo('info_start');
      return;
    }
    showInfo('');
    if (window.getSelection) {
      window.getSelection().removeAllRanges();
      var range = document.createRange();
      range.selectNode(document.getElementById('final_span'));
      window.getSelection().addRange(range);
    }
    if (create_email) {
      create_email = false;
      createEmail();
    }
  };
  recognition.onresult = function(event) {
    var interim_transcript = '';
    for (var i = event.resultIndex; i < event.results.length; ++i) {
      if (event.results[i].isFinal) {
        final_transcript += event.results[i][0].transcript;
      } else {
        interim_transcript += event.results[i][0].transcript;
      }
    }
    final_transcript = capitalize(final_transcript);
    final_span.innerHTML = linebreak(final_transcript);
    $('#interim_span').val(linebreak(interim_transcript));
    if (final_transcript || interim_transcript) {
      showButtons('inline-block');
    }
  };
}
function upgrade() {
  start_button.style.visibility = 'hidden';
  showInfo('info_upgrade');
}
var two_line = /\n\n/g;
var one_line = /\n/g;
function linebreak(s) {
  return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
}
var first_char = /\S/;
function capitalize(s) {
  return s.replace(first_char, function(m) { return m.toUpperCase(); });
}
function createEmail() {
  var n = final_transcript.indexOf('\n');
  if (n < 0 || n >= 80) {
    n = 40 + final_transcript.substring(40).indexOf(' ');
  }
  var subject = encodeURI(final_transcript.substring(0, n));
  var body = encodeURI(final_transcript.substring(n + 1));
  window.location.href = 'mailto:?subject=' + subject + '&body=' + body;
}
function copyButton() {
  if (recognizing) {
    recognizing = false;
    recognition.stop();
  }
  copy_button.style.display = 'none';
  copy_info.style.display = 'inline-block';
  showInfo('');
}
function emailButton() {
  if (recognizing) {
    create_email = true;
    recognizing = false;
    recognition.stop();
  } else {
    createEmail();
  }
  email_button.style.display = 'none';
  email_info.style.display = 'inline-block';
  showInfo('');
}
function startButton(event) {
  if (recognizing) {
    recognition.stop();
    return;
  }
  final_transcript = '';
  recognition.start();
  ignore_onend = false;
  final_span.innerHTML = '';
  interim_span.innerHTML = '';
  start_img.addClass('listening');
  showInfo('info_allow');
  showButtons('none');
  start_timestamp = event.timeStamp;
}
function showInfo(s) {
  if (s) {
    for (var child = info.firstChild; child; child = child.nextSibling) {
      if (child.style) {
        child.style.display = child.id == s ? 'inline' : 'none';
      }
    }
    info.style.visibility = 'visible';
  } else {
    info.style.visibility = 'hidden';
  }
}
var current_style;
function showButtons(style) {
  if (style == current_style) {
    return;
  }
  current_style = style;
  copy_button.style.display = style;
  email_button.style.display = style;
  copy_info.style.display = 'none';
  email_info.style.display = 'none';
}

var x = $("#location");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    x.html("Latitude: <span id='lat'>" + position.coords.latitude +
    "</span>Longitude: <span id='long'>" + position.coords.longitude + "</span>");
    console.log(position.coords.latitude);
    console.log(position.coords.longitude);
}

$(function(){
  getLocation();
});


$(document).ready(function () {
 $("#apiex").submit(function (e) {
   e.preventDefault();
   submitForm();
 });

 $("input[type=checkbox]").change(function (e) {
   if($("input:checked").length > 0) {
     $("#err").hide();
   } else {
     $("#err").show();
   }
 });


 var isshown = { }
 $("#opts td").mouseover(
     function () {
       var t = $(this);
       h = setTimeout(function () {$(".tipbubbleup, .tipbubbledown", t).show()}, 1800);
       t.mouseout(function () {
         clearTimeout(h);
         $(".tipbubbleup, .tipbubbledown", t).hide();
       });
     }
 );

 $("#api-q a").click ( function (e) {
   e.preventDefault();
   submitForm();
 });

 if (navigator.userAgent.indexOf("Firefox")!=-1) {
   $(".tipbubbledown, .tipbubbleup").addClass("ff");
 }
});
function submitForm() {
 var q = $('#interim_span').val();
 if(q != 'blockaccess') {
   if(q != '' && q != null) {
     q = encodeURIComponent(q);
     var o = "";
     var reqo = "";
     if($("#html").is(':checked')) { o += "&html=true"; if(reqo.length > 0) {reqo += ",html"; } else {reqo += "html";} }
     if($("#image").is(':checked')) { o += "&image=true"; if(reqo.length > 0) {reqo += ",image"; } else {reqo += "image";} }
     if($("#plaintext").is(':checked')) { o += "&plaintext=true"; if(reqo.length > 0) {reqo += ",plaintext"; } else {reqo += "plaintext";} }
     if($("#cells").is(':checked')) { o += "&cell=true"; if(reqo.length > 0) {reqo += ",cell"; } else {reqo += "cell";} }
     if($("#sound").is(':checked')) { o += "&sound=true"; if(reqo.length > 0) {reqo += ",sound"; } else {reqo += "sound";} }
     if($("#mathin").is(':checked')) { o += "&minput=true"; if(reqo.length > 0) {reqo += ",minput"; } else {reqo += "minput";}}
     if(reqo.length > 0) {
       $("#results").hide();
       $("#loading").show();
       $("#wa").attr("href", "http://www.wolframalpha.com/input/?i=" + q);
       $("#request").html("http://api.wolframalpha.com/v2/query?appid=G4UT54-4ETKTTH58Y&input="+q+"&format="+reqo);
       $("#links .hide").removeClass("hide");
       window.frames['results'].location = "/alpha/styledapiresults.jsp?i="+ q + o;

       var frame = window.fram
     } else {
       $("#err").show();
     }
   }
 } else {
   window.frames['results'].location = "blocked-explorer.html";
 }
}
var h;
//
// var map;
// function initialize() {
//   var lat = $("#lat").text();
//   var long = $("#long").text();
//   map = new google.maps.Map(document.getElementById('map-canvas'), {
//     zoom: 8,
//     center: { lat: lat, lng: long},
//   });
// }
//
// google.maps.event.addDomListener(window, 'load', initialize);
