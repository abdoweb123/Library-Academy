<div class="modal fade" id="cabinetModal">

    <div class="modal-dialog">

        <form id="cabinetForm">

            @csrf
            <input type="hidden" name="row_id" id="cabinet_row_id">

            <div class="modal-content">

                <div class="modal-header">
                    <h5>{{ trns('add_cabinet ') }}</h5>
                </div>

                <div class="modal-body">

                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="{{ trns('cabinet_name') }}">

                <div class="invalid-feedback d-block name-error"></div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">{{ trns('save') }}</button>
                </div>

            </div>

        </form>

    </div>

</div>