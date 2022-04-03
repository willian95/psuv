<table class="table">
    <thead>
        <tr>
            <th style="width: 150px;">Municipio</th>
            <th style="width: 150px;">Parroquia</th>
            <th style="width: 150px;">Cédula</th>
            <th style="width: 150px;">Nombre</th>
            <th style="width: 150px;">Teléfono principal</th>
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