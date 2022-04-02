<script type="text/javascript">

var sync = false;
var date = "";

const app = new Vue({
    el: '#dev-enlace-municipal',
    data() {
        return {

            action:'create',

            linkClass:"page-link",
            activeLinkClass:"page-link active-link bg-main",

            calleAction:"create",
            calleLoading:false,
            calleNombre:"",
            tipoCasa:"",
            numeroFamilias:"",
            calleId:"",

            errors:[],
            municipios:[],
            partidosPoliticos:[],
            movilizaciones:[],
            enlacesMunicipales:[],
            jefeCalles:[],
            parroquias:[],
            calles:[],
            jefeFamilias:[],
            estatus:[],
            selectedCasa:"",
            casas:[],
            showCasas:"",

            modalTitle:"Crear jefe de familia",
            disabledStoreButton:true,
            readonlyJefeComunidad:false,
            readonlyClapComunidad:false,
            readonlyJefeCalle:false,

            id:"",
            isSearchingCedula:false,
            storeLoader:false,
            updateLoader:false,
            searchLoading:false,
            suspendLoader:false,
            loading:false,

            jefeCalleId:"",
            jefeCalleNacionalidad:"V",
            jefeCalleCedula:"",
            jefeCalleNombre:"",
            nombreCalle:"",
            direccion:"",
            fechaNacimiento:"",
            
            nacionalidad:"V",
            cedula:"",
            nombre:"",
            sexo:"M",
            telefonoPrincipal:"",
            telefonoSecundario:"",
            tipoVoto:"",
            partidoPolitico:"",
            searchCedulaQuery:"",
            
            selectedMunicipio:"",
            selectedClapCalle:"",
            selectedEstado:"",
            selectedParroquia:"",
            selectedClapComunidad:"",
            selectedComunidad:"",
            selectedCentroVotacion:"",
            selectedPartidoPolitico:"",
            selectedMovilizacion:"",
            selectedEstatus:"",

            totalPages:"",
            links:[],
            currentPage:"", 

            nucleoFamiliarErrors:[],
            nucleoFamiliarJefeFamiliaNombre:"",
            nucleoFamiliarJefeFamiliaId:"",
            nucleoFamiliarTipoPersona:"",
            nucleoFamiliarNacionalidad:"",
            nucleoFamiliarCedula:"",
            nucleoFamiliarNombre:"",
            nucleoFamiliarSexo:"",
            nucleoFamiliarFechaNacimiento:"",
            nucleoFamiliarEstatusPersonal:"",
            nucleoFamiliarTelefonoPrincipal:"",
            nucleoFamiliarTelefonoSecundario:"",
            nucleoFamiliarPartido:"",
            nucleoFamiliarMovilizacion:"",
            nucleoFamiliarRaasEstadoId:"",
            nucleoFamiliarRaasMunicipioId:"",
            nucleoFamiliarRaasParroquiaId:"",
            nucleoFamiliarRaasCentroVotacionId:"",
            nucleoFamiliares:[]

        }
    },
    methods: {

        async getPartidosPoliticos(){

            let res = await axios.get("{{ url('/api/partidos-politicos') }}")
            this.partidosPoliticos = res.data

        },
        async getMovilizaciones(){

            let res = await axios.get("{{ url('/api/movilizacion') }}")
            this.movilizaciones = res.data

        },

        async getCalles(){

            if(this.selectedClapComunidad){
                let res = await axios.get("{{ url('/api/calles') }}"+"?comunidad_id="+this.selectedClapComunidad)
                this.calles = res.data
            }

        },

        async searchJefeCalleByCedula(){

            const response = await axios.post("{{ url('/api/clap/jefe-calle-clap/search-jefe-by-cedula') }}", {
                "cedula": this.jefeCalleCedula,
                "nacionalidad": this.jefeCalleNacionalidad
            })

            if(response.data.success == false){
                swal({
                    text:response.data.message,
                    icon:'error'
                })

                return
            }

            this.jefeCalleCedula = response.data?.jefe?.personal_caracterizacions?.cedula
            this.jefeCalleId = response.data?.jefe?.id
            this.jefeCalleNacionalidad = response.data?.jefe?.personal_caracterizacions?.nacionalidad
            this.jefeCalleNombre = response.data?.jefe?.personal_caracterizacions?.nombre_apellido
            this.calleNombre = response.data?.jefe?.calle?.nombre
            this.calleId = response.data?.jefe?.calle?.id

            this.getCasas()

        },

        async searchCedula(){

            if(this.cedula == ""){

                swal({
                    text:"Debes ingresar una cédula",
                    icon:"error"
                })

                return
            }

            this.isSearchingCedula = true

            let res = await axios.post("{{ url('api/clap/jefe-calle-clap/search-by-cedula') }}", {cedula: this.cedula, nacionalidad: this.nacionalidad})
            
            this.isSearchingCedula = false
            this.disabledStoreButton = false

            if(res.data.success == false){

                swal({
                    text:"Esta cédula no está registrada en el CNE, sin embargo puedes añadir a la persona",
                    icon:"warning"
                })

                return
            }

            this.setElectorData(res.data.elector)

        },  

        create(){
            
            this.modalTitle = "Crear Jefe de familia"
            this.id = ""
            this.disabledStoreButton = true
            this.action = "create"
            this.readonlyJefeCalle = false
            this.jefeCalleCedula = ""
            this.jefeCalleNombre = ""
            this.calleNombre = ""
            this.tipoCasa = ""
            this.numeroFamilias = ""
            this.direccion = ""
            this.fechaNacimiento = ""
            this.selectedEstatus = ""
            this.selectedCasa = ""
            this.errors = []
            
            this.clearForm()
            
        },

        clearForm(clearCedula = true){
            
            if(clearCedula == true){
                this.cedula = ""
                this.nacionalidad = ""
            }
            
            this.nombre = ""
            this.sexo = ""
            this.telefonoPrincipal = ""
            this.telefonoSecundario = ""
            this.tipoVoto = ""
            this.partidoPolitico = ""
            this.selectedMovilizacion = ""
            this.direccion = ""
            this.numeroFamilias = ""
            this.tipoCasa = ""
            this.calleNombre = ""
            this.jefeCalleNombre = ""
            this.jefeCalleNacionalidad = ""
            this.jefeCalleCedula = ""

        },

        async setElectorData(elector){

            this.selectedEstado = elector.raas_estado_id

            this.nombre = elector.nombre_apellido
            this.selectedMunicipio = elector.raas_municipio_id
            this.selectedParroquia = elector.raas_parroquia_id

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

        async store(){

            try{
                
                this.errors = []
                this.storeLoader = true
                
                let res = await axios.post("{{ url('api/clap/jefe-familia-clap') }}", {
                    cedula: this.cedula, 
                    nacionalidad: this.nacionalidad, 
                    nombre_apellido: this.nombre, 
                    sexo: this.sexo, 
                    telefono_principal: 
                    this.telefonoPrincipal, 
                    telefono_secundario: this.telefonoSecundario, 
                    tipo_voto: this.tipoVoto, 
                    raas_estado_id: this.selectedEstado, 
                    raas_municipio_id: this.selectedMunicipio, 
                    raas_parroquia_id: this.selectedParroquia, 
                    raas_centro_votacion_id: this.selectedCentroVotacion, 
                    partido_politico_id: this.selectedPartidoPolitico, 
                    movilizacion_id: this.selectedMovilizacion,
                    tipoCasa: this.tipoCasa,
                    jefeCalleId: this.jefeCalleId,
                    fecha_nacimiento: this.fechaNacimiento,
                    raas_estatus_personal_id: this.selectedEstatus,
                    numeroFamilia:this.numeroFamilias,
                    direccion:this.direccion,
                    selectedCasa: this.selectedCasa

                })

                this.storeLoader = false

                if(res.data.success == true){

                    swal({
                        text:res.data.message,
                        icon: "success"
                    }).then(ans => {

                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()
                    
                        this.fetch()
                    })


                }else{

                    swal({
                        text:res.data.message,
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
                
                let res = await axios.put("{{ url('api/clap/jefe-familia-clap') }}"+"/"+this.id, {
                    cedula: this.cedula, 
                    nacionalidad: this.nacionalidad, 
                    nombre_apellido: this.nombre, 
                    sexo: this.sexo, 
                    telefono_principal: 
                    this.telefonoPrincipal, 
                    telefono_secundario: this.telefonoSecundario, 
                    tipo_voto: this.tipoVoto, 
                    raas_estado_id: this.selectedEstado, 
                    raas_municipio_id: this.selectedMunicipio, 
                    raas_parroquia_id: this.selectedParroquia, 
                    raas_centro_votacion_id: this.selectedCentroVotacion, 
                    partido_politico_id: this.selectedPartidoPolitico, 
                    movilizacion_id: this.selectedMovilizacion,
                    fecha_nacimiento:this.fechaNacimiento,
                    raas_estatus_personal_id: this.selectedEstatus,
                    numeroFamilia:this.numeroFamilias,
                    direccion:this.direccion,
                })

                this.updateLoader = false

                if(res.data.success == true){

                    swal({
                        text:res.data.message,
                        icon: "success"
                    }).then(ans => {

                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()
                    
                        this.fetch()
                    })


                }else{

                    swal({
                        text:res.data.message,
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
        async storeCalle(){

            if(this.calleNombre == ""){

                swal({
                    text:"Debes agregar el nombre de la calle",
                    icon: "error"
                })

                return 
            }

            let form = {
                "raas_comunidad_id":this.selectedClapComunidad,
                "nombre":this.calleNombre,
                "tipo":"---",
                "sector":"---"
            }

            const response = await axios({
                method: 'POST',
                responseType: 'json',
                url: "{{ route('api.calles.store') }}",
                data: form
            });
            this.loading = false;
            swal({
                text:response.data.message,
                icon: "success"
            }).then(ans => {
                $('.calleModal').modal('hide')
                $('.modal-backdrop').remove()

                this.getCalles()

            })

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
        async fetch(link = "{{ url('api/clap/jefe-familia-clap/index') }}"){


            this.searchLoading = true

            const res = await axios.get(link, {
                params:{
                    "searchCedula": this.searchCedulaQuery
                }
            })

            this.searchLoading = false

            this.jefeFamilias = res.data.data
            this.links = res.data.links
            this.currentPage = res.data.current_page
            this.totalPages = res.data.last_page

        },

        async remove(id){

            swal({
                title: "¿Estás seguro?",
                text: "Eliminarás este Jefe de familia!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(async (willDelete) => {

                if (willDelete) {

                    let res = await axios.delete("{{ url('api/clap/jefe-familia-clap') }}"+"/"+id)

                    if(res.data.success == true){

                        swal({
                            text:res.data.message,
                            icon: "success"
                        })

                        this.fetch()

                    }else{

                        swal({
                            text:res.data.message,
                            icon: "error"
                        })

                    }

                }

            })
        },

        async edit(jefe){

            this.errors = []
            this.readonlyJefeCalle = true
            this.action = "edit"
            this.modalTitle = "Editar Jefe familia"
            this.id = jefe.id

            this.jefeCalleId = jefe.jefe_calle.id
            this.jefeCalleNacionalidad  = jefe.jefe_calle.personal_caracterizacions.nacionalidad
            this.jefeCalleCedula  = jefe.jefe_calle.personal_caracterizacions.cedula
            this.jefeCalleNombre  = jefe.jefe_calle.personal_caracterizacions.nombre_apellido
            this.calleNombre = jefe.jefe_calle.calle.nombre
            this.calleId = jefe.jefe_calle.calle.id

            await this.getCasas()

            this.tipoCasa = jefe.vivienda.tipo_vivienda
            this.numeroFamilias = jefe.vivienda.cantidad_familias
            this.selectedCasa = jefe.vivienda.vivienda_id
            this.direccion = jefe.vivienda.direccion

            const personalCaracterizacion = jefe.personal_caracterizacion

            this.cedula = personalCaracterizacion.cedula
            this.selectedEstado = personalCaracterizacion.raas_estado_id
            this.selectedEstatus = personalCaracterizacion.raas_estatus_personal_id

            this.nombre = personalCaracterizacion.nombre_apellido

            this.selectedCentroVotacion = personalCaracterizacion.raas_centro_votacion_id

            this.telefonoPrincipal = personalCaracterizacion.telefono_principal
            this.telefonoSecundario = personalCaracterizacion.telefono_secundario
            this.fechaNacimiento = personalCaracterizacion.fecha_nacimiento
            this.tipoVoto = personalCaracterizacion.tipo_voto

            this.nacionalidad = personalCaracterizacion.nacionalidad ? personalCaracterizacion.nacionalidad : this.nacionalidad

            this.sexo = personalCaracterizacion.sexo
            this.selectedPartidoPolitico = personalCaracterizacion.partido_politico_id
            this.selectedMovilizacion = personalCaracterizacion.movilizacion_id

        },

        async getEstatusPersonal(){

            const response = await axios.get("{{ url('api/clap/jefe-familia-clap/estatus-personal') }}")
            this.estatus = response.data

        },

        async getCasas(){

            const response = await axios.get("{{ url('api/clap/jefe-familia-clap/get-casas') }}"+"/"+this.calleId)
            this.casas = response.data

        },

        showNucleoFamiliar(jefe){

            this.nucleoFamiliarErrors = []
            this.nucleoFamiliarJefeFamiliaId = jefe.id
            this.nucleoFamiliarJefeFamiliaNombre = jefe.personal_caracterizacion.nombre_apellido

            this.nucleoFamiliarTipoPersona = ""
            this.nucleoFamiliarCedula = ""
            this.nucleoFamiliarNombre = ""
            this.nucleoFamiliarNacionalidad = ""
            this.nucleoFamiliarSexo = ""
            this.nucleoFamiliarFechaNacimiento = ""
            this.nucleoFamiliarEstatusPersonal = ""
            this.nucleoFamiliarTelefonoPrincipal = ""
            this.nucleoFamiliarTelefonoSecundario = ""
            this.nucleoFamiliarPartido = ""
            this.nucleoFamiliarMovilizacion = ""
            this.nucleoFamiliarTipoVoto = ""
            this.nucleoFamiliarRaasEstadoId = ""
            this.nucleoFamiliarRaasMunicipioId = ""
            this.nucleoFamiliarRaasParroquiaId = ""
            this.nucleoFamiliarRaasCentroVotacionId =  ""

            this.getFamiliares()

        },
        async nucleoFamiliarSearchCedula(){

            if(this.nucleoFamiliarCedula == ""){

                swal({
                    text:"Debes ingresar una cédula",
                    icon:"error"
                })

                return
            }

            this.isSearchingCedula = true

            const res = await axios.post("{{ url('api/clap/jefe-familia-clap/search-by-cedula') }}", {cedula: this.nucleoFamiliarCedula, nacionalidad: this.nucleoFamiliarNacionalidad})

            this.isSearchingCedula = false
            this.disabledStoreButton = false

            if(res.data.success == false){

                this.clearForm(false)

                swal({
                    text:"Esta cédula no está registrada en el CNE, sin embargo puedes añadir a la persona",
                    icon:"warning"
                })

                return
            }

            this.nucleoFamiliarNombre = res.data.elector.nombre_apellido
            this.nucleoFamiliarSexo = res.data.elector.sexo
            this.nucleoFamiliarFechaNacimiento = res.data.elector.fecha_nacimiento
            this.nucleoFamiliarEstatusPersonal = res.data.elector.raas_estatus_personal_id
            this.nucleoFamiliarTelefonoPrincipal = res.data.elector.telefono_principal
            this.nucleoFamiliarTelefonoSecundario = res.data.elector.telefono_secundario
            this.nucleoFamiliarPartido = res.data.elector.elecciones_partido_politico_id
            this.nucleoFamiliarMovilizacion = res.data.elector.elecciones_movilizacion_id
            this.nucleoFamiliarTipoVoto = res.data.elector.tipo_voto
            this.nucleoFamiliarRaasEstadoId = res.data.elector.raas_estado_id
            this.nucleoFamiliarRaasMunicipioId = res.data.elector.raas_municipio_id
            this.nucleoFamiliarRaasParroquiaId = res.data.elector.raas_parroquia_id
            this.nucleoFamiliarRaasCentroVotacionId =  res.data.elector.raas_centro_votacion_id

        },

        async storeNucleoFamiliar(){

            try{

                const response = await axios.post("{{ url('api/clap/jefe-familia-clap/store-nucleo') }}", {

                    'jefeFamiliaId':this.nucleoFamiliarJefeFamiliaId,
                    'cedula':this.nucleoFamiliarCedula,
                    'nombre_apellido':this.nucleoFamiliarNombre,
                    'sexo':this.nucleoFamiliarSexo,
                    'telefono_principal':this.nucleoFamiliarTelefonoPrincipal,
                    'telefono_secundario':this.nucleoFamiliarTelefonoSecundario,
                    'nacionalidad':this.nucleoFamiliarNacionalidad,
                    'tipo_voto': this.nucleoFamiliarTipoVoto,
                    'fecha_nacimiento': this.nucleoFamiliarFechaNacimiento,
                    'raas_estado_id': this.nucleoFamiliarRaasEstadoId,
                    'raas_municipio_id': this.nucleoFamiliarRaasMunicipioId,
                    'raas_parroquia_id': this.nucleoFamiliarRaasParroquiaId,
                    'raas_centro_votacion_id': this.nucleoFamiliarRaasCentroVotacionId,
                    'raas_estatus_personal_id': this.nucleoFamiliarEstatusPersonal,
                    'partido_politico_id': this.nucleoFamiliarPartido,
                    'movilizacion_id': this.nucleoFamiliarMovilizacion,
                    'tipoPersona':this.nucleoFamiliarTipoPersona

                })

                if(response.data.success == false){
                    swal({
                        text:response.data.message,
                        icon:"error"
                    })

                    return
                }

                swal({
                    text: response.data.message,
                    icon:"success"
                })

                this.nucleoFamiliarTipoPersona = ""
                this.nucleoFamiliarCedula = ""
                this.nucleoFamiliarNombre = ""
                this.nucleoFamiliarNacionalidad = ""
                this.nucleoFamiliarSexo = ""
                this.nucleoFamiliarFechaNacimiento = ""
                this.nucleoFamiliarEstatusPersonal = ""
                this.nucleoFamiliarTelefonoPrincipal = ""
                this.nucleoFamiliarTelefonoSecundario = ""
                this.nucleoFamiliarPartido = ""
                this.nucleoFamiliarMovilizacion = ""
                this.nucleoFamiliarTipoVoto = ""
                this.nucleoFamiliarRaasEstadoId = ""
                this.nucleoFamiliarRaasMunicipioId = ""
                this.nucleoFamiliarRaasParroquiaId = ""
                this.nucleoFamiliarRaasCentroVotacionId =  ""

                this.getFamiliares()

            }catch(err){

                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.nucleoFamiliarErrors = err.response.data.errors

            }

        },

        async getFamiliares(){

            const response = await axios.get("{{ url('api/clap/jefe-familia-clap/nucleo') }}"+"/"+this.nucleoFamiliarJefeFamiliaId)
            this.nucleoFamiliares = response.data
        },

        async nucleoFamiliarRemove(id){

            swal({
                title: "¿Estás seguro?",
                text: "Eliminarás este familiar!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(async (willDelete) => {

                if (willDelete) {

                    let res = await axios.delete("{{ url('api/clap/jefe-familia-clap/nucleo-delete') }}"+"/"+id)

                    if(res.data.success == true){

                        swal({
                            text:res.data.message,
                            icon: "success"
                        })

                        this.getFamiliares()

                    }else{

                        swal({
                            text:res.data.message,
                            icon: "error"
                        })

                    }

                }

            })

        }

    },
    created(){
        this.fetch()        
    },
    mounted() {

        this.getMovilizaciones()
        this.getPartidosPoliticos()
        this.getEstatusPersonal()

    }
});
</script>