<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra</title>
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

        /* Sección de detalles del cliente y proveedor */
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

        /* Detalles de la compra */
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
        .items tfoot td:nth-child(3) {
            text-align: right;
        }
        .items tfoot tr td:nth-child(1),
        .items tfoot tr td:nth-child(3) {
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
        <h1>Orden de Compra</h1>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($compra->fecha_hora)->format('d/m/Y') }}</p>
        <p><strong>Número de Orden:</strong> OC-{{ $compra->id }}</p>
    </div>

    <div class="details-section">
        <div class="details">
            <h3>Comprador</h3>
            <p><strong>Nombre:</strong> Nombre de la Empresa XYZ</p>
            <p><strong>Dirección:</strong> Dirección del Comprador</p>
            <p><strong>Teléfono:</strong> (123) 456-7890</p>
            <p><strong>Email:</strong> compras@xyz.com</p>
        </div>
        
        <div class="details">
            <h3>Proveedor</h3>
            <p><strong>Nombre:</strong> {{ $compra->proveedore->persona->razon_social }}</p>
            <p><strong>Tipo de Persona:</strong> {{ ucfirst($compra->proveedore->persona->tipo_persona) }}</p>
            <p><strong>Dirección:</strong> {{ $compra->proveedore->persona->direccion }}</p>
            {{--
                <p><strong>Teléfono:</strong> {{ $compra->proveedore->persona->telefono ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $compra->proveedore->persona->email ?? 'N/A' }}</p>
                
                --}}

           
        </div>
    </div>

    <div class="items">
        <h3>Detalles de la Compra</h3>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $total = 0; 
                    $impuestoPorcentaje = 0.16; // 16% de impuesto
                @endphp
                @foreach($compra->productos as $producto)
                @php
                    $subtotal = $producto->pivot->cantidad * $producto->pivot->precio_compra;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->pivot->cantidad }}</td>
                    <td>${{ number_format($producto->pivot->precio_compra, 2) }}</td>
                    <td>${{ number_format($subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $impuesto = $total * $impuestoPorcentaje;
                    $totalConImpuesto = $total + $impuesto;
                @endphp
                <tr>
                    <td colspan="3" style="text-align: right;">Subtotal</td>
                    <td>${{ number_format($total, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Impuesto ({{ $impuestoPorcentaje * 100 }}%)</td>
                    <td>${{ number_format($impuesto, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Total</td>
                    <td>${{ number_format($totalConImpuesto, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p><strong>Condiciones de Pago:</strong> Transferencia bancaria, pago a 30 días</p>
        <p><strong>Condiciones de Entrega:</strong> Envío a la dirección del comprador en un plazo de 7 días hábiles</p>
    </div>
</div>

<script>
    window.print();
</script>

</body>
</html>
