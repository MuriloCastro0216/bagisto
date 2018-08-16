@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.channels.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.channels.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.settings.channels.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('channels','Webkul\Admin\DataGrids\ChannelsDataGrid')
            {!! $channels->render() !!}
        </div>
    </div>
@stop