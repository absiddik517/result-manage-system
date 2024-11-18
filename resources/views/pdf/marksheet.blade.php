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
            background: #fff;
        }
        h1, h2, h3, h4, h5, h6, p, span{
          margin: 0;
          padding: 0;
        }

        .result-sheet {
            width: 100%;
            margin: 0 auto;
        }

        .school-info {
            text-align: center;
            margin-bottom: 0px;
            position: relative;
        }

        .school-info h2 {
            font-size: 24px;
            font-weight: bold;
        }

        .school-info p {
            font-size: 16px;
            margin: 2px 0;
        }

        .student-info, .grade-summary, .signatures {
            width: 100%;
            margin-bottom: 20px;
            font-size: 16px;
            border-collapse: collapse;
        }
        
        .student-info tr td{
          font-size: 20px;
        }

        .student-info td, .grade-summary td, .signatures td {
            padding: 8px;
        }
        
        .grade-summary td{
          border: 1px solid #ddd;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .marks-table th,
        .marks-table td {
            padding: 8px;
            text-align: center;
            border: 1px solid #000;
            font-size: 14px;
        }
        
        .marks-table th{
          background: rgba(117,210,255,0.1);
          color: #000;
        }

        .total-label {
            font-weight: bold;
            text-align: right;
        }

        /* Signature table styling */
        .signatures td {
            border: 1px solid #000;
            height: 100px; /* Increased height for double space */
            text-align: center; /* Center text horizontally */
            font-size: 14px;
            vertical-align: top; /* Align text to the top */
            padding-top: 10px; /* Space between text and border */
        }
        
        .comment{
          width: 100%;
          border: 1px solid #000;
          min-height: 50px;
          margin-bottom: 8px;
          padding-left: 6px;
          padding-right: 6px;
          padding-top: 6px;
          padding-bottom: 26px;
        }
        
        @page {
          header: page-header;
          footer: page-footer;
        }
        
        .school-info img {
          width 30px 
          border: 1px solid black;
          position: absolute;
          top:0;
          left:0;
        }
    </style>
</head>
<body>

    <div class="result-sheet">
      <div class="header" style="text-align: center">
          <div style="float: left; width: 120px;">
              <img class="logo" src="{{ base_path('storage/images/logo.png') }}" alt="">
          </div>

          <div style="float: right; width: calc(100% - 240px);">
            <h2>{{ $institute->name }}</h2>
            <p>{{ $institute->established_at }}</p>
            <p>{{ $institute->address }}</p>
            <p>{{ $exam->name }}</p>
          </div>

          <div style="clear: both; margin-top: 5px; padding: 0pt;"></div>
      </div>
        <hr>

        <table class="student-info">
            <tr>
                <td style="width: 33.33%;">নাম: <strong>{{ $student['name'] }}</strong></td>
                <td style="width: 33.33%; text-align:center;">শ্রেণি: 
                <strong> {{ $student['class'] }}@if($student['group']) <span> ( {{ $student['group'] }} )</span>@endif</strong></td>
                <td style="width: 33.33%; text-align: right;">রোল নং: <strong>{{ bnum($student['roll']) }}</strong></td>
            </tr>
        </table>
        <table class="marks-table">
            <thead>
                <tr>
                    <th colspan="2">বিষয়</th>
                    <th>পূর্ণমান</th>
                    @foreach($theads as $title)
                    <th>{{ $title }}</th>
                    @endforeach
                    <th>মোট</th>
                    <th>সর্বোচ্চ</th>
                    <th>গ্রেড</th>
                </tr>
            </thead>
            <tbody>
              @foreach($subjects as $name => $subject)
                <tr @if(!$subject['status']) style="background:
                rgba(0,0,0,0.149);" @endif>
                    <td style="width: 10px;">{{ bnum($loop->iteration) }}</td>
                    <td style="text-align:left;">{{ $name }}</td>
                    <td>{{ bnum($subject['full_mark']) }}</td>
                    @foreach($theads as $title)
                      @if(isset($subject['result'][$title]))
                        <td @if(!$subject['result'][$title]['status'])
                        style="color: red;" @endif>{{
                        bnum($subject['result'][$title]['mark_obtain']) }} </td>
                      @else
                        <td>-</td>
                      @endif
                    @endforeach
                    <td 
                      @if($subject['total_mark_obtain'] <
                      ($subject['full_mark']*33)/100))
                      style="color:red"
                      @endif
                    >
                      {{ bnum($subject['total_mark_obtain']) }}
                    </td>
                    <td @if($max[$name]['max'] == $subject['total_mark_obtain']) style="color: green;" @endif>
                      {{ bnum($max[$name]['max']) }}
                    </td>
                    <td>{{ $subject['grade'] }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="total-label">মোট</td>
                    <td>{{ bnum($result['total_full_mark']) }}</td>
                    <td colspan="{{ count($theads) + 1 }}"
                    style="text-align:right">{{ bnum($result['total_marks']) }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table class="grade-summary">
            <tr>
                <td style="width: 33.33%">গ্রেড: {{ $result['grade'] }}</td>
                <td style="text-align:center; width:33.33%">GPA: {{
                bnum(round($result['point'], 2)) }}</td>
                <td style="text-align:right; width: 33.33%">শতকরা: {{
                bnum(round($result['percent'], 0)) }}%</td>
            </tr>
        </table>
        <div class="comment">
          শ্রেণি শিক্ষকের মন্তব্য :
        </div>

        <table class="signatures">
            <tr>
                <td>শ্রেণি শিক্ষকের স্বাক্ষর</td>
                <td>প্রধান শিক্ষকের স্বাক্ষর</td>
                <td>অভিভাবকের স্বাক্ষর</td>
            </tr>
        </table>
    </div>

</body>
</html>