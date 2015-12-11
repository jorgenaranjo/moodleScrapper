<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 16/11/15
 * Time: 04:10 PM
 */

namespace Crayon\Model;


interface QuestionInterface
{
    const TYPE_MATCH = 'match';
    const TYPE_MULTIANSWER = 'multianswer';
    const TYPE_MULTICHOICE = 'multichoice';
    const TYPE_RANDOM = 'random';
    const TYPE_SHORTANSWER = 'shortanswer';

    /**
     * Set question text.
     *
     * @param string $text
     */
    public function setQuestionText($text);

    /**
     * Get question text.
     * @return string
     */
    public function getQuestionText();

    /**
     * Set question type.
     *
     * @param string $type
     */
    public function setQuestionType($type);

    /**
     * Get question type.
     * @return string
     */
    public function getQuestionType();
}