<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
<!--         <link href="{{ asset('library/css/confirm.css?v='. $version) }}" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vuetify-cabinet.css?v='.$version)}}" /> -->
        <title>Laravel Vuetify</title>
    </head>
  <body>
    
    <div id="app">
        <index></index>
    </div>
    <script type="text/javascript">
        var _token = '<?= csrf_token() ?>';
    </script>
    <script src="{{ asset('js/app.js?v='. $version) }}"></script>
  </body>
</html>