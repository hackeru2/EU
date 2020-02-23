<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ url('') }}" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/profile-cropper.css') }}" rel="stylesheet">

    <link href="{{ asset('css/bootstrap-grid.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile-blue.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/profile.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/family.css') }}" rel="stylesheet"> 
    <link href="{{ asset('css/loading.scss') }}" rel="stylesheet">  
    <link rel="stylesheet" href="{{ asset('css/my-spinner.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/display.css">
    <!-- import JavaScript -->

    <title>Document</title>
 
    <script src="{{ asset('js/app.js') }}" defer></script>
     
    
</head>
<body>
    
    <div id="app">
    
    </div>
  
</body>
</html>
 
