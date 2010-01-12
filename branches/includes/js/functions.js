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

function RefreshPage(myform){
    document.forms[myform].submit();
}
            
//Add 1 hour to session duration
function AugmenterSession(){
    var data = "type=augmenter_session";
    httpRequest("sources/main.queries.php",data);
    document.getElementById('countdown').style.color="black"; 
}

//Countdown before session expiration
function countdown()
{
    var DayTill
    var theDay =  document.getElementById('temps_restant').value;
    var today = new Date() //Create an Date Object that contains today's date.
    var second = Math.floor(theDay - (today.getTime()/1000))
    var minute = Math.floor(second/60) //Devide "second" into 60 to get the minute
    var hour = Math.floor(minute/60) //Devide "minute" into 60 to get the hour
    CHour= hour % 24 //Correct hour, after devide into 24, the remainder deposits here.
    if (CHour<10) {CHour = "0" + CHour}
    CMinute= minute % 60 //Correct minute, after devide into 60, the remainder deposits here.
    if (CMinute<10) {CMinute = "0" + CMinute}
    CSecond= second % 60 //Correct second, after devide into 60, the remainder deposits here.
    if (CSecond<10) {CSecond = "0" + CSecond}
    DayTill = CHour+":"+CMinute+":"+CSecond
    
    //Avertir de la fin imminante de la session
    if ( DayTill == "00:01:00" ){
        $('#div_fin_session').dialog('open');
        document.getElementById('countdown').style.color="red"; 
    }
    
    //Gérer la fin de la session
    if ( DayTill == "00:00:00" )
        document.location = "index.php?session=expiree";
    
    //Rewrite the string to the correct information.
    if ( document.getElementById('countdown') )
        document.getElementById('countdown').innerHTML = DayTill //Make the particular form chart become "Daytill"
    var counter = setTimeout("countdown()", 1000) //Create the timer "counter" that will automatic restart function countdown() again every second.
}

//Change language using icon flags
function ChangeLanguage(lang){
    document.getElementById('language').value = lang;
    document.temp_form.submit();
}

function OpenDialog(id){
    $('#'+id).dialog('open');
}