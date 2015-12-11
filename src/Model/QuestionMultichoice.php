<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 17/11/15
 * Time: 03:25 PM
 */

namespace Crayon\Model;


use Crayon\Database\MySqlConnection;
use PDO;

class QuestionMultichoice extends QuestionWrapper implements QuestionInterface
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
        $query = "SELECT * FROM mdl_question_answers WHERE question = :id";
        $statement = $dbh->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $answers = $statement->fetchAll();
        foreach($answers as $answer){
            $this->answers[] = static::processAnswer($answer);
        }
    }

    private static function processAnswer($answer_original)
    {
        $answer =  AnswerDefault::create();
        $answer->setTexto($answer_original['answer']);
        $answer->setValue(((float)$answer_original['fraction']) != 0);
        return $answer;
    }
}