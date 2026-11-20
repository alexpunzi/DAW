<?php

function urlBase(): string
{
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $dir = preg_replace('#/includes/procesadores$#', '', $dir);
    return rtrim($dir, '/');
}

function redirigir(string $pagina, array $params = []): void
{
    unset($params['usuario']);

    $esquema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ? 'https'
        : 'http';

    $url = $esquema . '://' . $_SERVER['HTTP_HOST']
        . urlBase() . '/' . ltrim($pagina, '/');

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    header('Location: ' . $url);
    exit;
}

function urlPrivada(string $pagina, array $params = []): string
{
    unset($params['usuario']);
    return $pagina . (!empty($params)
        ? '?' . http_build_query($params)
        : '');
}

function urlInicio(): string
{
    return estaIdentificado()
        ? urlPrivada('index_sesion.php')
        : 'index.php';
}