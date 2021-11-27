<?php
function getExtension($str){
      $i = strrpos($str,".");
      if (!$i) { return ""; }
      $I = strlen($str) - $i;
      $ext = substr($str,$i+1,$I);
      return $ext;
  }

  function getFilename($str){
      $i = strrpos($str,".");
      if (!$i) { return ""; }
      $I = strlen($str) - $i;
      $ext = substr($str,0,-$I);
      return $ext;
  }

  function version_name($str, $name){
      if ($name == 'medium'){
          $mid1 = '_'. $name.'.';
          $output =  getFilename($str).$mid1.getExtension($str);
      } elseif($name == 'thumbnail'){
          $mid1 = '_'. $name.'.';
          $output =  getFilename($str).$mid1.getExtension($str);
      } else{
          $ouput = "";
      }
      return $output;
  }

 function getImageFolder($str){
    $i = strrpos($str,"uploads");
    if (!$i) { return ""; }
    $I = strlen($str) - $i;
    $ext = substr($str,$i,$I);
    return $ext;
}
?>
