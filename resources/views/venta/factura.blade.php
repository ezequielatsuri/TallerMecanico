<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura de Venta</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Encabezado */
        .header {
            text-align: center;
            padding: 20px;
            background-color: #003366;
            color: #fff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        /* Sección de detalles del cliente y vendedor */
        .details-section {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #e6f0fa;
        }
        .details h3 {
            margin: 0 0 10px 0;
            color: #003366;
            font-weight: bold;
        }
        .details p {
            margin: 5px 0;
            color: #333;
        }

        /* Detalles de la venta */
        .items h3 {
            margin: 0;
            padding: 15px;
            background-color: #003366;
            color: #fff;
            text-align: center;
            font-weight: bold;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items th, .items td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .items th {
            background-color: #003366;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }
        .items tfoot td {
            font-weight: bold;
            padding: 10px;
        }
        .items tfoot td:nth-child(3),
        .items tfoot td:nth-child(4) {
            text-align: right;
        }
        .items tfoot tr td:nth-child(1),
        .items tfoot tr td:nth-child(3),
        .items tfoot tr td:nth-child(4) {
            border: none;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #e6f0fa;
            color: #333;
            font-size: 14px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Factura de Venta</h1>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y') }}</p>
        <p><strong>Número de Comprobante:</strong> {{ $venta->numero_comprobante }}</p>
    </div>

    <div class="details-section">
        <div class="details">
            <h3>Cliente</h3>
            <p><strong>Nombre:</strong> {{ $venta->cliente->persona->razon_social }}</p>
            <p><strong>Dirección:</strong> {{ $venta->cliente->persona->direccion }}</p>
            <p><strong>Teléfono:</strong> {{ $venta->cliente->persona->telefono ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $venta->cliente->persona->email ?? 'N/A' }}</p>
        </div>
        
        <div class="details">
            <h3>Vendedor</h3>
            <p><strong>Nombre:</strong> {{ $venta->user->name }}</p>
            <p><strong>Email:</strong> {{ $venta->user->email }}</p>
        </div>
    </div>

    <div class="items">
        <h3>Detalles de la Venta</h3>
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Producto/Servicio</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalProductos = 0; 
                    $totalServicios = 0;
                    $impuestoPorcentaje = 0.16; // 16% de impuesto
                @endphp

                <!-- Listado de Productos -->
                @foreach($venta->productos as $producto)
                @php
                    $subtotal = $producto->pivot->cantidad * $producto->pivot->precio_venta;
                    $totalProductos += $subtotal;
                @endphp
                <tr>
                    <td>Producto</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->pivot->cantidad }}</td>
                    <td>${{ number_format($producto->pivot->precio_venta, 2) }}</td>
                    <td>${{ number_format($subtotal, 2) }}</td>
                </tr>
                @endforeach

                <!-- Listado de Servicios -->
                @foreach($venta->servicios as $servicio)
                @php
                    $subtotalServicio = $servicio->pivot->precio; // Asumiendo cantidad = 1
                    $totalServicios += $subtotalServicio;
                @endphp
                <tr>
                    <td>Servicio</td>
                    <td>{{ $servicio->nombre }}</td>
                    <td>N/A</td>
                    <td>${{ number_format($servicio->pivot->precio, 2) }}</td>
                    <td>${{ number_format($subtotalServicio, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $subtotalGeneral = $totalProductos + $totalServicios;
                    $impuesto = $subtotalGeneral * $impuestoPorcentaje;
                    $totalConImpuesto = $subtotalGeneral + $impuesto;
                @endphp
                <tr>
                    <td colspan="4" style="text-align: right;">Subtotal</td>
                    <td>${{ number_format($subtotalGeneral, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Impuesto ({{ $impuestoPorcentaje * 100 }}%)</td>
                    <td>${{ number_format($impuesto, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Total</td>
                    <td>${{ number_format($totalConImpuesto, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p><strong>Condiciones de Pago:</strong> Transferencia bancaria, pago a 30 días</p>
        <p><strong>Condiciones de Entrega:</strong> Envío a la dirección del cliente en un plazo de 7 días hábiles</p>
    </div>
</div>

<script>
    window.print();
</script>

</body>
</html>
