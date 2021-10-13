<script src="{{ url('assets/js/pages/features/charts/apexcharts.js') }}"></script>

<script type="text/javascript">
    var app = new Vue({
        el: '#content',
        data() {
            return {

                clickCount:0,
                secondaryGraphic:0,
                type:"",
                metaGeneral:0,
                cargados:0,
                centroVotacionMetas:[],
                loading:false,
                secondaryInfo:"",

                selectedMunicipio:"0",
                selectedParroquia:"0",
                selectedCentroVotacion:"0",
                municipios:[],
                parroquias:[],
                centrosVotacion:[],
                email:"",
                emailError:"",
                loading:false,
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {

            async getMunicipios(){

                this.selectedParroquia = "0"
                this.selectedCentroVotacion = "0"

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
        
                this.selectedCentroVotacion = "0"

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },

            async getCentroVotacion(){

                let res = await axios.get("{{ url('/api/centro-votacion') }}"+"/"+this.selectedParroquia)
                this.centrosVotacion = res.data

            },
            async generate(){

                this.loading = true

                let paramsCentroVotacion = this.selectedCentroVotacion
                let paramsParroquia = this.selectedParroquia
                let paramsMunicipio = this.selectedMunicipio
                

                let res = await axios.post("{{ url('api/reporte-carga/generate') }}", {
                    centroVotacion: paramsCentroVotacion,
                    parroquia: paramsParroquia,
                    municipio: paramsMunicipio
                })

                this.loading = false
                this.secondaryInfo = res.data.entities
                this.type = res.data.type
                /*setTimeout(() => {
                    this.generateCharts(res.data.entities)
                }, 1000);*/
                
                
                this.metaGeneral = res.data.data.metas
                this.cargados = res.data.data.personalCaracterizacion
                this.centroVotacionMetas = res.data.data.centroVotacionMetas
                
                KTApexChartsDemo.init(this.metaGeneral, this.cargados, this.clickCount > 0 ? false : true, "#chart_12");
                this.clickCount++
                
            },

            generateCharts(entities){

                for(var i = 0; i < entities.length; i++){

                    KTApexChartsDemo.init(entities[i].meta, entities[i].cargados, true, "#graphic-"+i);

                }

                this.secondaryGraphic++;

            },
            
            validateCorreo(email){
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            },

            downloadExcel(){

                let paramsCentroVotacion = this.selectedParroquia != "0" ? "&centroVotacion="+this.selectedCentroVotacion : "&centroVotacion=0"
                let paramsParroquia = this.selectedMunicipio != "0" ? "&parroquia="+this.selectedParroquia : "&parroquia=0"
                let paramsMunicipio = "municipio="+this.selectedMunicipio

                window.location.href="{{ url('api/reporte-carga/download') }}"+"?"+paramsMunicipio+paramsParroquia+paramsCentroVotacion

            }


        },
        async created() {

            await this.getMunicipios()
            this.selectedMunicipio = "0"
            this.selectedParroquia = "0"
            this.selectedCentroVotacion = "0"
            this.selectedMunicipio = this.authMunicipio

            if(this.selectedMunicipio != "0"){
                await this.getParroquias()
            }

            this.generate()

        }
    });
</script>