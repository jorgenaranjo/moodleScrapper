<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 17/11/15
 * Time: 03:42 PM
 */

namespace Crayon\Model;


interface AnswerInterface
{
    /**
     * Sets text for answer.
     *
     * @param string $texto
     */
    public function setTexto($texto);

    /**
     * Sets value for answer.
     *
     * @param bool $value
     */
    public function setValue($value);

}