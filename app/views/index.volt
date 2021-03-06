<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Administraci&oacute;n de Inventario</title>
        <base href="/activoFijo/">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/reset.css">
		<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="css/w3.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="css/classic.css">
        <link rel="stylesheet" type="text/css" href="css/classic.date.css">

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
        <!-- jQuery Mask -->
        <script type="text/javascript" language="javascript" src="js/jquery.mask.min.js"></script>
        <!-- jQuery DatePicker -->
        <script type="text/javascript" language="javascript" src="js/picker.js"></script>
        <script type="text/javascript" language="javascript" src="js/picker.date.js"></script>
        <script type="text/javascript" language="javascript" src="js/es_ES.js"></script>
    </head>
    <body>
    	<?php
    	$usuario = $this->session->get("usuario");
		$user_time = $this->session->get("user_time");
		if($usuario != null && $usuario != ""){
			$texto = armaMenu($usuario);
			$u = Usuarios::findFirst($usuario);
			if(!$this->security->checkHash('fakePass', $u->u_contrasena)){
				echo $texto;
			}
						
		}else{
			$homepage = "/activoFijo/";
			$currentpage = $_SERVER['REQUEST_URI'];
			if(!($homepage==$currentpage)) {
				//echo $currentpage;
				//$this->flashSession->output();
				header("Location: /activoFijo/");
			}
			//window.location.replace("./inicio/salir");	
		}
		
		function armaMenu($uid) {
			//cargar rol
			$user = Usuarios::findFirst("u_id = $uid");
			//$rol = Roles::findFirst("r_id = $user->r_id");
			//$mxr = Mxr::find("r_id = $user->r_id");
			
			$titulos = Menu::find(array("m_parent is null and m_id in (select x.m_id from mxr x where x.r_id = $user->r_id)", "order" => "m_id"));
	    	$li1 = '<li class="pure-menu-item">';
	    	$lip = '<li class="pure-menu-item pure-menu-has-children pure-menu-allow-hover pure-menu-link">';
	    	$a1 = '<a href="';
	    	$a2 = '" class="pure-menu-link">';
	    	$afin = '" class="custom-link-exit">';
	    	$lie = '</a></li>';
	    	$ul1 = '<ul class="pure-menu-children">';
	    	$ul2 = '</ul>';
	    	$html = '
	    	<div class="pure-menu pure-menu-horizontal">
			    <ul class="pure-menu-list">';
	    	foreach ($titulos as $t){    			
	    		$smenus = Menu::find(array("m_parent = $t->m_id  and m_id in (select x.m_id from mxr x where x.r_id = $user->r_id)", "order" => "m_id"));
	    		if(count($smenus) > 0){
	    			$html = $html.$lip.$t->m_label.$ul1;
	    			foreach ($smenus as $sm){
	    				$html = $html.$li1.$a1.$sm->m_href.$a2.$sm->m_label.$lie;
	    			}
	    			$html = $html.$ul2;
	    		}else{
	    			if($t->m_id == 99){
	    				$html = $html.$li1.$a1.$t->m_href.$afin.$t->m_label.$lie;
	    			}else{
	    				$html = $html.$li1.$a1.$t->m_href.$a2.$t->m_label.$lie;
	    			}
	    		}    		
	    	}
	    	$html = $html.'</ul></div>';
	    	return $html;
	    }
	    
		function auto_logout($field)
		{
		    $t = time();
		    $t0 = $_SESSION[$field];
		    $diff = $t - $t0;
		    if ($diff > 10 || !isset($t0))
		    {          
		        return true;
		    }
		    else
		    {
		        $_SESSION[$field] = time();
		    }
		}
		
		if($user_time != null && $user_time != ""){
		
			if(auto_logout("user_time"))
		    {
		    	
		        /*session_unset();
		        session_destroy();
		        //location("/activoFijo/");
		        header("Location: /activoFijo/");          
		        exit;*/
		    }
		}
		 
		?>
    	    	
        <div class="container">
            {{ content() }}
        </div>        
    </body>
</html>
