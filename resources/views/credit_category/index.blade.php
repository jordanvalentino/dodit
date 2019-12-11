@extends('layouts.master')

@section('title', 'Credit Category')

@section('style')
  <!-- <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet"> -->
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection


@section('sidebar')

  @if ($is_category_exist == true)
    @include('layouts.sidebar')
  @elseif ($is_category_exist == false)
    @include('layouts.sidebar_category')
  @endif

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
    <h1 class="h3 mb-2 text-gray-800">Categories</h1>
    <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Expense Categories</h6>
        <a href="{{ url('credit_category/create') }}" class="btn btn-primary my-2">
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
                <th>Name</th>
                <th>Sub Categories of ...</th>
                <th colspan=2>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Name</th>
                <th>Sub Categories of ...</th>
                <th colspan=2>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              @if ($categories->count() == 0)
                <tr>
                  <td colspan=5 align="center">No category found</td>
                </tr>
              @else
                @foreach ($categories as $cat)
                <tr>
                  <td>{{ $cat->name }}</td>
                  <td><span></span></td>
                  <td><a href="{{ url('credit_category/'.$cat->id.'/edit') }}">Edit</a></td>
                  <td>
                    <form method="POST" action="{{ url('credit_category/'.$cat->id) }}" id="delete-{{ $cat->id }}">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <a href="#" onclick="document.getElementById('delete-{{ $cat->id }}').submit()">Delete</a>
                    </form>
                  </td>
                </tr>
                @foreach ($cat->children as $subcat)
                <tr>
                  <td>{{ $subcat->name }}</td>
                  <td>{{ $subcat->parent->name }}</td>
                  <td><a href="{{ url('credit_category/'.$subcat->id.'/edit') }}">Edit</a></td>
                  <td>
                    <form method="POST" action="{{ url('credit_category/'.$subcat->id) }}" id="delete-{{ $subcat->id }}">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <a href="#" onclick="document.getElementById('delete-{{ $subcat->id }}').submit()">Delete</a>
                    </form>
                  </td>
                </tr>
                @endforeach
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