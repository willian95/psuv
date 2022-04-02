<!-- Modal-->
<div class="modal fade nucleoFamilia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nucleo Familiar de @{{ nucleoFamiliarJefeFamiliaNombre }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="required-field">Tipo de persona</label>
                                <select name="" id="" class="form-control" v-model="nucleoFamiliarTipoPersona">
                                    <option value="1">Adulto cedulado</option>
                                    <option value="2">Adulto no cedulado</option>
                                    <option value="3">Niño cedulado</option>
                                    <option value="4">Niño no cedulado</option>
                                </select>
                                <small class="text-danger" v-if="nucleoFamiliarErrors.hasOwnProperty('tipoPersona')">@{{ nucleoFamiliarErrors['tipoPersona'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-12">

                        </div>

                        <div class="col-md-4" v-if="nucleoFamiliarTipoPersona == 1 || nucleoFamiliarTipoPersona == 3">
                            <div class="form-group">
                                <label for="cedula" class="required-field">Cédula del familiar</label>
                                <div class="d-flex">
                                    <div>
                                        <select class="form-control" v-model="nucleoFamiliarNacionalidad" style="width: 62px;">
                                            <option value="V">V</option>
                                            <option value="E">E</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="tel" class="form-control" id="cedula" v-model="nucleoFamiliarCedula" maxlength="8" @keypress="isNumber($event)">
                                        <small class="text-danger" v-if="nucleoFamiliarErrors.hasOwnProperty('cedula')">@{{ nucleoFamiliarErrors['cedula'][0] }}</small>
                                    </div>
                                    <div >
                                        <button class="btn btn-primary" @click="nucleoFamiliarSearchCedula()" v-if="!isSearchingCedula">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="spinner spinner-primary ml-1 mr-13 mt-5" v-if="isSearchingCedula"></div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre" class="required-field">Nombre familiar</label>
                                <input type="text" class="form-control" id="nombre" v-model="nucleoFamiliarNombre" style="text-transform:uppercase">
                                <small class="text-danger" v-if="nucleoFamiliarErrors.hasOwnProperty('nombre_apellido')">@{{ nucleoFamiliarErrors['nombre_apellido'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4" v-if="nucleoFamiliarTipoPersona == 2 || nucleoFamiliarTipoPersona == 4">
                            <div class="form-group">
                                <label for="nombre" class="required-field">Nacionalidad</label>
                                <select class="form-control" v-model="nucleoFamiliarNacionalidad">
                                    <option value="V">Venezolano</option>
                                    <option value="E">Extranjero</option>
                                </select>
                                <small class="text-danger" v-if="nucleoFamiliarErrors.hasOwnProperty('nacionalidad')">@{{ nucleoFamiliarErrors['nacionalidad'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="municipio" class="required-field">Sexo</label>
                                <select class="form-control" v-model="nucleoFamiliarSexo" id="sexo" >
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                </select>
                                <small class="text-danger" v-if="nucleoFamiliarErrors.hasOwnProperty('sexo')">@{{ nucleoFamiliarErrors['sexo'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fechaNacimiento" class="required-field">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="nucleoFamiliar" v-model="nucleoFamiliarFechaNacimiento">
                                <small class="text-danger" v-if="nucleoFamiliarErrors.hasOwnProperty('fecha_nacimiento')">@{{ nucleoFamiliarErrors['fecha_nacimiento'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="municipio" class="required-field">Estatus</label>
                                <select class="form-control" v-model="nucleoFamiliarEstatusPersonal" id="estatus" >
                                   <option :value="estat.id" v-for="estat in estatus">@{{ estat.estatus }}</option>
                                </select>
                                <small class="text-danger" v-if="nucleoFamiliarErrors.hasOwnProperty('raas_estatus_personal_id')">@{{ nucleoFamiliarErrors['raas_estatus_personal_id'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefonoPrincipal">Teléfono principal</label>
                                <input type="tel" class="form-control" id="telefonoPrincipal" v-model="nucleoFamiliarTelefonoPrincipal" maxlength="11" @keypress="isNumber($event)">
                                <small  class="text-danger" v-if="errors.hasOwnProperty('telefono_principal')">@{{ errors['telefono_principal'][0] }}</small>
                            </div>
                        </div>                        

                        <div class="col-md-4" v-if="nucleoFamiliarTipoPersona == 1 || nucleoFamiliarTipoPersona == 3">
                            <div class="form-group">
                                <label for="tipoVoto">Tipo de voto (opcional)</label>
                                <select class="form-control" v-model="tipoVoto">
                                    <option value="duro">Duro</option>
                                    <option value="blando">Blando</option>
                                    <option value="opositor">Opositor</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4" v-if="nucleoFamiliarTipoPersona == 1 || nucleoFamiliarTipoPersona == 3">
                            <div class="form-group">
                                <label for="partidoPolitico">Partido (opcional)</label>
                                <select class="form-control" v-model="nucleoFamiliarPartido" id="partidoPolitico">
                                    <option value="">Seleccione</option>
                                    <option :value="partidoPolitico.id" v-for="partidoPolitico in partidosPoliticos">@{{ partidoPolitico.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4" v-if="nucleoFamiliarTipoPersona == 1 || nucleoFamiliarTipoPersona == 3">
                            <div class="form-group">
                                <label for="movilizacion">Movilización (opcional)</label>
                                <select class="form-control" v-model="nucleoFamiliarMovilizacion" id="movilizacion">
                                    <option value="">Seleccione</option>
                                    <option :value="movilizacion.id" v-for="movilizacion in movilizaciones">@{{ movilizacion.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <p class="text-center">
                            <button type="button" class="btn btn-success font-weight-bold"  @click="storeNucleoFamiliar()">Crear</button>
                            </p>
                        </div>

                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Sexo</th>
                                        <th>Fecha de nacimiento</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="familiar in nucleoFamiliares">
                                        <td>
                                            <span v-if="familiar.cedula">
                                            @{{ familiar.nacionalidad }}-@{{ familiar.cedula }}
                                            </span>
                                            <span v-if="!familiar.cedula">
                                                No cedulado
                                            </span>
                                        </td>
                                        <td>@{{ familiar.nombre_apellido }}</td>
                                        <td>@{{ familiar.sexo }}</td>
                                        <td>@{{ familiar.fecha_nacimiento }}</td>
                                        <td><button class="btn btn-secondary" @click="nucleoFamiliarRemove(familiar.id)"><i class="fas fa-trash-alt"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>                    
            </div>
            <div class="modal-footer">

        
                   
                   

                <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                
                        

             


                
            </div>
        </div>
    </div>
</div>