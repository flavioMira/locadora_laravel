<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key" class="text-uppercase">{{ t.titulo }}</th>
                    <th v-if="atualizar || remover.visivel || visualizar.visivel" ></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor,chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo =='imagem'">
                            <img :src="'/storage/'+ valor" width="30" height="30">
                        </span>
                        <span v-else>
                            {{ valor }}
                        </span>
                    </td>
                    <td v-if="atualizar || remover.visivel || visualizar.visivel">
                        <button v-if="visualizar.visivel" :data-bs-toggle="visualizar.dataToggle" :data-bs-target="visualizar.dataTarget" class="btn btn-outline-primary btn-sm" @click="setStore(obj)">Visualizar</button>
                        <button v-if="atualizar" class="btn btn-outline-primary btn-sm">Atualizar</button>
                        <button v-if="remover.visivel" :data-bs-toggle="remover.dataToggle" :data-bs-target="remover.dataTarget" class="btn btn-outline-danger btn-sm" @click="setStore(obj)">Remover</button>
                    </td>
                </tr>
                <!--
                <tr v-for="obj in dados" :key="obj.id">
                    <td v-if="titulos.includes(chave)" v-for="valor, chave in obj" :key="chave">
                        <span v-if="chave =='imagem'">
                            <img :src="'/storage/'+ valor" width="30" height="30">
                        </span>
                        <span v-else>
                            {{ valor }}
                        </span>
                    </td>

                    <th scope="row">{{ m.id }}</th>
                    <td>{{ m.nome }}</td>
                    <td><img :src="'/storage/'+  m.imagem"></td>

                </tr>
                -->
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {
        props: ['dados','titulos','visualizar', 'atualizar', 'remover'],
        methods: {
            setStore(obj){
                this.$store.state.item = obj;
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = ''
            }
        },
        computed: {
            dadosFiltrados(){
                let campos = Object.keys(this.titulos);
                let dadosFiltrados = [];

                this.dados.map((item,chave) =>{
                    let itemFiltrado = {};

                    campos.forEach(campo =>{
                        itemFiltrado[campo] = item[campo];
                    });

                    dadosFiltrados.push(itemFiltrado);


                });


                return dadosFiltrados
            }
        }
    }
</script>

