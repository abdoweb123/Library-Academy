<!-- Create Modal -->
<div class="modal fade" id="createLedger" tabindex="-1" aria-labelledby="createLedgerModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="min-width: 60vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createLedgerModalLabel">{{ trns('add_ledger') }}</h5>
                <button type="button" class="close px-3 btn btn-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="createForm" method="post" action="{{ route('dashboard.ledgers.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content-around px-4">


                            <input type="hidden" value="" class="form-control id" id="id" name="id">

                            <div class=" row col-md-6 col-sm-12 mt-2">
                                <label class="p-0" for="title"> {{ trns('title') }}</label>
                                <input type="text" class="form-control title" id="title" name="title" onchange="previewImage(this)">

                            </div>

                            <div class=" row col-md-6 col-sm-12 mt-2">
                                <label class="p-0" for="alias"> {{ trns('alias') }}</label>
                                <input type="text" class="form-control alias" id="alias" name="alias" onchange="previewImage(this)">

                            </div>

                            <div class=" row col-md-6 col-sm-12 mt-2">
                                <label class="p-0" for="opening_balance">  {{ trns('opening_balance') }}</label>
                                <input type="number" class="form-control opening_balance" id="opening_balance" name="opening_balance" onchange="previewImage(this)">

                            </div>

                            <div class=" row col-md-6 col-sm-12 mt-2">
                                <label class="p-0" for="balance_type"> {{ trns('type') }}</label>
                                <select class="form-control  " id="balance_type" name="balance_type">
                                    <option value="" selected="">----</option>
                                    <option value="cr">Cr</option>
                                    <option value="dr">Dr</option>
                                </select>

                            </div>

                            <div class=" row col-md-6 col-sm-12 mt-2">
                                <label class="p-0" for="group_id"> {{ trns('group') }}</label>
                                <select class="form-control select2" name="group_id">
                                    <option value="" selected>----</option>
                                </select>
                            </div>

                            <div class=" row col-md-6 col-sm-12 mt-2">
                                <label class="p-0" for="address"> {{ trns('address') }}</label>
                                <input type="text" class="form-control address" id="address" name="address" onchange="previewImage(this)">

                            </div>

                            <div class=" row col-md-6 col-sm-12 mt-2">
                                <label class="p-0" for="governorate"> {{ trns('governorate') }}</label>
                                <input type="text" class="form-control governorate" id="governorate" name="governorate" onchange="previewImage(this)">

                            </div>

                            <div class=" row col-md-6 col-sm-12 mt-2">
                                <label class="p-0" for="country"> {{ trns('country') }}</label>
                                <input type="text" class="form-control country" id="country" name="country" onchange="previewImage(this)">

                            </div>
                        </div>

                    <button type='button' onclick="createModel(this)" class="btn btn-primary mt-3">{{ trns('save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>



