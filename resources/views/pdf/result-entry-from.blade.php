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
  @foreach($class['subjects'] as $subject_name => $criteria)
  <div class="col-6 ">
    <div class="table-container">
        <table class="marks-table no-break">
            <tr>
              <td colspan="{{ count($criteria ?? [])+2 }}">
                {{ $institute['exam_name'] }}
              </td>
            </tr>
            <tr>
              <td style="text-align:left;" colspan="{{ ceil((count($criteria ?? [])+2)/2) }}">{{ $subject_name }}</td>
              <td style="text-align:right;" colspan="{{ floor((count($criteria ?? [])+2)/2) }}">{{ $class['class_name'] }}</td>
            </tr>
            <thead>
                <tr>
                    <th style="width:20px;">রোল</th>
                    <th>নাম</th>
                    @foreach($criteria ?? [] as $item)
                    <th>{{ $item['title'] }} <br> {{ bnum($item['full_mark']) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
              @foreach($class['students'] as $student)
                <tr>
                    <td> {{ bnum($student['roll']) }}</td>
                    <td> {{ $student['name'] }} </td>
                    @for($i=0; $i<count($criteria ?? []); $i++)
                    <td></td>
                    @endfor
                </tr>
              @endforeach
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
  
  </div>
  @endforeach
  @endforeach
  
  </div>
</body>
</htmlpagebreak>