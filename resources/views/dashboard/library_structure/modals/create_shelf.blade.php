<div class="modal fade" id="shelfModal">

    <div class="modal-dialog">

        <form id="shelfForm">

            @csrf
            <input type="hidden" name="cabinet_id" id="shelf_cabinet_id">

            <div class="modal-content">

                <div class="modal-header">
                    <h5>{{ trns('add_shelf') }}</h5>
                </div>

                <div class="modal-body">

                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="{{ trns('shelf_name') }}">

                    <div class="invalid-feedback d-block name-error"></div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-warning">{{ trns('save') }}</button>
                </div>

            </div>

        </form>

    </div>

</div>