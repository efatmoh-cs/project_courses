@extends('layouts.master')

@section('title')
    {{ trans('Grades_trans.title_page') }}
@stop

@section('css')
@endsection

@section('page-header')
    @section('PageTitle')
        {{ trans('main_trans.Grades') }}
    @stop
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">

                    {{-- Display validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

                    {{-- Add Grade Button --}}
                    <button type="button" class="button x-small" data-toggle="modal" data-target="#exampleModal">
                        {{ trans('Grades_trans.add_Grade') }}
                    </button>
                    <br><br>

                    {{-- Grades Table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-sm table-bordered p-0" data-page-length="50" style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{trans('Grades_trans.Name')}}</th>
                                    <th>{{trans('Grades_trans.Notes')}}</th>
                                    <th>{{trans('Grades_trans.processes')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Grades as $index => $Grade)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $Grade->name }}</td>
                                        <td>{{ $Grade->notes }}</td>
                                        <td>
                                            {{-- Edit Button --}}
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#edit{{ $Grade->id }}" title="{{ trans('Grades_trans.Edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            {{-- Delete Button --}}
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete{{ $Grade->id }}" title="{{ trans('Grades_trans.Delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="edit{{ $Grade->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{ route('grades.update', $Grade->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ trans('Grades_trans.edit_Grade') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="{{ $Grade->id }}">
                                                        <div class="form-group">
                                                            <label>{{ trans('Grades_trans.stage_name_ar') }}:</label>
                                                            <input type="text" name="name" value="{{ $Grade->getTranslation('name', 'ar') }}" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ trans('Grades_trans.stage_name_en') }}:</label>
                                                            <input type="text" name="name_en" value="{{ $Grade->getTranslation('name', 'en') }}" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ trans('Grades_trans.Notes') }}:</label>
                                                            <textarea name="notes" class="form-control" rows="3">{{ $Grade->notes }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                                                        <button type="submit" class="btn btn-success">{{ trans('Grades_trans.submit') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Delete Modal --}}
                                    <div class="modal fade" id="delete{{ $Grade->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{ route('grades.destroy', $Grade->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ trans('Grades_trans.delete_Grade') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ trans('Grades_trans.Warning_Grade') }}</p>
                                                        <input type="hidden" name="id" value="{{ $Grade->id }}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                                                        <button type="submit" class="btn btn-danger">{{ trans('Grades_trans.Delete') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- Add Modal --}}
        {{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ trans('Grades_trans.add_Grade') }}</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>{{ trans('Grades_trans.stage_name_ar') }}:</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('Grades_trans.stage_name_en') }}:</label>
                                <input type="text" name="name_en" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('Grades_trans.Notes') }}:</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                            <button type="submit" class="btn btn-success">{{ trans('Grades_trans.submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                    {{ trans('Grades_trans.add_Grade') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- add_form -->
                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf
                    <div class="row">
            <div class="col">
            <label for="name_ar" class="mr-sm-2">{{ trans('Grades_trans.stage_name_ar') }} :</label>
            <input id="name_ar" type="text" name="name[ar]" class="form-control"  required>
        </div>
        <div class="col">
            <label for="name_en" class="mr-sm-2">{{ trans('Grades_trans.stage_name_en') }} :</label>
            <input id="name_en" type="text" class="form-control" name="name[en]"  required>
        </div>
    </div>

    <div class="form-group">
        <label for="notes_ar">{{ trans('Grades_trans.Notes') }} (Arabic):</label>
        <textarea class="form-control" name="notes[ar]" id="notes_ar" rows="3"></textarea>
    </div>

    <div class="form-group">
        <label for="notes_en">{{ trans('Grades_trans.Notes') }} (English):</label>
        <textarea class="form-control" name="notes[en]" id="notes_en" rows="3"></textarea>
    </div>
>
                    <br><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ trans('Grades_trans.Close') }}</button>
                <button type="submit" class="btn btn-success">{{ trans('Grades_trans.submit') }}</button>
            </div>
            </form>

        </div>
    </div>
</div>




    </div>
@endsection

@section('js')
@endsection
