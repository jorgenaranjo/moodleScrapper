<?php

include( 'vendor/autoload.php' );
include( 'src/autoloader.php' );

const DOWNLOAD_DIR = __DIR__;
const DOWNLOAD     = true;
const SESSIONCOOKIE       = "MoodleSession=84mnmtq449hrhvva71sabrbk63";

// Inicializamos el cliente
$scapper = new Goutte\Client();
// Recordar settear COOKIE de autentificación.
$scapper->setHeader('Cookie', SESSIONCOOKIE);
$sections = array(3,4,5,6,7,8,9,10,11,12,14,15,16,17,18,19,20,21,22,23,25,26,27,28,29,30,31,32,33,34,36,37,38,39,40,41,42,43,44,45);
foreach ($sections as $s) {
    // Hacemos el request para la página.
    $url      = "http://192.168.0.15/ingles/course/view.php?id=5&section={$s}";
    $url_info = explode('?', $url);
    $url_info = end($url_info);
    $crawler  = $scapper->request('GET', $url);

    if (preg_match('/id\=(\d*)/', $url, $curso)) {
        $curso  = $curso[1];
        $cursos = array(
            "3" => "A1",
            "4" => "A2",
            "5" => "B1",
        );
        $curso  = $cursos[$curso];
    } else {
        $curso = null;
    }

    if (preg_match('/section\=(\d*)/', $url, $section)) {
        $section = $section[1];
    } else {
        $section = null;
    }
    if ($curso == false || $section == false) {
        exit;
    }

    $fullname = "{$curso}section{$section}";
    $dirname  = DOWNLOAD_DIR . "/output/{$curso}/{$fullname}";
    if ( ! is_dir($dirname)) {
        mkdir($dirname, 0775, true);
    }
    $crawler = $crawler->filter('#region-main-wrap');
// Buscamos todas las imagenes del sitio.
    $images = $crawler->filter('img');
    if ($images->count()) {
        /** @var DOMElement $node */
        foreach ($images as $node) {
            // Obtenemos el source de la imagen.
            $url = $node->getAttribute('src');
            // La descargamos
            $opts    = array(
                'http' => array(
                    'method' => "GET",
                    'header' => "Cookie: " . SESSIONCOOKIE . "\r\n",
                )
            );
            $context = stream_context_create($opts);
            //$image   = file_get_contents($url, null, $context);
            // La guardamos
            $filename = basename($url);
            $filename = urldecode($filename);
            $path     = "{$dirname}/$filename";
            //file_put_contents($path, $image);
            $node->setAttribute('src', "/src/{$curso}/{$fullname}/$filename");
            $parent = $node->parentNode;
        }
    }
    $sources = $crawler->filter('source');
    if ($sources->count()) {
        /** @var DOMElement $node */
        foreach ($sources as $node) {
            // Obtenemos el source de la imagen.
            $url = $node->getAttribute('src');
            // La descargamos
            $opts    = array(
                'http' => array(
                    'method' => "GET",
                    'header' => "Cookie: " . SESSIONCOOKIE . "\r\n",
                )
            );
            $context = stream_context_create($opts);
            //$audio   = file_get_contents($url, null, $context);
            // La guardamos
            $filename = basename($url);
            $filename = urldecode($filename);
            $path     = "{$dirname}/$filename";
            //file_put_contents($path, $audio);
            $node->setAttribute('src', "/src/{$curso}/{$fullname}/$filename");
        }
    }

    $sources = $crawler->filter('a.mediafallbacklink');
    if ($sources->count()) {
        /** @var DOMElement $node */
        foreach ($sources as $node) {
            // Obtenemos el source de la imagen.
            $url = $node->getAttribute('href');
            // La guardamos
            $filename = basename($url);
            $filename = urldecode($filename);
            $node->setAttribute('href', "/src/{$curso}/{$fullname}/$filename");
        }
    }

    $iframe = $crawler->filter('iframe.iframeform');
    if ($iframe->count()) {
        /** @var DOMElement $node */
        foreach ($iframe as $node) {
            // Obtenemos el source de la imagen.
            $url   = $node->getAttribute('src');
            $index = strpos($url, '/iframe/');
            $url   = substr($url, $index, strlen($url) - 1);
            // La guardamos
            $filename = basename($url);
            $filename = urldecode($filename);
            $node->setAttribute('src', $url);
        }
    }

    $quiz = $crawler->filter('li.activity.quiz.modtype_quiz div.activityinstance a');
    if ($quiz->count()) {
        /** @var DOMElement $node */
        foreach ($quiz as $node) {
            // Obtenemos el source de la imagen.
            $id = $node->getAttribute('href');
            $matches = array();
            preg_match('/id\=(\d+)/', $id, $matches);
            $id = \Crayon\Utils\Utils::getExamIndex($matches[1]);
            $node->setAttribute('href', "/src/quiz{$id}.html");
        }
    }


    $backto = $crawler->filter('div.backto a');
    if ($backto->count()) {
        /** @var DOMElement $node */
        foreach ($backto as $node) {
            // Obtenemos el source de la imagen.
            $node->setAttribute('href', '/src/mainA1.html');
        }
    }

    $body = $crawler->html();

    $head = <<<TAG
<!DOCTYPE html>
<html dir="ltr" lang="es" xml:lang="es">
<head>
    <meta charset="UTF-8"/>
    <title>ILS</title>
    <script src="/dist/scripts/main.js"></script>
    <link rel="stylesheet" type="text/css" href="/dist/styles/main.css">
</head>
<body id="page-course-view-flexsections"
      class="format-flexsections  pagelayout-course side-pre-only">
<div id="page">
    <div id="page-header" class="clearfix">
        <a class="logoImage" href="/main.html">
            <h1 class="headermain">ILS - Inglés Online</h1>
        </a>

        <div class="headermenu">
            <!--<div class="logininfo">-->
            <!--Usted se ha identificado como <a href="http://192.168.1.76/ingles/user/profile.php?id=25" title="Ver perfil">Alumno1 [ILS]</a> -->
            <!--(<a href="/index.html">Salir</a>)-->
            <!--</div>-->
            <a class="logout" href="/index.html"></a>
        </div>
    </div>
    <!-- END OF HEADER -->

    <div id="page-content">
        <div id="region-main-box">
            <div id="region-post-box">
                <div id="region-main-wrap">
                    <!--END OF TOP SECTION-->
TAG;

    $footer = <<<TAG
                    <!--START OF BOTTOM SECTION-->
                </div>
            </div>
        </div>
    </div>

    <!-- START OF FOOTER -->
    <div id="page-footer" class="clearfix">
        <!-- <p class="helplink"><a href="http://docs.moodle.org/27/es/course/view/flexsections"><img class="iconhelp icon-pre" alt="Moodle Docs para esta página" title="Moodle Docs para esta página" src="http://192.168.1.76/ingles/theme/image.php/wlingua/core/1445790899/docs" />Moodle Docs para esta página</a></p>
        <div class="logininfo">Usted se ha identificado como <a href="http://192.168.1.76/ingles/user/profile.php?id=2" title="Ver perfil">Administrador [SIL]</a> (<a href="http://192.168.1.76/ingles/login/logout.php?sesskey=6oEqZKCPPs">Salir</a>)</div><div class="homelink"><a href="http://192.168.1.76/ingles/">Página Principal</a></div>  -->
        <span>©2014</span>
    </div>
    <div class="clearfix"></div>
</div>
</body>
</html>
TAG;

    $html = <<<TAG
{$head}
{$body}
{$footer}
TAG;
    file_put_contents(DOWNLOAD_DIR . "/output/{$fullname}.html", $html);
    echo "Seccion {$s} finalizada.\n";
}
