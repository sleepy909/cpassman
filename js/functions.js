function httpRequest(file,data,type){
     var xhr_object = null;
     
     if(window.XMLHttpRequest) // Firefox
      xhr_object = new XMLHttpRequest();
    else if(window.ActiveXObject) // Internet Explorer
      xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
    else { // XMLHttpRequest non supporté par le navigateur
      alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
      return;
    }
    
    if ( type == "GET" ) {
        xhr_object.open("GET", file+"?"+data, true);        
        xhr_object.send(null);
    }else{
        xhr_object.open("POST", file, true);
        xhr_object.onreadystatechange = function() {
          if(xhr_object.readyState == 4) eval(xhr_object.responseText);
        }
        //xhr_object.overrideMimeType('text/html; charset=ISO-8859-15');
        xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr_object.send(data);
    }
}