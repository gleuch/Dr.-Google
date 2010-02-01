<?php
/*

  Dr. Google
  ----------------------------------------

*/

define('BASE_PATH', dirname(preg_replace('#('. $_SERVER['DOCUMENT_ROOT'] .')#', '', $_SERVER['SCRIPT_FILENAME'])));

$route = get_route();

if ($route == '/search') {
  if (empty($_GET['q'])) {
    include('template/index.php');

  } elseif (isset($_GET['btnI'])) {
    if (false) {
      $urls = array(
        'http://sanebride.files.wordpress.com/2009/07/sick-cat.jpg',
      );
      $index = rand(0, count($urls));
      header('Location: '. $urls[$index]);
      exit;

    } else {
      $base = str_replace('+', '_', trim($_GET['q']));
      if (is_file('tmp/'. $base .'.cache')) {
        echo file_get_contents('tmp/'. $base .'.cache');
      } else {
        include('diagnosis_list.php');
        $diagnosis = $diagnosises[rand(0, count($diagnosises)-1)];
        ob_start();
        include('template/diagnosis.php');
        $content = ob_get_contents();
        file_put_contents('tmp/'. $base .'.cache', $content);
        ob_end_flush();
      }
    }

  } else {
    $find = array('/(src=[\'"]?)(\/)/i', '/(href=[\'"]?)(\/)/i', "/(\/\")/", "/http\:\/\/www\.google\.com\/images\/logo_sm\.gif/i", "/width=150 height=55/i", '/Google/');
    $replace = array('\1http://www.google.com\2', '\1http://www.google.com\2', '\1http://www.google.com', '/images/logo_sm.gif', "width=174 height=55", 'Dr. Google');
    echo preg_replace($find, $replace, file_get_contents('http://www.google.com'. $_SERVER['REQUEST_URI']));
  }

} else if ($route == '/advanced_search') {
  echo '<h1>ADVANCED SEARCH</h1>';

} else if ($route == '/symptom_tools') {
  include('template/symptoms_tools.php');

} else {
  include('template/index.php');
}



function get_route() {
  $path = $_SERVER['REQUEST_URI'];
  $call = preg_replace('/(.*)(\?)(.*)$/i', '\1', $path);

  $param = preg_replace('/(.*)(\?)(.*)$/i', '\3', $path);
  if ($params == $path) {
    $params = array();
  } else {
    $vals = explode('&', $params);
    $params = array();
    foreach ($vals as $param) {
      $val = explode('=', $param);
      $params[$val[0]] = $val[1];
    }

  }
  return $call;
}

function get_header() {include('template/_header.php');}
function get_footer() {include('template/_footer.php');}

function debug_arr($arr) {  echo '<pre>'. print_r($arr, true) .'</pre>';}

function pretty_format($str) {
  
  $str = preg_replace("/(\/wiki\/)([A-Z0-9\/\-\_\?\&\=]+)/i", BASE_PATH .'/search?btnI=1&q=\2', $str);
  $str = preg_replace("/(<sup.*<\/sup>)/i", '', $str);
  
  return $str;  
}


?>