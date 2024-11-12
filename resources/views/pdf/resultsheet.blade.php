<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Sheet</title>
    <style>
        body {
            font-family: 'nikosh', Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .result-sheet {
            width: 100%;
            height: 100%;
            margin: 0 auto;
            position: relative;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 80px; /* Adjust logo size */
            height: auto;
            margin-right: 15px;
        }

        .school-info {
            text-align: center;
        }

        .school-info h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .school-info p {
            font-size: 16px;
            margin: 2px 0;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px; /* Smaller font size for larger tables */
        }

        .marks-table th,
        .marks-table td {
            padding: 2px;
            text-align: center;
            border: 1px solid #000;
        }

        .marks-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .marks-table td.fail {
            background-color: #ffcccc; /* Highlight failed subjects in red */
            color: red;
        }

        .position {
            font-weight: bold;
            color: #4CAF50; /* Green color for positions */
        }
        .rotate {
            -webkit-transform: rotate(90deg);
            -webkit-transform-origin: left bottom;
            white-space: nowrap;
        }
      
        .comment {
            font-size: 14px;
            font-weight: bold;
        }
        .signature{
          position: fixed;
          float: right;
          margin-top: 30px;
          margin-right: 30px;
          text-align: center;
          width: 200px;
          height: 30px
          padding-top: 5px;
          border-bottom: 1px solid #000;
        }
    </style>
</head>
<body>

    <div class="result-sheet">
        <!-- Header Section with Logo and School Information -->
        <div class="header">
            <div class="school-info">
              <h2>{{ $institute['name'] }}</h2>
              <p>{{ $institute['established_at'] }}</p>
              <p>{{ $institute['address'] }}</p>
              <p>{{ $institute['exam_name'] }}</p>
              <p>{{ $institute['class_name'] }}</p>
            </div>
        </div>

        @foreach($groups as $group_name => $group)
          @if($group_name != "NO_GROUP")
          <div style="text-align: center; margin-bottom: 5px; margin-top: 5px;">
            {{ $group_name }}
          </div>
          @endif
          <!-- Marks Table -->
          <table class="marks-table">
              <thead>
                  <tr>
                      <th rowspan="2">রোল</th>
                      <th rowspan="2" style="width: 120px;">নাম</th>
                      @foreach($group['subjects'] as $subject => $item)
                      <th colspan="{{ count($item['criteria']) + 1}}">{{ $subject }}</th>
                      @endforeach
                      <th rowspan="2">মোট</th>
                      <th rowspan="2">গ্রেড</th>
                      <th rowspan="2">পয়েন্ট</th>
                  </tr>
                  <tr>
                    @foreach($group['subjects'] as $subject)
                    @foreach($subject['criteria'] as $short_name => $k)
                    <th style="font-size:10px;">{{ $short_name }}</th>
                    @endforeach
                    <th>মোট</th>
                    @endforeach
                  </tr>
              </thead>
              <tbody>
                @foreach($group['students'] as $student)
                  <tr>
                    <td>{{ bnum($student['roll']) }}</td>
                    <td style="text-align:left;">{{ $student['name'] }}</td>
                    @foreach($student['result'] as $subject)
                      @foreach($subject['criteria'] as $item)
                        <td @if(!$item['status']) style="color: red;" @endif> {{ bnum($item['mark_obtain']) }}</td>
                      @endforeach
                      <td>{{ bnum($subject['total_mark_obtain']) }}</td>
                    @endforeach
                    <td>{{ bnum($student['total']) }}</td>
                    <td>{{ $student['grade'] }}</td>
                    <td>{{ bnum($student['point']) }}</td>
                  </tr>
                @endforeach
              </tbody>
          </table>
        @endforeach
        
        <div class="signature">
          প্রধান শিক্ষক
        </div>
    </div>

</body>
</html>