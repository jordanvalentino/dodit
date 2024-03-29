@extends('layouts.master')

@section('title', 'Credit Category')

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
@endsection


@section('main-content')

<!-- Main Content -->
<div id="content">

  @include('layouts.topbar')

  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Budget Plans</h1>
    <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Budgets</h6>
        <a href="{{ url('budget/create') }}" class="btn btn-primary my-2">
          <span class="text">Create New</span>
        </a>
        @if (session('message'))
          <h6>{{ session('message') }}</h6>
        @endif
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Title</th>
                <th>Amount</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Saved</th>
                <th>Status</th>
                <th>Details</th>
                <th colspan=2>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Title</th>
                <th>Amount</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Saved</th>
                <th>Status</th>
                <th>Details</th>
                <th colspan=2>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              @if (!$is_budget_exist)

              <tr>
                <td colspan=6 align="center">No budget plan found</td>
              </tr>

              @else

                @foreach ($budgets as $budget)
                <tr>
                  <td>{{ $budget->title }}</td>
                  <td>{{ number_format($budget->amount) }}</td>
                  <td>{{ date('d M \'y', strtotime($budget->start)) }}</td>
                  <td>{{ date('d M \'y', strtotime($budget->end)) }}</td>
                  <td>
                    <div class="progress progress-sm mr-2">
                      <div class="progress-bar bg-info" role="progressbar" 
                      style="width: {{ $budget->progress() * 100 }}%" 
                      aria-valuenow="{{ $budget->progress() * 100 }}" 
                      aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    {{ $budget->progress() * 100 }}%
                  </td>
                  <td>{{ ($budget->is_finished) ? 'Done' : 'Not yet' }}</td>
                  <td>
                    <a href="{{ url('budget/'.$budget->id) }}">See details</a>
                  </td>
                  <td><a href="{{ url('budget/'.$budget->id.'/edit') }}">Edit</a></td>
                  <td>
                    <form method="POST" action="{{ url('budget/'.$budget->id) }}" id="delete-{{ $budget->id }}">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <a href="#" onclick="document.getElementById('delete-{{ $budget->id }}').submit()">Delete</a>
                    </form>
                  </td>
                </tr>
                @endforeach

              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

@endsection