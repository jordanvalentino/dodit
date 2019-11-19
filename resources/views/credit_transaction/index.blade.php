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
    <h1 class="h3 mb-2 text-gray-800">Transactions</h1>
    <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Spendings</h6>
        <a href="{{ url('credit_transaction/create') }}" class="btn btn-primary my-2">
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
                <th>Category</th>
                <th>Amount</th>
                <th>Detail</th>
                <th>Date & Time</th>
                <th>Attachment</th>
                <th colspan=2>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Category</th>
                <th>Amount</th>
                <th>Detail</th>
                <th>Date & Time</th>
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

                @for ($i = 0; $i < $categories->count(); $i++)
                  @for ($j = 0; $j < $categories[$i]->transactions->count(); $j++)
                  <tr>
                    @if ($j == 0)
                    <td rowspan="{{ $categories[$i]->transactions->count() }}">
                      {{ $categories[$i]->name }}
                    </td>
                    @endif
                    <td>{{ $categories[$i]->transactions[$j]->amount }}</td>
                    <td>{{ $categories[$i]->transactions[$j]->detail }}</td>
                    <td>{{ date('j M \'y (H:i)', strtotime($categories[$i]->transactions[$j]->created_at)) }}</td>
                    <td>
                      @if ($categories[$i]->transactions[$j]->attachment != null)
                      <a href="{{ Storage::url($categories[$i]->transactions[$j]->attachment) }}" target="_blank">See attachment</a>
                      @endif
                    </td>
                    <td><a href="{{ url('credit_transaction/'.$categories[$i]->transactions[$j]->id.'/edit') }}">Edit</a></td>
                    <td>
                      <form method="POST" action="{{ url('credit_transaction/'.$categories[$i]->transactions[$j]->id) }}" id="delete-{{ $categories[$i]->transactions[$j]->id }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <a href="#" onclick="document.getElementById('delete-{{ $categories[$i]->transactions[$j]->id }}').submit()">Delete</a>
                      </form>
                    </td>
                  </tr>
                  @endfor
                @endfor

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