<?php

namespace App\Models\Repositories;

use App\Models\VO\Contato;
use Illuminate\Support\Facades\DB;

class ContatoRepository
{
    public function getContatos()
    {
        $result = DB::select('SELECT id FROM contato');

        if (!$result) {
            throw new Exception('Nenhum contato encontrado');
        }

        $arrayContato = array();

        for ($i = 0 ; $i < sizeof($result) ; $i++)
        {
            $contato = new Contato();

            $contato->id = $result[$i]->id;

            $arrayContato[] = $contato;
        }
        
        return $arrayContato;
    }

    public function getContatoById($id)
    {
        $result = DB::select('SELECT * FROM contato WHERE id = ?', [$id]);

        if (!$result) {
            throw new Exception('Contato não encontrado');
        }

        $contato = new Contato();

        $contato->id = $result[0]->id;
        $contato->nome = $result[0]->nome;
        $contato->endereco = $result[0]->endereco;
        $contato->dataNasc = $result[0]->data_nasc;

        return $contato;
    }

    public function getContatoByNome($nome)
    {
        $result = DB::select('SELECT * FROM contato WHERE nome LIKE "%'. $nome . '%" ');

        if (!$result) {
            throw new Exception('Contato não encontrado');
        }

        if(sizeof($result)>1){
            throw new Exception('Foi encontrado mais de um contato com este nome!');
        }

        $contato = new Contato();

        $contato->id = $result[0]->id;
        $contato->nome = $result[0]->nome;
        $contato->endereco = $result[0]->endereco;
        $contato->dataNasc = $result[0]->data_nasc;

        return $contato;
    }

    public function getEmailById($id)
    {
        $result = DB::select('SELECT email FROM email WHERE fk_contato = ?', [$id]);

        return $result;
    }

    public function getEmailByEmail($email)
    {
        $result = DB::select('SELECT * FROM email WHERE email LIKE "%'. $email . '%" ');

        if (!$result) {
            throw new Exception('Contato não encontrado');
        }

        if(sizeof($result)>1){
            throw new Exception('Foi encontrado mais de um contato com este nome!');
        }

        return $result;
    }

    public function getTelefoneById($id)
    {
        $result = DB::select('SELECT telefone FROM telefone WHERE fk_contato = ?', [$id]);

        return $result;
    }

    public function deleteContatoById($id)
    {
        $result = DB::delete('DELETE FROM contato WHERE id = ?', [$id]);

        if (!$result) {
            throw new Exception('Erro ao deletar');
        }
    }

    public function deleteEmailById($id)
    {
        $result = DB::delete('DELETE FROM email WHERE fk_contato = ?', [$id]);

        if (!$result) {

            throw new Exception('Erro ao deletar');
        }
    }

    public function deleteEmailByEmail($email)
    {
        $result = DB::delete('DELETE FROM email WHERE email = ?', [$email]);

        if (!$result) {

            throw new Exception('Erro ao deletar');
        }
    }

    public function deleteTelefoneById($id)
    {
        $result = DB::delete('DELETE FROM telefone WHERE fk_contato = ?', [$id]);

        if (!$result) {
            throw new Exception('Erro ao deletar');
        }
    }

    public function deleteTelefoneByTelefone($telefone)
    {
        $result = DB::delete('DELETE FROM telefone WHERE telefone = ?', [$telefone]);

        if (!$result) {
            throw new Exception('Erro ao deletar');
        }
    }

    public function insertContato(Contato $contato)
    {
        $result = DB::insert('INSERT INTO contato (nome, endereco, data_nasc) VALUES (?, ?, ?)', [$contato->nome, $contato->endereco, $contato->dataNasc]);

        if (!$result) {
            throw new Exception('Erro ao cadastrar contato');
        }

        $result = DB::select('SELECT MAX(id) id FROM contato');

        return $result[0]->id;
    }

    public function insertEmail($email , $id)
    {
        $result = DB::insert('INSERT INTO email (fk_contato, email) VALUES (?, ?)', [$id, $email]);

        if (!$result) {
            throw new Exception('Erro ao cadastrar email');
        }
    }

    public function insertTelefone($telefone, $id)
    {
        $result = DB::insert('INSERT INTO telefone (fk_contato, telefone) VALUES (?, ?)', [$id, $telefone]);

        if (!$result) {
            throw new Exception('Erro ao cadastrar telefone');
        }
    }

    public function updateContato(Contato $contato)
    {
        $result = DB::update('UPDATE contato SET nome = ? , endereco = ?, data_nasc = ? WHERE id = ?', [$contato->nome, $contato->endereco, $contato->dataNasc, $contato->id]);

        if (!$result) {
            throw new Exception('Erro ao atualizar contato');
        }
    }
}
