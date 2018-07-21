<?php


class MWparaCORS
{
	
	public function HabilitarCORSTodos($request, $response, $next) {
		/*
		al ingresar no hago nada
		*/
		 $response = $next($request, $response);
		 //solo afecto la salida con los header
		 $response->getBody()->write('<p>habilitado HabilitarCORSTodos</p>');
   		 return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	}

	
	public function HabilitarCORS8080($request, $response, $next) {

		/*
		al ingresar no hago nada
		*/
		 $response = $next($request, $response);
		 $response->getBody()->write('<p>habilitado HabilitarCORS8080</p>');
   		 return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:8080')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	}

	
	public function HabilitarCORS4200($request, $response, $next) {

		/*
		al ingresar no hago nada
		*/
		 $response = $next($request, $response);
		 $response->getBody()->write('<p>habilitado HabilitarCORS4200</p>');
   		 return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	}

}