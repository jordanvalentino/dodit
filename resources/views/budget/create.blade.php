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
  <!-- <script src="vendor/datatables/jquery.dataTables.min.js"></script> -->
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
    </div>
    <div class="card-body">
      
      <form class="user" method="POST" action="{{ url('budget') }}">
        {{ csrf_field() }}

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <label class="form-check-label">
              Budget Title
            </label>
            <input type="text" class="form-control" placeholder="Title" name="title" required autofocus>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <label class="form-check-label">
              Budget Amount
            </label>
            <input type="number" class="form-control" placeholder="Budget amount" name="amount" min=0 value=0 required>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <label class="form-check-label">
              Start Date
            </label>
            <input type="date" class="form-control" placeholder="Start date" name="start" min="{{ date('Y-m-d', time()) }}" value="{{ date('Y-m-d', time()) }}" required>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <label class="form-check-label">
              End Date
            </label>
            <input type="date" class="form-control" placeholder="End date" name="end" value="{{ date('Y-m-d', strtotime('next month')) }}" required>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
          	<button type="submit" class="btn btn-primary btn-user btn-block">
	          Create
	        </button>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
          	<a href="{{ url('budget') }}" class="btn btn-danger btn-user btn-block">
	          Back
	        </a>
          </div>
        </div>


      </form>

    </div>
  </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

@endsection