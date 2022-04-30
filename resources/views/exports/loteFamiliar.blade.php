<table>
    <thead>
    <tr>
        <th>Código</th>
        <th>Tipo vivienda</th>
        <th>Cantidad Habitantes</th>
        <th>Cantidad Familias</th>
        <th>Cédula Jefe Familia</th>
        <th>Nombre</th>
        <th>Cédula Familia</th>
        <th>Teléfono</th>
        <th>Fecha nacimiento</th>
        <th>Sexo</th>
        <th>Es jefe</th>
        <th>Motivo rechazo</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result[0] }}</td>
            <td>{{ $result[1] }}</td>
            <td>{{ $result[2] }}</td>
            <td>{{ $result[3]}}</td>
            <td>{{ $result[4] }}</td>
            <td>{{ $result[5] }}</td>
            <td>{{ $result[6] }}</td>
            <td>{{ $result[7] }}</td>
            <td>{{ $result[8] }}</td>
            <td>{{ $result[9] }}</td>
            <td>{{ $result[10] }}</td>
            <td>{{ $result[11] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>