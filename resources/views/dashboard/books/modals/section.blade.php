<div class="modal fade" id="sectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ trns('add_section') }}</h5>
            </div>

            <div class="modal-body">
                <input type="text" id="section_name" class="form-control"
                       placeholder="{{ trns('section_name') }}">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveSection()">
                    {{ trns('save') }}
                </button>
            </div>

        </div>
    </div>
</div>