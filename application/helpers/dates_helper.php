<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('meses')) {
    function meses()
    {
        $meses = array(
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        );
        return $meses;
    }
}

if (!function_exists('anos')) {
    /**
     * Retorna os anos referentes ao período passado por parâmetro.
     * Caso não tenha sido passado um período, retorna o ano atual.
     *
     * @param integer $inicial Ano inicial
     * @param integer $final   Ano final
     * 
     * @return array Retorna os anos gerados
     */
    function anos($inicial = null, $final = null)
    {
        $anos = array();
        if ($inicial && $final) {
            for ($inicial; $inicial <= $final; $inicial++) {
                $anos[$inicial] = $inicial;
            }
        } else {
            $anos[date('Y')] = date('Y');
        }

        return $anos;
    }
}

if (!function_exists('diasdasemana')) {
    function diasdasemana()
    {
        $diasdasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');

        return $diasdasemana;
    }
}