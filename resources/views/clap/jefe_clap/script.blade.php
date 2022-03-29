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
            parroquias:[],
            comunidades:[],
            movilizaciones:[],
            enlacesMunicipales:[],
            jefeClaps:[],
            claps:[],

            modalTitle:"Crear jefe clap",
            disabledStoreButton:true,
            readonlySelectedMunicipioEnlaceMunicipal:false,

            id:"",
            isSearchingCedula:false,
            storeLoader:false,
            updateLoader:false,
            searchLoading:false,
            suspendLoader:false,
            loading:false,

            nacionalidad:"V",
            cedula:"",
            nombre:"",
            sexo:"M",
            telefonoPrincipal:"",
            telefonoSecundario:"",
            tipoVoto:"",
            partidoPolitico:"",
            searchCedulaQuery:"",
            
            selectedMunicipioEnlaceMunicipal:"",
            selectedEstado:"",
            selectedComunidad:"",
            selectedMunicipio:"",
            selectedParroquia:"",
            selectedCentroVotacion:"",
            selectedPartidoPolitico:"",
            selectedMovilizacion:"",
            selectedClap:"",

            raasSelectedMunicipio:"",
            raasSelectedParroquia:"",
            raasSelectedComunidad:"",
            raasSelectedCentroVotacion:"",

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

        async getMunicipios(){
            const falconStateId=9
            this.municipios =[]

            let res = await axios.get("{{ url('/api/municipios') }}"+"/"+falconStateId)
            this.municipios = res.data

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

            let res = await axios.post("{{ url('api/clap/enlace-municipal/search-by-cedula') }}", {cedula: this.cedula, nacionalidad: this.nacionalidad})
            
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
            this.readonlySelectedMunicipioEnlaceMunicipal = false
            this.modalTitle = "Crear jefe clap"

            this.readonlySelectedCensoClap=false
            this.readonlySelectedMunicipio=false
            this.readonlySelectedParroquia=false
            this.readonlySelectedComunidad=false
            
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
            this.selectedEstado = ""
            this.selectedMunicipio = ""
            this.selectedParroquia = ""
            this.selectedCentroVotacion = ""

            if(this.action != 'edit'){
                this.selectedMunicipioEnlaceMunicipal = ""
            }

        },

        async setElectorData(elector){

            this.selectedEstado = elector.raas_estado_id

            this.nombre = elector.nombre_apellido

            this.telefonoPrincipal = elector.telefono_principal
            this.telefonoSecundario = elector.telefono_secundario
            this.fechaNacimiento = elector.fecha_nacimiento
            this.tipoVoto = elector.tipo_voto

            this.raasSelectedMunicipio = elector.raas_municipio_id
            this.raasSelectedParroquia = elector.raas_parroquia_id
            this.raasSelectedCentroVotacion = elector.raas_centro_votacion_id

            this.nacionalidad = elector.nacionalidad ? elector.nacionalidad : this.nacionalidad

            this.sexo = elector.sexo
            this.selectedPartidoPolitico = elector.partido_politico_id
            this.selectedMovilizacion = elector.movilizacion_id

        },  

        async store(){

            try{
                
                this.errors = []
                this.storeLoader = true
                
                let res = await axios.post("{{ url('api/clap/jefe-clap') }}", {
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
                    selectedCensoClap: this.selectedClap,
                    
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
                
                let res = await axios.put("{{ url('api/clap/jefe-clap') }}"+"/"+this.id, {
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
                    selectedCensoClap: this.selectedClap
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
        isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },
        async fetch(link = "{{ url('api/clap/jefe-clap/index') }}"){


            this.searchLoading = true

            const res = await axios.get(link, {
                params:{
                    "searchCedula": this.searchCedulaQuery
                }
            })

            this.searchLoading = false

            this.jefeClaps = res.data.data
            this.links = res.data.links
            this.currentPage = res.data.current_page
            this.totalPages = res.data.last_page

        },

        async remove(id){

            swal({
                title: "¿Estás seguro?",
                text: "Eliminarás este Jefe de clap!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(async (willDelete) => {

                if (willDelete) {

                    let res = await axios.delete("{{ url('api/clap/jefe-clap') }}"+"/"+id)

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

            this.action = "edit"
            this.modalTitle = "Editar Jefe clap"
            this.id = jefe.id
            this.readonlySelectedCensoClap = true
            this.readonlySelectedMunicipio=true
            this.readonlySelectedParroquia=true
            this.readonlySelectedComunidad=true

            const personalCaracterizacion = jefe.personal_caracterizacions

            this.cedula = personalCaracterizacion.cedula
            this.selectedEstado = personalCaracterizacion.raas_estado_id

            this.nombre = personalCaracterizacion.nombre_apellido
            this.selectedMunicipio = jefe.censo_clap.comunidades[0].parroquia.municipio.id
            await this.getParroquias()
            this.selectedParroquia = jefe.censo_clap.comunidades[0].parroquia.id
            await this.getComunidades()
            this.selectedComunidad = jefe.censo_clap.comunidades[0].id
            await this.getClaps()
            this.selectedClap = jefe.censo_clap_id

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
            

        },

        async getEnlacesMunicipales(){

            const res = await axios.get("{{ url('api/clap/enlace-municipal/all') }}")
            this.enlacesMunicipales = res.data
        },
        async getParroquias(){
                
            this.selectedComunidad = "0"

            let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
            this.parroquias = res.data

        },
        async getComunidades(){

            this.selectedClap = "0"

            const response = await axios.get("{{ url('api/comunidades/') }}"+"/"+this.selectedParroquia)
            this.comunidades = response.data
        },
        async getMunicipios(){

            this.selectedParroquia = "0"

            let res = await axios.get("{{ url('/api/municipios') }}")
            this.municipios = res.data

        },
        async getClaps(){

            let res = await axios.get("{{ url('/api/clap') }}"+"/comunidad/"+this.selectedComunidad)
            this.claps = res.data

        },

        

    },
    created(){
        this.fetch()        
    },
    mounted() {

        this.getMovilizaciones()
        this.getPartidosPoliticos()
        this.getEnlacesMunicipales()
        
        const authUserMunicipio= "{{ Auth::user()->municipio_id }}"

        if(authUserMunicipio == ""){
            this.getMunicipios()
            return
        }

        @if(Auth::user()->municipio_id)
            const authUserMunicipioNombre = "{{ App\Models\Municipio::where('id', Auth::user()->municipio_id)->first()->nombre }}"
        @endif
        this.municipios.push({"nombre": authUserMunicipio, "id": authUserMunicipio})

    }
});
</script>