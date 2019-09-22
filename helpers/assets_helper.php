<?php
  function image($file){

    return base_url('assets/Images/'.$file.'.png');
  }

  function avatar($user){

    return base_url('assets/Images/Avatars/'.$user.'.png');
  }

  function css($file){

    return base_url('assets/CSS/'.$file.'.css');
  }

  function js($file){

    return base_url('assets/JS/'.$file.'.js');
  }
?>
