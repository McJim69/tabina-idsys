var http;

function loadXMLDoc(url, functionCall) {
  http = getHTTPObject();

  if (http) {
    http.onreadystatechange = functionCall;
    http.open("GET", url, true);
    http.send("");
  }
}

function getHTTPObject() {
  var xmlhttp;

  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }

  return xmlhttp;
}

function hideDiv(obj) {
  if (obj.style.visibility == 'visible') {
    obj.style.visibility = 'hidden';
    obj.style.display = 'none';
  }
}

function showDiv(obj) {
  if (obj.style.visibility != 'visible') {
    obj.style.visibility = 'visible';
    obj.style.display = 'inline';
  }
}

function isDivVisible(obj) {
  return (obj.style.visibility == 'visible');
}

function urlEncode(string) {
  if (window.encodeURIComponent) {
    return encodeURIComponent(string);
  }

  return escape(string);
}

function urlDecode(string) {
  if (window.decodURIComponent) {
    return decodeURIComponent(string);
  }

  return unescape(string);
}

var formSubmited = false;

function uploadFile() {
  if (formSubmited == true) {
    return false;
    }

    formSubmited = true;

    showDiv(document.getElementById('mBox'));

    document.getElementById('mBoxContents').innerHTML = '<p><img src="images/loading.gif" align="center" /></p><p>Uploading File... Please Wait.</p>';

  loadXMLDoc("gal_doc_uploader.php?action=gal_doc_uploader.php=" + (move_uploaded_file));
}