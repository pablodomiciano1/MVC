<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony{

/**
 * ID do depoimento
 * @var integer
 */
public $id;

/**
 * Nome do usuario depoimento
 * @var string
 */
public $nome;

/**
 * Mensagem do depoimento
 * @var string
 */
public $mensagem;

/**
 * data da publicação
 * @var string
 */
public $data;

/**
 * ID do depoimento
 * @return boolean
 */
public function cadastrar(){
    //DEFINE A DATA
    $this->data = date('Y-m-d H:i:s');

    //INSERE DEPOIMENTO NO BANCO
    $this->id = (new Database('depoimentos'))->insert([
        'nome' => $this->nome,
        'mensagem' => $this->mensagem,
        'data' => $this->data
    ]);

//return true;



}

public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('depoimentos'))->select($where,$order,$limit,$fields);
}

}