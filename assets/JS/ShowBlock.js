function show(id){

  if(document.getElementById(id).style.display == 'block'){

    document.getElementById(id).style.display = 'none';
    document.getElementById(id).style.border = 'hidden';
  }else{

    document.getElementById(id).style.display = 'block';
    document.getElementById(id).style.border = 'solid 3px black';
  }
}
