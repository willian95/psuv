<!-- Modal-->
<div class="modal fade marketModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva orden de operación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm()">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle" class="required-field">Operación </label>
                                <select class="form-control" v-model="selectedOperacion" @change="() => { rangoMenor=''; rangoMayor=''; }">
                                    <option value="entre">Entre</option>
                                    <option value="mayor">Mayor</option>
                                    <option value="menor">Menor</option>
                                </select>
                                <small class="text-danger" v-if="errors.hasOwnProperty('selectedOperacion')">@{{ errors['selectedOperacion'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle" class="required-field">Rango menor </label>
                                <input type="text" class="form-control" v-model="rangoMenor" @keypress="isNumber($event)" :disabled="selectedOperacion == 'menor'">
                                <small class="text-danger" v-if="errors.hasOwnProperty('rangoMenor')">@{{ errors['rangoMenor'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Rango mayor </label>
                                <input type="text" class="form-control" v-model="rangoMayor" :disabled="selectedOperacion == 'mayor'" @keypress="isNumber($event)">
                                <small class="text-danger" v-if="errors.hasOwnProperty('rangoMayor')">@{{ errors['rangoMayor'][0] }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="calle">Cantidad bolsas </label>
                                <input type="text" class="form-control" v-model="cantidadBolsas" @keypress="isNumber($event)">
                                <small class="text-danger" v-if="errors.hasOwnProperty('cantidadBolsas')">@{{ errors['cantidadBolsas'][0] }}</small>
                            </div>
                        </div>

                    </div>
                </div>                    
            </div>
            <div class="modal-footer">
                <div class="w-100 d-flex" style="justify-content: space-between">

                    <button type="button" class="btn btn-warning font-weight-bold" @click="clearForm()">Limpiar</button>

                    <div>
                        <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal" @click="clearForm()">Cerrar</button>
                        
                        <button type="button" class="btn btn-success font-weight-bold"  @click="store()" v-if="!loading">Crear</button>
                        <div class="spinner spinner-primary ml-1 mr-13 mt-2" v-if="loading"></div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>