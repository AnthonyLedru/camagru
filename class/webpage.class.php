<?php

class WebPage {

	private $title = null;
	private $body  = null;
	private $head  = null;

	public function __construct($title=null) {
		$this->title = "<title>".$title."</title>";
	}

	public function body() {
		return $this->body ; 
	}

	public function head() {
		return $this->head;
	}

	public function setTitle($title) {
		$this->title = "<title>".$title."</title>";
	}

	public function appendContent($content) {
		$this->body .= $content;
	}

	public function appendHead($head) {
		$this->head .= $head;
	}

	public function appendScript($script) {
		$this->head .= "<script>".$script."</script>";
	}

	public function appendJsUrl($url) {
		$this->head .= '<script src="'.$url.'"></script>';
	}

	public function appendCssUrl($url) {
		$this->head .= '<link rel="stylesheet" href="'.$url.'">';
	}

	public function toHTML() {

	$page=<<<HTML
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">    
	<meta name="description" content="Camagru">
	<meta name="author" content="Anthony Ledru">
	<link rel="stylesheet" href="css/spectre.min.css">
	<link rel="stylesheet" href="css/style.css">
	$this->title
	$this->head
</head>

<body>
	$this->body
</body>
</html>
HTML;
		return $page;
	}
}