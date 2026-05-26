<div class="modal fade" id="translatorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ trns('add_translator') }}</h5>
            </div>

            <div class="modal-body">
                <input type="text" id="translator_name" class="form-control"
                       placeholder="{{ trns('translator_name') }}">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveTranslator()">
                    {{ trns('save') }}
                </button>
            </div>

        </div>
    </div>
</div>