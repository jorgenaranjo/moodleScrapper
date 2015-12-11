<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 18/11/15
 * Time: 03:45 PM
 */

namespace Crayon\Model;


use Crayon\Database\MySqlConnection;
use PDO;

class QuestionRandom
{
    public static function GetQuestion($category)
    {
        $dbh = MySqlConnection::getInstance();
        /** @var \PDOStatement $statement */
        $statement = $dbh->prepare('SELECT * FROM mdl_question WHERE category = :cat AND qtype != "random" ORDER BY RAND() LIMIT 1');
        $statement->bindParam(':quizid', $category);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        $question = $statement->fetch();
        return Examen::instanceQuestion($question);
    }
}