//function for selecting all the checkboxes
function checkall()
{
	var target=document.getElementsByTagName('input');
	for(i=0; i<target.length; i++)
	{
		if(target[i].type=='checkbox')
			target[i].checked='checked';
	}
}

//function for unselecting all the checkboxes
function uncheckall()
{
	var target=document.getElementsByTagName('input');
	for(i=0; i<target.length; i++)
	{
		if(target[i].type=='checkbox')
				target[i].checked='';
	}
}

function confirmDelete(){
	var prompt = confirm("Are you sure you want to delete the schedule for this day?");
	if(prompt){
		var prompt2 = confirm("Are you really sure you want delete?");
		return prompt2;
	}else return prompt;
}

function confirmEdit(){
	var prompt = confirm("Are you sure you want to apply the change(s) you made?");
	return prompt;
}
function checkSelect()
{
	var i;
	var target=document.getElementsByTagName('select');
	var a = false;
	for(i=0; i<target.length; i++)
	{
		if(target[i].value!="Executive"){
			a = true;
			break;
		}
	}
	return a;
}

function editUser(user){
	var prompt = confirm("Are you sure you want to edit "+user.value+"?");
	return prompt;
}


function deleteUser(user){
	var prompt = confirm("Are you sure you want to delete "+user.value+"?");
	if(prompt){
		var prompt2 = confirm("Are you really sure?");
		if(prompt2){
			alert("Deleted Succesfully!");
		}
		return prompt2;
	}else return prompt;
}

function checkLevel()
{
	var i;
	var lvl=document.getElementById('level');
	var sect=document.getElementById('section');
	
	if(lvl.value=="Executive"){
		document.getElementById('sectId').style.display='none';
		document.getElementById('section').style.display='none';
	}else{
		document.getElementById('sectId').style.display='block';
		document.getElementById('section').style.display='block';
	}
}

function alertAdd(){
	var p1 = document.getElementById('password1');
	var p2 = document.getElementById('password2');
	
	if(p2.value==p1.value){
		return true;
	}else{
		alert("Password does not match.");
		p2.focus();
		return false;
	}
}

function checkPassword(){
	var p1 = document.getElementById('password1');
	var p2 = document.getElementById('password2');
	
	
	if(p2.value!=p1.value){
		alert("Password does not match.");
		p2.focus();
	}else{
		return;
	}
}

function confirmAdd(){
	if(checkSelect()){
		var prompt = confirm("Are you sure you want to add these person(s) to their respected schedules?");
		if(prompt){
			var prompt2 = confirm("Are you really sure you want to add these person(s)?");
			return prompt2;
		}else return prompt;
	}
}


	
	
