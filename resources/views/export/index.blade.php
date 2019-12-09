<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2 style="text-align: center;">Laporan Keuangan Anda</h2><br>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No.</th>
        <th>Date</th>
        <th>Detail</th>
        <th>Amount</th>
        <th>Category</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($transactions as $key => $trans)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ date('d-M-y', strtotime($trans->created_at)) }}</td>
                  <td>{{ $trans->detail }}</td>
                  <td>
                    @if ($trans->category->type == 'cr')
                    -
                    @else
                    +
                    @endif
                    {{ $trans->amount }}
                  </td>                  
                  <td>{{ $trans->category->name }}</td>
                </tr>
                @endforeach
    </tbody>
  </table>
</div>

</body>
</html>
