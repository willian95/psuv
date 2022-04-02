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
                                <label for="cedula" class="required-field">Cédula Jefe Calle</label>
                                <div class="d-flex">
                                    <div>
                                        <select class="form-control" :disabled="readonlyJefeCalle" v-model="jefeCalleNacionalidad" style="width: 62px;">
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="tel" :disabled="readonlyJefeCalle" class="form-control" id="jefeCalleCedula" v-model="jefeCalleCedula" maxlength="8" @keypress="isNumber($event)" :disabled="readonlyJefeCalle">
                                        <small class="text-danger" v-if="errors.hasOwnProperty('cedula')">@{{ errors['jefeClapId'][0] }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" :disabled="readonlyJefeCalle" @click="searchJefeCalleByCedula()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jefeCalleNombre" class="required-field">Nombre jefe calle</label>
                                <input type="text" class="form-control" id="jefeCalleNombre" v-model="jefeCalleNombre" :disabled="readonlyJefeCalle">
                                <small v-if="errors.hasOwnProperty('nombre')">@{{ errors['nombre'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calleNombre" class="required-field">Calle</label>
                                <input type="text" class="form-control" id="calleNombre" v-model="calleNombre" disabled>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <label for="">Tipo de vivienda</label>
                            <select class="form-control" v-model="tipoCasa" :disabled="readonlyJefeCalle">
                                <option value="casa">Casa</option>
                                <option value="anexo">Anexo</option>
                            </select>
                        </div>

                        <div class="col-md-4" v-if="tipoCasa == 'anexo'">
                            <label for="">Casas</label>
                            <select class="form-control" v-model="selectedCasa" :disabled="readonlyJefeCalle">
                                <option :value="casa.id" v-for="casa in casas">@{{ casa.codigo }}</option>
                            </select>
                            <small v-if="errors.hasOwnProperty('selectedCasa')">@{{ errors['selectedCasa'][0] }}</small>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numeroFamilias" class="required-field">Número de familas</label>
                                <input type="text" class="form-control" id="numeroFamilias" v-model="numeroFamilias" @keypress="isNumber($event)">
                                <small v-if="errors.hasOwnProperty('numeroFamilias')">@{{ errors['numeroFamilias'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="numeroFamilias">Dirección de la casa (opcional)</label>
                                <input class="form-control" v-model="direccion">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cedula" class="required-field">Cédula jefe Familia</label>
                                <div class="d-flex">
                                    <div>
                                        <select class="form-control" v-model="nacionalidad" style="width: 62px;">
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="cedula" maxlength="8" @keypress="isNumber($event)">
                                        <small class="text-danger" v-if="errors.hasOwnProperty('cedula')">@{{ errors['cedula'][0] }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="searchCedula()" v-if="!isSearchingCedula">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="isSearchingCedula"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre" class="required-field">Nombre jefe familia</label>
                                <input type="text" class="form-control" id="nombre" v-model="nombre">
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
                                <label for="fechaNacimiento" class="required-field">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fechaNacimiento" v-model="fechaNacimiento">
                                <small v-if="errors.hasOwnProperty('fecha_nacimiento')">@{{ errors['fecha_nacimiento'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="municipio" class="required-field">Estatus</label>
                                <select class="form-control" v-model="selectedEstatus" id="estatus" >
                                   <option :value="estat.id" v-for="estat in estatus">@{{ estat.estatus }}</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('estatus')">@{{ errors['estatus'][0] }}</small>
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
                        <button type="button" class="btn btn-warning font-weight-bold" @click="clearForm()" v-if="action == 'create' && !storeLoader">Limpiar</button>

                    </div>
                    <div class="col-6 d-flex justify-content-end">

                        <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                
                        <button type="button" :disabled="disabledStoreButton" class="btn btn-success font-weight-bold"  @click="store()" v-if="action == 'create' && !storeLoader">Crear</button>
                        <button type="button" class="btn btn-success font-weight-bold"  @click="update()" v-if="action == 'edit' && !updateLoader">Actualizar</button>
                        <button type="button" class="btn btn-success font-weight-bold"  @click="remove()" v-if="action == 'suspend' && !suspendLoader">Suspender</button>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="storeLoader"></div>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="updateLoader"></div>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="suspendLoader"></div>

                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>