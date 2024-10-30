function winListener(eventName, fn, fnName) {
  if (window.attachEvent)
    window.attachEvent("on"+eventName, fn);
  else
  if (window.addEventListener)
    window.addEventListener(eventName, fn, false);
  else
    eval(
      "if (window.on"+eventName+") {" +
        "old_window_onEvent"+eventName+" = window.on"+eventName+";" +
        "window.on"+eventName+" = function(){old_window_onEvent"+eventName+"(); fnName();}" +
      "} else window.on"+eventName+" = fnName;"
    );
}
function docListener(eventName, fn, fnName) {
  if (document.attachEvent)
    document.attachEvent("on"+eventName, fn);
  else
  if (document.addEventListener)
    document.addEventListener(eventName, fn, false);
  else
    eval(
      "if (document.on"+eventName+") {" +
        "old_window_onEvent"+eventName+" = document.on"+eventName+";" +
        "document.on"+eventName+" = function(){old_window_onEvent"+eventName+"(); fnName();}" +
      "} else document.on"+eventName+" = fnName;"
    );
}
function iPop_close(pop_name) {
  if(!pop_name) pop_name = "pops_popup";
  DHTMLAPI_hide(pop_name);
}
function pops_iPOP_close() { DHTMLAPI_hide("pops_popup"); }
function pops_iPOP_init0() {
  if (!pops_iPOP_CookieCheck()) return;
  DHTMLAPI_init();
  pops_popup_Obj = DHTMLAPI_getRawObject("pops_popup");
  pops_popup_move();
  window.onscroll=pops_popup_move;
  window.onresize=pops_popup_move;
}
function pops_iPOP_init1() {
  if (!pops_iPOP_CookieCheck()) return;
  DHTMLAPI_init();
  pops_popup_delta = 16;
  pops_popup_Obj = DHTMLAPI_getRawObject("pops_popup");
  var theObj = pops_popup_Obj; if (theObj && isCSS) theObj = theObj.style;
  //if (theObj && theObj.visibility == "hidden") return;
  if (theObj && theObj.visibility == "hidden") { theObj.visibility = "visible"; }
  if (theObj && theObj.display == "none") { theObj.display = "block"; theObj.position = "absolute"; }
  DHTMLAPI_shiftTo(pops_popup_Obj, 0, 5000);
  var center = DHTMLAPI_positionWindow(pops_popup_Obj, true);
  pops_popup_x = center[0];
  pops_popup_y = center[1];
  var w_scroll = DHTMLAPI_getScrollWindow();
  var start_y = parseInt((w_scroll[1]-pops_popup_y-DHTMLAPI_getObjectHeight(pops_popup_Obj)-100)/100)*100 + pops_popup_y;
  DHTMLAPI_shiftTo(pops_popup_Obj, pops_popup_x, start_y);
  pops_popup_dropstart=setInterval("pops_popup_drop()",50);
}
function pops_popup_move() {
  if (window.pops_popup_timeout) clearTimeout(window.pops_popup_timeout);
  if (!pops_popup_Obj) return;
  var theObj = pops_popup_Obj; if (theObj && isCSS) theObj = theObj.style;
  if (theObj && theObj.visibility == "hidden") return;
  if (theObj && theObj.display == "none") { theObj.display = "block"; theObj.position = "absolute"; }
  DHTMLAPI_positionWindow(pops_popup_Obj);
  window.pops_popup_timeout = setTimeout("pops_popup_move()", 100);
}
function DHTMLAPI_positionWindow(elemID, positionOnly) {
  var obj = DHTMLAPI_getRawObject(elemID);
  var position = obj.getAttribute("pos");
  var scrollX = 0, scrollY = 0;
  if (document.body && typeof(document.body.scrollTop) != "undefined") {
    scrollX += document.body.scrollLeft;
    scrollY += document.body.scrollTop;
    if (0 == document.body.scrollTop
    && document.documentElement
    && typeof(document.documentElement.scrollTop) != "undefined") {
      scrollX += document.documentElement.scrollLeft;
      scrollY += document.documentElement.scrollTop;
    }
  } else if (typeof(window.pageXOffset) != "undefined") {
    scrollX += window.pageXOffset;
    scrollY += window.pageYOffset;
  }
  var x = Math.round((DHTMLAPI_getInsideWindowWidth( )/2) - (DHTMLAPI_getObjectWidth(obj)/2)) + scrollX;
  var y = Math.round((DHTMLAPI_getInsideWindowHeight( )/2) - (DHTMLAPI_getObjectHeight(obj)/2)) + scrollY;
  var shift_position = parseInt(0);
  if (isNaN(shift_position)) shift_position = 0;
  switch (position) {
    case "tc": y = scrollY+shift_position; break;
    case "tl": y = scrollY+shift_position; x = scrollX+shift_position; break;
    //case "tr": y = scrollY+shift_position; x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position - 17; break;
    case "tr":
    	y = scrollY+shift_position;
    	var pops_margin_right_elem = document.getElementById('pops_popup');
    	if (isIE4)
			x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position;
    	else
    		x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position - 17 - pops_margin_right_elem.style.marginRight.replace("px", "");
	break;
    case "ml": x = scrollX+shift_position; break;
    //case "mr": x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position; break;
    case "mr":
    	var pops_margin_right_elem = document.getElementById('pops_popup');
    	if (isIE4)
    		x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position;
    	else
			x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position - 17 - pops_margin_right_elem.style.marginRight.replace("px", "");
    break;
    case "bc": y = Math.round(DHTMLAPI_getInsideWindowHeight( ) - DHTMLAPI_getObjectHeight(obj)) + scrollY-shift_position; break;
    case "bl": y = Math.round(DHTMLAPI_getInsideWindowHeight( ) - DHTMLAPI_getObjectHeight(obj)) + scrollY-shift_position; x = scrollX+shift_position; break;
    //case "br": y = Math.round(DHTMLAPI_getInsideWindowHeight( ) - DHTMLAPI_getObjectHeight(obj)) + scrollY-shift_position; x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position; break;
    case "br":
    	var pops_margin_elem = document.getElementById('pops_popup');
    	if (isIE4)
    	{
    		y = Math.round(DHTMLAPI_getInsideWindowHeight( ) - DHTMLAPI_getObjectHeight(obj)) + scrollY-shift_position;
    		x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position;
    	}
    	else
    	{
    		y = Math.round(DHTMLAPI_getInsideWindowHeight( ) - DHTMLAPI_getObjectHeight(obj)) + scrollY-shift_position - pops_margin_elem.style.marginBottom.replace("px", "");
    		x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position - 17 - pops_margin_elem.style.marginRight.replace("px", "");
    	}
	break;
  }
  if (!positionOnly) DHTMLAPI_shiftTo(obj, x, y);
  return [x, y];
}
function pops_popup_drop() {
  var y = DHTMLAPI_getObjectTop(pops_popup_Obj);
  if ( pops_popup_y > y ) DHTMLAPI_shiftTo(pops_popup_Obj, pops_popup_x, 50+y);
  else {
    clearInterval(pops_popup_dropstart);
    pops_popup_vibrostart = setInterval("pops_popup_vibro()",40);
  }
}
function pops_popup_vibro() {
  var y = DHTMLAPI_getObjectTop(pops_popup_Obj);
  DHTMLAPI_shiftTo(pops_popup_Obj, pops_popup_x, y-pops_popup_delta);
  if (pops_popup_delta<0) pops_popup_delta += 4;
  pops_popup_delta *= -1;
  if (pops_popup_delta==0) {
    clearInterval(pops_popup_vibrostart);
    pops_popup_move();
    window.onscroll=pops_popup_move;
    window.onresize=pops_popup_move;
  }
}
function DHTMLAPI_hide(obj) {
  var theObj = DHTMLAPI_getObject(obj);
  //if (theObj) theObj.visibility = "hidden";
  if (theObj)
  {
  	theObj.visibility = "hidden";
  	theObj.display = "none";
  }
}
function DHTMLAPI_getRawObject(obj) {
  var theObj;
  if (typeof obj == "string") {
    if (isW3C) theObj = document.getElementById(obj);
    else if (isIE4) theObj = document.all(obj);
    else if (isNN4) theObj = DHTMLAPI_seekLayer(document, obj);
  } else theObj = obj;
  return theObj;
}
function DHTMLAPI_shiftTo(obj, x, y) {
  var theObj = DHTMLAPI_getObject(obj);
  if (theObj) {
    if (isCSS) {
      var units = (typeof theObj.left == "string") ? "px" : 0;
      theObj.left = x + units;
      theObj.top = y + units;
    } else if (isNN4) theObj.moveTo(x,y);
  }
}
function DHTMLAPI_getScrollWindow() {
  var scrollX = 0, scrollY = 0;
  if (document.body && typeof(document.body.scrollTop) != "undefined") {
    scrollX += document.body.scrollLeft;
    scrollY += document.body.scrollTop;
  } else if (typeof(window.pageXOffset) != "undefined") {
    scrollX += window.pageXOffset;
    scrollY += window.pageYOffset;
  }
  return [scrollX, scrollY];
}
function DHTMLAPI_getObjectHeight(obj)  {
  var elem = DHTMLAPI_getRawObject(obj);
  var result = 0;
  if (elem.offsetHeight) result = elem.offsetHeight;
  else if (elem.clip && elem.clip.height) result = elem.clip.height;
  else if (elem.style && elem.style.pixelHeight) result = elem.style.pixelHeight;
  return parseInt(result);
}
function DHTMLAPI_getObjectTop(obj)  {
  var elem = DHTMLAPI_getRawObject(obj);
  var result = 0;
  if (document.defaultView) {
    var style = document.defaultView;
    var cssDecl = style.getComputedStyle(elem, "");
    result = cssDecl.getPropertyValue("top");
  }
  else if (elem.currentStyle) result = elem.currentStyle.top;
  else if (elem.style) result = elem.style.top;
  else if (isNN4) result = elem.top;
  return parseInt(result);
}
function DHTMLAPI_getObject(obj) {
  var theObj = DHTMLAPI_getRawObject(obj);
  if (theObj && isCSS) theObj = theObj.style;
  return theObj;
}
function DHTMLAPI_seekLayer(doc, name) {
  var theObj;
  for (var i = 0; i < doc.layers.length; i++) {
    if (doc.layers[i].name == name) {
      theObj = doc.layers[i];
      break;
    }
    if (doc.layers[i].document.layers.length > 0) theObj = DHTMLAPI_seekLayer(document.layers[i].document, name);
  }
  return theObj;
}
function DHTMLAPI_getInsideWindowWidth( ) {
  if (window.innerWidth) return window.innerWidth;
  else if (isIE6CSS) return document.body.parentElement.clientWidth;
  else if (document.body && document.body.clientWidth) return document.body.clientWidth;
  return 0;
}
function DHTMLAPI_getInsideWindowHeight( ) {
  if (window.innerHeight) return window.innerHeight;
  else if (isIE6CSS) return document.body.parentElement.clientHeight;
  else if (document.body && document.body.clientHeight) return document.body.clientHeight;
  return 0;
}
function DHTMLAPI_getObjectWidth(obj)  {
  var elem = DHTMLAPI_getRawObject(obj);
  var result = 0;
  if (elem.offsetWidth) result = elem.offsetWidth;
  else if (elem.clip && elem.clip.width) result = elem.clip.width;
  else if (elem.style && elem.style.pixelWidth) result = elem.style.pixelWidth;
  return parseInt(result);
}
function DHTMLAPI_init( ) {
  if (document.images) {
    isCSS = (document.body && document.body.style) ? true : false;
    isW3C = (isCSS && document.getElementById) ? true : false;
    isIE4 = (isCSS && document.all) ? true : false;
    isNN4 = (document.layers) ? true : false;
    isIE6CSS = (document.compatMode && document.compatMode.indexOf("CSS1") >= 0) ? true : false;
  }
}
function pops_iPOP_CookieCheck() {return true;};
