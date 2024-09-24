<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="{{asset('css/tabulator_bootstrap5.min.css')}}" rel="stylesheet">
    <title>Crud de Produtos e Variações</title>
</head>
<body>

    <div class="container p-4 mt-4">
        <div class="card d-flex justify-content-center">
            <h5 class="card-header">Crud de Produtos e Variações</h5>
            <div class="card-body">
                @if(session('success') || $errors->any())
                    <div class="alert alert-dismissible fade show {{ session('success') ? 'alert-success' : 'alert-danger' }}">
                        {{ session('success') ?? "" }}
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="col-auto d-flex justify-content-end">
                    <button type="button" id="btnNovoProduto" class="btn btn-primary mb-3">
                        + Novo
                    </button>
                </div>
                <div class="form-group ">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="showDeleteds">
                        <label class="form-check-label" for="showDeleteds">Mostrar produtos removidos</label>
                    </div>
                </div>
                <div id="products-table" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalProduto" tabindex="-1" aria-labelledby="modalProdutoLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form class="row g-3" method="POST" action="{{route('produtos.store')}}" id="formProduto">
                <div class="modal-header">
                <h5 class="modal-title" id="modalProdutoLabel">Cadastrar/Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="input_id">
                        <div class="row">
                            <div class="col-6">
                                <label for="nome" class="visually-hidden">Nome</label>
                                <input type="text" class="form-control" name="nome" id="input_nome" placeholder="Nome do Produto" required>
                            </div>
                            <div class="col-6">
                                <label for="preco" class="visually-hidden">Preço</label>
                                <input type="text" class="form-control" name="preco" id="input_preco" placeholder="Preço ex: 1234.90" required>
                            </div>
                        </div>
                            <div class="col-12 p-2">
                                <label for="variacoes" class="col-form-label">Variações</label>
                                <select class="form-control js-example-tokenizer" name="variacoes[]" id="input_variacoes" multiple="multiple" style="width: 100%">
                                </select>
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
        </div>
    </div>
    <!-- Fim Modal -->

    <script type="text/javascript" src="{{asset('js/tabulator.min.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            reloadTable(false)
        });

        var table = new Tabulator("#products-table", {
            layout:"fitColumns",
            pagination:"local",
            paginationSize:3,
            paginationSizeSelector:[3,5,10],
            columns:[
                {title:"deleted_at", field:"deleted_at", visible:false},
                {title:"Id", field:"id", width: "10%"},
                {title:"Nome", field:"nome"},
                {title:"Preço", field:"preco", width: 150},
                {title:"Variações", field:"variacoes", formatter: function(cell){
                        let data = cell.getData();
                        let variacoes = data.variacoes.map(function(variacao) {
                            return variacao.nome;
                        }).join(', ');
                        return variacoes;
                    }
                },
                { //editar
                    title: "Ações",
                    formatter: function(cell, formatterParams) {
                        let row = cell.getRow()
                        let rowData = row.getData()
                        let isDeleted = rowData.deleted_at

                        return isDeleted ? "<button class='btn btn-warning btn-sm' id='editButton'>Restaurar</button>"
                                         : "<button class='btn btn-warning btn-sm' id='editButton'>Editar</button";
                    },
                    cellClick: function(e, cell){
                        let row = cell.getRow()
                        let rowData = row.getData()
                        let idProduto = rowData.id
                        let isDeleted = rowData.deleted_at

                        if(isDeleted){
                            $.post("{{url('produtos/restaurar')}}/"+idProduto,{
                                _token: "{{csrf_token()}}"
                            }).done(function(response) {
                                if (response.success) {
                                    reloadTable(true)
                                }
                            });

                            return
                        }

                        $.get("{{url('produtos/listar')}}/"+idProduto, function(response){
                            let produto = response.data

                            $('#input_id').val(produto.id);
                            $('#input_nome').val(produto.nome);
                            $('#input_preco').val(produto.preco);

                            $('#formProduto').attr("action", "{{url('produtos/update')}}/" + produto.id);

                            let selectVariacoes = $('#input_variacoes');
                            selectVariacoes.empty();

                            let data = produto.variacoes.map(variacao => {
                                return {
                                    text:variacao.nome,
                                    selected: true
                                }
                            });

                            selectVariacoes.select2({
                                data: data,
                                dropdownParent: $('#modalProduto'),
                                tags: true,
                                tokenSeparators: [',']
                            }).trigger('change');

                            $("#modalProduto").modal('show')
                        });

                    },
                    width: 100,
                    // hozAlign: "center", 
                    align: "center", 
                    headerSort: false
                },
                {//remover
                    formatter: function(cell, formatterParams) {
                        let row = cell.getRow()
                        let rowData = row.getData()
                        let isDeleted = rowData.deleted_at

                        if(!isDeleted){
                            return "<button class='btn btn-danger btn-sm' id='editButton'>Excluir</button>"
                        }
                    },
                    cellClick: function(e, cell){
                        let row = cell.getRow()
                        let rowData = row.getData()
                        let idProduto = rowData.id

                        $.post("{{url('produtos/remover')}}/"+idProduto,{
                            _token: "{{csrf_token()}}"
                        }).done(function(response) {
                            
                            if (response.success) {
                                $('#showDeleteds').prop('checked', false);
                                reloadTable(false)
                            }
                        });

                    },
                    width: 100,
                    align: "center", 
                    headerSort: false
                }

            ],
        });

        function reloadTable(showDeleteds = false){

            $.get("{{route('produtos.listar')}}", {
                desativados: showDeleteds ? 1 : 0
            }, function(response) {
                let dados = response.data;
                table.setData(dados);
            });
        }

        $(document).on('click', '#showDeleteds', function() {
            let showDeleteds = $('#showDeleteds').is(':checked')
            reloadTable(showDeleteds)
        })

        $(document).on('click', '#btnNovoProduto', function() {
            
            $('#input_id').val("");
            $('#input_nome').val("");
            $('#input_preco').val("");

            $('#formProduto').attr("action", "{{route('produtos.store')}}");

            $('#input_variacoes').empty().trigger('change');

            $("#modalProduto").modal('show')
        })

        $(".js-example-tokenizer").select2({
            dropdownParent: $('#modalProduto'),
            tags: true,
            tokenSeparators: [',']
        })

    </script>
</body>
</html>