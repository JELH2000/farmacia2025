<?php
	
	namespace app\models;

	class viewsModel{

		
		protected function obtenerVistasModelo($vista){

			$listaBlanca=["inicio", "categoria", "empleado", "proveedor", "importe", "producto", "login", "cerrar"];

			if(in_array($vista, $listaBlanca)){
				if(is_file("app/views/content/".$vista.".php")){
					$contenido="app/views/content/".$vista.".php";
				}else{
					$contenido="app/views/content/404.php";
				}
			}else if($vista=="index"){
				$contenido="app/views/content/login.php";
			}else{
				$contenido="app/views/content/404.php";
			}
			return $contenido;
		}

	}