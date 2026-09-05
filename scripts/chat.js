var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie  = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
          && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
          && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));

var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

// Define the bbCode tags
bbcode = new Array();
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[ul]','[/ul]','[ol]','[/ol]','[img size=150]','[/img]','[url]','[/url]','[li]','[/li]');
imageTag = false;

// Replacement for arrayname.length property
function getarraysize(thearray) {
   for (i = 0; i < thearray.length; i++) {
      if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
         return i;
      }
   return thearray.length;
}

// Replacement for arrayname.push(value) not implemented in IE until version 5.5
// Appends element to the array
function arraypush(thearray,value) {
   thearray[ getarraysize(thearray) ] = value;
}

// Replacement for arrayname.pop() not implemented in IE until version 5.5
// Removes and returns the last element of an array
function arraypop(thearray) {
   thearraysize = getarraysize(thearray);
   retval = thearray[thearraysize - 1];
   delete thearray[thearraysize - 1];
   return retval;
}

function bbstyle(bbnumber) {

   var input = document.postform.message;

   textarea.focus();
   donotinsert = false;
   theSelection = false;
   bblast = 0;

   if (bbnumber == -1) { // Close all open tags & default button names
      while (bbcode[0]) {
         butnumber = arraypop(bbcode) - 1;
         textarea.value += bbtags[butnumber + 1];
         buttext = eval('document.postform.addbbcode' + butnumber + '.value');
         eval('document.postform.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
	}
      imageTag = false; // All tags are closed including image tags :D
      textarea.focus();
      return;
	}

	if ((clientVer >= 4) && is_ie && is_win){
		theSelection = document.selection.createRange().text; // Get text selection
      
		if (theSelection) {
			// Add tags around selection
			document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
			textarea.focus();
			theSelection = '';
			return;
		} else {
			textarea.focus();
			document.selection.createRange().text = bbtags[bbnumber] + bbtags[bbnumber + 1];
			return;
		}
	}
	else if (textarea.selectionEnd && (textarea.selectionEnd - textarea.selectionStart > 0)){
      mozWrap(input, bbtags[bbnumber], bbtags[bbnumber+1]);
      return;
	}
	else //if (textarea.selectionEnd == textarea.selectionStart) // don't know if we need it... it works even if commented out. ;)
	{
		textarea.value = textarea.value.substring(0, textarea.selectionStart) + bbtags[bbnumber] + bbtags[bbnumber + 1] + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
        return;
	}

	// Find last occurance of an open tag the same as the one just clicked
	for (i = 0; i < bbcode.length; i++) {
      if (bbcode[i] == bbnumber+1) {
         bblast = i;
         donotinsert = true;
		}
	}

   if (donotinsert) { // Close all open tags up to the one just clicked & default button names
		while (bbcode[bblast]) {
			butnumber = arraypop(bbcode) - 1;
            textarea.value += bbtags[butnumber + 1];
            buttext = eval('document.postform.addbbcode' + butnumber + '.value');
            eval('document.postform.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
            imageTag = false;
		}
        textarea.focus();
        return;
	} else { // Open tags

		if (imageTag && (bbnumber != 14)) {    // Close image tag before adding another
			textarea.value += bbtags[15];
			lastValue = arraypop(bbcode) - 1;   // Remove the close image tag from the list
			document.postform.addbbcode14.value = "Img";  // Return button back to normal state
			imageTag = false;
		}

      // Open tag
	textarea.value += bbtags[bbnumber];
    if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
		arraypush(bbcode,bbnumber+1);
		eval('document.postform.addbbcode'+bbnumber+'.value += "*"');
		textarea.focus();
		return;
	}
   storeCaret(input);
}

// From http://www.massless.org/mozedit/
function mozWrap(input, open, close){
	var selLength = textarea.textLength;
	var selStart = textarea.selectionStart;
	var selEnd = textarea.selectionEnd;
	if (selEnd == 1 || selEnd == 2)
      selEnd = selLength;

	var s1 = (textarea.value).substring(0,selStart);
	var s2 = (textarea.value).substring(selStart, selEnd)
	var s3 = (textarea.value).substring(selEnd, selLength);
	textarea.value = s1 + open + s2 + close + s3;
	return;
}

// Insert at Claret position. Code from
// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
function storeCaret(textEl) {
   if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}

function bbfontstyle(bbopen, bbclose) {
	var input = document.postform.message;

	if ((clientVer >= 4) && is_ie && is_win) {
		theSelection = document.selection.createRange().text;

		textarea.focus();

		if (!theSelection) {
			document.selection.createRange().text = bbopen + bbclose;
		} else {
			document.selection.createRange().text = bbopen + theSelection + bbclose;
		}

		textarea.focus();

		return;
	}
		else if (textarea.selectionEnd && (textarea.selectionEnd - textarea.selectionStart > 0))
	{
		mozWrap(input, bbopen, bbclose);
		return;
	} else {
		textarea.value = textarea.value.substring(0, textarea.selectionStart) + bbopen + bbclose + textarea.value.substring(textarea.selectionEnd, textarea.value.length);
		textarea.focus();
	}
	storeCaret(input);
}

//#######################################################
//code used in My Profile (userprofile.php)
function textCounter(field, countfield, maxlimit) {
	if(field.value.length > maxlimit){
		field.value = field.value.substring(0, maxlimit);
	} else {
		countfield.value = maxlimit - field.value.length;
	}
}
//*********************************************************

// Insert emoticons

function emo($e) {
	var textfield = document.postform.message;

    // Support for IE
    if (document.selection) {
		textfield.focus();
		var sel = document.selection.createRange();
        sel.text = $e;
    }
    // Support for Mozilla
    else if (textfield.selectionStart || textfield.selectionStart == '0') {
		var start = textfield.selectionStart;
        var end = textfield.selectionEnd;
        textfield.value = textfield.value.substring(0, start) + $e + textfield.value.substring(end, textfield.value.length);
    } else {
        textfield.value = textfield.value + $e;
    }

    textfield.focus();
}

function submitForm() {
	if (submitme>0) {
    var message = document.postform.message.value;
    message = message.replace(/</g,"&lt;");
    message = message.replace(/>/g,"&gt;");  
    document.postform.message.value = message;
	
var message = document.postform.message;

	//these gotta be in both... I don't know why, but it works...
	messageString = message.innertext;
	messageString = message.value;

if (disemoticons == 0) {
	messageString = messageString.replace(/B\)/g,'<img src="emoticons/cool.png"/>');
	messageString = messageString.replace(/;-\)/g,'<img src="emoticons/wink.png"/>');
	messageString = messageString.replace(/;\)/g,'<img src="emoticons/wink.png"/>');
	messageString = messageString.replace(/:y32b4:/g,'<img src="emoticons/silly.png"/>');
	messageString = messageString.replace(/:x/g,'<img src="emoticons/sick.png"/>');
	messageString = messageString.replace(/:woohoo:/g,'<img src="emoticons/w00t.png"/>');
	messageString = messageString.replace(/:whistle:/g,'<img src="emoticons/whistling.png"/>');
	messageString = messageString.replace(/:unsure:/g,'<img src="emoticons/unsure.png"/>');
	messageString = messageString.replace(/:silly:/g,'<img src="emoticons/silly.png"/>');
	messageString = messageString.replace(/:side:/g,'<img src="emoticons/sideways.png"/>');
	messageString = messageString.replace(/:sick:/g,'<img src="emoticons/sick.png"/>');
	messageString = messageString.replace(/:s/g,'<img src="emoticons/dizzy.png"/>');
	messageString = messageString.replace(/:rolleyes:/g,'<img src="emoticons/blink.png"/>');
	messageString = messageString.replace(/:pinch:/g,'<img src="emoticons/pinch.png"/>');
	messageString = messageString.replace(/:love:/g,'<img src="emoticons/love.png"/>');
	messageString = messageString.replace(/:p/g,'<img src="emoticons/tongue.png"/>');
	messageString = messageString.replace(/:ohmy:/g,'<img src="emoticons/shocked.png"/>');
	messageString = messageString.replace(/:mad:/g,'<img src="emoticons/angry.png"/>');
	messageString = messageString.replace(/:lol:/g,'<img src="emoticons/grin.png"/>');
	messageString = messageString.replace(/:laugh:/g,'<img src="emoticons/laughing.png"/>');
	messageString = messageString.replace(/:kiss:/g,'<img src="emoticons/kissing.png"/>');
	messageString = messageString.replace(/:huh:/g,'<img src="emoticons/wassat.png"/>');
	messageString = messageString.replace(/:evil:/g,'<img src="emoticons/devil.png"/>');
	messageString = messageString.replace(/:ermm:/g,'<img src="emoticons/ermm.png"/>');
	messageString = messageString.replace(/:dry:/g,'<img src="emoticons/ermm.png"/>');
	messageString = messageString.replace(/:cheer:/g,'<img src="emoticons/cheerful.png"/>');
	messageString = messageString.replace(/:blush:/g,'<img src="emoticons/blush.png"/>');
	messageString = messageString.replace(/:blink:/g,'<img src="emoticons/blink.png"/>');
	messageString = messageString.replace(/:angry:/g,'<img src="emoticons/angry.png"/>');
	messageString = messageString.replace(/:X/g,'<img src="emoticons/sick.png"/>');
	messageString = messageString.replace(/:S/g,'<img src="emoticons/dizzy.png"/>');
	messageString = messageString.replace(/:P/g,'<img src="emoticons/tongue.png"/>');
	messageString = messageString.replace(/:D/g,'<img src="emoticons/laughing.png"/>');
	messageString = messageString.replace(/:-\)/g,'<img src="emoticons/smile.png"/>');
	messageString = messageString.replace(/:-\(/g,'<img src="emoticons/sad.png"/>');
	messageString = messageString.replace(/:\)/g,'<img src="emoticons/smile.png"/>');
	messageString = messageString.replace(/:\(/g,'<img src="emoticons/sad.png"/>');
}

// change the following line to true to submit form
	return true;

	} else {
	return false;
	}
}