@extends('layouts.master')

@section('title', 'Debit Category')

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
  <h1 class="h3 mb-2 text-gray-800">Categories</h1>
  <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Income Categories</h6>
    </div>
    <div class="card-body">
      
      <form class="user" method="POST" action="{{ url('debit_category/'.$category->id) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <input type="text" class="form-control" placeholder="Category Name" name="name" value="{{ $category->name }}" required>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
          	<!-- <input type="email" class="form-control form-control-user" placeholder="Email Address" name="email" required> -->
          	<select class="form-control" name="super_id" placeholder="Sub Category of...">
          	  <option value="0">None</option>
          	  @foreach ($categories as $cat)
          	  <option value="{{ $cat->id }}" @if ($cat->id == $category->super_id) selected @endif>{{ $cat->name }}</option>
          	  @endforeach
          	</select>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
          	<button type="submit" class="btn btn-primary btn-user btn-block">
	          Update
	        </button>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <a href="{{ url('debit_category') }}" class="btn btn-danger btn-user btn-block">
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