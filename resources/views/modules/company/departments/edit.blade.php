@extends('layout')

@section('header')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.css') }}">
@endsection

@section('content')
    @include('ajax.modules.company.departments.edit')
@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
        });
    </script>
@endsection