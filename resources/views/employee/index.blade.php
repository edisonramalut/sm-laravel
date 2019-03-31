@extends('layouts.crud')
@section('content')
    <div class="container">
        <?php
            $encrypter = app('Illuminate\Encryption\Encrypter');
            $encrypted_token = $encrypter->encrypt(csrf_token());
        ?>

        <input id="token" type="hidden" value="{{$encrypted_token}}">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Manage <b>Employees</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Employee</span></a>
                    </div>
                </div>
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    @foreach($tableColumns as $key => $column)
                        <th>{{$column}}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($records as $record)
                    <tr>
                        @foreach($tableColumns as $key => $column)
                            <td>{{$record->$key}}</td>
                        @endforeach
                        <td>
                            <a href="#" class="edit" data-id="{{$record->id}}" onclick="onEdit(this);return false;" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#" data-id="{{$record->id}}" class="delete" onclick="onDelete(this);return false;" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Edit Modal HTML -->
    <div id="addEmployeeModal" class="modal fade">
        <form action="/employee" method="POST">
            {{csrf_field()}}
            <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Employee</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            @foreach ($columns as $key => $column)
                                <div class="form-group">
                                    <label>{{$column}}</label>
                                    @if ($key == 'email')
                                        <input type="email" name="{{$key}}" class="form-control" required>
                                    @elseif($key == 'password')
                                        <input type="password" name="{{$key}}" class="form-control" required>
                                    @else
                                        <input type="text" name="{{$key}}" class="form-control" required>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <input type="submit" class="btn btn-success" value="Add">
                        </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Edit Modal HTML -->
    <div id="editEmployeeModal" class="editModal modal fade">

    </div>
    <!-- Delete Modal HTML -->
    <div id="deleteEmployeeModal" class="modal deleteModal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" id="deleteForm" method="POST">
                    @method('DELETE')
                    {{csrf_field()}}
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Employee</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this dummy?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


