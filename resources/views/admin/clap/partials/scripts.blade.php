<script type="text/javascript">
    /********* VUE ***********/
    var vue_instance = new Vue({
        el: '#content',
        components: {},
        data: {
            loading: true,
            action:"create",//create,edit,suspend
            //Class
            linkClass:"page-link",
            activeLinkClass:"page-link active-link bg-main",
            municipios:[],
            parroquias:[],
            selectedMunicipio:"",
            selectedParroquia:"",
            comunidades:[],
            selectedComunidades:[],
            selectedComunidad:"",

            readonlyMunicipio:false,
            readonlyParroquia:false,
            readonlyComunidad:false,

            //Form
            form:{
                tipo:"---",
                sector:"---",
                nombre:"",
            },
            entityId:null,
            searchText:"",
            //Array data
            
            results:[],


            //paginate
            modalTitle:"Crear Clap",
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                await this.fetch();
                await this.getMunicipios()
                this.loading = false;
            });
        },
        methods: {
            create(){

                this.action="create";

                this.selectedComunidades = []
                this.readonlyMunicipio = false
                this.readonlyParroquia = false
                this.readonlyComunidad = false

                this.selectedComunidad = ""
                this.selectedMunicipio = ""
                this.selectedParroquia = ""
                this.modalTitle = "Crear Clap"

                

            },
            async fetch(link = ""){
                this.loading = true;
                let filters={
                    municipio_id:"{{Auth::user()->municipio_id ? Auth::user()->municipio_id : 0}}",
                    includes:"comunidades.parroquia.municipio",
                    search:this.searchText
                };
                if(link==""){
                    filters.page=1;
                }
                let res = await axios.get(link == "" ? "{{ route('api.clap.index') }}" : link.url,{
                    params:filters
                })
                this.results = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
                this.loading = false;
            },
            async store(){
                //Validations
               if(this.form.nombre==""){
                    swal({
                        text:"Debe ingresar el nombre",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.tipo==""){
                    swal({
                        text:"Debe ingresar el tipo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.sector==""){
                    swal({
                        text:"Debe ingresar el sector",
                        icon:"error"
                    });
                    return false;
                }
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.clap.store') }}",
                        data: {
                            comunidades:this.selectedComunidades,
                            nombre:this.form.nombre
                        }
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()

                    })
                    this.clearForm();
                    this.fetch();
                } catch (error) {
                    this.loading = false;
                    console.log(error)
                    let msg=error.response.data.message;
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                    // swal({
                    //     text:error.response.data.message,
                    //     icon:"error"
                    // });

                }
            },
            async edit(entity){

                this.readonlyMunicipio = true
                this.readonlyParroquia = true
                this.readonlyComunidad = true
                this.modalTitle = "Editar Clap"
                this.action="edit";
                this.entityId=entity.id;
                this.selectedMunicipio = entity.comunidades[0].parroquia.municipio.id
                await this.getParroquias()

                this.selectedParroquia = entity.comunidades[0].parroquia.id
                await this.getComunidades()

                this.selectedComunidad = entity.comunidades[0].id
                this.form.nombre = entity.nombre
                this.selectedComunidades = entity.comunidades
                
            },
            async suspend(entityId){

                swal({
                        title: "¿Estás seguro?",
                        text: "Eliminarás este clap!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then(async (willDelete) => {
                        try {

                            if(willDelete){

                                this.loading = true;
                                const response = await axios({
                                    method: 'DELETE',
                                    responseType: 'json',
                                    url: "{{ url('api/clap') }}"+"/"+entityId,
                                    data: this.form
                                });
                                this.loading = false;
                                swal({
                                    text:response.data.message,
                                    icon: "success"
                                }).then(ans => {
                                    $('.marketModal').modal('hide')
                                    $('.modal-backdrop').remove()

                                })
                                this.clearForm();
                                this.fetch();

                            }
                            
                        } catch (error) {
                            this.loading = false;
                            let msg=error.response.data.message;
                            if(msg=="The given data was invalid."){
                                msg="Los datos proporcionados no son válidos.";
                            }
                            swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));

                            // swal({
                            //     text:error.response.data.message,
                            //     icon:"error"
                            // });
                        }
                    })
            },
            async update(){
              //Validations
                //Validations
                if(this.form.nombre==""){
                    swal({
                        text:"Debe ingresar el nombre",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.tipo==""){
                    swal({
                        text:"Debe ingresar el tipo",
                        icon:"error"
                    });
                    return false;
                }else if(this.form.sector==""){
                    swal({
                        text:"Debe ingresar el sector",
                        icon:"error"
                    });
                    return false;
                }
              try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/clap') }}"+"/"+this.entityId,
                        params: {
                            nombre:this.form.nombre
                        }
                    });
                    this.loading = false;
                    swal({
                        text:response.data.message,
                        icon: "success"
                    }).then(ans => {
                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()

                    })
                    this.clearForm();
                    this.fetch();
                } catch (error) {
                    this.loading = false;
                    let msg=error.response.data.message;
                    if(msg=="The given data was invalid."){
                        msg="Los datos proporcionados no son válidos.";
                    }
                    swalAlert("error",msg, errorsToHtmlList(error.response.data.errors));
                }
            },
            clearForm(){

                if(this.action == "create"){
                    this.selectedMunicipio = "";
                    this.selectedParroquia= "";
                    this.form.raas_comunidad_id="0";
                }
                
                this.form.nombre="";
                this.form.sector="---";
                this.form.tipo="---";
                
            },
            async obtenerComunidades() {
                try {
                    this.loading = true;
                    let filters = {
                        municipio_id:"{{Auth::user()->municipio_id ? Auth::user()->municipio_id : 0}}",
                        includes:"comunidad",
                        order_by:"nombre",
                        order_direction:"ASC"
                     }
                    const response = await axios({
                        method: 'get',
                        responseType: 'json',
                        url: "{{ route('api.comunidades.index') }}",
                        params: filters
                    });

                    if(response.data.success == false){
                        swal({
                            text:response.data.msg,
                            icon:"error"
                        })

                        return false;
                    }

                    this.loading = false;
                    this.comunidades = response.data.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async getMunicipios(){
                const falconStateId=9
                this.municipios =[]

                let res = await axios.get("{{ url('/api/municipios') }}"+"/"+falconStateId)
                this.municipios = res.data

            },
            async getParroquias(){
                
                this.selectedParroquia = "0"
                this.selectedCentroVotacion = "0"

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },
            async getComunidades(){
                const response = await axios.get("{{ url('api/comunidades/') }}"+"/"+this.selectedParroquia)
                this.comunidades = response.data
            },
            toggleSelectComunidad(selectedComunidad = null){
                
                if(selectedComunidad != null){
                    this.selectedComunidad = selectedComunidad
                }

                if(!this.selectedComunidad || this.selectedComunidad == 0){
                    return
                }

                if(this.selectedComunidades.find(data => data == this.selectedComunidad)){
                    this.selectedComunidades = this.selectedComunidades.filter(data => data != this.selectedComunidad)
                    return
                }

                this.selectedComunidades.push(this.selectedComunidad)

            }

        } //methods
    });
</script>
