<!-- show -->
@can('show_'.$model->getTable() )
    <a type="button" class="btn text-primary btn-sm mx-0 px-0" data-toggle="modal" data-target="#showModal_{{ $model->id }}">
        <i class="fas fa-eye"></i>
    </a>
@endcan


<!-- edit -->
@can('edit_'.$model->getTable() )
    <a class="edit btn text-black btn-sm mx-0 px-0" data-toggle="modal" data-target="#editModal_{{$model->id}}">
        <i class="fas fa-edit"></i>
    </a>
@endcan


<!-- delete -->
@can('delete_'.$model->getTable() )
    <a type="button" class="btn text-danger btn-sm mx-0 px-0" data-toggle="modal" data-target="#deleteModal_{{ $model->id }}">
        <i class="fas fa-trash-alt"></i>
    </a>
@endcan
