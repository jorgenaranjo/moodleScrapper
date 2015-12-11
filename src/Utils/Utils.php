<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 20/11/15
 * Time: 10:43 PM
 */

namespace Crayon\Utils;


use Crayon\Database\ConnectionInfo;
use Crayon\Database\ConnectionInfoWrapper;
use Crayon\Database\MySqlConnection;
use DOMDocument;
use DOMXPath;
use PDO;

class Utils
{
    public static function cleanStyles($html)
    {

        $html = trim(preg_replace('/\s+/', ' ', $html));
        $domd = new DOMDocument();
        libxml_use_internal_errors(true);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $domd->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors(false);

        $domx = new DOMXPath($domd);
        /** @var \DOMNodeList $items */
        $items = $domx->query("//*[@style]");

        /** @var \DOMElement $item */
        foreach ($items as $item) {
            $item->removeAttribute("style");
        }

        return $domd->saveHTML();
    }

    public static function getExamIndex($course_module_id)
    {
        $info = ConnectionInfo::create();
        /** @var MySqlConnection $dbh */
        $dbh = MySqlConnection::create($info);
        /** @var \PDOStatement $statement */
        $statement = $dbh->prepare('SELECT quiz.id FROM mdl_course_modules AS cmi JOIN mdl_quiz AS quiz ON quiz.id = cmi.instance WHERE cmi.id = :id');
        $statement->bindParam(':id', $course_module_id);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        $result = $statement->fetchAll();
        $result = reset($result);

        return $result['id'];
    }
}