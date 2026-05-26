<div class="modal fade" id="publisherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ trns('add_publisher') }}</h5>
            </div>

            <div class="modal-body">
                <input type="text" id="publisher_name" class="form-control"
                       placeholder="{{ trns('publisher_name') }}">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="savePublisher()">
                    {{ trns('save') }}
                </button>
            </div>

        </div>
    </div>
</div>