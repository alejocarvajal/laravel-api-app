@extends('layouts.app')

@section('content')

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
                                    <a id="submit" class="btn btn-primary" onclick="findName('{{ route("find") }}')"><i
                                            class="fa fa-search"></i> Buscar</a>
                                </div>
                            </div>
                        </form>
                        <div id="notificaciones">
                        </div>
                        <div class="table-result">
                            <table class="table" style="display: none;">
                                <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Localidad</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Años activo</th>
                                    <th scope="col">Tipo persona</th>
                                    <th scope="col">Tipo cargo</th>
                                    <th scope="col">Porcentaje</th>
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

