@extends('layouts.app')
@section('content')
    <div class="row mb-3">
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class=" mb-3">Stock In Today</h5>
                    <h1 class="display-3">{{ $count['stock_in_today'] }}</h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class=" mb-3">Stock Out Today</h5>
                    <h1 class="display-3">{{ $count['stock_out_today'] }}</h1>

                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class=" mb-3">Total Product</h5>
                    <h1 class="display-3">{{ $count['product'] }}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h5 class=" mb-3">Statistic</h5>
                        <canvas id="stockChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('dashboard.chart') }}',
                method: 'GET',
                success: function(response) {
                    var ctx = document.getElementById('stockChart').getContext('2d');
                    var stockChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: response.dates,
                            datasets: [{
                                    label: 'Stock In',
                                    data: response.stockIn,
                                    fill: true,
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    tension: 0.4
                                },
                                {
                                    label: 'Stock Out',
                                    data: response.stockOut,
                                    fill: true,
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    tension: 0.4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Date'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Quantity'
                                    }
                                }
                            }
                        }
                    });
                },
                error: function() {
                    alert('Failed to load data');
                }
            });
        });
    </script>
@endpush
