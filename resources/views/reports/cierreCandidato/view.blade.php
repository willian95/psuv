@extends("layouts.main")

@section("content")
@php
$candidatos=\App\Models\Candidato::all();
$municipios=\App\Models\Municipio::all();
@endphp
    <div class="d-flex flex-column-fluid" id="content" v-cloak>

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
                        <h3 class="card-label">Estadística cierre de mesa por candidatos</h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <!-- Municipio -->
                        <div class="col-6">
                            <label for="">Candidato</label>
                            <select class="form-control" v-model="candidato_id">
                                @if(Auth::user()->municipio_id)
                                    @foreach($candidatos as $candidato)
                                        @if($candidato->municipio_id==Auth::user()->municipio_id)
                                            <option value="{{$candidato->id}}">{{$candidato->full_name}}</option>
                                        @endif
                                    @endforeach
                                @else
                                <option value="">Seleccione</option>
                                    @foreach($candidatos as $candidato)
                                            <option value="{{$candidato->id}}">{{$candidato->full_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <!-- End municipio -->
                        <!-- candidato -->
                        <div class="col-6">
                            <label for="">Municipio</label>
                            <select class="form-control" v-model="municipio_nombre" @change="obtenerParroquias()">
                                @if(Auth::user()->municipio_id)
                                    @foreach($municipios as $municipio)
                                        @if($municipio->id==Auth::user()->municipio_id)
                                            <option value="{{$municipio->nombre}}">{{$municipio->nombre}}</option>
                                        @endif
                                    @endforeach
                                @else
                                <option value="">Seleccione</option>
                                    @foreach($municipios as $municipio)
                                            <option value="{{$municipio->nombre}}">{{$municipio->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <!-- End candidato -->
                        <!-- Parroquia -->
                        <div class="col-6">
                            <label for="">Parroquia</label>
                            <select class="form-control" v-model="parroquia_nombre" @change="obtenerCentrosVotacion()" >
                                <option value="">Seleccione</option>
                                <option v-for="parroquia in parroquias" :value="parroquia.nombre">
                                    @{{parroquia.nombre}}
                                </option>
                            </select>
                        </div>
                        <!-- End Parroquia -->
                        <!-- Parroquia -->
                        <div class="col-6">
                            <label for="">Centro de votación</label>
                            <select class="form-control" v-model="centro_votacion_nombre" >
                                <option value="">Seleccione</option>
                                <option v-for="centroVotacion in centrosVotacion" :value="centroVotacion.nombre">
                                    @{{centroVotacion.nombre}}
                                </option>
                            </select>
                        </div>
                        <!-- End Parroquia -->
                        <div class="col-12 text-center my-5">
                            <button class="btn btn-success" @click="getCandidatos()">
                                Generar
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="col-12 text-center my-5" v-show="results.length>0">

                        <br>

                        <div id="chartContainer" style="height: 450px; width: 100%;"></div>

                        <br>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Candidato</th>
                                        <th>Cargo</th>
                                        <th>Total de votos</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="result in results">
                                        <td>@{{result.candidato}}</td>
                                        <td>@{{result.cargo_eleccion}}</td>
                                        <td>@{{result.total_votos}}</td>
                                        <td>
                                            <button class="btn btn-info" @click="entity=result;"  data-toggle="modal" data-target=".marketModal">
                                                Detalle
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Body -->


                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

<!-- Modal-->
<div class="modal fade marketModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" v-if="entity">Detalle estadístico del candidato(a): @{{entity.candidato}} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-6">
                            <div class="form-group">
                                <select v-model="tipo_detalle" class="form-control" @change="resultsDetail=[]">
                                    <option value="">Seleccione el tipo de detalle a visualizar</option>
                                    <option value="municipio">Por municipio</option>
                                    <option value="parroquia">Por parroquia</option>
                                    <option value="centro_votacion">Por centro de votación</option>
                                    <option value="mesa">Por mesa</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6 text-center">
                            <button class="btn btn-success" @click="getDetail()">
                                Obtener detalle
                            </button>
                        </div>
                        
                        <div class="col-12" v-if="resultsDetail.length>0">
                            <table class="table table-bordered" id="tableDetail" v-if="tipo_detalle!='mesa'">
                                <thead>
                                    <tr>
                                        <th>
                                            <span class="text-capitalize" v-if="tipo_detalle=='municipio'">Municipio</span>
                                            <span class="text-capitalize" v-if="tipo_detalle=='parroquia'">Parroquia</span>
                                            <span class="text-capitalize" v-if="tipo_detalle=='centro_votacion'">Centro de votación</span>
                                        </th>
                                        <th>Total de votos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="rd in resultsDetail">
                                        <td>@{{rd.categoria}}</td>
                                        <td>@{{rd.total_votos}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered" id="tableDetail2" v-if="tipo_detalle=='mesa'">
                                <thead>
                                    <tr>
                                        <th>
                                            Centro de votación
                                        </th>
                                        <th>
                                            Mesa
                                        </th>
                                        <th>Total de votos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="rd in resultsDetail">
                                        <td>@{{rd.centro_votacion}}</td>
                                        <td>Mesa @{{rd.numero_mesa}}</td>
                                        <td>@{{rd.total_votos}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div> <!-- row-->
                </div> <!-- container fluid-->                    
            </div> <!-- modal body-->   
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" @click="clearForm()">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->
    </div>

@endsection

@push('scripts')
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script type="text/javascript">
    /********* VUE ***********/
    var vue_instance = new Vue({
        el: '#content',
        components: {},
        data: {
            loading: true,
            results:[],
            resultsDetail:[],
            municipios:[],
            parroquias:[],
            centrosVotacion:[],
            municipio_nombre:"{{Auth::user()->municipio ? Auth::user()->municipio->nombre : ''}}",
            candidato_id:"",
            parroquia_nombre:"",
            centro_votacion_nombre:"",
            tipo_detalle:"",
            entity:null
        },
        created: function() {
            this.$nextTick(async function() {
                this.loading = false;
                if(this.municipio_nombre){
                    this.obtenerParroquias();
                }
            });
        },
        methods: {
            async getDetail(){
                this.resultsDetail=[];
                if(this.tipo_detalle==""){
                    swal({
                        text:"Debe seleccionar un tipo de detalle",
                        icon:"error"
                    })
                    return false;
                }
                try {
                    let params={
                        tipo_detalle:this.tipo_detalle,
                        candidato_id:this.entity.candidato_id,
                    };
                    this.loading = true;
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/report/cierre/candidatos/detalle') }}",
                        params: params
                    });

                    this.loading = false;
                    this.resultsDetail = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            clearForm(){
                this.entity=null;
                this.tipo_detalle="";
                this.resultsDetail=[];
            },
            async obtenerCentrosVotacion() {
                try {
                    if(this.parroquia_nombre==""){
                        // swal({
                        //     text:"Debe seleccionar una parroquia",
                        //     icon:"error"
                        // })
                        this.centrosVotacion = [];
                        this.centro_votacion_nombre="";
                        return false;
                    }
                    this.loading = true;
                    let filters = {
                        parroquia_nombre:this.parroquia_nombre,
                     }
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/centro-votacion-busqueda') }}"+"/"+this.parroquia_nombre,
                        params: filters
                    });

                    this.loading = false;
                    this.centrosVotacion = response.data;
                    this.centro_votacion_nombre="";
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerParroquias() {
                try {
                    if(this.municipio_nombre==""){
                        // swal({
                        //     text:"Debe seleccionar un municipio",
                        //     icon:"error"
                        // })
                        this.parroquias = [];
                        this.parroquia_nombre="";
                        this.centrosVotacion = [];
                        this.centro_votacion_nombre="";
                        return false;
                    }
                    this.loading = true;
                    let filters = {
                        municipio_nombre:this.municipio_nombre,
                     }
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/parroquias-busqueda') }}"+"/"+this.municipio_nombre,
                        params: filters
                    });

                    this.loading = false;
                    this.parroquias = response.data;
                    this.parroquia_nombre="";
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

            async getCandidatos(){
                try {
                    let params={
                        candidato_id:this.candidato_id,
                        municipio_nombre:this.municipio_nombre,
                        parroquia_nombre:this.parroquia_nombre,
                        centro_votacion_nombre:this.centro_votacion_nombre,
                    };
                    this.loading = true;
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ url('api/report/cierre/candidatos') }}",
                        params: params
                    });

                    this.loading = false;
                    this.results = response.data;
                    let dataPie=this.results.map(function(obj){
                        let rObj={};
                        rObj.y=obj.total_votos;
                        rObj.label=obj.candidato;
                        return rObj;
                    });
                    console.log(dataPie);

                    var chart = new CanvasJS.Chart("chartContainer", {
	                    theme: "light2", // "light1", "light2", "dark1", "dark2"
	                    exportEnabled: true,
	                    animationEnabled: true,
	                    title: {
		                    text: "Cierre de mesa candidatos"
	                    },
                        exportFileName: "Cierre de mesa candidatos",  
	                    data: [{
		                    type: "pie",
		                    startAngle: 25,
		                    toolTipContent: "{y} votos",
		                    showInLegend: "true",
		                    legendText: "{label}",
		                    indexLabelFontSize: 16,
		                    indexLabel: "{label}",
		                    dataPoints: dataPie
	                    }]
                    });
                    chart.render();
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

            generate(){
                let params={
                    candidato_id:this.candidato_id,
                    municipio_nombre:this.municipio_nombre,
                    parroquia_nombre:this.parroquia_nombre,
                    centro_votacion_nombre:this.centro_votacion_nombre,
                };
                this.loading=true;
                axios({
                    url: `api/report/cierre/candidatos`,
                    method: 'GET',
                    params:params,
                    responseType: 'blob' // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'reporte-cierre-candidatos.xlsx')
                    document.body.appendChild(link)
                    link.click()
                    this.loading=false;
                })
            }//generate()

        } //methods
    });
</script>

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style>

@endpush