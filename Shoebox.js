// filename: Shoebox.js
// Copyright © 2010-2017 Susie White
// purpose:  Shoebox javascript tool box
// usage:    <script type="text/javascript" src="Shoebox.js"></script>
// history:
//	swhite	13-feb-2013	created
//

// .............................................................................
// hint()
// .............................................................................
// <?php header('Content-Type: text/javascript; charset=UTF-8'); ?>
// Pop up hint text 
//
// Usage:
// <select name="v_catid" id="v_catid" 
//     onChange="javascript:hint(this.value);" title="Select a report" >
//     <option value="clients">Clients</option></select>
function hint(item) {
    var Str = "Command line options: ";
    if (item == "clients")       { Str = "Optional qualifiers: name and client id "; } 
    document.getElementById("msgbox").innerHTML = Str;
}

// ....................................................................................
// linkOn() and linkOff()
// ....................................................................................
// Usage:
//  <a href='mailto:myfubar@gmail.com'
//    onMouseOver='linkOn(this);' onMouseOut='linkOff(this);'>
//    <u>myfubar@gmail.com</u></a>
//
// Function to activate a link in the toolbar
var currentLink = "zzzzz";
function linkOn(newLink) {
  if (currentLink != newLink) {
    if (currentLink != "zzzzz") {
      currentLink.style.color = "#000000";
      currentLink.style.fontWeight = "normal";
      currentLink.style.fontFamily = "Arial,Helvetica";
      currentLink.style.textDecoration = "none";
    }
    currentLink = newLink;

    newLink.style.color = "#ffffff";
    newLink.style.fontWeight = "normal";
    newLink.style.fontFamily = "Arial,Helvetica";
    newLink.style.textDecoration = "none";
  }
}

function linkOff(newLink) {
    newLink.style.color = "#000000";
    newLink.style.fontWeight = "normal";
    newLink.style.fontFamily = "Arial,Helvetica";
    newLink.style.textDecoration = "none";
}

// ....................................................................................
// turnOn() and turnOff()
// ....................................................................................
// Usage
// <a onMouseOver="linkOn(this);" href="javascript:turnOn('doc1div')">
//       DBA Best Practices</font></a>
var currentDoc = "homediv";
var currentDoc2 = "zzzzz";
function turnOn(newDoc) {
  if (currentDoc2 != newDoc) {
    // Adjust the visibility and background color for the folders
    var thisDoc = document.getElementById(newDoc);
    thisDoc.style.visibility = "visible";

    if (currentDoc2 != "zzzzz" && currentDoc2 != "tooldiv" ) {
      var oldDoc = document.getElementById(currentDoc2);
      oldDoc.style.visibility = "hidden";
    }
    currentDoc2 = newDoc;
  }
}

// ....................................................................................
// go_back()
// ....................................................................................
function go_back() {
       gb = new backlink();
       gb.text = "Go Back";
       gb.write();
}

function GetAcct() {
   var comp_acct = "<?php echo json_encode($comp_acct); ?>";
   var x = document.getElementById("f_inv_cid").value;
   document.getElementById("selectBox").value = comp_acct[x];
}
