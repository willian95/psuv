<table class="table">
    <thead>
        <tr>
            <td colspan="1">
                <img src="{{ public_path('gob-logo80.jpg') }}" alt="" style="width: 80px;">
            </td>
            <td colspan="15">
                <p style="text-align:center; font-size: 30px; margin-bottom: 20px;">Gobernación del Estado Falcón</p>
            </td>
        </tr>
        <tr>
            <th style="width: 150px; text-align:center; background-color:#00b0f0; ">N°</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0; ">Estado</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0; ">Municipio</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0; ">Parroquia</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0; ">Nombre del CLAP</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0; ">Comunidad</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Jefe de calle</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Calle</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">N° Casa CLAP</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Tipo de vivienda</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Nombre y apellido</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Cédula</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Teléfono</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Fecha nacimiento</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Sexo</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Jefe de familia</th>
            <th style="width: 150px; text-align:center; background-color:#00b0f0;">Cantidad de combos clap</th>
        </tr>
        <tbody>
        @php
            $combosTotales = 0;
            $ordenOperaciones = App\Models\CensoOrdenOperaciones::all();
        @endphp
        @foreach($data as $data)
            <tr>
                <td>
                    {{ $loop->index + 1 }}
                </td>
                <td>
                    {{ $data->estado }}
                </td>
                <td>
                    {{ $data->municipio }}
                </td>
                <td>
                    {{ $data->parroquia }}
                </td>
                <td> 
                    {{ $data->clap }}
                </td>
                <td>
                    {{ $data->comunidad }}
                </td>
                <td>
                    {{ $data->jefe_calle }}
                </td>
                <td>
                    {{ $data->calle }}
                </td>
                <td>
                    {{ $data->num_casa }}
                </td>
                <td>
                    {{ $data->tipo_vivienda }}
                </td>
                <td>
                    {{ $data->nombre_apellido }}
                </td>
                <td>
                    {{ $data->cedula }}
                </td>
                <td>
                    {{ $data->telefono_principal }}
                </td>
                <td>
                    {{ $data->fecha_nacimiento }}
                </td>
                <td>
                    {{ strtoupper($data->sexo) }}
                </td>
                <td>
                    @if($data->cedula == $data->cedula_jefe_familia && $data->cedula != null) X @endif
                </td>
                <td>
                @if($data->cedula == $data->cedula_jefe_familia && $data->cedula != null)
             
                    @php
                        $censoVivienda = App\Models\CensoVivienda::where("id",$data->vivienda_id)->first();
                        
                    @endphp
                        
                    @foreach($ordenOperaciones as $operacion)
                        {{ $operacion->operacion }}
                        @if($operacion->operacion == 'menor' && $censoVivienda->cantidad_habitantes < $operacion->valor_fin)
           
                            @php
                                $combosTotales = $combosTotales + $operacion->cantidad_bolsas;
                            @endphp

                            {{ $operacion->cantidad_bolsas }} combos

                        @elseif($operacion->operacion == 'entre' && ($censoVivienda->cantidad_habitantes >= $operacion->valor_inicio && $censoVivienda->cantidad_habitantes <= $operacion->valor_fin))
                    
                            {{ $operacion->cantidad_bolsas }} combos    
                            @php
                                $combosTotales = $combosTotales + $operacion->cantidad_bolsas;
                            @endphp
                        
                        @elseif($operacion->operacion == 'mayor' && $censoVivienda->cantidad_habitantes > $operacion->valor_inicio)
                           
                            {{ $operacion->cantidad_bolsas }} combos
                            @php
                                $combosTotales = $combosTotales + $operacion->cantidad_bolsas;
                            @endphp

                        @endif

                    @endforeach
        

                @endif
                </td>

            </tr>
        @endforeach
        <tr>
            <td colspan="16"></td>
            <td style="background-color: #e5a503;">{{ $combosTotales }}</td>
        </tr>
    </tbody>
</table>