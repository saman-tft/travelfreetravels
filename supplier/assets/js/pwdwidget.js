/* 
*
* Password Widget 1.0
*
* This script is distributed under the GNU Lesser General Public License.
* Read the entire license text here: http://www.gnu.org/licenses/lgpl.html
*
* Copyright (C) 2009 HTML Form Guide 
* http://www.html-form-guide.com/
*/

function PasswordWidget(divid,pwdname)
{
	this.maindivobj = document.getElementById(divid);
	this.pwdobjname = pwdname;

	this.MakePWDWidget=_MakePWDWidget;

	this.showing_pwd=1;
	this.txtShow = 'Show';
	this.txtMask = 'Mask';
	this.txtGenerate = 'Generate';
	this.txtWeak='Password strenght: Too Short';
	this.txtMedium='Password strenght: Medium';
	this.txtGood='Password strenght: Fair';

	this.enableShowMask=true;
	this.enableGenerate=true;
	this.enableShowStrength=true;
	this.enableShowStrengthStr=true;

}

function _MakePWDWidget()
{
	var code="";
    var pwdname = this.pwdobjname;

	this.forminputbigid = pwdname+"_id";

	code += "<input type='password' placeholder='Password' onblur='check_password_status(this.value)' class='form-control padpas' name='"+pwdname+"' id='"+this.forminputbigid+"' data-validate='required'>";

	this.pwdtxtfield=pwdname+"_text";

	this.pwdtxtfieldid = this.pwdtxtfield+"_id";

	code += "<input type='text'  placeholder='Password' onblur='check_password_status(this.value)' class='form-control padpas' name='"+this.pwdtxtfield+"' id='"+this.pwdtxtfieldid+"' style='display: none;' data-validate='required'>";

	this.pwdshowdiv = pwdname+"_showdiv";

	this.pwdshow_anch = pwdname + "_show_anch";

	code += "<div class='wrapgen'><div class='pwdopsdiv divcol1' id='"+this.pwdshowdiv+"'><a id='"+this.pwdshow_anch+"'>"+this.txtShow+"</a></div>";

	this.pwdgendiv = pwdname+"_gendiv";

	this.pwdgenerate_anch = pwdname + "_gen_anch";

	code += "<div class='pwdopsdiv divcol2'id='"+this.pwdgendiv+"'><a id='"+this.pwdgenerate_anch+"'>"+this.txtGenerate+"</a></div></div>";

	this.pwdstrengthdiv = pwdname + "_strength_div";

	code += "<div class='pwdstrength' id='"+this.pwdstrengthdiv+"'>";

	this.pwdstrengthbar = pwdname + "_strength_bar";

	code += "<div class='pwdstrengthbar' id='"+this.pwdstrengthbar+"'></div>";

	this.pwdstrengthstr = pwdname + "_strength_str";

	code += "<div class='pwdstrengthstr' id='"+this.pwdstrengthstr+"'></div>";

	code += "</div>";

	this.maindivobj.innerHTML = code;

	this.forminputbigobj = document.getElementById(this.forminputbigid);
	
	this.forminputbigobj.pwdwidget=this;

	this.pwdstrengthbar_obj = document.getElementById(this.pwdstrengthbar);
	
	this.pwdstrengthstr_obj = document.getElementById(this.pwdstrengthstr);

	this._showPasswordStrength = passwordStrength;

	this.forminputbigobj.onkeyup=function(){ this.pwdwidget._onKeyUpforminputbigs(); }

	this._showGeneatedPwd = showGeneatedPwd;

	this.generate_anch_obj = document.getElementById(this.pwdgenerate_anch);
	
	this.generate_anch_obj.pwdwidget=this;

	this.generate_anch_obj.onclick = function(){ this.pwdwidget._showGeneatedPwd(); }

	this._showpwdchars = showpwdchars;

	this.show_anch_obj = document.getElementById(this.pwdshow_anch);

	this.show_anch_obj.pwdwidget = this;

	this.show_anch_obj.onclick = function(){ this.pwdwidget._showpwdchars();}

	this.pwdtxtfield_obj = document.getElementById(this.pwdtxtfieldid);

	this.pwdtxtfield_obj.pwdwidget=this;

	this.pwdtxtfield_obj.onkeyup=function(){ this.pwdwidget._onKeyUpforminputbigs(); }
	

	this._updateforminputbigValues = updateforminputbigValues;

	this._onKeyUpforminputbigs=onKeyUpforminputbigs;

	if(!this.enableShowMask)
	{ document.getElementById(this.pwdshowdiv).style.display='none';}

	if(!this.enableGenerate)
	{ document.getElementById(this.pwdgendiv).style.display='none';}

	if(!this.enableShowStrength)
	{ document.getElementById(this.pwdstrengthdiv).style.display='none';}

	if(!this.enableShowStrengthStr)
	{ document.getElementById(this.pwdstrengthstr).style.display='none';}
}

