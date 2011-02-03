// Password strength meter v2.0
// Matthew R. Miller - 2007
// www.codeandcoffee.com
// Based off of code from:
//  http://www.intelligent-web.co.uk
//  http://www.geekwisdom.com/dyn/passwdmeter

/*
	Password Strength Algorithm:
	
	Password Length:
		5 Points: Less than 4 characters
		10 Points: 5 to 7 characters
		25 Points: 8 or more
		
	Letters:
		0 Points: No letters
		10 Points: Letters are all lower case
		20 Points: Letters are upper case and lower case

	Numbers:
		0 Points: No numbers
		10 Points: 1 number
		20 Points: 3 or more numbers
		
	Characters:
		0 Points: No characters
		10 Points: 1 character
		25 Points: More than 1 character

	Bonus:
		2 Points: Letters and numbers
		3 Points: Letters, numbers, and characters
		5 Points: Mixed case letters, numbers, and characters
		
	Password Text Range:
	
		>= 90: Very Secure
		>= 80: Secure
		>= 70: Very Strong
		>= 60: Strong
		>= 50: Average
		>= 25: Weak
		>= 0: Very Weak
		
*/


// Settings
// -- Toggle to true or false, if you want to change what is checked in the password
var m_strUpperCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
var m_strLowerCase = "abcdefghijklmnopqrstuvwxyz";
var m_strNumber = "0123456789";
var m_strCharacters = "!@#$%^&*?_~.,:!";

// Check password
function checkPassword(strPassword)
{
	// Reset combination count
	var nScore = 0;
	
	// Password length
	// -- Less than 4 characters
	if (strPassword.length < 5)
	{
		nScore += 5;
	}
	// -- 5 to 7 characters
	else if (strPassword.length > 4 && strPassword.length < 8)
	{
		nScore += 10;
	}
	// -- 8 or more
	else if (strPassword.length > 7)
	{
		nScore += 25;
	}

	// Letters
	var nUpperCount = countContain(strPassword, m_strUpperCase);
	var nLowerCount = countContain(strPassword, m_strLowerCase);
	var nLowerUpperCount = nUpperCount + nLowerCount;
	// -- Letters are all lower case
	if (nUpperCount == 0 && nLowerCount != 0) 
	{ 
		nScore += 10; 
	}
	// -- Letters are upper case and lower case
	else if (nUpperCount != 0 && nLowerCount != 0) 
	{ 
		nScore += 20; 
	}
	
	// Numbers
	var nNumberCount = countContain(strPassword, m_strNumber);
	// -- 1 number
	if (nNumberCount == 1)
	{
		nScore += 10;
	}
	// -- 3 or more numbers
	if (nNumberCount >= 3)
	{
		nScore += 20;
	}
	
	// Characters
	var nCharacterCount = countContain(strPassword, m_strCharacters);
	// -- 1 character
	if (nCharacterCount == 1)
	{
		nScore += 10;
	}	
	// -- More than 1 character
	if (nCharacterCount > 1)
	{
		nScore += 25;
	}
	
	// Bonus
	// -- Letters and numbers
	if (nNumberCount != 0 && nLowerUpperCount != 0)
	{
		nScore += 2;
	}
	// -- Letters, numbers, and characters
	if (nNumberCount != 0 && nLowerUpperCount != 0 && nCharacterCount != 0)
	{
		nScore += 3;
	}
	// -- Mixed case letters, numbers, and characters
	if (nNumberCount != 0 && nUpperCount != 0 && nLowerCount != 0 && nCharacterCount != 0)
	{
		nScore += 5;
	}
	
	
	return nScore;
}
 
// Runs password through check and then updates GUI 
function runPassword(strPassword, strFieldID, lang) 
{
	// Check password
	var nScore = checkPassword(strPassword);
	
	 // Get controls
    	var ctlBar = document.getElementById(strFieldID + "_bar"); 
    	var ctlText = document.getElementById(strFieldID + "_text");
        var ctlComplex = document.getElementById(strFieldID + "_complex");
    	if (!ctlBar || !ctlText)
    		return;
    	
    	// Set new width
    	ctlBar.style.width = nScore + "%";
        
        var strText;

 	// Color and text
	// -- Very Secure
 	if (nScore >= 90)
 	{ 		
        if ( lang == "french" ) strText="Très sûr";else strText="Very heavy";
 		var strColor = "#0ca908";
 	}
 	// -- Secure
 	else if (nScore >= 80)
 	{
 		if ( lang == "french" ) strText="Sûr";else strText="Heavy";
 		vstrColor = "#7ff67c";
	}
	// -- Very Strong
 	else if (nScore >= 70)
 	{
 		if ( lang == "french" ) strText="Très fort";else strText="Very strong";
 		var strColor = "#1740ef";
	}
	// -- Strong
 	else if (nScore >= 60)
 	{
 		if ( lang == "french" ) strText="Fort";else strText="Strong";
 		var strColor = "#5a74e3";
	}
	// -- Average
 	else if (nScore >= 50)
 	{
 		if ( lang == "french" ) strText="Moyen";else strText="Medium";
 		var strColor = "#e3cb00";
	}
	// -- Weak
 	else if (nScore >= 25)
 	{
 		if ( lang == "french" ) strText="Faible";else strText="Weak";
 		var strColor = "#e7d61a";
	}
	// -- Very Weak
 	else
 	{
 		if ( lang == "french" ) strText="Très faible";else strText="Very weak";
 		var strColor = "#e71a1a";
	}
	ctlBar.style.backgroundColor = strColor;
	ctlText.innerHTML = "<span style='color: " + strColor + ";'>" + strText + "</span>";    //" - " + nScore + 
    if ( ctlComplex != null ) ctlComplex.value = nScore;
}
 
// Checks a string for a list of characters
function countContain(strPassword, strCheck)
{ 
	// Declare variables
	var nCount = 0;
	
	for (i = 0; i < strPassword.length; i++) 
	{
		if (strCheck.indexOf(strPassword.charAt(i)) > -1) 
		{ 
	        	nCount++;
		} 
	} 
 
	return nCount; 
} 
 
function AfficherPsw(elem,strLength,strType,strSpecial){
    document.getElementById(elem).value = newPsw(strLength,strType,strSpecial);
}
 
// Generate a password
function newPsw(strLength,strType,strSpecial){
    var val                = "";
    for(c = 0; c < strLength; c++){
        var char        = Math.round(32+Math.random()*222);
        var ok            = 0;
        // Number
        if((char > 47 && char < 58) || (char > 64 && char < 91) || (char > 96 && char < 123)){ ok = 1; }
        // Upper or lower case
        if(strType == 1 && char < 127){ ok = 1; }
        // Puntuations
        if(strType == 2){ ok = 1; }
        // Special
        if(strSpecial && (char == 48 || char == 49 || char == 50 || char == 53 || char == 54 || char == 56 || char == 57 || char == 66 || char == 67 || char == 68 || char == 71 || char == 73 || char == 75 || char == 79 || char == 80 || char == 81 || char == 83 || char == 85 || char == 86 || char == 87 || char == 88 || char == 90 || char == 99 || char == 104 || char == 105 || char == 107 || char == 108 || char == 111 || char == 112 || char == 113 || char == 115 || char == 117 || char == 118 || char == 119 || char == 120 || char == 122)){ ok = 0; }
        if(ok == 1){ val += String.fromCharCode(char); }else{ c--; }
    }
    return val;
}
 
 
 


