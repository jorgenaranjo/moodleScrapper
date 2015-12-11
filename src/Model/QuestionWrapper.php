<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 16/11/15
 * Time: 04:17 PM
 */

namespace Crayon\Model;


use Crayon\Utils\Utils;

abstract class QuestionWrapper implements QuestionInterface
{
    public $question_text;
    public $question_type;

    /**
     * Semi "factory" like instantiation.
     */
    private function __construct()
    {

    }

    /**
     * Returns an instance of current class
     * @return static
     */
    public static function create()
    {
        $question = new static();
        return $question;
    }

    /**
     * @inheritdoc
     */
    public function setQuestionText($text)
    {
        $this->question_text = Utils::cleanStyles($text);
    }

    /**
     * @inheritdoc
     */
    public function getQuestionText()
    {
        return $this->question_text;
    }

    /**
     * @inheritdoc
     */
    public function setQuestionType($type)
    {
        $this->question_type = $type;
    }

    /**
     * @inheritdoc
     */
    public function getQuestionType()
    {
        return $this->question_type;
    }
}