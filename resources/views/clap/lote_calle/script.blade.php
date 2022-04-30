<script type="text/javascript">

var sync = false;
var date = "";

const app = new Vue({
    el: '#dev-enlace-municipal',
    data() {
        return {

            municipios:[],
            parroquias:[],
            comunidades:[],
            calles:[],
            selectedMunicipio:"",
            selectedParroquia:"",
            selectedComunidad:"",
            selectedCalle:""

        }
    },
    methods: {

        async getMunicipios(){

            this.selectedParroquia = "0"
            this.selectedComunidad = "0",
            this.selectedCalle = "0"

            let res = await axios.get("{{ url('/api/municipios/9') }}")
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

        async getJefeCalle(){

            const res = await axios.get("{{ url('/api/clap/jefe-calle-clap/by-calle/') }}"+"/"+this.selectedCalle)

            return res.data.success

        },

        async store(){
            if(await this.getJefeCalle()){
                const form = document.getElementById("form")
                form.submit()
            }else{

                swal({
                    "text" : "Calle no posee jefe",
                    "icon": "error"
                })

            }
            
            

      

        }

        

    },
    mounted() {

       this.getMunicipios()

    }
});
</script>