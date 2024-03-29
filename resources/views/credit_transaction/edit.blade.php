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
  <h1 class="h3 mb-2 text-gray-800">Transactions</h1>
  <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Spendings</h6>
    </div>
    <div class="card-body">
      
      <form class="user" method="POST" action="{{ url('credit_transaction/'.$spending->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <input type="number" class="form-control" placeholder="Spended Amount" name="amount" min=0 value="{{ $spending->amount }}" required autofocus>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <textarea class="form-control" placeholder="Transaction details.." name="detail" required>{{ $spending->detail }}</textarea>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">
          	<!-- <input type="email" class="form-control form-control-user" placeholder="Email Address" name="email" required> -->
          	<select class="form-control" name="category_id" placeholder="Category">
          	  @for ($i = 0; $i < $categories->count(); $i++)
          	  <option value="{{ $categories[$i]->id }}" @if ($categories[$i]->id == $spending->category_id) selected @endif>
                {{ $categories[$i]->name }}

                @isset ($categories[$i]->parent)
                  ({{ $categories[$i]->parent->name }})
                @endisset
              </option>
          	  @endfor
          	</select>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6 mb-3 mb-sm-0">

            @if ($spending->attachment != NULL)
            <div class="form-check">
              <input class="form-check-input" type="radio" name="update" id="radioUpdateNo" value="no" checked>
              <label class="form-check-label" for="radioUpdateNo">
                Use previous file
              </label>
              <div>
                <a class="btn btn-info" href="{{ Storage::url($spending->attachment) }}" target="_blank">See attachment</a>
              </div>
            </div>
            @endif

            <div class="{{ ($spending->attachment != NULL) ? 'form-check' : '' }}">
              <input class="form-check-input" type="{{ ($spending->attachment != NULL) ? 'radio' : 'hidden' }}" name="update" id="radioUpdateYes" value="yes">
                @if ($spending->attachment != NULL)
                <label class="form-check-label" for="radioUpdateYes">
                  Upload new file
                </label>
                @endif
              <input type="file" class="form-control-file" placeholder="Attachment" name="attachment" accept=".jpg,.png">
            </div>

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
          	<a href="{{ url('credit_transaction') }}" class="btn btn-danger btn-user btn-block">
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