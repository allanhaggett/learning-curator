<!DOCTYPE html>
<html lang="en">
<head>
<?= $this->Html->charset() ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title><?= $this->fetch('title') ?> | Learning Curator</title>

<!--
<link rel="stylesheet" href="/bootstrap-theme/dist/css/bootstrap-theme.min.css">

Wanna go from getting a 60 on peformance in Lighthouse to a 97? 
	Stop serving the Gov Bootstrap theme and call in Bootstrap via its CDN:

-->
	
<link rel="stylesheet" 
		href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" 
		integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" 
		crossorigin="anonymous">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">

<link href="/css/home.css" rel="stylesheet"> 
<link href="/fontawesome/css/all.css" rel="stylesheet"> 


<!-- ****** faviconit.com favicons ****** -->
<link rel="shortcut icon" href="/favicon/favicon.ico">
<link rel="icon" sizes="16x16 32x32 64x64" href="/favicon/favicon.ico">
<link rel="icon" type="image/png" sizes="196x196" href="/favicon/favicon-192.png">
<link rel="icon" type="image/png" sizes="160x160" href="/favicon/favicon-160.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96.png">
<link rel="icon" type="image/png" sizes="64x64" href="/favicon/favicon-64.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16.png">
<link rel="apple-touch-icon" href="/favicon/favicon-57.png">
<link rel="apple-touch-icon" sizes="114x114" href="/favicon/favicon-114.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicon/favicon-72.png">
<link rel="apple-touch-icon" sizes="144x144" href="/favicon/favicon-144.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicon/favicon-60.png">
<link rel="apple-touch-icon" sizes="120x120" href="/favicon/favicon-120.png">
<link rel="apple-touch-icon" sizes="76x76" href="/favicon/favicon-76.png">
<link rel="apple-touch-icon" sizes="152x152" href="/favicon/favicon-152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/favicon/favicon-180.png">
<meta name="msapplication-TileColor" content="#FFFFFF">
<meta name="msapplication-TileImage" content="/favicon/favicon-144.png">
<meta name="msapplication-config" content="/browserconfig.xml">
<!-- ****** faviconit.com favicons ****** -->


</head>
<body class="" data-spy="scroll" data-target="#stepnav" data-offset="110">
<?php 
if ( strpos($_SERVER['HTTP_USER_AGENT'], 'rv:11.0')     !== false
&& strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0;')!== false): ?>
<div class="alert alert-warning mb-0 fade show">
	You appear to be using Internet Explorer as your browser. 
	To see the intended user experience, please access this site using 
	<em><a href="/pages/anybrowser">any modern browser</a></em>.
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<?php endif ?>
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
	
	<a class="navbar-brand" href="/">
		<img class="animate__animated animate__rotateIn" alt="Logo" height="50" src="/img/curator-rings-logo.svg" width="50">
		Learning Curator
	</a>
	<?php if(!empty($active)): ?>
	<button class="navbar-toggler " 
		type="button" 
		data-toggle="collapse" 
		data-target="#navbarSupportedContent" 
		aria-controls="navbarSupportedContent" 
		aria-expanded="false" 
		aria-label="Toggle navigation">
		<i class="fas fa-bars"></i>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	<ul class="navbar-nav mr-auto">
	<li class="nav-item">
		<a class="nav-link" href="/users/pathways">Your Profile</a>
	</li>
	
	<?php if($active->role_id == 2 || $active->role_id ==5): ?>
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Curators
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item" href="/users/list">All Users</a>
			<a class="dropdown-item" href="/reports/list">All Reports</a>
		</div>
	</li>
	<?php endif ?>
	</ul>

	<form method="get" action="/activities/find" class="form-inline my-2 my-lg-0 mr-3">
		<input class="form-control mr-sm-2" type="search" placeholder="Activity Search" aria-label="Search" name="q">
		<button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Search</button>
	</form>

	</div> <!-- /endof .collapse navbar-collapse -->
	<?php endif ?>
</nav>



<?= $this->fetch('content') ?>




<div class="container-fluid bg-light py-3">
<div class="row mt-3 justify-content-md-center">
<?php if(!empty($active)): ?>
<div class="col-md-5 mt-3">
<nav class="nav bg-white shadow-sm p-3 m-3">
	<a class="nav-link" href="/pages/faq">Frequently Asked Questions</a>
	<a class="nav-link" href="/activities/contribute">Contribute</a>
</nav>
</div>
<?php endif ?>
<div class="col-md-5 mt-3">

	<div class="p-3 m-3 bg-white shadow-sm text-center">
		Brought to you by The 
		<a href="https://learningcentre.gww.gov.bc.ca/" target="_blank" rel="noopener">
			Learning Centre 
			<img src="/img/learning-logo-small-transparent.png" width="40" alt="Learning Centre logo">
		</a>
	</div>
	<div class="p-3 m-3 bg-white shadow-sm">
	<div><img src="/img/BCID_BCPSA_rgb_pos.jpg" width="400" alt="BC Public Service Agency logo"></div>
	<p>Your personal information is collected by the BC Public Service Agency in accordance with 
		section 26(c) of the Freedom of Information and Protection of Privacy Act for the purposes 
		of managing and administering employee development and training. If you have any questions, 
		submit an AskMyHR request at 
			<a href="https://www.gov.bc.ca/myhr/contact" target="_blank" rel="noopener">
				www.gov.bc.ca/myhr/contact
			</a> 
		or call 250-952-6000.</p>
	</div>

</div>
</div>
</div>

</body>
</html>
