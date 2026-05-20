@extends('layouts.admin')

@section('title', $config['title_singular'])

@section('content')
    <div class="row mb-2">
        <div class="col-md-6">

        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('admin.crud.csvImport.modal', [
                'config' => $config,
            ])

            @can($config['id'] . '_create')
                <button type="button" class="btn btn-sm btn-success" onclick="crudApp.create_modal()">
                    {{ trans('global.add') }} {{ $config['title_singular'] }}
                </button>
            @endcan

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }}   {{ trans('cruds.' . $config['id'] . '.title') }}
        </div>

        <div class="card-body">
            @include($config['list_view'], [
                'config' => $config,
            ])
        </div>
    </div>

    @include('admin.crud.modal', ['id' => $config['id'] . '_modal', 'size' => 'modal-lg'])


@endsection
@section('scripts')
    @parent
    @include($config['list_js_view'], [
        'config' => $config,
    ])

    @include('admin.crud.app', [
        'form_id' => $config['id'] . '_form',
        'modal_id' => $config['id'] . '_modal',
        'title' => $config['title_singular'],
        'table_id' => $config['id'] . '_datatable',
        'url' => $config['url'],
    ])

@endsection
