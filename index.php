<?php
// Remove first slash, getting MD file
$url = substr($_SERVER["REQUEST_URI"], 1);
if(empty($url)) $url = "index"; // default index

$extension = pathinfo($url, PATHINFO_EXTENSION);
if(!empty($extension) && $extension != "md" && $extension != "php") {
  // media
  $url = "./res/" . $url;
  $url = parse_url($url, PHP_URL_PATH);
  if(file_exists($url)) {
    $ct = mime_content_type($url);
    header("Content-Type: $ct");
    echo file_get_contents($url);
    exit;
  } else exit(http_response_code(404));
}


$mdfile = "./pages/" . $url . ".md";
$cssfile = "./css/" . $url . ".css";

// file checks
if(file_exists("./css/global.css")) echo "<link rel='stylesheet' href='/css/global.css'>";
if(file_exists($cssfile)) echo "<link rel='stylesheet' href='/$cssfile'>";

if(!file_exists($mdfile)) {
  echo <<<EOL
    <title>404: Not Found!</title>
    <center><h1>404 Not Found!</h1>
    <hr>
    <h2><a href="/">Home</a></h2>
    <p>Powered by DimisAIO Portfolio</p></center>
  EOL;
  exit;
}

$md = file_get_contents($mdfile);
echo "<title>" . ucfirst(basename($mdfile, ".md")) . "</title>";

require "Parsedown.php";
$Parsedown = new Parsedown();
echo $Parsedown->text($md);