<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Página no encontrada</title>
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #FFF;
	background-color: #49B7E0;
	border-bottom: 1px solid #D0D0D0;
	font-size: 29px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 24px 15px 20px 15px;
	
}
.logo_img {
    background-color: #49B7E0;
    border-top-right-radius: 10px !important;
    border-top-left-radius: 10px;
}
.logo_img > img {
    max-width: 100%;
    height: 8rem;
}
code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
	text-align: center;
	border-radius: 10px;
}
html {
    background-color: #E0FFF0;
}

p {
	margin: 25px 15px 12px 15px;
	font-size: 19px;
}
</style>

</head>
<?php
if (isset($this->session)) {
	if(!$this->session->userdata("username")){
		redirect("Compras/Login", "");
	}
}
?>
<body>

	<div id="container">
		<!--begin::Main-->
        <div class="d-flex flex-column flex-root">
        	<!--begin::Error-->
        	<div class="error error-6 d-flex flex-row-fluid bgi-size-cover bgi-position-center" style="background-image: url(assets/media/error/bg6.jpg);">
        		<!--begin::Content-->
        		<div class="d-flex flex-column flex-row-fluid text-center">
        			<h1 class="error-title font-weight-boldest text-white mb-12" style="margin-top: 12rem;">
        				Oops...
        			</h1>
        			<p class="display-4 font-weight-bold text-white">
	        			Lo sentimos, esta página no esta disponible.</br>
	        			We're working on it
        			</p>
        		</div>
            	<!--end::Content-->
            </div>
            <!--end::Error-->
	    </div>
        <!--end::Main-->
	</div>
</body>
</html>