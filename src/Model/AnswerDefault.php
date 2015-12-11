<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 17/11/15
 * Time: 03:43 PM
 */

namespace Crayon\Model;


use Crayon\Utils\Utils;

class AnswerDefault implements AnswerInterface
{
    public $texto;
    public $value;

    private function __construct()
    {

    }

    public static function create()
    {
        return new static();
    }

    /**
     * Sets text for answer.
     *
     * @param string $texto
     */
    public function setTexto($texto)
    {
        //$this->texto = Utils::cleanStyles($texto);
        $this->texto = strip_tags($texto);
    }

    /**
     * Sets value for answer.
     *
     * @param bool $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}