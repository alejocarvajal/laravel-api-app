@extends('layouts.app')

@section('content')
<style>
    .tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
</style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header">BÚSQUEDA</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form name="busquedaForm" id="busquedaForm" method="POST">
                            <div class="form-group row">
                                <label class="col-4 col-form-label" for="nombre">Nombre</label>
                                <div class="col-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-address-book"></i>
                                            </div>
                                        </div>
                                        <input id="nombre" name="nombre" placeholder="Ingrese el nombre" type="text"
                                               class="form-control" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="coincidencia" class="col-4 col-form-label">% Coincidencia</label>
                                <div class="col-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-percent"></i>
                                            </div>
                                        </div>
                                        <input id="porcentaje" name="porcentaje" placeholder="% de Coincidencia"
                                               type="number" min="0" max="100" class="form-control" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-4 col-8">
                                    <a id="findName" class="btn btn-info" onclick="findName('{{ route("find") }}')"><i
                                            class="fa fa-search"></i> Buscar</a>
                                    <a id="exportExcel" class="btn btn-success" data-export="Excel"
                                       onclick="exportReport('{{ route("exportReport") }}', 'xlsx', this)"><i
                                            class="fa fa-download"></i> Exportar Excel</a>
                                    <a id="exportPDF" class="btn btn-danger" data-export="PDF"
                                       onclick="exportReport('{{ route("exportReport") }}', 'pdf', this)"><i
                                            class="fa fa-download"></i> Exportar PDF</a>
                                </div>
                            </div>
                        </form>
                        <div id="notificaciones">
                        </div>
                        <div class="table-result col-md-12" style="overflow: scroll; max-height: 500px;">
                            <table class="table tableFixHead" style="display: none;">
                                <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Porcentaje</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Localidad</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Años activo</th>
                                    <th scope="col">Tipo persona</th>
                                    <th scope="col">Tipo cargo</th>
                                </tr>
                                </thead>
                                <tbody id="compare-result">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="{{ URL::asset('js/home.js') }}" type="text/javascript"></script>

