<!-- Modal-->
<div class="modal fade marketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@{{ modalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula">Cédula Jefe de Comunidad</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedula_jefe_comunidad" :readonly="entityId">
                                        <small class="text-danger" v-if="cedula_jefe_comunidad_error">@{{ cedula_jefe_comunidad_error }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="obtenerJefeComunidad()" v-if="!loading"  :disabled="entityId">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre Jefe Comunidad</label>
                                <input type="text" class="form-control" v-if="jefe_comunidad" v-model="jefe_comunidad.personal_caracterizacion.full_name" readonly>
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula">Cédula Jefe</label>
                                <div class="d-flex">
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedula_jefe">
                                        <small class="text-danger" v-if="cedula_jefe_error">@{{ cedula_jefe_error }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="obtenerJefe()" v-if="!loading">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="loading"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre Jefe</label>
                                <input type="text" class="form-control" v-if="form.personal_caraterizacion" v-model="form.personal_caraterizacion.full_name" readonly>
                                <input type="text" class="form-control" v-else readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Calle</label>
                                <select class="form-control" v-model="form.calle_id" v-if="calles.length>0"  :disabled="entityId">
                                    <option value="0">Seleccione</option>
                                    <option :value="calle.id" v-for="calle in calles">@{{ calle.nombre }}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipoVoto">Tipo de voto</label>
                                <select class="form-control" v-model="form.tipo_voto" v-if="form.personal_caraterizacion">
                                    <option value="" selected>Seleccione</option>
                                    <option v-for="tipoVoto in tipoDeVotos" :value="tipoVoto">@{{tipoVoto}}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label >Teléfono principal</label>
                                <input type="number" class="form-control" maxlength="11" v-if="form.personal_caraterizacion" v-model="form.telefono_principal">
                                <input type="tel" class="form-control" v-else disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefonoSecundario">Teléfono secundario</label>
                                <input type="number" class="form-control" maxlength="11" v-if="form.personal_caraterizacion" v-model="form.telefono_secundario">
                                <input type="tel" class="form-control" v-else disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="partidoPolitico">Partido político</label>
                                <select class="form-control" v-model="form.partido_politico_id" v-if="form.personal_caraterizacion" >
                                    <option value="" selected>Seleccione</option>
                                    <option :value="partidoPolitico.id" v-for="partidoPolitico in partidosPoliticos">@{{ partidoPolitico.nombre }}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="movilizacion">Tipo de movilización</label>
                                <select class="form-control" v-model="form.movilizacion_id" v-if="form.personal_caraterizacion">
                                    <option value="" selected>Seleccione</option>
                                    <option :value="movilizacion.id" v-for="movilizacion in tiposDeMovilizacion">@{{ movilizacion.nombre }}</option>
                                </select>
                                <select class="form-control" v-else disabled>
                                    <option value="" selected>Seleccione</option>
                                </select>
                            </div>
                        </div>
                
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" @click="clearForm()">Cerrar</button>
                <button type="button" class="btn btn-light-warning font-weight-bold" @click="clearForm()" v-if="action == 'create' && !loading">Limpiar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="store()" v-if="action == 'create' && !loading">Crear</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit' && !loading">Actualizar</button>
                <button type="button" class="btn btn-primary font-weight-bold"  @click="suspend()" v-if="action == 'suspend' && !loading">Suspender</button>
                <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>
            </div>
        </div>
    </div>
</div>