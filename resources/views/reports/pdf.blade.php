<html>

<head>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body{
            margin: 5%;
        }
        header {
            height: 1rem;
            background-color: #3E4B93;
            color: white;
            text-align: center;
            line-height: 30px;
            padding: 20px;
            font-size: 20px;
            font-weight: bolder;
        }

        table {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
        }
        th,
        td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {background-color: #f2f2f2;}
    </style>
</head>

<body>
    <header>
        Reporte de {{$report_type}}
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    @foreach ($titles as $title)
                        <th>{{ $title }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($fields_data as $data)
                    <tr>
                        @foreach ($data as $field)
                            <td>{{ $field }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>
