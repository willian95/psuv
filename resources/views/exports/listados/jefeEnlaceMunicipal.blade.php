<table class="table">
    <thead>
        <tr>
            <td colspan="1">
                <img src="{{ public_path('gob-logo80.jpg') }}" alt="" style="width: 80px;">
            </td>
            <td colspan="4">
                <p style="text-align:center; font-size: 30px; padding-bottom: 20px;">Gobernación del Estado Falcón</h3>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:center; color: #FFFFFF; background-color: #e5a503">Listado de enlace municipal</td>
        </tr>
        <tr>
            <th style="width: 150px; text-align:center;">Municipio</th>
            <th style="width: 150px; text-align:center;">Parroquia</th>
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