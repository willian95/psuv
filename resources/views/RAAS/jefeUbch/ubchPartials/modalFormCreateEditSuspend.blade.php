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
                                <label for="cedula" class="required-field">Cédula</label>
                                <div class="d-flex">
                                    <div>
                                        <select class="form-control" v-model="nacionalidad" style="width: 62px;">
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedula" :readonly="readonlyCedula" maxlength="8" @keypress="isNumber($event)">
                                        <small class="text-danger" v-if="errors.hasOwnProperty('cedula')">@{{ errors['cedula'][0] }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="searchCedula()" v-if="!cedulaSearching">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="cedulaSearching"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre" class="required-field">Nombre</label>
                                <input type="text" class="form-control" id="nombre" v-model="nombre" readonly>
                                <small v-if="errors.hasOwnProperty('nombre')">@{{ errors['nombre'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="municipio" class="required-field">Sexo</label>
                                <select class="form-control" v-model="sexo" id="sexo" >
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('estado')">@{{ errors['estado'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="municipio" class="required-field">Estado</label>
                                <select class="form-control" v-model="selectedEstado" id="estado" @change="getMunicipios()" :disabled="readonlyState">
                                    <option value="">Seleccione</option>
                                    <option :value="estado.id" v-for="estado in estados">@{{ estado.nombre }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('estado')">@{{ errors['estado'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="municipio" class="required-field">Municipio</label>
                                <select class="form-control" v-model="selectedMunicipio" id="municipio" @change="getParroquias()" :disabled="readonlyMunicipio">
                                    <option value="">Seleccione</option>
                                    <option :value="municipio.id" v-for="municipio in municipios">@{{ municipio.nombre }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('municipio')">@{{ errors['municipio'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parroquia" class="required-field">Parroquia</label>
                                <select class="form-control" v-model="selectedParroquia" id="parroquia" @change="getCentroVotacion()" :disabled="readonlyParroquia">
                                    <option value="">Seleccione</option>
                                    <option :value="parroquia.id" v-for="parroquia in parroquias">@{{ parroquia.nombre }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('parroquia')">@{{ errors['parroquia'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="centroVotacion" class="required-field">UBCH</label>
                                <select class="form-control" v-model="selectedCentroVotacion" id="centroVotacion" :disabled="readonlyCentroVotacion">
                                    <option value="">Seleccione</option>
                                    <option :value="centroVotacion.id" v-for="centroVotacion in centroVotaciones">@{{ centroVotacion.nombre }}</option>
                                </select>
                                <small  class="text-danger" v-if="errors.hasOwnProperty('centro_votacion_id')">@{{ errors['centro_votacion_id'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefonoPrincipal" class="required-field">Teléfono principal</label>
                                <input type="tel" class="form-control" id="telefonoPrincipal" v-model="telefonoPrincipal" maxlength="11" @keypress="isNumber($event)">
                                <small  class="text-danger" v-if="errors.hasOwnProperty('telefono_principal')">@{{ errors['telefono_principal'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefonoSecundario">Teléfono secundario (opcional)</label>
                                <input type="tel" class="form-control" id="telefonoSecundario" v-model="telefonoSecundario" maxlength="11" @keypress="isNumber($event)">
                                <small  class="text-danger" v-if="errors.hasOwnProperty('telefono_secundario')">@{{ errors['telefono_secundario'][0] }}</small>
                            </div>
                        </div>

                        

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipoVoto">Tipo de voto (pocional)</label>
                                <select class="form-control" v-model="tipoVoto">
                                    <option value="duro">Duro</option>
                                    <option value="blando">Blando</option>
                                    <option value="opositor">Opositor</option>
                                </select>
                                <small  class="text-danger" v-if="errors.hasOwnProperty('tipo_voto')">@{{ errors['tipo_voto'][0] }}</small>
                            </div>
                        </div>

                        

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="partidoPolitico">Partido (opcional)</label>
                                <select class="form-control" v-model="selectedPartidoPolitico" id="partidoPolitico">
                                    <option value="">Seleccione</option>
                                    <option :value="partidoPolitico.id" v-for="partidoPolitico in partidosPoliticos">@{{ partidoPolitico.nombre }}</option>
                                </select>
                                <small  class="text-danger" v-if="errors.hasOwnProperty('partido_politico_id')">@{{ errors['partido_politico_id'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="movilizacion">Movilización (opcional)</label>
                                <select class="form-control" v-model="selectedMovilizacion" id="movilizacion">
                                    <option value="">Seleccione</option>
                                    <option :value="movilizacion.id" v-for="movilizacion in movilizaciones">@{{ movilizacion.nombre }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('movilizacion_id')">@{{ errors['movilizacion_id'][0] }}</small>
                            </div>
                        </div>
                
                        
                
                    </div>
                </div>                    
            </div>
            <div class="modal-footer">

                <div class="row w-100">
                    <div class="col-6">
                        <button type="button" class="btn btn-light-warning font-weight-bold" @click="clearForm()" v-if="action == 'create' && !storeLoader">Limpiar</button>

                    </div>
                    <div class="col-6 d-flex justify-content-end">

                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                
                        <button type="button" class="btn btn-primary font-weight-bold"  @click="store()" v-if="action == 'create' && !storeLoader">Crear</button>
                        <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit' && !updateLoader">Actualizar</button>
                        <button type="button" class="btn btn-primary font-weight-bold"  @click="remove()" v-if="action == 'suspend' && !suspendLoader">Suspender</button>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="storeLoader"></div>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="updateLoader"></div>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="suspendLoader"></div>

                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>