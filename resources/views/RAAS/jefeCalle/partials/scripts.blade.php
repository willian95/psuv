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

            //Form
            form:{
                jefe_comunidad_id:null,
                calle_id:"0",
                personal_caraterizacion:null,
                tipo_voto:"",
                telefono_principal:"",
                telefono_secundario:"",
                partido_politico_id:"",
                movilizacion_id:"",
            },
            entityId:null,
            //search
            cedula_jefe_comunidad:"",
            cedula_jefe_comunidad_error:"",
            jefe_comunidad:null,
            cedula_jefe:"",
            cedula_jefe_error:"",
            //Array data
            calles:[],
            tipoDeVotos:[
                "Duro",
                "Blando"
            ],
            partidosPoliticos:[],
            tiposDeMovilizacion:[],
            results:[],


            //paginate
            modalTitle:"Crear Jefe de Calle",
            currentPage:1,
            links:"",
            totalPages:"",
   
        },
        created: function() {
            this.$nextTick(async function() {
                this.loading = false;
                await this.fetch();
                await this.obtenerPartidosPoliticos();
                await this.obtenerTiposMovilizacion();
            });
        },
        methods: {
            async fetch(link = ""){
                let res = await axios.get(link == "" ? "{{ route('api.jefe-calle.index') }}" : link.url)
                this.results = res.data.data
                this.links = res.data.links
                this.currentPage = res.data.current_page
                this.totalPages = res.data.last_page
            },
            async store(){
                //Validations
                try {
                    this.loading = true;
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ route('api.jefe-calle.store') }}",
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
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            edit(entity){
                this.action="edit";
                this.entityId=entity.id;
                //Jefe comunidad
                this.cedula_jefe_comunidad=entity.jefe_comunidad.personal_caracterizacion.cedula;
                this.jefe_comunidad=entity.jefe_comunidad;
                this.form.jefe_comunidad_id=entity.jefe_comunidad.id;
                //Calle
                this.form.calle_id=entity.calle_id;
                //Jefe calle
                this.cedula_jefe=entity.personal_caracterizacion.cedula;
                this.form.personal_caraterizacion=entity.personal_caracterizacion;
                this.form.tipo_voto=entity.personal_caracterizacion.tipo_voto;
                this.form.telefono_principal=entity.personal_caracterizacion.telefono_principal;
                this.form.telefono_secundario=entity.personal_caracterizacion.telefono_secundario;
                this.form.partido_politico_id=entity.personal_caracterizacion.partido_politico_id;
                this.form.movilizacion_id=entity.personal_caracterizacion.movilizacion_id;
                //obtener calles
                this.obtenerCalles();
            },
            async suspend(){

            },
            async update(){
              //Validations
              try {
                    this.loading = true;
                    const response = await axios({
                        method: 'PUT',
                        responseType: 'json',
                        url: "{{ url('api/raas/jefe-calle') }}"+"/"+this.entityId,
                        params: this.form
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
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            clearForm(){
                this.form.jefe_comunidad_id=null;
                this.form.calle_id="0";
                this.form.personal_caraterizacion=null;
                this.form.tipo_voto="";
                this.form.telefono_principal="";
                this.form.telefono_secundario="";
                this.form.partido_politico_id="";
                this.form.movilizacion_id="";
                this.cedula_jefe="";
                this.cedula_jefe_comunidad="";
                this.cedula_jefe_comunidad_error="";
                this.cedula_jefe_error="";
                this.entityId=null;
                this.jefe_comunidad=null;
                this.calles=[];
            },
            async obtenerJefeComunidad() {
                try {
                    this.loading = true;
                    let filters = {
                        cedula:this.cedula_jefe_comunidad
                    }
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ url('api/raas/jefe-comunidad/search-by-cedula') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.jefe_comunidad = response.data.data;
                    this.form.jefe_comunidad_id = this.jefe_comunidad.id;
                    this.obtenerCalles();
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerJefe() {
                try {
                    this.loading = true;
                    let filters = {
                        cedula:this.cedula_jefe
                    }
                    const response = await axios({
                        method: 'POST',
                        responseType: 'json',
                        url: "{{ url('api/raas/jefe-comunidad/search-by-cedula') }}",
                        data: filters
                    });
                    this.loading = false;
                    if(response.data.success==true){
                        this.form.personal_caraterizacion=response.data.elector;
                    }else{
                        this.form.personal_caracterization=null;
                        this.cedula_jefe_error="Elector no encontrado";
                    }
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerCalles() {
                try {
                    this.loading = true;
                    let filters = {
                        comunidad_id:this.jefe_comunidad.comunidad_id
                    }
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.calles.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.calles = response.data.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerPartidosPoliticos() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.partidos-politicos.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.partidosPoliticos = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },
            async obtenerTiposMovilizacion() {
                try {
                    this.loading = true;
                    let filters = {}
                    const response = await axios({
                        method: 'Get',
                        responseType: 'json',
                        url: "{{ route('api.movilizacion.index') }}",
                        params: filters
                    });
                    this.loading = false;
                    this.tiposDeMovilizacion = response.data;
                } catch (err) {
                    this.loading = false;
                    console.log(err)
                }
            },

        } //methods
    });
</script>
