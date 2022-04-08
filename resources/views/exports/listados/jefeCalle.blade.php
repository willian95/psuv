<table class="table">
    <thead>
        <tr>
            <td colspan="1">
                <img src="{{ public_path('gob-logo80.jpg') }}" alt="" style="width: 80px;">
            </td>
            <td colspan="6">
                <p style="text-align:center; font-size: 30px; margin-bottom: 20px;">Gobernación del Estado Falcón</p>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:center; color: #FFFFFF; background-color: #e5a503">Listado de Jefes de Calles</td>
        </tr>
        <tr>
            <th style="width: 150px; text-align:center;">Municipio</th>
            <th style="width: 150px; text-align:center;">Parroquia</th>
            <th style="width: 150px; text-align:center;">Comunidad</th>
            <th style="width: 150px; text-align:center;">Calle</th>
            <th style="width: 150px; text-align:center;">Cédula</th>
            <th style="width: 150px; text-align:center;">Nombre</th>
            <th style="width: 150px; text-align:center;">Teléfono principal</th>
        </tr>
    </thead>

    <tbody style="font-size: 12px;">
        @foreach($data as $data)
            <tr>
                <td>
                    {{ $data->municipio }}
                </td>
                <td>
                    {{ $data->parroquia }}
                </td>
                <td>
                    {{ $data->comunidad }}
                </td>
                <td>
                    {{ $data->calle }}
                </td>
                <td>
                    {{ $data->cedula }}
                </td>
                <td>
                    {{ $data->nombre_apellido }}
                </td>
                <td>
                    {{ $data->telefono_principal }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>