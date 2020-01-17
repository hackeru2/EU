<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>channels</title>
</head>
<body>
        <ul>
            @foreach ($channels as $channel)
            <li>
            {{$channel->name}}</li>    
            @endforeach
        </ul>
        @include('partials.channels.list')
</body>
</html>