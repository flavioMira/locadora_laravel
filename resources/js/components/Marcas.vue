<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="InputID" id-help="idHelp" texto-help="Opcional. Informe o ID do registro">
                                    <input type="number" class="form-control" id="InputId" aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da Marca" id="InputNome" id-help="nomeHelp" texto-help="Opcional. Informe o nome do registro">
                                    <input type="text" class="form-control" id="InputNome">
                                </input-container-component>
                            </div>

                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary float-end">Pesquisar</button>
                    </template>
                </card-component>

                <!--carde de registros-->

                <card-component titulo="Lista de marcas">
                    <template v-slot:conteudo>
                        <table-component :dados="marcas" :titulos="['ID','nome','imagem']"></table-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <modal-component id="modalMarca" titulo="Adicionar Marca">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionada'"></alert-component>
                        <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
                    </template>
                    <template v-slot:conteudo>
                        <div class="form-group">
                            <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp" texto-help="Informe o nome da marca">
                                <input type="text" class="form-control" id="novoNome" placeholder="nome da Marca" v-model="nomeMarca">
                            </input-container-component>
                        </div>

                        <div class="form-group">
                            <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-help="Selecione uma imagem no formato png">
                                <input type="file" class="form-control" id="novoImagem" placeholder="selecione uma imagem" @change="carregarImagem($event)">
                            </input-container-component>
                        </div>

                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                    </template>
                </modal-component>

            </div>
        </div>



    </div>
</template>

<script>
 export default{
     data(){
         return {
             urlBase: 'http://localhost:8000/api/v1/marca',
             nomeMarca: '',
             arquivoImagem: [],
             transacaoStatus: '',
             transacaoDetalhes: {},
             marcas: []
         }
     },
     methods: {

         carregarLista(){
             axios.get(this.urlBase)
                .then(response =>{
                    this.marcas = response.data
                    console.log(this.marcas)
                })
                .catch(errors =>{
                    console.log(errors)
                })
         },

         carregarImagem(e) {
             this.arquivoImagem = e.target.files
         },
         salvar() {
             console.log(this.nomeMarca+' - '+this.arquivoImagem[0]);

             let formData = new FormData();
             formData.append('nome',this.nomeMarca);
             formData.append('imagem', this.arquivoImagem[0]);

             let config = {
                 headers: {
                     'content-Type': 'multipart/form-data',
                     'Accept': 'application/json'
                 }
             }

             axios.post(this.urlBase, formData, config)
                .then(response =>{
                    this.transacaoStatus = 'adicionada'
                    this.transacaoDetalhes = {
                        mensagem: "ID do registro: " + response.data.id
                    }
                    console.log(response)
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors
                    }
                })
         }
     },
     mounted() {
         this.carregarLista();
     }
 }
</script>
