<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- início do card de busca -->
                <card-component titulo="Busca de editoras">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>                   
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Editora" id="inputEditora" id-help="editoraHelp" texto-ajuda="Opcional. Informe a editora">
                                    <input type="text" class="form-control" id="inputEditora" aria-describedby="editoraHelp" placeholder="Editora" v-model="busca.editora">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->


                <!-- início do card de listagem de editoras -->
                <card-component titulo="Relação de editoras">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="editoras.data"
                            :visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalEditoraVisualizar'}"
                            :atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalEditoraAtualizar'}"
                            :remover="{visivel: true, dataToggle: 'modal', dataTarget: '#modalEditoraRemover'}"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'texto'},
                                editora: {titulo: 'Editora', tipo: 'texto'},
                                logotipo: {titulo: 'Logotipo', tipo: 'imagem'},
                                created_at: {titulo: 'Criação', tipo: 'data'},
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in editoras.links" :key="key"
                                        :class="l.active ? 'page-item active' : 'page-item'"
                                        @click="paginacao(l)"
                                    >
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>

                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalEditora">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de listagem de editoras -->
            </div>
        </div>

        <!-- início do modal de cadastro de editoras -->
        <modal-component id="modalEditora" titulo="Adicionar editora">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a editora" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Editora" id="novoEditora" id-help="novoEditoraHelp" texto-ajuda="Informe a editora">
                        <input type="text" class="form-control" id="novoEditora" aria-describedby="novoEditoraHelp" placeholder="Editora" v-model="nomeEditora">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Logotipo" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG ou JPG/JPEG">
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- fim do modal de cadastro de editoras -->

        <!-- início do modal de visualização de editora -->
        <modal-component id="modalEditoraVisualizar" titulo="Visualizar editora">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Editora">
                    <input type="text" class="form-control" :value="$store.state.item.editora" disabled>
                </input-container-component>

                <input-container-component titulo="Logotipo">
                    <img :src="'storage/'+$store.state.item.logotipo" v-if="$store.state.item.logotipo">
                </input-container-component>

                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
        </modal-component>
        <!-- fim do modal de visualização de editora -->

        <!-- início do modal de remoção de editora -->
        <modal-component id="modalEditoraRemover" titulo="Remover editora">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Editora">
                    <input type="text" class="form-control" :value="$store.state.item.editora" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>
        <!-- fim do modal de remoção de editora -->

        <!-- início do modal de atualização de editora -->
        <modal-component id="modalEditoraAtualizar" titulo="Atualizar editora">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Editora" id="atualizarEditora" id-help="atualizarEditoraHelp" texto-ajuda="Informe a editora">
                        <input type="text" class="form-control" id="atualizarEditora" aria-describedby="atualizarEditoraHelp" placeholder="Editora" v-model="$store.state.item.editora">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Logotipo" id="atualizarImagem" id-help="atualizarImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG ou JPG/JPEG">
                        <input type="file" class="form-control-file" id="atualizarImagem" aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- início do modal de atualização de editora -->

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
                urlBase: 'http://localhost:8000/api/v1/editora',
                urlPaginacao: '',
                urlFiltro: '',
                nomeEditora: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                editoras: { data: [] },
                busca: { id: '', editora: '' }
            }
        },
        methods: {
            atualizar(){
                let formData = new FormData();
                formData.append('_method', 'patch')
                formData.append('editora', this.$store.state.item.editora)
                if(this.arquivoImagem[0]) {
                    formData.append('logotipo', this.arquivoImagem[0])
                }
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
                        this.$store.state.transacao.mensagem = 'Registro de editora atualizado com sucesso!'
                        //limpar o campo de seleção de arquivos
                        atualizarImagem.value = ''
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
                        this.editoras = response.data
                        //console.log(this.editoras)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            salvar() {
                console.log(this.nomeEditora, this.arquivoImagem[0])

                let formData = new FormData();
                formData.append('editora', this.nomeEditora)
                formData.append('logotipo', this.arquivoImagem[0])

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
