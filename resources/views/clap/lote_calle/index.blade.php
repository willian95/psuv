@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="dev-enlace-municipal" v-cloak>

        <div class="loader-cover-custom" v-if="loading == true">
            <div class="loader-custom"></div>
        </div>

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Nucleo familiar en lote por calle</h3>
                    </div>
                    <div class="card-toolbar">

                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" id="kt_datatable" style="">
                        
                        <form action="{{ url('clap/lote-calle') }}" method="post" enctype="multipart/form-data" id="form">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Municipio</label>
                                        <select class="form-control"v-model="selectedMunicipio" @change="getParroquias()">
                                            <option value="0">Seleccione</option>
                                            <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Parroquia</label>
                                        <select class="form-control"v-model="selectedParroquia" @change="getComunidades()">
                                            <option value="0">Seleccione</option>
                                            <option :value="parroquia.id" v-for="parroquia in parroquias">@{{ parroquia.nombre }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Comunidad</label>
                                        <select class="form-control"v-model="selectedComunidad" @change="getCalles()">
                                            <option value="0">Seleccione</option>
                                            <option :value="comunidad.id" v-for="comunidad in comunidades">@{{ comunidad.nombre }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Calle</label>
                                        <select class="form-control"v-model="selectedCalle" name="calle">
                                            <option value="0">Seleccione</option>
                                            <option :value="calle.id" v-for="calle in calles">@{{ calle.nombre }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Archivo nucleo familiar</label>
                                        <input name="nucleo" type="file" class="form-control">
                                    </div>
                                </div>

                                <div class="row w-100">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-warning font-weight-bold" @click="clearForm()">Limpiar</button>

                                    </div>
                                    <div class="col-6 d-flex justify-content-end">

                                        
                                        <button type="button" :disabled="disabledStoreButton" class="btn btn-success font-weight-bold"  @click="store()" >Crear</button>

                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
        

    </div>

@endsection

@push("scripts")
    @include('clap.lote_calle.script')
@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style

@endpush