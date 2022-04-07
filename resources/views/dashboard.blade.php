@extends("layouts.main")

@section("content")

    <style>

    .round-circle{
        width: 20px; 
        height: 20px;
        border-radius: 50%;
    }

    </style>

    <div class="d-flex flex-column-fluid" id="content" v-cloak>

        <!--begin::Container-->
        <div class="container-fluid" v-cloak>
            <!--begin::Card-->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-control'">
                                    <label for="">Municipio</label>
                                    <select class="form-control" @change="getParroquias()" v-model="selectedMunicipio" :disabled="authMunicipio != 0">
                                        <option value="0">Todos</option>
                                        <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-control'">
                                    <label for="">Parroquia</label>
                                    <select class="form-control" @change="getComunidades()" v-model="selectedParroquia">
                                        <option value="0">Todos</option>
                                        <option :value="parroquia.id" v-for="parroquia in parroquias">@{{ parroquia.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-control'">
                                    <label for="">Comunidad</label>
                                    <select class="form-control" @change="getCalles()" v-model="selectedComunidad">
                                        <option value="0">Todos</option>
                                        <option :value="comunidad.id" v-for="comunidad in comunidades">@{{ comunidad.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-control'">
                                    <label for="">Calle</label>
                                    <select class="form-control" v-model="selectedCalle">
                                        <option value="0">Todos</option>
                                        <option :value="calle.id" v-for="calle in calles">@{{ calle.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                        
                                <p class="text-center"><button class="btn btn-primary mt-8" @click="generate()">Generar gráfica</button></p>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-6">
                                <img src="{{ url('falcon.png') }}" alt="" class="w-100">
                            </div>
                            <div class="col-lg-6">
                                <table class="table">
                                    <tr>
                                        <td># Jefe de familias</td>
                                        <td>@{{ jefesFamilia }}</td>
                                    </tr>
                                    <tr>
                                        <td># Mujeres</td>
                                        <td>@{{ mujeres }}</td>
                                    </tr>
                                    <tr>
                                        <td># Hombres</td>
                                        <td>@{{ hombres }}</td>
                                    </tr>
                                    <tr>
                                        <td># Niños</td>
                                        <td>@{{ ninos }}</td>
                                    </tr>
                                    <tr>
                                        <td># Niñas</td>
                                        <td>@{{ ninas }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Municipio</th>
                                                <th>Sugerido</th>
                                                <th>Casas</th>
                                                <th>Anexos</th>
                                                <th>Habitantes</th>
                                                <th>Familias</th>
                                                <th>Jefes de familia</th>
                                                <th>Mujeres</th>
                                                <th>Hombres</th>
                                                <th>Niños</th>
                                                <th>Niñas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="dat in datas">
                                                <td>@{{ dat.entity }}</td>
                                                <td></td>
                                                <td>@{{ dat.casas }}</td>
                                                <td>@{{ dat.anexos }}</td>
                                                <td>@{{ dat.habitantes }}</td>
                                                <td>@{{ dat.familias }}</td>
                                                <td>@{{ dat.jefesFamilias }}</td>
                                                <td>@{{ dat.mujeres }}</td>
                                                <td>@{{ dat.hombres }}</td>
                                                <td>@{{ dat.ninos }}</td>
                                                <td>@{{ dat.ninas }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--<img src="{{ url('dashboard.png') }}" alt="" class="w-100">--}}
                    </div>
                </div>
          
            <!--end::Card-->
        </div>
        <!--end::Container-->

    </div>

@endsection

@push("scripts")
    @include("scriptDashboard")
@endpush