<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 16/11/15
 * Time: 04:22 PM
 */

namespace Crayon\Model;


use Crayon\Utils\Utils;

class QuestionShortanswer extends QuestionWrapper implements QuestionInterface
{
    public $answers = array();

    /**
     * @inheritdoc
     */
    public static function create()
    {
        $question = parent::create();
        $question->setQuestionType(QuestionInterface::TYPE_SHORTANSWER);
        return $question;
    }

    /**
     * Sets answer for current question.
     * @param $answer
     */
    public function setAnswer($answer){
        $matches = array();
        $answer = Utils::cleanStyles($answer);
        preg_match_all('/{\d+\:SHORTANSWER\:\%\d+\%(.*?)\#\}+?/',$answer, $matches);
        if(empty($matches)){
           throw new \UnexpectedValueException("No se ha obtenido ningun valor esperado de respuesta");
        }
        array_shift($matches);
        $this->answer = reset($matches[0]);
    }


}