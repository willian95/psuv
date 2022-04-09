<table class="table">
    <thead>
        <tr>
            <td colspan="1">
                <img src="{{ public_path('gob-logo80.jpg') }}" alt="" style="width: 80px;">
            </td>
            <td colspan="5">
                <p style="text-align:center; font-size: 30px; margin-bottom: 20px;">Gobernación del Estado Falcón</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color:#c0c0c0; color: #FFFFFF; text-align:center;"></td>
            <td colspan="3" style="text-align:center; color: #FFFFFF; background-color: #ffcc00">ENLACE MUNICIPAL</td>
            <td colspan="4" style="text-align:center; color: #FFFFFF; background-color: #008000">JEFE DE CLAP</td>
            <td colspan="4" style="text-align:center; color: #FFFFFF; background-color: #003366">JEFE DE COMUNIDAD</td>
            <td colspan="4" style="text-align:center; color: #FFFFFF; background-color: #ccccff">JEFE DE CALLE</td>
        </tr>
        <tr>
            <th style="width: 150px; text-align:center; background-color:#c0c0c0; color: #FFFFFF;">Estado</th>
            <th style="width: 150px; text-align:center; background-color:#c0c0c0; color: #FFFFFF;">Municipio</th>
            <th style="width: 150px; text-align:center; background-color:#c0c0c0; color: #FFFFFF;">Parroquia</th>
            <th style="width: 150px; text-align:center; background-color:#ffcc00; color: #FFFFFF;">Cédula</th>
            <th style="width: 150px; text-align:center; background-color:#ffcc00; color: #FFFFFF;">Nombre y apellido</th>
            <th style="width: 150px; text-align:center; background-color:#ffcc00; color: #FFFFFF;">Teléfono</th>
            <th style="width: 150px; text-align:center; background-color:#008000; color: #FFFFFF;">Nombre del CLAP</th>
            <th style="width: 150px; text-align:center; background-color:#008000; color: #FFFFFF;">Cédula</th>
            <th style="width: 150px; text-align:center; background-color:#008000; color: #FFFFFF;">Nombre y apellido</th>
            <th style="width: 150px; text-align:center; background-color:#008000; color: #FFFFFF;">Teléfono</th>
            <th style="width: 150px; text-align:center; background-color:#003366; color: #FFFFFF;">Comunidad</th>
            <th style="width: 150px; text-align:center; background-color:#003366; color: #FFFFFF;">Cédula</th>
            <th style="width: 150px; text-align:center; background-color:#003366; color: #FFFFFF;">Nombre y apeliido</th>
            <th style="width: 150px; text-align:center; background-color:#003366; color: #FFFFFF;">Teléfono</th>
            <th style="width: 150px; text-align:center; background-color:#ccccff;">Calle</th>
            <th style="width: 150px; text-align:center; background-color:#ccccff;">Cédula</th>
            <th style="width: 150px; text-align:center; background-color:#ccccff;">Nombre y apellido</th>
            <th style="width: 150px; text-align:center; background-color:#ccccff;">Teléfono</th>
        </tr>
    </thead>

    <tbody style="font-size: 12px;">
        @foreach($data as $data)
            <tr>
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
                    {{ $data->cedula_enlace_municipal }}
                </td>
                <td>
                    {{ $data->enlace_municipal }}
                </td>
                <td>
                    {{ $data->telefono_enlace_municipal }}
                </td>
                <td>
                    {{ $data->clap }}
                    
                </td>
                <td>
                    {{ $data->cedula_jefe_clap }}
                </td>
                <td>
                    {{ $data->jefe_clap }}
                </td>
                <td>
                    {{ $data->telefono_jefe_clap }}
                </td>
                <td>
                    {{ $data->comunidad }}
                </td>
                <td>
                    {{ $data->cedula_jefe_comunidad }}
                    
                    
                </td>
                <td>
                    {{ $data->jefe_comunidad }}
                </td>
                <td>
                    {{ $data->telefono_jefe_comunidad }}
                </td>
                <td>
                    {{ $data->calle }}
                </td>
                <td>
                    {{ $data->cedula_jefe_calle }}
                </td>
                <td>
                    {{ $data->jefe_calle }}
                </td>
                <td>
                    {{ $data->telefono_jefe_calle }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>