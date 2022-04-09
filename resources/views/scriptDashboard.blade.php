<script src="{{ url('assets/js/pages/features/charts/apexchartsdashboard.js') }}"></script>

<script type="text/javascript">
    var app = new Vue({
        el: '#content',
        data() {
            return {


                selectedMunicipio:"0",
                selectedParroquia:"0",
                selectedComunidad:"0",
                selectedCalle:"0",
                municipios:[],
                parroquias:[],
                comunidades:[],
                calles:[],
                datas:[],
                loading:false,
                entidad:"Municipio",
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}",

                jefesFamilia: 0,
                mujeres:0,
                hombres:0,
                ninos:0,
                ninas:0,
                
            }
        },
        methods: {

            async getMunicipios(){

                this.selectedParroquia = "0"
                this.selectedComunidad = "0",
                this.selectedCalle = "0"

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
                
                this.selectedComunidad = "0",
                this.selectedCalle = "0"

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },

            async getComunidades(){
                this.selectedCalle = "0"
                const response = await axios.get("{{ url('api/comunidades/') }}"+"/"+this.selectedParroquia)
                this.comunidades = response.data
            },

            async getCalles(){
                let res = await axios.get("{{ url('/api/calles') }}"+"?comunidad_id="+this.selectedComunidad)
                this.calles = res.data
            },
            async generate(){

                this.jefesFamilia = 0
                this.mujeres = 0
                this.hombres = 0
                this.ninos = 0
                this.ninas = 0
                this.anexos = 0
                this.cantidadHabitantes = 0
                this.familias = 0
                this.casas = 0

                this.loading = true
                

                let res = await axios.post("{{ url('api/reporte-dashboard/generate') }}", {
                    municipio: this.selectedMunicipio,
                    parroquia: this.selectedParroquia,
                    comunidad: this.selectedComunidad,
                    calle: this.selectedCalle
                })
         
                this.jefesFamilia = res.data.jefesFamiliaCount
                this.mujeres = res.data.mujeresCount
                this.hombres = res.data.hombresCount
                this.ninos = res.data.ninosCount
                this.ninas = res.data.ninasCount
                this.datas = res.data.data
                this.entidad = res.data.entidad

            },


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

            setTimeout(() => {
                this.generate()
            }, 2000);
            
        }
    });
</script>