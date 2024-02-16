<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- início do card de busca -->
                <card-component titulo="Busca de tipo de série">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>                   
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Tipo de Série" id="inputTipoSerie" id-help="tipoSerieHelp" texto-ajuda="Opcional. Informe o tipo de série">
                                    <input type="text" class="form-control" id="inputTipoSerie" aria-describedby="tipoSerieHelp" placeholder="Tipo de Série" v-model="busca.tiposerie">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->


                <!-- início do card de listagem de tipo de serie -->
                <card-component titulo="Relação de tipo de série">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="tiposeries.data"
                            :visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalTipoSerieVisualizar'}"
                            :atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalTipoSerieAtualizar'}"
                            :remover="{visivel: true, dataToggle: 'modal', dataTarget: '#modalTipoSerieRemover'}"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'texto'},
                                tiposerie: {titulo: 'Tipo de Série', tipo: 'texto'},
                                created_at: {titulo: 'Criação', tipo: 'data'},
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in tiposeries.links" :key="key"
                                        :class="l.active ? 'page-item active' : 'page-item'"
                                        @click="paginacao(l)"
                                    >
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>

                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalTipoSerie">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de listagem de tipo de série -->
            </div>
        </div>

        <!-- início do modal de cadastro de tipo de série -->
        <modal-component id="modalTipoSerie" titulo="Adicionar tipo de série">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar o tipo de série" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Tipo de Série" id="novoTipoSerie" id-help="novoTipoSerieHelp" texto-ajuda="Informe o tipo de série">
                        <input type="text" class="form-control" id="novoTipoSerie" aria-describedby="novoTipoSerieHelp" placeholder="Tipo de Série" v-model="nomeTipoSerie">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- fim do modal de cadastro de tipo de série -->

        <!-- início do modal de visualização de tipo de série -->
        <modal-component id="modalTipoSerieVisualizar" titulo="Visualizar tipo de série">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Tipo de Série">
                    <input type="text" class="form-control" :value="$store.state.item.tiposerie" disabled>
                </input-container-component>

                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
        </modal-component>
        <!-- fim do modal de visualização de tipo de série -->

        <!-- início do modal de remoção de tipo de série -->
        <modal-component id="modalTipoSerieRemover" titulo="Remover tipo de série">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Tipo de Série">
                    <input type="text" class="form-control" :value="$store.state.item.tiposerie" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>
        <!-- fim do modal de remoção de tipo de série -->

        <!-- início do modal de atualização de tipo de série -->
        <modal-component id="modalTipoSerieAtualizar" titulo="Atualizar tipo de série">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Tipo de Série" id="atualizarTipoSerie" id-help="atualizarTipoSerieHelp" texto-ajuda="Informe o tipo de série">
                        <input type="text" class="form-control" id="atualizarTipoSerie" aria-describedby="atualizarTipoSerieHelp" placeholder="Tipo de Série" v-model="$store.state.item.tiposerie">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- início do modal de atualização de tipo de série -->

    </div>
</template>

<script>
export default {
    computed: {
            token() {
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                })

                token = token.split('=')[1]
                token = 'Bearer ' + token

                return token
            }
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/tiposerie',
                urlPaginacao: '',
                urlFiltro: '',
                nomeTipoSerie: '',
                transacaoStatus: '',
                transacaoDetalhes: {},
                tiposeries: { data: [] },
                busca: { id: '', tiposerie: '' }
            }
        },
        methods: {
            atualizar(){
                let formData = new FormData();
                formData.append('_method', 'patch')
                formData.append('tiposerie', this.$store.state.item.tiposerie)
                let url = this.urlBase + '/' + this.$store.state.item.id
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Autorization': this.token
                    }
                }
                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'Registro de tipo de série atualizado com sucesso!'
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                    })
            },
            remover() {
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?')
                if(!confirmacao) {
                    return false;
                }
                let formData = new FormData();
                formData.append('_method', 'delete')
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Autorization': this.token
                    }
                }
                let url = this.urlBase + '/' + this.$store.state.item.id
                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.erro
                    })
            },
            pesquisar() {
                //console.log(this.busca)
                let filtro = ''
                for(let chave in this.busca) {
                    if(this.busca[chave]) {
                        //console.log(chave, this.busca[chave])
                        if(filtro != '') {
                            filtro += ";"
                        }
                        filtro += chave + ':like:' + this.busca[chave]
                    }
                }
                if(filtro != '') {
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro='+filtro
                } else {
                    this.urlFiltro = ''
                }
                this.carregarLista()
            },
            paginacao(l){
                if(l.url) {
                    //this.urlBase = l.url //ajustando a url de consulta com o parâmetro de página
                    this.urlPaginacao = l.url.split('?')[1]
                    this.carregarLista() //requisitando novamente os dados para nossa API
                }
            },
            carregarLista(){
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro
                console.log(url)
                axios.get(url, config)
                    .then(response => {
                        this.tiposeries = response.data
                        //console.log(this.tiposeries)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            salvar() {
                console.log(this.nomeTipoSerie)

                let formData = new FormData();
                formData.append('tiposerie', this.nomeTipoSerie)

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = response
                        this.carregarLista()
                        console.log(response)
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = errors.response
                        console.log(errors)
                    })
            }
        },
        mounted() {
            this.carregarLista()
        }
}
</script>