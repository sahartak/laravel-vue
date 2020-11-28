@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        @if(Illuminate\Support\Facades\Session::has('error'))
                            <div class="alert alert-danger">
                                <ul>
                                    {{Illuminate\Support\Facades\Session::get('error')}}
                                </ul>
                            </div>
                        @endif
                        <div id="app">
                            <login-page></login-page>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
