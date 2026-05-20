@extends('layouts.admin')
@section('content')
    <div class="card">


        <div class="card-body">
            <div class="form-group">

                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                Checkin Id
                            </th>
                            <td>
                                {{ $checkIn->checkin_id ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Reservation Id
                            </th>
                            <td>
                                {{ $checkIn->reservation_id ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Nombres
                            </th>
                            <td>
                                {{ $checkIn->firstname ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Apellidos
                            </th>
                            <td>
                                {{ $checkIn->lastname ?? '' }} {{ $checkIn->lastname2 ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Email
                            </th>
                            <td>
                                {{ $checkIn->email ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Mobile
                            </th>
                            <td>
                                {{ $checkIn->mobile ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                No. Documento
                            </th>
                            <td>
                                {{ $checkIn->document_number }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Fecha Expedición Documento
                            </th>
                            <td>
                                {{ $checkIn->document_expedition_date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Documento Soporte
                            </th>
                            <td>
                                {{ $checkIn->document_support_number }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Genero
                            </th>
                            <td>
                                {{ $checkIn->gender }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Fecha Nacimiento
                            </th>
                            <td>
                                {{ $checkIn->birthdate }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Dirección Residencia
                            </th>
                            <td>
                                {{ $checkIn->residence_street }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Código Postal
                            </th>
                            <td>
                                {{ $checkIn->zip }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Pais 
                            </th>
                            <td>
                                {{ $checkIn->country_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Nacionalidad
                            </th>
                            <td>
                                {{ $checkIn->nationality_name }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                {{ trans('cruds.checkIn.fields.created_at') }}
                            </th>
                            <td>
                                {{ $checkIn->created_at }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-warning" href="{{ route('admin.check-ins.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
