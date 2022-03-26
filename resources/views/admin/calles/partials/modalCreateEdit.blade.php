<!-- Modal-->
<div class="modal fade marketModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@{{ modalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Municipios </label>
                                <select class="form-control" v-model="selectedMunicipio" @change="getParroquias()" :disabled="readonlyMunicipio">
                                    <option value="0">Seleccione</option>
                                    <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Parroquias </label>
                                <select class="form-control" v-model="selectedParroquia" @change="getComunidades()" :disabled="readonlyParroquia">
                                    <option value="0">Seleccione</option>
                                    <option :value="parroquia.id" v-for="parroquia in parroquias">@{{ parroquia.nombre }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Comunidad</label>
                                <select class="form-control" v-model="form.raas_comunidad_id" :disabled="readonlyComunidad">
                                    <option value="0">Seleccione</option>
                                    <option :value="comunidad.id" v-for="comunidad in comunidades">@{{ comunidad.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label >Nombre</label>
                                <input type="text" class="form-control" maxlength="150" v-model="form.nombre" >
                            </div>
                        </div>

                        {{--
                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Tipo</label>
                                <input type="text" class="form-control" maxlength="50" v-model="form.tipo" >
                            </div>
                        </div>

                                              
                        <div class="col-md-6">
                            <div class="form-group">
                                <label >Sector</label>
                                <input type="text" class="form-control" maxlength="100" v-model="form.sector" >
                            </div>
                        </div>
                        --}}

                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <div class="w-100 d-flex" style="justify-content: space-between">

                    <button type="button" class="btn btn-warning font-weight-bold" @click="clearForm()">Limpiar</button>

                    <div>
                        <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal" @click="clearForm()">Cerrar</button>
                        
                        <button type="button" class="btn btn-success font-weight-bold"  @click="store()" v-if="action == 'create' && !loading">Crear</button>
                        <button type="button" class="btn btn-success font-weight-bold"  @click="update()" v-if="action == 'edit' && !loading">Actualizar</button>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>