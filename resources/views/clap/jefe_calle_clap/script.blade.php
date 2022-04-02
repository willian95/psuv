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

            errors:[],
            municipios:[],
            partidosPoliticos:[],
            movilizaciones:[],
            enlacesMunicipales:[],
            jefeCalles:[],
            jefeComunidades:[],
            parroquias:[],
            calles:[],

            modalTitle:"Crear jefe calle clap",
            disabledStoreButton:true,
            readonlyJefeComunidad:false,
            readonlyClapComunidad:false,

            id:"",
            isSearchingCedula:false,
            storeLoader:false,
            updateLoader:false,
            searchLoading:false,
            suspendLoader:false,
            loading:false,

            jefeComunidadId:"",
            jefeComunidadNacionalidad:"V",
            jefeComunidadCedula:"",
            jefeComunidadNombre:"",
            
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
            clapComunidades:[],

            totalPages:"",
            links:[],
            currentPage:""


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

        async searchJefeComunidadByCedula(){

            const response = await axios.post("{{ url('/api/clap/jefe-comunidad-clap/search-jefe-by-cedula') }}", {
                "cedula": this.jefeComunidadCedula,
                "nacionalidad": this.jefeComunidadNacionalidad
            })

            if(response.data.success == false){
                swal({
                    text:response.data.message,
                    icon:'error'
                })

                return
            }

            this.jefeComunidadCedula = response.data?.jefe[0]?.personal_caracterizacions?.cedula
            this.jefeComunidadNacionalidad = response.data?.jefe[0]?.personal_caracterizacions?.nacionalidad
            this.jefeComunidadNombre = response.data?.jefe[0]?.personal_caracterizacions?.nombre_apellido
            this.jefeComunidadId = response.data?.jefe[0]?.id
            this.clapComunidades = response.data.jefe.map(data => data.comunidad)


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

                this.clearForm(false)

                swal({
                    text:"Esta cédula no está registrada en el CNE, sin embargo puedes añadir a la persona",
                    icon:"warning"
                })

                return
            }

            this.setElectorData(res.data.elector)

        },  

        create(){
       
            this.id = ""
            this.disabledStoreButton = true
            this.action = "create"
            this.readonlyJefeComunidad = false
            this.jefeComunidadCedula = ""
            this.jefeComunidadNombre = ""
            this.readonlyClapComunidad = false
            
            this.clearForm()
            
        },

        clearForm(clearCedula = true){
            
            if(clearCedula == true){
                this.cedula = ""
                this.nacionalidad = "V"
            }
            
            this.nombre = ""
            this.sexo = ""
            this.telefonoPrincipal = ""
            this.telefonoSecundario = ""
            this.tipoVoto = ""
            this.partidoPolitico = ""
            this.selectedMovilizacion = ""
            this.jefeComunidadCedula = ""
            this.jefeComunidadNombre = ""
            this.selectedClapComunidad = ""
            this.selectedClapCalle = ""

            if(this.action != 'edit'){
                this.jefeComunidadId = ""
                this.selectedEstado = ""
                this.selectedMunicipio = ""
                this.selectedParroquia = ""
                this.selectedCentroVotacion = ""
                this.selectedComunidad = ""
            }

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
                
                let res = await axios.post("{{ url('api/clap/jefe-calle-clap') }}", {
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
                    jefeComunidadId: this.jefeComunidadId,
                    calleId:this.selectedClapCalle

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
                
                let res = await axios.put("{{ url('api/clap/jefe-calle-clap') }}"+"/"+this.id, {
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
                    selectedMunicipioEnlaceMunicipal: this.selectedMunicipioEnlaceMunicipal,
                    jefeComunidadId: this.jefeComunidadId,
                    calleId:this.selectedClapCalle
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
        async fetch(link = "{{ url('api/clap/jefe-calle-clap/index') }}"){


            this.searchLoading = true

            const res = await axios.get(link, {
                params:{
                    "searchCedula": this.searchCedulaQuery
                }
            })

            this.searchLoading = false

            this.jefeCalles = res.data.data
            this.links = res.data.links
            this.currentPage = res.data.current_page
            this.totalPages = res.data.last_page

        },

        async remove(id){

            swal({
                title: "¿Estás seguro?",
                text: "Eliminarás este Jefe de calle!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(async (willDelete) => {

                if (willDelete) {

                    let res = await axios.delete("{{ url('api/clap/jefe-calle-clap') }}"+"/"+id)

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

            this.readonlyClapComunidad = true
            this.readonlyJefeComunidad = true
            this.action = "edit"
            this.modalTitle = "Editar Jefe calle"
            this.id = jefe.id

            this.jefeComunidadId = jefe.jefe_comunidad.id
            this.jefeComunidadNacionalidad  = jefe.jefe_comunidad.personal_caracterizacions.nacionalidad
            this.jefeComunidadCedula  = jefe.jefe_comunidad.personal_caracterizacions.cedula
            this.jefeComunidadNombre  = jefe.jefe_comunidad.personal_caracterizacions.nombre_apellido

            await this.searchJefeComunidadByCedula()

            this.selectedClapComunidad = jefe.calle.comunidad.id

            await this.getCalles()

            this.selectedClapCalle = jefe.raas_calle_id

            const personalCaracterizacion = jefe.personal_caracterizacions

            this.cedula = personalCaracterizacion.cedula
            this.selectedEstado = personalCaracterizacion.raas_estado_id

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

        

    },
    created(){
        this.fetch()        
    },
    mounted() {

        this.getMovilizaciones()
        this.getPartidosPoliticos()

    }
});
</script>