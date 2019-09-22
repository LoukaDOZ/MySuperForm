function startAutocomplete(id){

  document.getElementById(id).style.display = 'block';
}

var close = true;
function stopAutocomplete(id){

  if(close){

    document.getElementById(id).style.display = 'none';
  }
}

function forceStopAutocomplete(id){

  document.getElementById(id).style.display = 'none';
  close = true;
}

function closeAutocomplete(canClose){

  close = canClose;
}

function complete(inputID,ulID){

  var ul = document.getElementById(ulID);
  var input = document.getElementById(inputID).value;

  ul.innerHTML = '';

  if(input != ''){

    for(var i = 1; i < length; i++){

      var search;
      if(searchList[i]['title'] != 'Titre' && searchList[i]['title'] != 'Description'){

        search = "Question" + i + searchList[i]['title'];
      }else{

        search = searchList[i]['title'];
      }

      if(search.toLowerCase().replace(' ','').search(input.toLowerCase().replace(' ','')) >= 0){

        if(search != 'Titre' && search != 'Description'){

          ul.innerHTML = ul.innerHTML + "<a href='#goto_" + searchList[i]['reference'] + "' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete1');><li>Question " + i + " : " + searchList[i]['title'] + "</li></a>";
        }else{

          ul.innerHTML = ul.innerHTML + "<a href='#goto_" + searchList[i]['reference'] + "' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete1');><li>" + searchList[i]['title'] + "</li></a>";
        }
      }
    }
  }else{

    for(var i = 1; i < length; i++){

      if(searchList[i]['title'] != 'Titre' && searchList[i]['title'] != 'Description'){

        ul.innerHTML = ul.innerHTML + "<a href='#goto_" + searchList[i]['reference'] + "' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete1');><li>Question " + i + " : " + searchList[i]['title'] + "</li></a>";
      }else{

        ul.innerHTML = ul.innerHTML + "<a href='#goto_" + searchList[i]['reference'] + "' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete1');><li>" + searchList[i]['title'] + "</li></a>";
      }
    }
  }
}

function completeFormList(inputID,ulID){

  var ul = document.getElementById(ulID);
  var input = document.getElementById(inputID).value;

  ul.innerHTML = '';

  if(input != ''){

    for(var i = 1; i < length; i++){

      var search = searchList[i]['key'] + searchList[i]['title'];

      if(search.toLowerCase().replace(' ','').search(input.toLowerCase().replace(' ','')) >= 0){

        ul.innerHTML = ul.innerHTML + "<a href='#goto_" + searchList[i]['key'] + "' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete1');><li>" + searchList[i]['title'] + " (Clée: " + searchList[i]['key'] + ")</li></a>";
      }
    }
  }else{

    for(var i = 1; i < length; i++){

      ul.innerHTML = ul.innerHTML + "<a href='#goto_" + searchList[i]['key'] + "' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete1');><li>" + searchList[i]['title'] + " (Clée: " + searchList[i]['key'] + ")</li></a>";
    }
  }
}
