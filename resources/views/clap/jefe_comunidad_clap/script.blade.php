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

            errors:[],
            municipios:[],
            partidosPoliticos:[],
            movilizaciones:[],
            enlacesMunicipales:[],
            jefeClaps:[],
            jefeComunidades:[],
            parroquias:[],
            comunidades:[],

            modalTitle:"Crear jefe comunidad clap",
            disabledStoreButton:true,
            readonlyJefeClap:false,
            readonlyComunidad:false,

            id:"",
            isSearchingCedula:false,
            storeLoader:false,
            updateLoader:false,
            searchLoading:false,
            suspendLoader:false,
            loading:false,

            jefeClapId:"",
            jefeClapNacionalidad:"",
            jefeClapCedula:"",
            jefeClapNombre:"",
            
            nacionalidad:"V",
            cedula:"",
            nombre:"",
            sexo:"M",
            telefonoPrincipal:"",
            telefonoSecundario:"",
            tipoVoto:"",
            partidoPolitico:"",
            searchCedulaQuery:"",
            nombreClap:"",
            
            selectedMunicipio:"",
            selectedClapMunicipio:"",
            selectedEstado:"",
            selectedParroquia:"",
            selectedClapParroquia:"",
            selectedComunidad:"",
            selectedCentroVotacion:"",
            selectedPartidoPolitico:"",
            selectedMovilizacion:"",

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

        async getParroquias(){

            if(this.selectedClapMunicipio){
                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedClapMunicipio)
                this.parroquias = res.data
            }

        },

        async getComunidades(){
            this.selectedComunidad = ""
            if(this.selectedClapParroquia){
                let res = await axios.get("{{ url('/api/comunidades') }}"+"/"+this.selectedClapParroquia)
                this.comunidades = res.data
            }

        },

        async searchJefeClapByCedula(){

            const response = await axios.post("{{ url('/api/clap/jefe-clap/search-jefe-by-cedula') }}", {
                "cedula": this.jefeClapCedula,
                "nacionalidad": this.jefeClapNacionalidad
            })

            if(response.data.success == false){
                swal({
                    text:response.data.message,
                    icon:'error'
                })

                return
            }

            this.jefeClapCedula = response.data?.jefe?.personal_caracterizacions?.cedula
            this.jefeClapId = response.data?.jefe?.id
            this.jefeClapNacionalidad = response.data?.jefe?.personal_caracterizacions?.nacionalidad
            this.jefeClapNombre = response.data?.jefe?.personal_caracterizacions?.nombre_apellido
            this.nombreClap = response.data?.jefe?.censo_clap?.nombre
            this.comunidades = response.data?.jefe?.censo_clap?.comunidades

            this.getParroquias()

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

            let res = await axios.post("{{ url('api/clap/jefe-comunidad-clap/search-by-cedula') }}", {cedula: this.cedula, nacionalidad: this.nacionalidad})
            
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
       
            this.id = ""
            this.disabledStoreButton = true
            this.action = "create"
            this.modalTitle = "Crear Jefe Comunidad"
            this.readonlyJefeClap = false
            this.readonlyComunidad = false
            this.jefeClapCedula = ""
            this.jefeClapNombre = ""
            
            this.clearForm()
            
        },

        clearForm(clearCedula = true){
            
            if(clearCedula == true){
                this.cedula = ""
                this.nacionalidad = ""
            }

            this.jefeClapCedula = ""
            this.jefeClapNombre = ""
            
            this.nombreClap = ""
            this.nombre = ""
            this.sexo = ""
            this.telefonoPrincipal = ""
            this.telefonoSecundario = ""
            this.tipoVoto = ""
            this.partidoPolitico = ""
            this.selectedMovilizacion = ""

            if(this.action != 'edit'){
                this.jefeClapId = ""
                this.selectedEstado = ""
                this.selectedMunicipio = ""
                this.selectedClapParroquia = ""
                this.selectedCentroVotacion = ""
                this.selectedComunidad = ""
            }

        },

        async setElectorData(elector){

            this.selectedEstado = elector.raas_estado_id

            this.nombre = elector.nombre_apellido
            this.telefonoPrincipal = elector.telefono_principal
            this.telefonoSecundario = elector.telefono_secundario
            this.fechaNacimiento = elector.fecha_nacimiento
            this.tipoVoto = elector.tipo_voto

            this.nacionalidad = elector.nacionalidad ? elector.nacionalidad : this.nacionalidad

            this.sexo = elector.sexo
            this.selectedPartidoPolitico = elector.partido_politico_id
            this.selectedMovilizacion = elector.movilizacion_id

            this.raasSelectedMunicipio = elector.raas_municipio_id
            this.raasSelectedParroquia = elector.raas_parroquia_id
            this.raasSelectedCentroVotacion = elector.raas_centro_votacion_id

        },  

        async store(){

            try{
                
                this.errors = []
                this.storeLoader = true
                
                let res = await axios.post("{{ url('api/clap/jefe-comunidad-clap') }}", {
                    cedula: this.cedula, 
                    nacionalidad: this.nacionalidad, 
                    nombre_apellido: this.nombre, 
                    sexo: this.sexo, 
                    telefono_principal: 
                    this.telefonoPrincipal, 
                    telefono_secundario: this.telefonoSecundario, 
                    tipo_voto: this.tipoVoto, 
                    raas_estado_id: this.selectedEstado, 
                    raas_municipio_id: this.raasSelectedMunicipio, 
                    raas_parroquia_id: this.raasSelectedParroquia, 
                    raas_centro_votacion_id: this.raasSelectedCentroVotacion, 
                    partido_politico_id: this.selectedPartidoPolitico, 
                    movilizacion_id: this.selectedMovilizacion, 
                    jefeClapId: this.jefeClapId,
                    comunidadId:this.selectedComunidad

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
                
                const response = await axios.put("{{ url('api/clap/jefe-comunidad-clap') }}"+"/"+this.id, {
                    cedula: this.cedula, 
                    nacionalidad: this.nacionalidad, 
                    nombre_apellido: this.nombre, 
                    sexo: this.sexo, 
                    telefono_principal: this.telefonoPrincipal, 
                    telefono_secundario: this.telefonoSecundario, 
                    tipo_voto: this.tipoVoto, 
                    raas_estado_id: this.selectedEstado, 
                    raas_municipio_id: this.selectedMunicipio, 
                    raas_parroquia_id: this.selectedParroquia, 
                    raas_centro_votacion_id: this.selectedCentroVotacion, 
                    partido_politico_id: this.selectedPartidoPolitico, 
                    movilizacion_id: this.selectedMovilizacion, 
                    selectedMunicipioEnlaceMunicipal: this.selectedMunicipioEnlaceMunicipal,
                    jefeClapId: this.jefeClapId,
                    comunidadId: this.selectedComunidad
                })

                this.updateLoader = false

                if(response.data.success == true){

                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {

                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()
                    
                        this.fetch()
                    })


                }else{

                    swal({
                        text:response.data.message,
                        icon: "error"
                    })

                }

            }catch(err){
                console.log(err)
                this.storeLoader = false
                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.errors = err.response.data.errors

            }
            this.storeLoader = false
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
        async fetch(link = "{{ url('api/clap/jefe-comunidad-clap/index') }}"){


            this.searchLoading = true

            const res = await axios.get(link, {
                params:{
                    "searchCedula": this.searchCedulaQuery
                }
            })

            this.searchLoading = false

            this.jefeComunidades = res.data.data
            this.links = res.data.links
            this.currentPage = res.data.current_page
            this.totalPages = res.data.last_page

        },

        async remove(id){

            swal({
                title: "¿Estás seguro?",
                text: "Eliminarás este Jefe de comunidad!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(async (willDelete) => {

                if (willDelete) {

                    let res = await axios.delete("{{ url('api/clap/jefe-comunidad-clap') }}"+"/"+id)

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

            this.readonlyJefeClap = true
            this.readonlyComunidad = true
            this.action = "edit"
            this.modalTitle = "Editar Jefe Comunidad"
            this.id = jefe.id

            this.jefeClapId = jefe.jefe_clap.id
            this.jefeClapNacionalidad  = jefe.jefe_clap.personal_caracterizacions.nacionalidad
            this.jefeClapCedula  = jefe.jefe_clap.personal_caracterizacions.cedula
            this.jefeClapNombre  = jefe.jefe_clap.personal_caracterizacions.nombre_apellido
            this.nombreClap = jefe.jefe_clap.censo_clap.nombre
            this.comunidades = jefe.jefe_clap.censo_clap.comunidades

            this.selectedComunidad = jefe.comunidad.id

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

            this.raasSelectedMunicipio = personalCaracterizacion.raas_municipio_id
            this.raasSelectedParroquia = personalCaracterizacion.raas_parroquia_id
            this.raasSelectedCentroVotacion = personalCaracterizacion.raas_centro_votacion_id

        }

        

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