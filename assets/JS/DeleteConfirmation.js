function deleteConfirmation(redirectURL){
  if(confirm("Confirmer la suppression?")){
    window.location.href = redirectURL;
  }
}
