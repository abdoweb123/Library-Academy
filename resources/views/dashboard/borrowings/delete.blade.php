<a type="button"
   class="btn text-danger btn-sm mx-0 px-0"
   data-toggle="modal"
   data-target="#deleteModal_{{ $model->id }}">
    <i class="fas fa-trash-alt"></i>
</a>

@include('dashboard.modals.delete')