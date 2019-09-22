function hideInformation(){

	document.getElementById('infos_visible').id = 'infos_hide';
}

function showInformation(information){

	var info = information;
	while(true){

		if(info.search('//') >= 0){

			info = info.replace('//',' ');
		}else{

			break;
		}
	}

	document.getElementById('infos_hide').innerHTML = info;
	document.getElementById('infos_hide').id = 'infos_visible';
}
