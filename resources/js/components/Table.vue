<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key" class="text-uppercase">{{ t.titulo }}</th>
                    <th v-if="atualizar || remover || visualizar" ></th>
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
                    <td v-if="atualizar || remover || visualizar">
                        <button v-if="visualizar" data-bs-toggle="modal" data-bs-target="#modalVizualizarMarca" class="btn btn-outline-primary btn-sm">Visualizar</button>
                        <button v-if="atualizar" class="btn btn-outline-primary btn-sm">Atualizar</button>
                        <button v-if="remover" class="btn btn-outline-danger btn-sm">Remover</button>
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

