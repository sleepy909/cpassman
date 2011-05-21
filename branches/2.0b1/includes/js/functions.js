/**
 * @file 		functions.js
 * @author		Nils Laumaillé
 * @version 	2.0
 * @copyright 	(c) 2009-2011 Nils Laumaillé
 * @licensing 	CC BY-ND (http://creativecommons.org/licenses/by-nd/3.0/legalcode)
 * @link		http://cpassman.org
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

// Function - do a pause during javascript execution
function PauseInExecution(millis)
{
    var date = new Date();
    var curDate = null;

    do { curDate = new Date(); }
    while(curDate-date < millis);
}

//Fonction qui permet d'appeler un fichier qui exécute une requete passée en parametre
function httpRequest(file,data,type){
    var xhr_object = null;
    var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;

	if (document.getElementById("menu_action") != null) {
		document.getElementById("menu_action").value = "action";
	}

    if(window.XMLHttpRequest){ // Firefox
        xhr_object = new XMLHttpRequest();
    }else if(window.ActiveXObject){ // Internet Explorer
        xhr_object = new ActiveXObject("Microsoft.XMLHTTP");  //Info IE8 now supports =>  xhr_object = new XMLHttpRequest()
    }else { // XMLHttpRequest non support? par le navigateur
        alert("Your browser does not support XMLHTTPRequest objects ...");
        return;
    }

    if ( type == "GET" ) {
        xhr_object.open("GET", file+"?"+data, true);
        xhr_object.send(null);
    }else{
        xhr_object.open("POST", file, true);
        xhr_object.onreadystatechange = function() {
          if(xhr_object.readyState == 4) {
              eval(xhr_object.responseText);
              //Check if query is for user identification. If yes, then reload page.
              if ( data != "" && data.indexOf('ype=identify_user') > 0 ) {
                  if ( is_chrome == true ) PauseInExecution(100);  //Needed pause for Chrome
                  if ( type == "" ){
                      if ( document.getElementById('erreur_connexion').style.display == "" ){
                          //rise an error in url. This in order to display the eror after refreshing
                          window.location.href="index.php?error=rised";
                      }else{
                        window.location.href="index.php";
                      }
                  }else{
                      if ( type = "?error=rised" ){
                            if ( document.getElementById('erreur_connexion').style.display == "none" ) type = "";   //clean error in url
                            else type = "?error=rised"; //Maintain the ERROR
                      }
                      window.location.href="index.php"+type;
                  }
              }
          }
        }
        xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
        xhr_object.send(data);
    }
}

function LoadingPage(){
    if ( document.getElementById('div_loading').style.display == "" )
        document.getElementById('div_loading').style.display = "none";
    else
        document.getElementById('div_loading').style.display = "";
}

//permet de mettre ? jour la liste contenant les ID des utilisateurs
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
    document.getElementById('countdown').style.color="white";
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

    //G?rer la fin de la session
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

//Permits to open a dialogbox
function OpenDialog(id){
    $('#'+id).dialog('open');
}

//Permits to toggle a div
function toggleDiv(id){
    $('#'+id).toggle();
}

//Permits to check if a value is an integer
function isInteger(s) {
  return (s.toString().search(/^-?[0-9]+$/) == 0);
}

//Permits to create random strings
function CreateRandomString(size,type){
    var chars = "";

    // CHoose what kind of string we want
    if ( type == "num" ) chars = "0123456789";
    else if ( type == "num_no_0" ) chars = "123456789";
    else if ( type == "alpha" ) chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    else chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";

    //generate it
    var randomstring = '';
    for (var i=0; i<size; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum,rnum+1);
    }

    //return
    return randomstring;
}