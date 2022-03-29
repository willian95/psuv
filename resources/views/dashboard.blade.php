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
            
                <img src="{{ url('dashboard.png') }}" alt="" class="w-100">
          
            <!--end::Card-->
        </div>
        <!--end::Container-->

    </div>

@endsection

@push("scripts")
    @include("scriptDashboard")
@endpush