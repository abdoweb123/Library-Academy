<div class="modal fade" id="createSectorModal">

    <div class="modal-dialog">

        <form id="createSectorForm">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>{{ trns('add_sector') }}</h5>
                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>
                            {{ trns('name') }}
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control">

                    </div>                   

                </div>

                <div class="modal-footer">

                    <button class="btn btn-primary">
                        {{ trns('save') }}
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>