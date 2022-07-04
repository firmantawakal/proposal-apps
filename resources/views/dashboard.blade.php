@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <style>
        .dash-image{
            background-size: cover;
            position: center center;
            max-height: 560px;
            max-width: 100%;
        }
    </style>
  @endpush

@section('content')

<div class="row">
  <div class="col-lg col-sm-12 grid-margin">
    <div class="card overflow-hidden">
      <div class="card-body">
        <img class="dash-image mx-auto d-block" src="{{ asset('assets/images/dashboard.jpg') }}" alt="">
      </div>
    </div>
  </div>
</div> <!-- row -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script src="{{ asset('assets/js/datepicker.js') }}"></script>
@endpush
