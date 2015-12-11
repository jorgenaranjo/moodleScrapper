<?php
/**
 * Created by PhpStorm.
 * User: isaac
 */
namespace Crayon\Utils;

use DOMElement;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebScrapper
{
    private $download;
    private $download_dir;
    private $session_cookie;
    /** @var Client $scrapper */
    private $scrapper;
    /** @var string $uri_pattern */
    private $uri_pattern = null;
    private $template = null;

    function __construct($session_cookie, $download_dir, $download)
    {
        if ( ! isset( $session_cookie, $download_dir, $download )) {
            throw new \InvalidArgumentException('Paramatetros inválidos');
        }

        // Inicializacion de variables de configuración.
        $this->session_cookie = $session_cookie;

        if (substr($download_dir, - 1) != '/') {
            $download_dir .= $download_dir . '/';;
        }
        $this->download_dir = $download_dir;
        $this->download     = $download;
        $this->setScrapper();
    }

    public function setTemplate($path)
    {
        $this->template = file_get_contents($path);
    }

    /**
     * Inicializa el Web Scrapper.
     */
    private function setScrapper()
    {
        $this->scrapper = new Client();
        $scrapper       = &$this->scrapper;
        $scrapper->setHeader('Cookie', $this->session_cookie);
    }

    /**
     * Define el patrón de URI para preparar las URI que utilizará el scrapper.
     * El parametro 'id' debe de reemplazarse por la palabra reservada :id para
     * que pueda ser reemplazado.
     *
     * @param $pattern
     */
    public function setUriPattern($pattern)
    {
        $this->uri_pattern = $pattern;
    }

    /**
     * Retorna la URI con el reemplazo del ID ingresado.
     *
     * @param $course
     * @param int $id ID a reemplazar en el patrón.
     *
     * @return string
     */
    private function getUri($course, $id)
    {
        return strtr($this->uri_pattern, array(':course' => $course, ':id' => $id));
    }


    public function scrapeId($course, $id)
    {
        $this->validateConfig($course, $id);
        $url = $this->getUri($course, $id);

        $crawler = $this->scrapper->request('GET', $url);
        $crawler = $crawler->filter('#region-main-wrap');
        list( $curso, $seccion ) = $this->getInfoFromUri($url);

        if ( ! $seccion || ! $curso) {
            throw new \Exception('Curso o sección inválida');
        }

        $fullname = "{$curso}section{$seccion}";
        $dir      = $this->download_dir . "{$curso}/{$fullname}";
        if ( ! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $this->getContent('img', $crawler, $curso, $fullname, $dir);
        $this->getContent('source', $crawler, $curso, $fullname, $dir);

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
                $node->setAttribute('src', $url);
            }
        }

        $quiz = $crawler->filter('li.activity.quiz.modtype_quiz div.activityinstance a');
        if ($quiz->count()) {
            /** @var DOMElement $node */
            foreach ($quiz as $node) {
                // Obtenemos el source de la imagen.
                $id      = $node->getAttribute('href');
                $matches = array();
                preg_match('/id\=(\d+)/', $id, $matches);
                $id = Utils::getExamIndex($matches[1]);
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

        $backto = $crawler->filter('img.smallicon');
        if ($backto->count()) {
            /** @var DOMElement $node */
            foreach ($backto as $node) {
                if(strpos($node->getAttribute('src'), '/up') !== FALSE){
                    // Obtenemos el source de la imagen.
                    $node->setAttribute('src', '/dist/images/up.svg');
                }
            }
        }

        $body = $crawler->html();
        $html = strtr($this->template, array(':content' => $body));
        file_put_contents("{$this->download_dir}/{$fullname}.html", $html);
    }

    /**
     * @param $type
     * @param Crawler $crawler
     * @param $curso
     * @param $fullname
     * @param $download_dir
     */
    private function getContent($type, $crawler, $curso, $fullname, $download_dir)
    {
        $items = $crawler->filter($type);
        if ($items->count()) {
            /** @var DOMElement $node */
            foreach ($items as $node) {
                // Obtenemos el source de la imagen.
                $url = $node->getAttribute('src');
                // La descargamos
                $opts    = array(
                    'http' => array(
                        'method' => "GET",
                        'header' => "Cookie: " . $this->session_cookie . "\r\n",
                    )
                );
                $context = stream_context_create($opts);
                $item    = null;
                if ($this->download) {
                    $item = file_get_contents($url, null, $context);
                }
                // La guardamos
                $filename = basename($url);
                $filename = urldecode($filename);
                $path     = "{$download_dir}/{$filename}";
                if ($this->download) {
                    file_put_contents($path, $item);
                }
                $node->setAttribute('src', "/src/{$curso}/{$fullname}/$filename");
            }
        }
    }

    private function getInfoFromUri($uri)
    {
        if (preg_match('/id\=(\d*)/', $uri, $curso)) {
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

        if (preg_match('/section\=(\d*)/', $uri, $section)) {
            $section = $section[1];
        } else {
            $section = null;
        }

        return array($curso, $section);
    }

    private function validateConfig($course, $id)
    {
        if (filter_var($this->getUri($course, $id), FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('URL inválida');
        }

        if ( ! $this->scrapper instanceof Client) {
            throw new \BadMethodCallException('No se ha inicializado el Web Scrapper');
        }
    }
}