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
                    {{ $data->sexo }}
                </td>
                <td>
                    @if($data->cedula == $data->cedula_jefe_familia) X @endif
                </td>
                <td>
                    0
                </td>

            </tr>
        @endforeach
    </tbody>
</table>