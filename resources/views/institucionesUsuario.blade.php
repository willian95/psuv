@extends('layouts.template')

@section('content')

    <div class="container" id="content">

        <div class="card mt-3">
            <div class="card-body">

                <div class="row">
                
                    <div class="col-lg-4">

                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Buscar" id="example-search-input" v-model="query" @keyup="search($event)">
                            <span class="input-group-append">
                                <button class="btn btn-outline-secondary bg-white ms-n3" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>

                    </div>

                </div>


                <div class="row mt-3">

                    <div class="col-lg-3 col-md-6 mt-3 mb-3" v-for="module in modules">
                        
                        <div class="card card-custom pt-4 pb-4">
                            <div class="card-header border-0">
                                <div class="card-title d-flex justify-content-center  w-100">
                                    <img src="{{ url('logo_clap.png') }}" alt="" style="width: 70px;">
                                    
                                </div>
                                <div class="card-body d-flex justify-content-center">
                                    <a :href="module.url">
                                        <h3 class="card-label text-white">@{{ module.name }}</h3>
                                    </a>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>



    </div>
    

@endsection

@push("scripts")

<script type="text/javascript">
    var app = new Vue({
        el: '#content',
        data() {
            return {
                query:"",
                originalModules:[{
                    "name":"CLAP",
                    "url":"{{ url('/clap/home') }}"
                }],
                modules:[
                    {
                        "name":"CLAP",
                        "url":"{{ url('/clap/home') }}"
                    }
                ]
            }
        },
        methods: {

            search(){

                this.modules = this.originalModules.filter(data => { return data.name.indexOf(this.query) >= 0  })

            }

        }
    });
</script>


@endpush