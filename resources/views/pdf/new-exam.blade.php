<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Table</title>
    <style>
        body {
            font-family: 'nikosh', Arial, sans-serif;
            padding: 20px;
        }
        
        .row{
          width: 100%;
        }
        
        .col-6{
          width: 45%;
          float: left;
          padding: 0 15px;
        }
        
        .no-break{
          page-break-inside: avoid;
        }

        .header {
            text-align: center;
        }

        .table-container {
            width: 100%;
            margin-bottom: 30px;
        }

        .exam-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .marks-table th, .marks-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .marks-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>
<body>
  <div class="row">
  @foreach($classes as $class)
  <div class="col-6" style="margin-bottom: 10px;">
    <table class="marks-table no-break">
      <thead>
        <tr>
          <td colspan="4">
            {{ $exam }}
          </td>
          <td colspan="2">{{ $class['name'] }}</td>
        </tr>
          <tr>
              <th>বিষয়</th>
              <th style="width: 60px;"></th>
              <th style="width: 60px;"></th>
              <th style="width: 60px;"></th>
              <th style="width: 60px;"></th>
              <th></th>
          </tr>
      </thead>
      <tbody>
          @foreach($class['subjects'] as $subject)
          <tr>
              <td rowspan="2" style="text-align:left;">{{ $subject }}</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>পূর্ণমান</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>পাস</td>
          </tr>
          @endforeach
          <!-- Add more rows as needed -->
      </tbody>
    </table>
  
  </div>
  @endforeach
  </div>
</body>
</htmlpagebreak>