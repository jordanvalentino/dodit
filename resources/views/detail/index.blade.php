@extends('layouts.master')

@section('title', 'Budget Details')

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
    <h1 class="h3 mb-2 text-gray-800">Budget Details</h1>
    <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Plan : {{ $budget->title }}</h6>
        <a href="{{ url('detail/create/'.$budget->id) }}" class="btn btn-primary my-2">
          <span class="text">Create New</span>
        </a>
        <a href="{{ url('budget') }}" class="btn btn-danger my-2">
          <span class="text">Back</span>
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
                <th>Date</th>
                <th>Amount</th>
                <th colspan=2>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Date</th>
                <th>Amount</th>
                <th colspan=2>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              @if (!$is_detail_exist)

              <tr>
                <td colspan=6 align="center">No budget detail found</td>
              </tr>

              @else

                @foreach ($details as $det)
                <tr>
                  <td>{{ date('d M \'y', strtotime($det->created_at)) }}</td>
                  <td>{{ number_format($det->amount) }}</td>
                  <td><a href="{{ url('detail/'.$budget->id.'/'.$det->id.'/edit') }}">Edit</a></td>
                  <td>
                    <form method="POST" action="{{ url('detail/'.$budget->id.'/'.$det->id) }}" id="delete-{{ $det->id }}">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <a href="#" onclick="document.getElementById('delete-{{ $det->id }}').submit()">Delete</a>
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