@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="dev-ubch" v-cloak>

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
                        <h3 class="card-label">UBCH</h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <button style="cursor: pointer;" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target=".marketModal" @click="create()">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                    <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>Nuevo Jefe de UBCH</button>
                        <!--end::Button-->

                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded" id="kt_datatable" style="">
                        
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="float-right">
                                        <div class="form-group">
                                            <label>Buscar</label>
                                            <div class="d-flex">
                                                <input class="form-control" placeholder="cedula" @keypress="isNumber($event)" v-model="cedulaSearch">
                                                <button class="btn btn-primary" v-if="!searchLoading" @click="search()">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <div class="spinner spinner-primary ml-1 mr-13" v-if="searchLoading"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>UBCH</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Cédula</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Nombre</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Teléfono</span>
                                        </th>

                                        <th class="datatable-cell datatable-cell-sort">
                                            <span>Acción</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="jefeUbch in jefesUbch">
                                        <td>@{{ jefeUbch.centro_votacion.nombre }}</td>
                                        <td>@{{ jefeUbch.personal_caracterizacion.cedula }}</td>
                                        <td>@{{ jefeUbch.personal_caracterizacion.nombre_apellido }}</td>
                                        <td>@{{ jefeUbch.personal_caracterizacion.telefono_principal }}</td>

                                        <td>
                                            <button class="btn btn-success" data-toggle="modal" data-target=".marketModal" @click="edit(jefeUbch, jefeUbch.personal_caracterizacion, jefeUbch.id)">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button class="btn btn-secondary" @click="remove(jefeUbch.id)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="kt_datatable_info" role="status" aria-live="polite">Mostrando página @{{ currentPage }} de @{{ totalPages }}</div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_full_numbers" id="kt_datatable_paginate">
                                    <ul class="pagination">
                                        
                                        <li class="paginate_button page-item active" v-for="(link, index) in links">
                                            <a style="cursor: pointer" aria-controls="kt_datatable" tabindex="0" :class="link.active == false ? linkClass : activeLinkClass":key="index" @click="fetch(link)" v-html="link.label.replace('Previous', 'Anterior').replace('Next', 'Siguiente')"></a>
                                        </li>
                                        
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        @include("RAAS.jefeUbch.ubchPartials.modalFormCreateEditSuspend")


    </div>

@endsection

@push("scripts")

<script type="text/javascript">

var sync = false;
var date = "";

