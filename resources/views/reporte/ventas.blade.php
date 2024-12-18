<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 100px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .details { margin-bottom: 10px; font-size: 12px; }
        .total-row { font-weight: bold; background-color: #e0e0e0; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $logo }}" alt="Logo" class="logo">
        <h2>Reporte de Ventas</h2>
        <p>{{ $fecha_filtro }}</p>
    </div>

    <div class="details">
        <p>Fecha del Reporte: {{ $fecha_reporte }}</p>
        <p>Generado por: {{ $usuario }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Comprobante</th>
                <th>Cliente</th>
                <th>Fecha y Hora</th>
                <th>Vendedor</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneral = 0; // Variable para almacenar el total de todas las ventas
            @endphp
            @foreach ($ventas as $index => $venta)
            @php
                $totalGeneral += $venta->total; // Sumar el total de la venta actual
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $venta->comprobante->tipo_comprobante ?? 'N/A' }}</td>
                <td>{{ optional($venta->cliente->persona)->razon_social ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d-m-Y H:i') }}</td>
                <td>{{ $venta->user->name ?? 'N/A' }}</td>
                <td>{{ number_format($venta->total, 2) }}</td>
            </tr>
            @endforeach
            <!-- Agregar fila de total general -->
            <tr class="total-row">
                <td colspan="5">Total General</td>
                <td>{{ number_format($totalGeneral, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
