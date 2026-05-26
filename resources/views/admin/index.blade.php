@extends('admin/layouts/master')
@section('title')
    {{ trns('home') }}
@endsection
@section('page_name')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title text-center ">
                <h2>{{ trns('general_statistics') }}</h2>
            </div>
            <form method="GET" id="filterForm">
                <div class="form-check mb-3">
                    <input class="form-check-input"
                           type="checkbox"
                           name="ignore_company_scope"
                           value="1"
                           id="allCompanies"
                           onchange="document.getElementById('filterForm').submit()"
                           {{ request('ignore_company_scope') ? 'checked' : '' }}>
            
                    <label class="form-check-label mx-4" for="allCompanies">
                        {{ trns('all_companies') }}
                    </label>
                </div>
            </form>
            <div class="row">
                {{-- 🔹 Counters --}}
                @foreach($data['counts'] as $key => $value)
                    <div class="col-md mb-3">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <h6>{{ trns($key) }}</h6>
                                <h3>{{ $value }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row mt-4">

                {{-- 🔹 Books per Section --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ trns('books_per_section') }}
                        </div>
                        <div class="card-body">
                            <canvas id="booksChart"></canvas>
                        </div>
                    </div>
                </div>
            
                {{-- 🔹 Borrow Trend --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ trns('borrow_trend') }}
                        </div>
                        <div class="card-body">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
            
            </div>


            <div class="row mt-4">

                {{-- 🔹 Top Borrowed Books --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            {{ trns('top_borrowed_books') }}
                        </div>
                        <div class="card-body">
                            <canvas id="topBooksChart"></canvas>
                        </div>
                    </div>
                </div>
            
            </div>
            
        </div>
    </div>
@endsection

@push('scripts')

{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script src="{{ asset('assets/js/chart.min.js') }}"></script>

<script>

    // 🔵 Books per Section
    new Chart(document.getElementById('booksChart'), {
        type: 'bar',
        data: {
            labels: @json($data['booksPerSection']->pluck('name')),
            datasets: [{
                label: '{{ trns("books") }}',
                data: @json($data['booksPerSection']->pluck('books_count'))
            }]
        }
    });

    // 🔵 Borrow Trend
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: @json($data['trend']->pluck('month')),
            datasets: [{
                label: '{{ trns("borrowed") }}',
                data: @json($data['trend']->pluck('total')),
                fill: false
            }]
        }
    });

    // 🔵 Top Borrowed Books
    new Chart(document.getElementById('topBooksChart'), {
        type: 'bar',
        data: {
            labels: @json($data['topBooks']->pluck('title')),
            datasets: [{
                label: '{{ trns("times_borrowed") }}',
                data: @json($data['topBooks']->pluck('borrowings_count'))
            }]
        }
    });

</script>

@endpush