<?php
require_once('header.php');
$message = "";
$actualUrl = "";
$shortUrl = "";
$postURL = "";
$url = new Url();
if(isset($_POST['url'])){
	$url->url = $_POST['url'];
	$url->alias = $url->generateAlias();
	$shortUrl = $url->setURL();
	$postURL = $_POST['url'];
}
if(isset($_GET["link"])){
	$redir = $url->getURL($_GET["link"]);
	if($redir){
		$actualUrl = $redir->url;
	}
}
if($actualUrl !=""){
	header("Location: {$actualUrl}");
}
require_once('html/index.html');
require_once('footer.php');