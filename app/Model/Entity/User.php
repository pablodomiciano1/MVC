<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{

    public $id;

    public $nome;

    public $email;

    public $senha;

    public static function getUserByEmail($email){
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);

    }

    public function cadastrar(){
        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('usuarios'))->insert([
            'nome'=>$this->nome,
            'email'=>$this->email,
            'senha'=>$this->senha,
        ]);
    }

    public function atualizar(){
        //ATUALIZA A INSTANCIA NO BANCO
        return (new Database('usuarios'))->update('id = '.$this->id,[
            'nome'=>$this->nome,
            'email'=>$this->email,
            'senha'=>$this->senha,
        ]);
    }


    public function excluir(){
        //EXCLUI A INSTANCIA NO BANCO
        return (new Database('usuarios'))->delete('id = '.$this->id);
    }



    //METODO RESPONSAVEL POR RETORNAR USUARIOS
public static function getUsers($where = null, $order = null, $limit = null, $fields = '*'){
    return (new Database('usuarios'))->select($where,$order,$limit,$fields);
}



//METODO RESPONSAVEL POR RETORNAR UMA INSTANCIA COM BASE NO ID
public static function getUserById($id){
    return self::getUsers('id = '.$id)->fetchObject(self::class);
}
}