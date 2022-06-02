<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

class AbstractRepository{
    public function __construct(Model $model){
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos){
        $this->model = $this->model->with($atributos);
    }

    public function filtro($filtros){
        $filtros = explode(';', $filtros);

            foreach($filtros as $key => $condicao){

                $condicoes = explode(':', $condicao);
                $this->model = $this->model->where($condicoes[0],$condicoes[1],$condicoes[2]);

            }


    }

    public function selectAtributos($atributos){
        $this->model = $this->model->selectRaw($atributos);

    }

    public function getResultados(){

        return $this->model = $this->model->get();
    }

    public function getResultadosPaginados($numeroRegistrosPorPagina){

        return $this->model = $this->model->paginate($numeroRegistrosPorPagina);
    }
}

?>
