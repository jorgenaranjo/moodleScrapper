<?php
/**
 * Created by PhpStorm.
 * User: isaac
 */

use Crayon\Utils\WebScrapper;

include( 'vendor/autoload.php' );
include( 'src/autoloader.php' );

const SESSIONCOOKIE = "MoodleSession=lrcs0f561gq2ckdh039u091cb1";

// Download DIR.
$download_dir = __DIR__ . '/output/';
$curso = 5;
$webSections = array(3,4,5,6,7,8,9,10,11,12,14,15,16,17,18,19,20,21,22,23,25,26,27,28,29,30,31,32,33,34,36,37,38,39,40,41,42,43,44,45);

// Inicializamos scrapper
$moodleScrapper = new WebScrapper(SESSIONCOOKIE, $download_dir, TRUE);
$moodleScrapper->setUriPattern('http://192.168.0.15/ingles/course/view.php?id=:course&section=:id');
$moodleScrapper->setTemplate(__DIR__.'/assets/html/TemplateSession.html');
foreach ($webSections as $section) {
    echo "Iniciando Seccion {$section}.\n";
    $moodleScrapper->scrapeId($curso, $section);
    echo "Seccion {$section} terminada.\n";
}


