<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 16/11/15
 * Time: 11:05 AM
 */

namespace Crayon\Model;


use Crayon\Database\MySqlConnection;
use DOMDocument;
use DOMXPath;
use PDO;

class Examen
{
    public $examen = null;
    public $questions = array();

    private function __construct(array $examen)
    {
        $this->examen = array(
            'id'   => $examen['id'],
            'name' => $examen['name']
        );
        $this->getQuestions();
    }

    public static function create(array $examen)
    {
        if (count($examen) < 38) {
            throw new \InvalidArgumentException("Arreglo no cumple con el tamaño minimo definido en la tabla quiz");
        }

        // TODO: Añadir validaciones pertinentes en la creación del exámen.
        return new static($examen);
    }

    public function json_dump()
    {
        return addslashes(json_encode($this, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS));
    }

    private function getQuestions()
    {
        /** @var MySqlConnection $dbh */
        $dbh = MySqlConnection::getInstance();
        /** @var \PDOStatement $statement */
        $statement = $dbh->prepare('SELECT questionid FROM ingles.mdl_quiz_slots WHERE quizid = :quizid');
        $statement->bindParam(':quizid', $this->examen['id']);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        $_questions = $statement->fetchAll();

        foreach ($_questions as $_question) {
            $statement = $dbh->prepare('SELECT * FROM mdl_question WHERE id = :id');
            $statement->bindParam(':id', $_question['questionid']);
            $statement->execute();
            $_question = $statement->fetch();
            if (is_array($_question)) {
                $_question = static::instanceQuestion($_question);
                if ($_question instanceof QuestionInterface) {
                    $this->questions[] = $_question;
                }
            }
        }
    }

    public static function instanceQuestion(array $question_original)
    {
        $question = null;
        switch ($question_original['qtype']) {
            case 'match':
                return null;
                //$question = QuestionMatch::create();
                //$question->setQuestionText($question_original['questiontext']);
                //$question->setAnswers($question_original['id']);
                break;
            case 'multianswer':
                return null;
                //$question = QuestionMultiAnswer::create();
                //$question->setQuestionText($question_original['questiontext']);
                //$question->processQuestion($question_original['id']);
                break;
            case 'multichoice':
                $question = QuestionMultichoice::create();
                $question->setQuestionText($question_original['questiontext']);
                $question->setAnswers($question_original['id']);
                break;
            case 'random':
                return null;
                //$question = QuestionRandom::GetQuestion($question_original['category']);
                break;
            case 'shortanswer';
                return null;
                //$question = QuestionShortanswer::create();
                //$question->setQuestionText($question_original['questiontext']);
                //$question->setAnswer($question_original['questiontext']);
                break;
        }

        return $question;
    }
}