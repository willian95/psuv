@extends('layouts.login')

@section('content')
    <div class="login_admin " id="dev-login">

        <div class="row">
            <div class="login100-more mask col-md-6"
                style="background-image: url('psuv-login.jpg');">


               <!---- <p>Bienvenido a Aidacaceres CMS</p>--->
            </div>
            <div class="login100-form validate-form col-md-6" v-cloak>

                <div class="d-flex pl-5 pr-5 align-items-center">
                    <img src="{{ url('gob-logo.jpg') }}" style="width: 200px;">
                    <h2 class="text-center gob-yellow">Sistema Integral para la Gestión de Atención Social</h3>
                </div>


                <div class="wrap-input100 validate-input" style="margin-top: 50px;">
                    <input class="input100" type="text" v-model="email">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Correo electrónico</span>
                    
                </div>
                <small style="color: red; margin-top: 10px;" v-if="errors.hasOwnProperty('email')">@{{ errors['email'][0] }}</small>


                <div class="wrap-input100 validate-input">
                    <input class="input100" type="password" v-model="password">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Contraseña</span>
                    
                </div>
                <small style="color: red; margin-top: 10px;" v-if="errors.hasOwnProperty('password')">@{{ errors['password'][0] }}</small>




                <div class="w-100">
                    <div class="d-flex justify-content-end pr-5">
                        <button class="login100-form-btn" style="background-color: #025da5; margin-right: 10px; width: 223px !important;" v-if="loading == false">
                            Recuperar contraseña
                        </button>
                        <button class="login100-form-btn" @click="login()" style="background-color: #086e0c;" v-if="loading == false">
                            Ingresar
                        </button>
                    </div>
                    
                </div>
                <div class="w-100">
                    <p class="text-center">
                        <div class="loader-custom" v-if="loading"></div>
                    </p>
                </div>
                
            </div>


        </div>

    </div>
@endsection


@push("scripts")

<script type="text/javascript">
const app = new Vue({
    el: '#dev-login',
    data() {
        return {
            email: "",
            password: "",
            errors:[],
            loading:false
        }
    },
    methods: {

        login() {

            window.location.href="{{ url('instituciones-usuario') }}"
            /*this.loading = true

            axios.post("{{ url('/login') }}", {
                email: this.email,
                password: this.password
            })
            .then(res => {
                
                this.loading = false
                if (res.data.success == true) {

                    this.email = ""
                    this.password = ""

                    window.location.href = res.data.url


                } else {
                    alert(res.data.msg)
                }

            })
            .catch(err => {
                this.loading = false
                swal({
                    text:"Hay algunos campos que debes revisar",
                    icon: "error"
                })

                this.errors = err.response.data.errors

            })*/

        }

    },
    created() {


    }
});
</script>

@endpush