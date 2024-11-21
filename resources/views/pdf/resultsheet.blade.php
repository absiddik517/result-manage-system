<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Sheet</title>
    <style>
        body {
            font-family: 'nikosh', Arial, sans-serif;
            background-color: #fff;
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
            width: 100%;
        }

        .logo {
            width: 160px; /* Adjust logo size */
            height: auto;
            margin-right: 15px;
        }

        .school-info {
            text-align: center;
            float: right;
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
          font-size: 20px;
        }
    </style>
</head>
<body>

    <div class="result-sheet">
        <!-- Header Section with Logo and School Information -->
        <table style="width: 100%">
          <tr>
            <td style="width: 160px;"><img class="logo" src="{{ base_path('storage/images/logo.png') }}" alt="Logo"></td>
            <td style="text-align: center; vertical-align: top;">
              <div class="header">
                  <div class="school-info" >
                    <div style="font-size: 36px;"><b>{{ $institute['name'] }}</b></div>
                    <p style="font-size: 18px;">{{ $institute['established_at'] }}</p>
                    <p style="font-size: 18px;">{{ $institute['address'] }}</p>
                    <p style="font-size:18px;"><b>{{ $institute['exam_name'] }}</b></p>
                    <p style="font-size: 18px;"><b>{{ $institute['class_name'] }}</b></p>
                  </div>
              </div>
            </td>
            <td style="width: 160px;"></td>
          </tr>
        </table>

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
                      <th colspan="{{ count($item['criteria']) > 1 ? count($item['criteria']) + 1 : 0}}">{{ $subject }}</th>
                      @endforeach
                      <th rowspan="2">মোট</th>
                      <th rowspan="2">গ্রেড</th>
                      <th rowspan="2">পয়েন্ট</th>
                  </tr>
                  <tr>
                    @foreach($group['subjects'] as $subject)
                      @if(count($subject['criteria']) > 1)
                        @foreach($subject['criteria'] as $short_name => $k)
                        <th style="font-size:10px;">{{ $short_name }}</th>
                        @endforeach
                        <th>মোট</th>
                      @else 
                        <td style="padding:0;"></td>
                      @endif
                    @endforeach
                  </tr>
              </thead>
              <tbody>
                @foreach($group['students'] as $student)
                  <tr>
                    <td>{{ bnum($student['roll']) }}</td>
                    <td style="text-align:left;">{{ $student['name'] }}</td>
                    @foreach($student['result'] as $subject)
                      @if(count($subject['criteria']) > 1)
                      @foreach($subject['criteria'] as $item)
                        <td @if(!$item['status']) style="color: red;" @endif> {{ bnum($item['mark_obtain']) }}</td>
                      @endforeach
                      @endif
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