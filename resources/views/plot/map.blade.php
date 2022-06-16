@extends('layouts.appbs')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Add Plots') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    @can('isAdmin')
                        <div id="app">
                            <map-view v-bind:is-admin="true"></map-view>
                        </div>
                    @elsecan('isFrontDesk')
                        <div id="app">
                            <map-view></map-view>
                        </div>
                   @elsecan('isClient')
                        <div id="app">
                            <map-view></map-view>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
