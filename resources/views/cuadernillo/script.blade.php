<script type="text/javascript">
    var app = new Vue({
        el: '#content',
        data() {
            return {

                linkClass:"page-link",
                activeLinkClass:"page-link active-link bg-main",
                currentPage:1,
                links:"",
                totalPages:"",

                loading:false,

                selectedMunicipio:"0",
                selectedParroquia:"0",
                
                municipios:[],
                parroquias:[],
                centrosVotacion:[],
                
                authMunicipio:"{{ \Auth::user()->municipio_id ? \Auth::user()->municipio_id : 0}}"
            }
        },
        methods: {

            async getMunicipios(){

                this.selectedParroquia = "0"
                this.selectedComunidad = "0"

                let res = await axios.get("{{ url('/api/municipios') }}")
                this.municipios = res.data

            },
            async getParroquias(){
                
                this.selectedParroquia = "0"
                this.selectedComunidad = "0"

                let res = await axios.get("{{ url('/api/parroquias') }}"+"/"+this.selectedMunicipio)
                this.parroquias = res.data

            },

            async getCentrosVotacion(){

                if(this.selectedParroquia == 0){

                    /*swal({
                        text:"Debe seleccionar una parroquia",
                        icon:"warning"
                    })*/

                    return

                }

                this.loading = true
                let res = await axios.get("{{ url('/api/votaciones/cuadernillo') }}"+"?parroquia_id="+this.selectedParroquia)
                this.centrosVotacion = res.data.centros
                this.loading = false

            }


        },
        async created() {

            await this.getMunicipios()
            this.selectedMunicipio = "0"
            this.selectedParroquia = "0"
            this.selectedComunidad = "0"
            this.selectedMunicipio = this.authMunicipio

            if(this.selectedMunicipio != "0"){
                await this.getParroquias()
            }


        }
    });
</script>