const app = new Vue({
    el: '#dev-ubch',
    data() {
        return {

            linkClass:"page-link",
            activeLinkClass:"page-link active-link bg-main",

            readonlyState:false,
            readonlyCentroVotacion:false,
            readonlyCedula:false,
            cedulaSearching:false,
            storeLoader:false,
            updateLoader:false,
            suspendLoader:false,

            selectedId:"",
            action:"create",
            errors:[],
            loading:false,
            searchLoading:false,

            estados:[],
            selectedEstado:"",
            cedulaSearch:"",
            municipios:[],
            selectedMunicipio:"",
            parroquias:[],
            selectedParroquia:"",
            centroVotaciones:[],
            selectedCentroVotacion:"",
            partidosPoliticos:[],
            selectedPartidoPolitico:"",
            movilizaciones:[],
            selectedMovilizacion:"",
            selectedEstado:"",
            jefesUbch:[],
            readonlyMunicipio:false,
            readonlyParroquia:false,

            cedula:"",
            nombre:"",
            telefonoPrincipal:"",
            telefonoSecundario:"",
            tipoVoto:"",
            fechaNacimiento:"",
            nacionalidad:"V",
            primerNombre:"",
            segundoNombre:"",
            primerApellido:"",
            segundoApellido:"",
            sexo:"masculino",

            modalTitle:"Crear Jefe de UBCH",
            currentPage:1,
            links:"",
            totalPages:"",
        }
    },
    methods: {

        create(){
            this.selectedId = ""
            this.action = "create"
            this.modalTitle = "Crear Jefe de UBCH",
            this.selecteMunicipio = ""
            this.selectedParroquia = ""
            this.selectedCentroVotacion = ""
            this.selectedPartidoPolitico = ""
            this.selectedMovilizacion = "",
            this.cedula=""
            this.nombre=""
            this.telefonoPrincipal=""
            this.telefonoSecundario=""
            this.tipoVoto=""
            this.municipioId = ""
            this.errors = []

            this.readonlyState = false
            this.readonlyMunicipio = false
            this.readonlyParroquia = false
            this.readonlyCentroVotacion = false

        },
        async edit(jefeUbch, elector, jefeId){

            this.readonlyState = true
            this.readonlyMunicipio = true
            this.readonlyParroquia = true
            this.readonlyCentroVotacion = true

            await this.getEstados();
            this.selectedEstado = elector.raas_estado_id

            await this.getMunicipios();
            this.selectedMunicipio = elector.raas_municipio_id

            this.selectedId = jefeId
            this.action = "edit"
            this.modalTitle = "Editar Jefe de UBCH",
            this.cedula = elector.cedula

            this.nombre = elector.nombre_apellido
            await this.getParroquias()

            this.selectedParroquia = jefeUbch.centro_votacion.raas_parroquia_id
            await this.getCentroVotacion()

            this.selectedCentroVotacion = jefeUbch.centro_votacion.id

            this.telefonoPrincipal = elector.telefono_principal
            this.telefonoSecundario = elector.telefono_secundario
            this.fechaNacimiento = elector.fecha_nacimiento
            this.tipoVoto = elector.tipo_voto
            
            
            this.nacionalidad = elector.nacionalidad
            this.nombre = elector.nombre_apellido

           
            this.sexo = elector.sexo
            this.selectedPartidoPolitico = elector.elecciones_partido_politico_id
            this.selectedMovilizacion = elector.elecciones_movilizacion_id
            


        },
        clearForm(){
            this.create()
        },
        async searchCedula(){

            if(this.cedula == ""){
                swal({
                    text:"Debes ingresar una cédula",
                    icon:"error"
                })

                this.cedula = ""

                return
            }

            this.cedulaSearching = true

            let res = await axios.post("{{ url('raas/ubch/search-by-cedula') }}", {cedula: this.cedula, nacionalidad: this.nacionalidad, municipio_id: "{{ \Auth::user()->municipio_id }}"})

            if(res.data.success == false){
                this.readonlyCedula = false
                this.create()
                swal({
                    text:res.data.msg,
                    icon:"error"
                })
            }
            else{
                
                if(this.action == "edit"){
                    this.setElectorDataEdit(res.data.elector)
                }else{
                    this.setElectorData(res.data.elector)
                }
                

            }

            this.cedulaSearching = false

        },  
        async setElectorData(elector){

            await this.getEstados();
            this.selectedEstado = elector.raas_estado_id

            await this.getMunicipios();

            this.nombre = elector.nombre_apellido
            this.selectedMunicipio = elector.raas_municipio_id
            await this.getParroquias()

            this.selectedParroquia = elector.raas_parroquia_id
            await this.getCentroVotacion()

            this.selectedCentroVotacion = elector.raas_centro_votacion_id

            this.telefonoPrincipal = elector.telefono_principal
            this.telefonoSecundario = elector.telefono_secundario
            this.fechaNacimiento = elector.fecha_nacimiento
            this.tipoVoto = elector.tipo_voto

            this.nacionalidad = elector.nacionalidad ? elector.nacionalidad : this.nacionalidad

            this.sexo = elector.sexo
            this.selectedPartidoPolitico = elector.partido_politico_id
            this.selectedMovilizacion = elector.movilizacion_id

        },  
        async setElectorDataEdit(elector){

            this.nombre = elector.nombre_apellido

            this.telefonoPrincipal = elector.telefono_principal
            this.telefonoSecundario = elector.telefono_secundario
            this.fechaNacimiento = elector.fecha_nacimiento
            this.tipoVoto = elector.tipo_voto
            this.selectedEstado = elector.raas_estado_id
            this.nacionalidad = elector.nacionalidad ? elector.nacionalidad : this.nacionalidad

            this.sexo = elector.sexo
            this.selectedPartidoPolitico = elector.partido_politico_id
            this.selectedMovilizacion = elector.movilizacion_id

            await this.getEstados();
            this.selectedEstado = elector.raas_estado_id

            await this.getMunicipios();

            this.nombre = elector.nombre_apellido
            this.selectedMunicipio = elector.raas_municipio_id
            await this.getParroquias()

            this.selectedParroquia = elector.raas_parroquia_id
            await this.getCentroVotacion()

            this.selectedCentroVotacion = elector.raas_centro_votacion_id

        },  
        
        async fetch(link = ""){

            let res = await axios.get(link == "" ? "{{ url('api/raas/ubch/fetch') }}"+"?_token={{ csrf_token() }}" : link.url+"&_token={{ csrf_token() }}")
            this.jefesUbch = res.data.data
            this.links = res.data.links
            this.currentPage = res.data.current_page
            this.totalPages = res.data.last_page


        },
        async store(){

            try{
                
                this.errors = []
                this.storeLoader = true

                let res = await axios.post("{{ url('api/raas/ubch/store') }}", {cedula: this.cedula, nacionalidad: this.nacionalidad, nombre_apellido: this.nombre, sexo: this.sexo, telefono_principal: this.telefonoPrincipal, telefono_secundario: this.telefonoSecundario, tipo_voto: this.tipoVoto, estado_id: this.selectedEstado, municipio_id: this.selectedMunicipio, parroquia_id: this.selectedParroquia, centro_votacion_id: this.selectedCentroVotacion, partido_politico_id: this.selectedPartidoPolitico, movilizacion_id: this.selectedMovilizacion, fecha_nacimiento: this.fechaNacimiento, estado_id: this.selectedEstado})
                
                this.storeLoader = false

                if(res.data.success == true){

                    swal({
                        text:res.data.msg,
                        icon: "success"
                    }).then(ans => {

                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()
                      

                    })

                    this.fetch(this.page)

                }else{

                    swal({
                        text:res.data.msg,
                        icon: "error"
                    })

                }

            }catch(err){
                this.storeLoader = false
                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.errors = err.response.data.errors

            }
            this.storeLoader = false

        },

        async update(){

            try{
                
                this.errors = []
                this.updateLoader = true

                let res = await axios.post("{{ url('api/raas/ubch/update') }}", {cedula: this.cedula, nacionalidad: this.nacionalidad, nombre_apellido: this.nombre, sexo: this.sexo, telefono_principal: this.telefonoPrincipal, telefono_secundario: this.telefonoSecundario, tipo_voto: this.tipoVoto, estado_id: this.selectedEstado, municipio_id: this.selectedMunicipio, parroquia_id: this.selectedParroquia, centro_votacion_id: this.selectedCentroVotacion, partido_politico_id: this.selectedPartidoPolitico, movilizacion_id: this.selectedMovilizacion, fecha_nacimiento: this.fechaNacimiento, id: this.selectedId})
                
                this.updateLoader = false

                if(res.data.success == true){

                    swal({
                        text:res.data.msg,
                        icon: "success"
                    }).then(ans => {

                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()
                      

                    })

                    this.fetch(this.page)

                }else{
                    this.updateLoader = false
                    swal({
                        text:res.data.msg,
                        icon: "error"
                    })

                }

            }catch(err){
                this.updateLoader = false
                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.errors = err.response.data.errors

            }
            this.updateLoader = false

        },

        async remove(id){

            swal({
                title: "¿Estás seguro?",
                text: "Eliminarás este jefe de UBCH!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(async (willDelete) => {

                if (willDelete) {

                    try{

                        let res = await axios.post("{{ url('api/raas/ubch/suspend') }}", {id: id})

                        if(res.data.success == true){

                            swal({
                                text:res.data.msg,
                                icon: "success"
                            })

                            this.fetch(this.page)

                        }else{

                            swal({
                                text:res.data.msg,
                                icon: "error"
                            })

                        }

                    }catch(err){

                        swal({
                            text:"Hay algunos campos que debes revisar",
                            icon: "error"
                        })

                        this.errors = err.response.data.errors

                    }

                }
                


            })

        },
        async getEstados(){

            this.selecteMunicipio = ""
            this.selectedParroquia = ""
            this.selectedCentroVotacion = ""

            let res = await axios.get("{{ url('/api/estados') }}")
            this.estados = res.data

        },
        
        async getMunicipios(){
            this.municipios =[]
            this.selectedParroquia = ""
            this.selectedCentroVotacion = ""

            let res = await axios.get("{{ url('/api/municipios') }}"+"/"+this.selectedEstado)
            this.municipios = res.data

        },
        async getParroquias(){

            if(this.selectedMunicipio){
                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data
            }
                
            

        },

        async getCentroVotacion(){

            if(this.selectedParroquia){
                let res = await axios.get("{{ url('/api/centro-votacion') }}"+"/"+this.selectedParroquia)
                this.centroVotaciones = res.data
            }

        },

        async getPartidosPoliticos(){

            let res = await axios.get("{{ url('/api/partidos-politicos') }}")
            this.partidosPoliticos = res.data

        },

        async getMovilizaciones(){

            let res = await axios.get("{{ url('/api/movilizacion') }}")
            this.movilizaciones = res.data

        },
        isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },
        async search(){

            this.searchLoading = true
            let res = await axios.get("{{ url('/api/raas/ubch/search') }}",  {"params": {
                    "search": this.cedulaSearch,
                    "_token": "{{ csrf_token() }}"
            }})

            this.searchLoading = false

            this.jefesUbch = res.data.data
            this.links = res.data.links
            this.currentPage = res.data.current_page
            this.totalPages = res.data.last_page
            

        }
        

    },
    created() {

        this.getEstados()
        this.getPartidosPoliticos()
        this.getMovilizaciones()
        this.fetch()

    }
});
</script>

@endpush

@push("styles")

    <style>

        .active-link{
            background-color:#c0392b !important;
        }

    </style

@endpush