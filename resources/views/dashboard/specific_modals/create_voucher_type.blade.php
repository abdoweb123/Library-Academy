<!-- Create Modal -->
<div class="modal fade" id="createVoucherType" tabindex="-1" aria-labelledby="createVoucherTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createVoucherTypeModalLabel">{{ trns('add_voucher_type') }}</h5>
                <button type="button" class="close px-3 btn btn-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="createForm" method="post" action="{{ route('dashboard.voucherTypes.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content-center">

                        <div class="row col-md-12 col-sm-12 mt-2">
                            <label class="p-0">{{ trns('title') }}</label>
                            <input type="text" class="form-control" name="title">

                            <label class="p-0">{{ trns('voucher_type') }}</label>
                            <select class="form-control select2" name="voucherType_id">
                                <option value="" selected>----</option>
                            </select>
                        </div>
                    </div>
                    <button type='button' onclick="createModel(this)" class="btn btn-primary mt-3">{{ trns('save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>



