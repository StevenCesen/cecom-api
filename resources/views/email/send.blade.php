<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facturación</title>
</head>
<body>
    <h4>Señor(a) {{ request('client_name') }}</h4>
    <p>Adjunto se encuentra su comprobante electrónico emitido por {{request('contributor_name')}}, por concepto de {{ request('concept') }}.</p>
    
    <footer>
        <cite>Mensaje enviado por el sistema de facturación electrónica ANYPLACE</cite>
    </footer>
</body>
</html>
