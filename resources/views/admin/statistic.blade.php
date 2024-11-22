@extends('admin.layouts.master')

@section('content')
<div class="charts-container">
  <!-- Biểu đồ phân bổ đơn hàng -->
  <div class="chart" id="piechart">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Orders'],
          @foreach($statusCount as $status => $count)
            ['{{ \App\Models\OrderStatus::find($status)->name }}', {{ $count }}],
          @endforeach
        ]);

        var options = {
          title: 'Phân bổ đơn hàng theo trạng thái'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
  </div>

  <!-- Biểu đồ tổng doanh thu -->
  <div class="chart" id="linechart">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart', 'line']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Ngày', 'Doanh thu'],
          @foreach($revenueByDay as $revenue)
            ['{{ \Carbon\Carbon::parse($revenue->date)->format('d/m/Y') }}', {{ $revenue->total_revenue }}],
          @endforeach
        ]);

        var options = {
          title: 'Tổng doanh thu theo ngày',
          vAxis: {title: 'Doanh thu (VND)'},
          legend: { position: 'none' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('linechart'));
        chart.draw(data, options);
      }
    </script>
  </div>

</div>

@endsection

<style>
  .charts-container {
    display: flex;
    justify-content: space-between;
    gap: 20px; /* Khoảng cách giữa các biểu đồ */
  }

  .chart {
    width: 50%; /* Mỗi biểu đồ chiếm khoảng 50% chiều rộng */
    height:300px; /* Chiều cao của biểu đồ */
  }

  /* Optional: Can add responsive behavior for smaller screens */
  @media (max-width: 768px) {
    .charts-container {
      flex-direction: column;
    }

    .chart {
      width: 100%; /* Mỗi biểu đồ chiếm toàn bộ chiều rộng trên màn hình nhỏ */
      margin-bottom: 20px; /* Khoảng cách giữa các biểu đồ */
    }
  }
</style>
