<?php
namespace App\Routers;
use Exception;

function load (string $controller) {
    try {
        $controller_namespace = "App\\Controllers\\{$controller}";
        if (!class_exists($controller_namespace)) {
            throw new Exception("O controller não existe");
        }
        $controller_object =  new $controller_namespace();
        return $controller_object;
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}

$routes = [
        "home" => fn() => load("PaginaController"),
        "noticias" => fn() => load("NoticiaController"),
        "parcerias" => fn() => load("ParceriaController"),
        "publicacao" => fn() => load("PublicacaoController"),
        "usuario" => fn() => load("UsuarioController")
];