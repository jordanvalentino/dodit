@section('title', 'Home')

@extends('layouts.master')

@section('sidebar')
  @include('layouts.sidebar')
@endsection

@section('main-content')

<!-- Main Content -->
<div id="content">

  @include('layouts.topbar')

  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800">Blank Page</h1>

  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

@endsection