@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="content" v-cloak>

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Reporte de carga</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Municipio</label>
                                <select class="form-control" v-model="selectedMunicipio" @change="getParroquias()" :disabled="authMunicipio != 0">
                                    <option value="0">Todos los municipios</option>
                                    <option v-for="municipio in municipios" :value="municipio.id">@{{ municipio.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Parroquia</label>
                                <select class="form-control" v-model="selectedParroquia" @change="getCentroVotacion()">
                                    <option value="0">Todas las parroquias</option>
                                    <option v-for="parroquia in parroquias" :value="parroquia.id">@{{ parroquia.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Centro de Votación</label>
                                <select class="form-control" v-model="selectedCentroVotacion">
                                    <option value="0">Todos los centros de votación</option>
                                    <option v-for="centroVotacion in centrosVotacion" :value="centroVotacion.id">@{{ centroVotacion.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label style="visibility: hidden;">Centro de Votación</label>
                            <button class="btn btn-primary" @click="generate()" v-if="loading == false">Generar gráficas</button>
                            <p class="text-center">
                                <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                            </p>
                        </div>
                        
                    </div>

                    <div class="row" v-show="metaGeneral > 0">
                        <div class="col-12">
                            <p><div id="chart_12" class="d-flex justify-content-center"></div></p>
                            <p class="text-center">@{{ metaGeneral }} / @{{ cargados }}</p>
                        </div>
                    </div>

                    <div class="row" v-show="metaGeneral > 0">
                        <div class="col-12">
                            <h3 class="text-center">Centros de votación</h3>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Centro Votación</th>
                                            <th>Meta</th>
                                            <th>Carga</th>
                                            <th>Pendiente</th>
                                            <th></th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        <tr v-for="centroVotacionMeta in centroVotacionMetas">
                                            <td>@{{ centroVotacionMeta.nombre }}</td>
                                            <td>@{{ centroVotacionMeta.metas_ubchs[0].meta }}</td>
                                            <td>@{{ centroVotacionMeta.personal_caracterizacions.length }}</td>
                                            <td>@{{ centroVotacionMeta.metas_ubchs[0].meta - centroVotacionMeta.personal_caracterizacions.length }}</td>
                                            <td>
                                                <span v-if="(centroVotacionMeta.personal_caracterizacions.length) <=  Math.ceil(centroVotacionMeta.metas_ubchs[0].meta*0.5)" >🔴</span>
                                                <span v-if="(centroVotacionMeta.personal_caracterizacions.length) >  Math.ceil(centroVotacionMeta.metas_ubchs[0].meta*0.5) && (centroVotacionMeta.personal_caracterizacions.length) <=  Math.ceil(centroVotacionMeta.metas_ubchs[0].meta*0.69)">🟠</span>
                                                <span v-if="(centroVotacionMeta.personal_caracterizacions.length) >=  Math.ceil(centroVotacionMeta.metas_ubchs[0].meta*0.7)">🟢</span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>



                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        @include("Listados.rep.partials.modal")

    </div>


@endsection

@push("scripts")

    @include("reporteCarga.scripts")

@endpush