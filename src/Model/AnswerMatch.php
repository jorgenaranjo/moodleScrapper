<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 18/11/15
 * Time: 03:40 PM
 */

namespace Crayon\Model;


use Crayon\Utils\Utils;

class AnswerMatch extends AnswerDefault
{
    public $respuesta;

    /**
     * Sets text for answer.
     *
     * @param string $texto
     */
    public function setRespuesta($texto)
    {
        $this->respuesta = Utils::cleanStyles($texto);
    }

}