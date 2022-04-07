<script type="text/javascript">
    /********* VUE ***********/
    var vue_instance = new Vue({
        el: '#content',
        components: {},
        data: {
            loading: false,
            action:"create",//create,edit,suspend
            selectedOperacion:"entre",
            rangoMenor:"",
            rangoMayor:"",
            errors:[],
            operaciones:[],
            cantidadBolsas:""
   
        },
        created: function() {
            this.$nextTick(async function() {
                await this.fetch();
                this.loading = false;
            });
        },
        methods: {
            create(){

                this.clearForm()

            },
            async fetch(){
                
                this.loading = true
                const response = await axios.get("{{ url('api/admin/orden-operaciones') }}")
                this.operaciones = response.data
                this.loading = false

            },
            clearForm(){
                this.errors = []
                this.selectedOperacion = "entre"
                this.rangoMenor = ""
                this.rangoMayor = ""
                this.cantidadBolsas = ""

            },
            async store(){
                
                if(this.rangoMayor < this.rangoMenor && this.selectedOperacion === "entre"){
                    swal({
                        text:"Rango mayor debe ser mayor que rango menor",
                        icon:"error"
                    })

                    return

                }

                try{
                    this.errors = []
                    const response = await axios.post("{{ url('api/admin/orden-operaciones') }}",{
                        "selectedOperacion": this.selectedOperacion,
                        "rangoMenor": this.rangoMenor,
                        "rangoMayor": this.rangoMayor,
                        "cantidadBolsas": this.cantidadBolsas
                    })

                    if(response.data.success == false){
                        swal({
                            text:response.data.message,
                            icon:"error"
                        })

                        return
                    }

                    swal({
                        text:response.data.message,
                        icon:"success"
                    }).then(ans => {

                        $('.marketModal').modal('hide')
                        $('.modal-backdrop').remove()
                        this.clearForm()

                        this.fetch()
                    })
                    
                    this.fetch()


                }catch(err){

                    swal({
                        text:"Los datos proporcionados no son válidos",
                        icon:"error"
                    })

                    this.errors = err.response.data.errors

                }

            },

            async remove(id){

                swal({
                    title: "¿Estás seguro?",
                    text: "Eliminarás esta orden!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then(async (willDelete) => {

                    if (willDelete) {

                        let res = await axios.delete("{{ url('api/admin/orden-operaciones') }}"+"/"+id)

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

            isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            }


        } //methods
    });
</script>
