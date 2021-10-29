<table>
    <thead>
    <tr>
        <th>Institución</th>
        <th>Cedula trabajador</th>
        <th>Trabajador</th>
        <th>Teléfono trabajador</th>
        <th>Municipio</th>
        <th>Parroquia</th>
        <th>Cedula</th>
        <th>Familiar</th>
        <th>Teléfono principal</th>
        <th>Código</th>
        <th>Movilización</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->institucion }}</td>
            <td>{{ $result->cedula_trabajador }}</td>
            <td>{{ $result->trabajador }}</td>
            <td>{{ $result->telefono_trabajador }}</td>
            <td>{{ $result->municipio }}</td>
            <td>{{ $result->parroquia }}</td>
            <td>{{ $result->cedula }}</td>
            <td>{{ $result->familiar }}</td>
            <td>{{ $result->telefono_principal }}</td>
            <td>{{ $result->codigo }}</td>
            <td>{{ $result->nombre }}</td>
            <td>{{ $result->movilizacion_nombre }}</td>
        </tr>
    @endforeach
    </tbody>
</table>