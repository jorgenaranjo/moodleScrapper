<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 18/11/15
 * Time: 03:34 PM
 */

namespace Crayon\Model;


use Crayon\Database\MySqlConnection;
use PDO;

class QuestionMatch extends QuestionWrapper implements QuestionInterface
{
    public $answers = array();
    /**
     * @inheritdoc
     */
    public static function create()
    {
        $question = parent::create();
        $question->setQuestionType(QuestionInterface::TYPE_MULTICHOICE);
        return $question;
    }

    public function setAnswers($id){
        $dbh = MySqlConnection::getInstance();
        $query = "SELECT * FROM mdl_qtype_match_subquestions WHERE questionid = :id";
        $statement = $dbh->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $answers = $statement->fetchAll();

        foreach($answers as $answer_original){
            $answer = AnswerMatch::create();
            $answer->setTexto($answer_original['questiontext']);
            $answer->setRespuesta($answer_original['answertext']);
            $answer->setValue(true);
        }
    }

}