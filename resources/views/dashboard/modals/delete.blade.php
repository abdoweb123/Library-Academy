<!-- Delete Modal -->
<div class="modal fade" id="deleteModal_{{ $model->id }}" tabindex="-1" aria-labelledby="deleteModalLabel_{{ $model->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel_{{ $model->id }}">{{ trns('delete') }}</h5>
                <button type="button" class="close px-3 btn btn-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ trns('are_you_sure_you_want_to_delete_this_element') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> {{ trns('cancel') }}</button>
                <form id="deleteModelForm_{{$model->id}}" method="post" action="{{ route($deleteRoute, $model->id) }}">
                    <button type="button" onclick="deleteModel({{ $model->id }})" class="btn btn-danger">{{ trns('delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

