@extends("layouts.main")

@section("content")

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-custom bg-dark">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <span class="card-icon">
                                <i class="flaticon2-chat-1 text-white"></i>
                            </span>
                            <h3 class="card-label text-white">Números telefónicos</h3>
                        </div>
                    </div>
                    <div class="separator separator-solid separator-white opacity-20"></div>
                    <div class="card-body text-white d-flex justify-content-center">
                        <span class="mr-3">validados</span> <h3>10  / 20 </h3> <span class="ml-3">registrados</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom bg-info">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <span class="card-icon">
                                <i class="flaticon2-chat-1 text-white"></i>
                            </span>
                            <h3 class="card-label text-white">Mercados</h3>
                        </div>
                    </div>
                    <div class="separator separator-solid separator-white opacity-20"></div>
                    <div class="card-body text-white">
                        <h3 class="text-center">3 </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom bg-success">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <span class="card-icon">
                                <i class="flaticon2-chat-1 text-white"></i>
                            </span>
                            <h3 class="card-label text-white">Actividades registradas</h3>
                        </div>
                    </div>
                    <div class="separator separator-solid separator-white opacity-20"></div>
                    <div class="card-body text-white">
                        <h3 class="text-center">4 </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="card card-custom gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Últimos mercados</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-info font-weight-bolder font-size-sm">Nuevo mercado</a>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body py-0">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_2">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th>
                                            Nombre
                                        </th>
                                        <th>
                                            Departamento
                                        </th>
                                        <th>
                                            Municipio
                                        </th>
                                        <th>
                                            Dirección
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Body-->
                </div>

            </div>

        </div>
    </div>

@endsection