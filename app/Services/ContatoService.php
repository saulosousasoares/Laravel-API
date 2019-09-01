<?php

namespace App\Services;

use App\Models\Repositories\ContatoRepository;
use App\Models\VO\Contato;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;


class ContatoService extends ServiceProvider
{ 
    /**
    * @var ContatoRepository
    */
   private $repository;

   /**
    * ContatoService constructor.
    */
    public function __construct(ContatoRepository $repository) {
        $this->repository = $repository;
    }

    public function getById($id): Contato
    {
        // Buscar contato pelo ID
        $contato = $this->repository->getContatoById($id);

        // Buscar o email pelo ID
        $email = $this->repository->getEmailById($id);

        $emailString = '';
        // Juntar os email em uma String
        for($i = 0 ; $i < sizeof($email) ; $i++){
            $emailString .= $email[$i]->email . ';';
        }

        // Adicionar no VO Contato
        $contato->email = $emailString;

        // Buscar o email pelo ID
        $telefone = $this->repository->getTelefoneById($id);

        $telefoneString = '';
        // Juntar os telefone em uma String
        for($i = 0 ; $i < sizeof($telefone) ; $i++){
            $telefoneString .= $telefone[$i]->telefone . ';';
        }

        // Adicionar no VO Contato
        $contato->telefone = $telefoneString;

        return $contato;
    }

    public function getByNome($nome): Contato
    {
        // Buscar contato pelo Nome
        $contato = $this->repository->getContatoByNome($nome);

        // Buscar o email pelo ID
        $email = $this->repository->getEmailById($contato->id);

        $emailString = '';
        // Juntar os email em uma String
        for($i = 0 ; $i < sizeof($email) ; $i++){
            $emailString .= $email[$i]->email . '; ';
        }

        // Adicionar no VO Contato
        $contato->email = $emailString;

        // Buscar o email pelo ID
        $telefone = $this->repository->getTelefoneById($contato->id);

        $telefoneString = '';
        // Juntar os telefone em uma String
        for($i = 0 ; $i < sizeof($telefone) ; $i++){
            $telefoneString .= $telefone[$i]->telefone . '; ';
        }

        // Adicionar no VO Contato
        $contato->telefone = $telefoneString;

        return $contato;
    }

    public function getByEmail($email): Contato
    {
        // Buscar o email pelo email ~ exemplo : jose@gmail.com
        $response = $this->repository->getEmailByEmail($email);

        // Buscar contato pelo ID do fk_contato
        $contato = $this->repository->getContatoById($response[0]->fk_contato);

        $emailString = '';
        // Juntar os email em uma String
        for($i = 0 ; $i < sizeof($response) ; $i++){
            $emailString .= $response[$i]->email . '; ';
        }

        // Adicionar no VO Contato
        $contato->email = $emailString;

        // Buscar o email pelo ID
        $telefone = $this->repository->getTelefone($contato->id);

        $telefoneString = '';
        // Juntar os telefone em uma String
        for($i = 0 ; $i < sizeof($telefone) ; $i++){
            $telefoneString .= $telefone[$i]->telefone . '; ';
        }

        // Adicionar no VO Contato
        $contato->telefone = $telefoneString;

        return $contato;
    }

    public function getContatos()
    {
        // Buscar todos os contatos
        $contatos = $this->repository->getContatos();

        for ($i = 0 ; $i < sizeof($contatos) ; $i++){
            $contatos[$i] = $this->getById($contatos[$i]->id);
        }

        return $contatos;
    }

    public function insertContato(Request $request)
    {
        $contato = new Contato();

        $contato->nome = $request->nome;
        $contato->endereco = $request->endereco;
        $contato->dataNasc = $request->dataNasc;
        $contato->email = $request->email;
        $contato->telefone = $request->telefone;

        // Insere o contato e caso consiga retorna o ID
        $contato->id = $this->repository->insertContato($contato);

        $email = explode(";", $contato->email);

        foreach ($email as $val) {
            $this->repository->insertEmail($val , $contato->id);
        }

        $telefone = explode(";", $contato->telefone);

        foreach ($telefone as $val) {
            $this->repository->insertTelefone($val , $contato->id);
        }

        return true;
    }

    public function updateContato(Request $request)
    {
        $contato = new Contato();

        $contato->id = $request->id;
        $contato->nome = $request->nome;
        $contato->endereco = $request->endereco;
        $contato->dataNasc = $request->dataNasc;
        $contato->email = $request->email;
        $contato->telefone = $request->telefone;

        // Obtém o contato
        $response = $this->getById($contato->id);    

        // Atualiza o contato
        $this->repository->updateContato($contato);

        // Separa o email do contato do BANCO e o que será ATUALIZADO
        $email = explode(";", $contato->email);
        $emailBD = explode(";", $response->email);

        // Registra num array a lista de emails a serem adicionados e removidos
        $arrayEmailAdd = array_diff($email, $emailBD); // adicionar
        $arrayEmailRemover = array_diff($emailBD, $email); // remover

        foreach ($arrayEmailAdd as $val) {
            if(!empty($val)){
                $this->repository->insertEmail($val , $contato->id);
            }
        }

        foreach ($arrayEmailRemover as $val) {
            if(!empty($val)){
                $this->repository->deleteEmailByEmail($val);
            }
        }

        // $telefone = explode(";", $contato->telefone);

        // foreach ($telefone as $val) {
        //     $this->repository->insertTelefone($val , $contato->id);
        // }

        // Separa o telefone do contato do BANCO e o que será ATUALIZADO
        $telefone = explode(";", $contato->telefone);
        $telefoneBD = explode(";", $response->telefone);

        // Registra num array a lista de telefones a serem adicionados e removidos
        $arrayTelefoneAdd = array_diff($telefone, $telefoneBD); // adicionar
        $arrayTelefoneRemover = array_diff($telefoneBD, $telefone); // remover

        foreach ($arrayTelefoneAdd as $val) {
            if(!empty($val)){
                $this->repository->insertTelefone($val , $contato->id);
            }
        }

        foreach ($arrayTelefoneRemover as $val) {
            if(!empty($val)){
                $this->repository->deleteTelefoneByTelefone($val);
            }
        }

        return true;
    }

    public function deleteById($id)
    {
        // Obtém o contato
        $response = $this->getById($id);     
        
        if(!empty($response->email)){
            // Deletar Email pelo ID
            $this->repository->deleteEmailById($id);
        }

        if(!empty($response->telefone)){
            // Deletar Telefone pelo ID
            $this->repository->deleteTelefoneById($id);
        }
        
        if(!empty($response->id)){
            // Deletar contato pelo ID
            $this->repository->deleteContatoById($id);
        }

        return true;
    }
}
