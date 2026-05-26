
<div class="modal fade" id="rowModal">

    <div class="modal-dialog">

        <form id="rowForm">

            @csrf
            <input type="hidden" name="sector_id" id="row_sector_id">

            <div class="modal-content">

                <div class="modal-header">
                    <h5> {{ trns('add_row') }}</h5>
                </div>

                <div class="modal-body">

                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="{{  trns('row_name') }}">
                           
                    <div class="invalid-feedback d-block name-error"></div>

                    <select name="side" class="form-control mt-3">
                        <option value="front">{{ trns('front') }}</option>
                        <option value="back">{{ trns('back') }}</option>
                    </select>
                    
                <div class="invalid-feedback d-block side-error"></div>

                <div class="invalid-feedback d-block sector_id-error"></div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">{{ trns('save') }}</button>
                </div>

            </div>

        </form>

    </div>

</div>