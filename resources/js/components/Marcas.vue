<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="InputID" id-help="idHelp" texto-help="Opcional. Informe o ID do registro">
                                    <input type="number" class="form-control" id="InputId" aria-describedby="idHelp" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da Marca" id="InputNome" id-help="nomeHelp" texto-help="Opcional. Informe o nome do registro">
                                    <input type="text" class="form-control" id="InputNome" v-model="busca.nome">
                                </input-container-component>
                            </div>

                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary float-end" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <!--carde de registros-->

                <card-component titulo="Lista de marcas">
                    <template v-slot:conteudo>
                        <table-component :dados="marcas.data"
                         :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalVizualizarMarca'}"
                         :atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalAtualizarMarca'}"
                         :remover="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalRemoverMarca' }"
                         :titulos="{id:{titulo: 'ID', tipo: 'text'},nome:{titulo: 'Nome', tipo: 'texto'},imagem:{titulo: 'Imagem', tipo: 'imagem'}}">
                         </table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <pagination-component>
                                    <li v-for="l, key in marcas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" style="cursor: pointer">
                                        <a class="page-link" v-html="l.label" @click="paginacao(l)"></a>
                                    </li>
                                </pagination-component>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modalMarca" @click="limpar()">Adicionar</button>
                            </div>
                        </div>

                    </template>
                </card-component>

                <modal-component id="modalMarca" titulo="Adicionar Marca">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionada'"></alert-component>
                        <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
                    </template>
                    <template v-slot:conteudo v-if="transacaoStatus != 'adicionada'">
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
                        <button type="button" class="btn btn-primary" @click="salvar()" v-if="transacaoStatus != 'adicionada'">Salvar</button>
                    </template>
                </modal-component>

                <!-- Modal para vizualizar Marca-->

                <modal-component id="modalVizualizarMarca" titulo="Visualizar Marca">
                    <template v-slot:alertas>
                    </template>
                    <template v-slot:conteudo>
                       <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                       </input-container-component>
                       <input-container-component titulo="Nome da marca">
                            <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                       </input-container-component>
                       <input-container-component titulo="Imagem">
                            <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
                       </input-container-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </template>
                </modal-component>

                <!-- Modal para Remoção da Marca-->

                <modal-component id="modalRemoverMarca" titulo="Remover Marca">
                    <template v-slot:alertas>
                        <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                        <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                    <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                       <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                       </input-container-component>
                       <input-container-component titulo="Nome da marca">
                            <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                       </input-container-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
                    </template>
                </modal-component>

                <!-- Modal para atualizar marca -->

                <modal-component id="modalAtualizarMarca" titulo="Atualizar Marca">
                    <template v-slot:alertas>
                        <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                        <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                    <template v-slot:conteudo v-if="transacaoStatus != 'adicionada'">
                        <div class="form-group">
                            <input-container-component titulo="Nome da Marca" id="atualizarNome" id-help="atualizarNomeHelp" texto-help="Informe o nome da marca">
                                <input type="text" class="form-control" id="atualizarNome" placeholder="nome da Marca" v-model="$store.state.item.nome">
                            </input-container-component>
                        </div>

                        <div class="form-group">
                            <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp" texto-help="Selecione uma imagem no formato png">
                                <input type="file" class="form-control" id="atualizarImagem" placeholder="selecione uma imagem" @change="carregarImagem($event)">
                            </input-container-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
                    </template>
                </modal-component>

            </div>
        </div>



    </div>
</template>

<script>
import Pagination from './Pagination.vue'
 export default{
  components: { Pagination },
     data(){
         return {
             urlBase: 'http://localhost:8000/api/v1/marca',
             urlPaginacao: '',
             urlFiltro: '',
             nomeMarca: '',
             arquivoImagem: [],
             transacaoStatus: '',
             transacaoDetalhes: {},
             marcas: { data : [] },
             busca: { id: '', nome: '' }
         }
     },
     methods: {
         atualizar(){
             let formData = new FormData();
             formData.append('_method', 'patch');
             formData.append('nome', this.$store.state.item.nome);
             if(this.arquivoImagem[0]){
                formData.append('imagem', this.arquivoImagem[0]);
             }

             let url = this.urlBase + '/' + this.$store.state.item.id

             let config = {
                 headers: {
                     'Content-Type': 'multipart/form-data',
                     'Accept': 'application/json',
                     'Authorization': this.token
                 }
             }

             axios.post(url, formData, config)
                .then(response =>{
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = 'Registro de marca atualizado com sucesso'
                    atualizarImagem.value = ''
                    this.carregarLista();
                })
                .catch(errors =>{
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.message
                    this.$store.state.transacao.dados = errors.response.data.errors
                })
         },
         limpar(){
             this.transacaoStatus = ''
         },
         remover(){
             let confirmacao = confirm('Tem certeza que quer remover esse registro?');

            if(!confirmacao){

                return false;

            };

            let formData = new FormData();
            formData.append('_method','delete')

            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            let url = this.urlBase + '/' + this.$store.state.item.id;


            axios.post(url, formData, config)
            .then(response =>{
                this.$store.state.transacao.status = 'sucesso'
                this.$store.state.transacao.mensagem = response.data.msg

                this.carregarLista();
            })
            .catch(errors =>{


                this.$store.state.transacao.status = 'erro'
                this.$store.state.transacao.mensagem = errors.response.data.erro


            })

         },
         pesquisar(){
             let filtro = '';

             for(let chave in this.busca){

                if(this.busca[chave] != '') {

                    if(filtro != ''){
                        filtro += ';';
                    }

                    filtro += chave + ':like:' + this.busca[chave]
                }
             }

             if(filtro != '') {
                 this.urlPaginacao = ''
                 this.urlFiltro = '&filtro=' + filtro
             } else {
                 this.urlFiltro = ''
             }

             this.carregarLista();
         },
         paginacao(l){
             if(l.url){
                //this.urlBase = l.url;
                this.urlPaginacao = l.url.split('?')[1]
                this.carregarLista();
             }

         },
         carregarLista(){

             let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

             axios.get(url)
                .then(response =>{
                    this.marcas = response.data
                })
                .catch(errors =>{
                    console.log(errors)
                })
         },

         carregarImagem(e) {
             this.arquivoImagem = e.target.files
         },
         salvar() {

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
                    this.carregarLista();
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
