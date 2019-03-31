<div class="modal-dialog">
    <div class="modal-content">
        <form action="employee/{{$record->id}}" method="POST">
            @method('PUT')
            {{csrf_field()}}
            <div class="modal-header">
                <h4 class="modal-title">Edit Employee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                @foreach ($columns as $key => $column)
                    <div class="form-group">
                        <label>{{$column}}</label>
                        @if ($key == 'email')
                            <input type="email" value="{{$record->$key}}" name="{{$key}}" class="form-control" required>
                        @elseif($key == 'password')
                            <input type="password" value="{{$record->$key}}" name="{{$key}}" class="form-control" required>
                        @else
                            <input type="text" value="{{$record->$key}}" name="{{$key}}" class="form-control" required>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                <input type="submit" class="btn btn-info" value="Save">
            </div>
        </form>
    </div>
</div>