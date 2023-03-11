<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de votre compte</title>
</head>
<body>
    <h1>{{ $details['title']}}</h1>
    <p>{{ $details['body']}}</p>
    <a href="http://localhost:4200/verif/{{ $details['id']}}">cliquer ici</a>
</body>
</html>
