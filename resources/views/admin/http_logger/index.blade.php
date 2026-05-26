@extends('admin/layouts/master')

@section('title')
    {{ $bladeName }}
@endsection
@section('page_name')
    {{ $bladeName }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ $bladeName }} </h3>
                    <div class="">
                        <a href="{{  route($route.'.show',1) }}" class="btn btn-danger btn-icon text-white">
                            <span><i class="fe fe-trash"></i></span> {{ trns('delete all traffic') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-50px">{{ trns('ip') }}</th>
                                    <th class="min-w-125px">{{ trns('method') }}</th>
                                    <th class="min-w-125px">{{ trns('status') }}</th>
                                    <th class="min-w-50px rounded-end">{{ trns('timestamp') }}</th>
                                    <th class="min-w-50px rounded-end">{{ trns('url') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
        {
            data: 'ip',
            name: 'ip'
        },
        {
            data: 'method',
            name: 'method'
        },
        {
            data: 'status',
            name: 'status'
        },
        {
            data: 'timestamp',
            name: 'timestamp'
        },
        {
            data: 'url',
            name: 'url'
        }
        ];
        showData('{{ route($route . '.index') }}', columns);
    </script>
@endsection
