@extends('layouts.app')

@section('title', 'Editar Fruta')

@section('content')
    <div class="pagetitle">
        <h1>Editar Fruta</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Frutas</li>
                <li class="breadcrumb-item"><a href="{{ route('fruta.mostrar') }}">Ver Frutas</a></li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Modificar Fruta</h5>

                        {{-- Validaciones --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('fruta.update', $fruta->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label">Nombre Fruta</label>
                                <div class="col-sm-12">
                                    <input type="text" name="nombrefruta" class="form-control" value="{{ old('nombrefruta', $fruta->nombrefruta) }}">
                                </div>
                            </div>

                            {{-- Imagen actual --}}
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label">Imagen Actual</label>
                                <div class="col-sm-12">
                                    <img src="{{ asset($fruta->imgfruta) }}" alt="{{ $fruta->nombrefruta }}" width="100">
                                </div>
                            </div>

                            {{-- Nueva Imagen (opcional) --}}
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label">Cambiar Imagen (opcional)</label>
                                <div class="col-sm-10">
                                   <input type="file" class="form-control" name="imgfruta" accept=".svg,.png,.jpg,.jpeg">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
                                    <a href="{{ route('fruta.mostrar') }}" class="btn btn-secondary">CANCELAR</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
