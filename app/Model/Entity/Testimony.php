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
//SUCESSO
return true;
}

public function atualizar(){

    //ATUALIZA DEPOIMENTO NO BANCO
   return (new Database('depoimentos'))->update('id = '.$this->id,[
        'nome' => $this->nome,
        'mensagem' => $this->mensagem
    ]);
}

public function excluir(){
    //EXCLUIR DEPOIMENTO DO BANCO
   return (new Database('depoimentos'))->delete('id = '.$this->id);
}

//METODO RESPONSAVEL POR RETORNAR DEPOIMENTO COM BASE NO ID
public static function getTestimonyById($id){
    return self::getTestimonies('id = '.$id)->fetchObject(self::class);
}

//METODO RESPONSAVEL POR RETORNAR DEPOIMENTOS
public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('depoimentos'))->select($where,$order,$limit,$fields);
}

}