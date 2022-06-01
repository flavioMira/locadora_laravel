<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente){
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);
        //dd($clienteRepository);

            if($request->has('filtro')){

                $clienteRepository->filtro($request->filtro);

            }

            if($request->has('atributos')){

                $clienteRepository->selectAtributos($request->atributos);

            }


            //$marcas = $this->marca->with('modelos')->get();
            return response()->json($clienteRepository->getResultados(), 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());
        $cliente = $this->cliente->create([
            'nome' => $request->nome
        ]);
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($cliente, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(['erro' => 'Impossivel atualizar. Recurso pesquisado não existe'], 404);
        }

        if($request->method() === "PATCH"){

            $regrasDinamicas = array();

            foreach($cliente->rules() as $input => $regra){

                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }

                $request->validate($regrasDinamicas);
            }

         } else {

                $request->validate($cliente->rules());

            }


        $cliente->fill($request->all());

        $cliente->save();

        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(['erro' => 'Impossivel deletar. Recurso pesquisado não existe'], 404);
        }


        $cliente->delete();
        return response()->json(['msg' => 'O cliente foi deletado com sucesso!'], 200);
    }
}