function onKeyUpforminputbigs()
{
	this._updateforminputbigValues(); 
	this._showPasswordStrength();
}

function updateforminputbigValues()
{
	if(1 == this.showing_pwd)
	{
		this.pwdtxtfield_obj.value = this.forminputbigobj.value;	
	}
	else
	{
		this.forminputbigobj.value = this.pwdtxtfield_obj.value;
	}
}

function showpwdchars()
{
	var innerText='';
	var forminputbig = this.forminputbigobj;
	var pwdtxt = this.pwdtxtfield_obj;
	var field;
	if(1 == this.showing_pwd)
	{
		this.showing_pwd=0;
		innerText = this.txtMask;

		pwdtxt.value = forminputbig.value;
		forminputbig.style.display='none';
		pwdtxt.style.display='';
		pwdtxt.focus();
	}
	else
	{
		this.showing_pwd=1;
		innerText = this.txtShow;	
		forminputbig.value = pwdtxt.value;
		pwdtxt.style.display='none';
		forminputbig.style.display='';
		forminputbig.focus();
			
	}
	this.show_anch_obj.innerHTML = innerText;

}

function passwordStrength()
{
	var colors = new Array();
	colors[0] = "#cccccc";
	colors[1] = "#ff0000";
	colors[2] = "#ff5f5f";
	colors[3] = "#56e500";
	colors[4] = "#4dcd00";
	colors[5] = "#399800";

	var forminputbig = this.forminputbigobj;
	var password = forminputbig.value

	var score   = 0;

	if (password.length > 6) {score++;}

	if ( ( password.match(/[a-z]/) ) && 
	     ( password.match(/[A-Z]/) ) ) {score++;}

	if (password.match(/\d+/)){ score++;}

	if ( password.match(/[^a-z\d]+/) )	{score++};

	if (password.length > 12){ score++;}
	
	var color=colors[score];
	var strengthdiv = this.pwdstrengthbar_obj;
	
	strengthdiv.style.background=colors[score];
	
	if (password.length <= 0)
	{ 
		strengthdiv.style.width=0; 
	}
	else
	{
		strengthdiv.style.width=(score+1)*10+'px';
	}

	var desc='';
	if(password.length < 1){desc='';}
	else if(score<3){ desc = this.txtWeak; }
	else if(score<4){ desc = this.txtMedium; }
	else if(score>=4){ desc= this.txtGood; }

	var strengthstrdiv = this.pwdstrengthstr_obj;
	strengthstrdiv.innerHTML = desc;
}

function getRand(max) 
{
	return (Math.floor(Math.random() * max));
}

function shuffleString(mystr)
{
	var arrPwd=mystr.split('');

	for(i=0;i< mystr.length;i++)
	{
		var r1= i;
		var r2=getRand(mystr.length);

		var tmp = arrPwd[r1];
		arrPwd[r1] = arrPwd[r2];
		arrPwd[r2] = tmp;
	}

	return arrPwd.join("");
}

function showGeneatedPwd()
{
	var pwd = generatePWD();
	this.forminputbigobj.value= pwd;
	this.pwdtxtfield_obj.value =pwd;
	document.getElementById("confirm_password").value = pwd;
	$('#userpasswrod').html('');
	$("#confirm_password_Group").removeClass( "form-group validate-has-error" ).addClass("form-group" );
	this._showPasswordStrength();
}

function generatePWD()
{
    var maxAlpha = 26;
	var strSymbols="~!@#$%^&*(){}?><`=-|][";
	var password='';
	for(i=0;i<3;i++)
	{
		password += String.fromCharCode("a".charCodeAt(0) + getRand(maxAlpha));
	}
	for(i=0;i<3;i++)
	{
		password += String.fromCharCode("A".charCodeAt(0) + getRand(maxAlpha));
	}
	for(i=0;i<3;i++)
	{
		password += String.fromCharCode("0".charCodeAt(0) + getRand(10));
	}
	for(i=0;i<4;i++)
	{
		password += strSymbols.charAt(getRand(strSymbols.length));
	}

	password = shuffleString(password);
	password = shuffleString(password);
	password = shuffleString(password);

	return password;
}
