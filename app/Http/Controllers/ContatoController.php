<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ContatoService;
use Illuminate\Http\Request;

class ContatoController extends Controller
{
     /**
     * @var ContatoService
     */
    private $contatoService;

    /**
     * ContatoController constructor.
     */
    public function __construct(ContatoService $contatoService)
    {
        $this->contatoService = $contatoService;
    }

    public function getById($id)
    {
        return response()->json($this->contatoService->getById($id));
    }

    public function getByNome($nome)
    {
        return response()->json($this->contatoService->getByNome($nome));
    }

    public function getByEmail($email)
    {
        return response()->json($this->contatoService->getByNome($email));
    }

    public function listAll()
    {
        return response()->json($this->contatoService->getContatos());
    }

    public function insertContato(Request $request)
    {
        return response()->json(
            [[
                'result' => $this->contatoService->insertContato($request)
            ]]
        );
    }

    public function updateContato(Request $request)
    {
        return response()->json(
            [[
                'result' => $this->contatoService->updateContato($request)
            ]]
        );
    }

    public function deleteContato(Request $request)
    {   
        // Pegar o ID
        $id = $request->input('id');

        return response()->json(
            [[
                'result' => $this->contatoService->deleteById($id)
            ]]
        );
        
    }
}