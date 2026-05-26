<div class="modal fade" id="authorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ trns('add_author') }}</h5>
            </div>

            <div class="modal-body">
                <input type="text" id="author_name" class="form-control"
                       placeholder="{{ trns('author_name') }}">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveAuthor()">
                    {{ trns('save') }}
                </button>
            </div>

        </div>
    </div>
</div>