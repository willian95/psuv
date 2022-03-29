@extends("layouts.main")

@section("content")

    <style>

    .round-circle{
        width: 20px; 
        height: 20px;
        border-radius: 50%;
    }

    </style>

    <div class="d-flex flex-column-fluid" id="content" v-cloak>

        <!--begin::Container-->
        <div class="container" v-cloak>
            <!--begin::Card-->
            <div class="card card-custom">
                
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

    </div>

@endsection

@push("scripts")
    @include("scriptDashboard")
@endpush