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
        <th>Category</th>
        <th>Detail</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($transactions as $key => $trans)
      <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ date('d/m/Y', strtotime($trans->created_at)) }}</td>
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
      </tr>
      @endforeach
      <tr>
        <td colspan=4 align="right"><b>Total Amount</b></td>
        <td>
          {{ ($transactions->sum('amount') < 0) ? '-' : '' }} 
          {{ number_format($total_amount) }}
        </td>
      </tr>
    </tbody>
  </table>
</div>

</body>
</html>
