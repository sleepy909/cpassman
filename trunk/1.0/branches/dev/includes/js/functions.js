//Fonction qui permet d'appeler un fichier qui exécute une requete passée en parametre
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

function LoadingPage(){
    if ( document.getElementById('div_loading').style.display == "" )
        document.getElementById('div_loading').style.display = "none";
    else
        document.getElementById('div_loading').style.display = "";
}

//permet de mettre à jour la liste contenant les ID des utilisateurs
function maj_liste_restriction(val, element){
    var liste = document.getElementById(element).value
    var index = liste.lastIndexOf(val+";");
    var longueur = val.length+1;
    if ( index != -1 ){
        liste = liste.substr(0,index)+liste.substr(index+longueur);
    }else{
        liste = liste + val + ";";
    }
    document.getElementById(element).value = liste;
}

function AfficherCacher(divId){
    if ( document.getElementById(divId).style.display == "" )
        document.getElementById(divId).style.display = "none";
    else
        document.getElementById(divId).style.display = "";
}