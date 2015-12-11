<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 18/11/15
 * Time: 03:20 PM
 */

namespace Crayon\Model;


use Crayon\Database\MySqlConnection;
use PDO;

class QuestionMultiAnswer extends QuestionWrapper implements QuestionInterface
{
    public $subQuestions = array();

    /**
     * @inheritdoc
     */
    public static function create()
    {
        $question = parent::create();
        $question->setQuestionType(QuestionInterface::TYPE_MULTIANSWER);
        return $question;
    }

    public function processQuestion($id){
        $dbh = MySqlConnection::getInstance();
        $query = "SELECT * FROM mdl_question_multianswer WHERE question = :id";
        $statement = $dbh->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $questions = $statement->fetch();
        $questions = explode(',', $questions['sequence']);
        $index = 1;
        foreach($questions as $question){
            $question = $this->getQuestion($question);
            if($question instanceof QuestionInterface){
                $this->subQuestions["#{$index}"] = $question;
                $index++;
            }
        }
    }

    private function getQuestion($id)
    {
        /** @var MySqlConnection $dbh */
        $dbh = MySqlConnection::getInstance();
        /** @var \PDOStatement $statement */
        $statement = $dbh->prepare('SELECT * FROM mdl_question WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();
        $_question = $statement->fetch();
        if (is_array($_question)) {
            return Examen::instanceQuestion($_question);
        }
        return null;
    }
}