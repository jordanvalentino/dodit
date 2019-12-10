@extends('layouts.master')

@section('title', 'Report')

@section('style')
  <!-- <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet"> -->
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('sidebar')
  @include('layouts.sidebar')
@endsection

@section('plugin')
  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <!-- <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script> -->
  <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection


@section('main-content')

<!-- Main Content -->
<div id="content">

  @include('layouts.topbar')

  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">

        @isset ($months)
        <select id="select-month" class="form-control col-sm-6" name="month" placeholder="Month Year" onchange="window.location.href='/report/monthly/'+this.value;">
          @foreach($months as $month)
          <option value="{{ date('m', strtotime($month->month)) }}_{{ $month->year }}"
            @if (date('m', strtotime($month->month)) == $sel_month && $month->year == $sel_year) selected @endif>
            {{ $month->month }} {{ $month->year }}
          </option>
          @endforeach
        </select>
        @endisset

        @isset ($years)
        <select id="select-year" class="form-control col-sm-6" name="year" placeholder="Year" onchange="window.location.href='/report/annually/'+this.value;">
          @foreach($years as $year)
          <option value="{{ $year->year }}"
            @if ($year->year == $sel_year) selected @endif>
            {{ $year->year }}
          </option>
          @endforeach
        </select>
        @endisset

        @if (session('message'))
          <h6>{{ session('message') }}</h6>
        @endif
      </div>

      <div class="card-body">
        @if ($is_earning_exist || $is_spending_exist)
        <div class="chart-pie pt-4 pb-2">
          <canvas id="totalDough"></canvas>
        </div>
        @endif

        @if ($is_earning_exist)
        <div class="chart-pie pt-4 pb-2">
          <canvas id="earnDough"></canvas>
        </div>
        @endif
            
        @if ($is_spending_exist)
        <div class="chart-pie pt-4 pb-2">
          <canvas id="spendDough"></canvas>
        </div>
        @endif
      </div>

    </div>

  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

@endsection


@section('script')
  <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
  <script src="{{ asset('vendor/chart.js/Chart.js') }}"></script>
  <script type="text/javascript">
      var totalCtx = document.getElementById("totalDough");
      var totalDough = new Chart(totalCtx, {
        type: 'doughnut',
        data: {
          labels: <?php echo $revenue->keys()->toJson(); ?>,
          datasets: [{
            data: <?php echo $revenue->values()->toJson(); ?>,
            backgroundColor: <?php echo $colors->toJson(); ?>,
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "#ffffff",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
          },
          title: {
            display: true,
            fontSize: 24,
            text: 'Earnings and Spendings'
          },
          legend: {
            display: true,
            position: 'bottom',
          },
        },
      });

      var earnCtx = document.getElementById("earnDough");
      var earnDough = new Chart(earnCtx, {
        type: 'doughnut',
        data: {
          labels: <?php echo $earning_by_category->keys()->toJson(); ?>,
          datasets: [{
            data:  <?php echo $earning_by_category->values()->toJson(); ?>,
            backgroundColor: <?php echo $earning_colors->toJson(); ?>,
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
          },
          title: {
            display: true,
            fontSize: 24,
            text: 'Earnings by Category'
          },
          legend: {
            display: true,
            position: 'bottom',
          },
        },
      });

  </script>

  <script type="text/javascript">
      var spendCtx = document.getElementById("spendDough");
      var spendDough = new Chart(spendCtx, {
        type: 'doughnut',
        data: {
          labels: <?php echo $spending_by_category->keys()->toJson(); ?>,
          datasets: [{
            data:  <?php echo $spending_by_category->values()->toJson(); ?>,
            backgroundColor: <?php echo $spending_colors->toJson(); ?>,
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
          },
          title: {
            display: true,
            fontSize: 24,
            text: 'Spendings by Category'
          },
          legend: {
            display: true,
            position: 'bottom',
          },
        },
      });

  </script>

@endsection