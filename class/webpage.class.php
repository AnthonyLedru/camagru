<?php

require_once __DIR__ . '/../include/autoload.include.php';

class WebPage {

	protected $title = null;
	protected $body  = null;
	protected $head  = null;

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

	public function appendJs($script) {
		$this->head .= "<script>".$script."</script>";
	}

	public function appendJsUrl($url) {
		$this->head .= '<script src="'.$url.'"></script>';
	}

	public function appendCssUrl($url) {
		$this->head .= '<link rel="stylesheet" href="'.$url.'">';
	}


	public function toHTML() {
		$page_name = basename($_SERVER['PHP_SELF']);
		$home = "<a class='navbar-item' href='index.php'>Home</a>";
		$gallery = "<a class='navbar-item' href='gallery.php'>Gallery</a>";

		if ($page_name === "index.php")
			$home = str_replace("class='navbar-item'", "class='navbar-item is-active'", $home);
		else if ($page_name === "gallery.php")
			$gallery = str_replace("class='navbar-item'", "class='navbar-item is-active'", $gallery);	
		$page=<<<HTML
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">    
		<meta name="description" content="Camagru">
		<meta name="author" content="Anthony Ledru">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" href="css/bulma.min.css">
HTML;
		if (isset($_SESSION['user']) && UserPreference::isDefaultThemeActive($_SESSION['user']['userId']) == 0) {
			$page.=<<<HTML
		
		<link rel="stylesheet" href="css/styleDark.css">

HTML;
		} else {
			$page.=<<<HTML

		<link rel="stylesheet" href="css/styleDefault.css">	
HTML;
		}

		$page.=<<<HTML
		
		<script src="js/request.js"></script>
		<script src="js/common.js"></script>
		$this->head
		$this->title
	</head>

	<body>
		<section class="hero is-fullheight is-default is-bold">
			<div class="hero-head">
				<nav class="navbar is-fixed-top">
					<div class="navbar-brand">
						<a class="navbar-item" href="index.php">
							<img src="img/42.png" alt="42 Logo" width="30" height="35">
						</a>
						<div class="navbar-burger burger" id="burger_menu">
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>
					<div class="navbar-menu">
						<div class="navbar-start">
HTML;
	$page .= <<<HTML

							$home
							$gallery
						</div>

						<div class="navbar-end">
							<div class="navbar-item">
								<div class="field is-grouped">
HTML;

	if (!isset($_SESSION['user'])) {
		$page .= <<<HTML

									<p class="control">
										<a class="button button is-dark" id="login_button">
											<span>Log in</span>
										</a>
									</p>
									<p class="control">
										<a class="button button is-dark" id="signup_button">
											<span>Sign up</span>
										</a>
									</p>
HTML;
	} else {
		$page .= <<<HTML

									<p class="control">
										<a class="button button is-dark" id="photo_button" href="editPhoto.php">
											<span>Photo</span>
										</a>
									</p>
									<p class="control">
										<a class="button button is-dark" id="account_button" href="account.php">
											<span>Account</span>
										</a>
									</p>
									<p class="control">
										<a class="button button is-dark" id="profile_button" href="profile.php?user_id={$_SESSION['user']['userId']}">
											<span>Profile</span>
										</a>
									</p>
									<p class="control">
										<a class="button button is-dark" id="logout_button">
											<span>Log out</span>
										</a>
									</p>
HTML;
	}

		$page .= <<<HTML

								</div>
							</div>
						</div>
					</div>
				</nav>
			</div>

			<div class="modal" id="login_modal">
				<div class="modal-background"></div>
					<div class="modal-card">
						<header class="modal-card-head">
							<p class="modal-card-title">Log in</p>
							<button class="delete login_cancel" aria-label="close"></button>
						</header>
						<section class="modal-card-body">
							<form action="connect.php" method="post" id="login_form">
								<div class="field">
									<label class="label">Login</label>
									<input class="input" type="text" placeholder="John_doe" name="login">
								</div>
								<div class="field">
									<label class="label">Password</label>
									<input class="input" type="password" placeholder="*********" name="password">
								</div>
								<br>
								<a class="link" href="changePassword.php">Forgot your password ?</a>
							</form>
						</section>
						<footer class="modal-card-foot">
							<button class="button is-dark" form="login_form" type="submit">Log in</button>
							<button class="button login_cancel is-dark">Cancel</button>
						</footer>
					</div>
				</div>
			</div>

			<div class="modal" id="signup_modal">
				<div class="modal-background"></div>
					<div class="modal-card">
						<header class="modal-card-head">
							<p class="modal-card-title">Sign up</p>
							<button class="delete signup_cancel" aria-label="close"></button>
						</header>
						<section class="modal-card-body">
							<form action="script/signup.php" method="post" id="signup_form">
								<div class="field">
									<label class="label">Mail</label>
									<input class="input" type="text" placeholder="john.doe@gmail.com" name="mail">
								</div>
								<div class="field">
									<label class="label">Login</label>
									<input class="input" type="text" placeholder="JohnDoe" name="login">
								</div>
								<div class="field">
									<label class="label">Password</label>
									<input class="input" type="password" placeholder="**********" name="password">
								</div>
								<div class="field">
									<label class="label">Password confirmation</label>
									<input class="input" type="password" placeholder="**********" name="passwordConf">
								</div>
								<div class="field">
									<label class="label">Last name</label>
									<input class="input" type="text" placeholder="Doe" name="lastName">
								</div>
								<div class="field">
									<label class="label">First name</label>
									<input class="input" type="text" placeholder="John" name="firstName">
								</div>
								<div class="field">
									<label class="label">Gender</label>
									<div class="control">
										<div class="select">
											<select name="gender">
												<option>Male</option>
												<option>Female</option>
											</select>
										</div>
									</div>
								</div>
							</form>
						</section>
						<footer class="modal-card-foot">
							<button class="button is-dark" form="signup_form" type="submit">Sign up</button>
							<button class="button signup_cancel is-dark">Cancel</button>
						</footer>
					</div>
				</div>
			</div>

			<div id="notification"></div>
			$this->body

			<div class="hero-foot">
				<footer class="footer">
					<div class="container">
						<div class="content has-text-centered">
							<p><i class="fa fa-hashtag"></i> &nbsp;&copy; 2018 - Camagru</p>
							<p class="copyright">Made with ❤️ by <a href="http://profile.intra.42.fr/users/aledru" target="_new">aledru</a></p>
						</div>
					</div>
				</footer>
			</div>
		</section>
	</body>
</html>
HTML;
		return $page;
	}
}