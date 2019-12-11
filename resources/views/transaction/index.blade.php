@extends('layouts.master')

@section('title', 'Transactions')

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

@section('script')
  <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
  <script src="{{ asset('vendor/chart.js/Chart.js') }}"></script>
@endsection


@section('main-content')

<!-- Main Content -->
<div id="content">

  @include('layouts.topbar')

  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Transactions</h1>
    <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <!-- <h6 class="m-0 font-weight-bold text-primary">Spendings</h6> -->
        <a href="{{ url('transaction/create') }}" class="btn btn-primary my-2">
          <span class="text">Create New</span>
        </a>

        @isset ($months)
        <select id="select-month" class="form-control col-sm-6" name="month" placeholder="Month Year" onchange="window.location.href='/transaction/monthly/'+this.value;">
          @foreach($months as $month)
          <option value="{{ date('m', strtotime($month->month)) }}_{{ $month->year }}"
            @if (date('m', strtotime($month->month)) == $sel_month && $month->year == $sel_year) selected @endif>
            {{ $month->month }} {{ $month->year }}
          </option>
          @endforeach
        </select>
        @endisset

        @isset ($years)
        <select id="select-year" class="form-control col-sm-6" name="year" placeholder="Year" onchange="window.location.href='/transaction/annually/'+this.value;">
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
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Detail</th>
                <th>Amount</th>
                <th>Attachment</th>
                <th colspan=2>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Detail</th>
                <th>Amount</th>
                <th>Attachment</th>
                <th colspan=2>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              @if (!$is_transaction_exist)

              <tr>
                <td colspan=6 align="center">No transaction found</td>
              </tr>

              @else

                @foreach ($transactions as $trans)
                <tr>
                  <td>{{ date('j/m/Y', strtotime($trans->created_at)) }}</td>
                  <td>{{ $trans->category->name }}</td>
                  <td>{{ $trans->detail }}</td>
                  <td>
                    @if ($trans->category->type == 'cr')
                    -
                    @else
                    +
                    @endif
                    {{ number_format($trans->amount) }}
                  </td>
                  <td>
                    @if ($trans->attachment != null)
                    <a href="{{ Storage::url($trans->attachment) }}" target="_blank">See attachment</a>
                    @endif
                  </td>
                  <td><a href="{{ url('transaction/'.$trans->id.'/edit') }}">Edit</a></td>
                  <td>
                    <form method="POST" action="{{ url('transaction/'.$trans->id) }}" id="delete-{{ $trans->id }}">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <a href="#" onclick="document.getElementById('delete-{{ $trans->id }}').submit()">Delete</a>
                    </form>
                  </td>
                </tr>
                @endforeach

              @endif
            </tbody>
          </table>

          <div class="my-2">
            <a class="btn btn-danger mx-2 my-2" href="{{ url('transaction/export/pdf') }}">
              Export to PDF
            </a>
            <a class="btn btn-success mx-2 my-2" href="{{ url('transaction/export/excel') }}">
              Export to Excel
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

@endsection