<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card</title>
    <style>
        body {
            font-family: 'nikosh', Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        h1, h2, h3, h4, h5, h6, p{
          margin:0;
          padding:0;
        }
        .row{
          width: 100%;
          display: flex;
          flex-direction: column;
        }
        
        .col{
          width: 47%;
          float: left;
          padding: 0 15px;
        }
        
        
        .admit-card {
            border: 3px solid #000;
            border-radius: 5px;
            width: 100%;
            padding: 15px;
            box-sizing: border-box;
            height: 148.5cm;
        }
        
        .pagefooter{
          position: fixed;
          bottom: 5cm;
          left: 5cm;
          width: 100%;
        }
        .header {
            text-align: center;
            width: 100%;
            height: auto;
            position: relative;
        }
        .logo {
            width: 115px;
            height: auto;
            position: absolute;
            z-index: 1;
            left: 0;
        }
        .header h1 {
            font-size: 25px;
            margin: 0;
        }
        .header p {
            font-size: 16px;
            margin: 0;
        }
        .info-table, .subjects-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .info-table td, .subjects-table th, .subjects-table td {
            border: 1px solid #000;
            padding: 2px 5px;
        }
        .info-table td {
            vertical-align: top;
        }
        .subjects-table th {
            background-color: #f0f0f0;
        }
        .signature {
            text-align: right;
            margin-top: 80px;
            margin-right: 8px;
        }
        .signature img {
            width: 120px;
        }
        .footer {
            font-size: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
  @foreach($students as $student)
      <div class="admit-card">
        <div class="header" style="text-align: center">
          <div style="float: left; width: 120px;">
              <img class="logo" src="{{ base_path('storage/images/logo.png') }}" alt="">
          </div>

          <div style="float: right; width: calc(100% - 240px);">
            <h3>{{ $institute->name }}</h3>
            <p>{{ $exam->name }}</p>
            <p>
              প্রবেশ পত্র
            </p>
          </div>

          <div style="clear: both; margin-top: 5px; padding: 0pt;"></div>
      </div>
       
        <div class="pagebody">
          <table class="info-table">
              <tr>
                  <td style="width:50%;"><strong>
                    পরিক্ষার্থীর নাম:
                  </strong> {{ $student->name }}</strong>
                  <td style="text-align:right;"><strong>
                    শ্রেণি:
                  </strong> {{ $student->class_name }}</td>
              </tr>
              <tr>
                  <td><strong>
                    বিভগ:
                  </strong> {{ $student->group_name ?? '---' }}</strong></td>
                  <td style="text-align:right;"><strong>
                    রোল নম্বর:
                  </strong> {{ bnum($student->roll) }}</strong></td>
              </tr>
          </table>
          <h4>পরিক্ষার বিষয়: </h4>
          <table class="subjects-table">
              <thead>
                  <tr>
                      <th style="width: 40px;">ক্রমিক</th>
                      <th>বিষয়</th>
                  </tr>
              </thead>
              <tbody>
                @foreach($student->subjects as $subject)
                  <tr>
                      <td style="text-align:center;">{{ bnum($loop->iteration) }}</td>
                      <td>{{ $subject->name }}</td>
                  </tr>
                @endforeach
              </tbody>
          </table>
        </div>
        <div class="pagefooter">
          <div class="signature">
              <p>
                প্রধান শিক্ষকের সীল ও সাক্ষর
              </p>
          </div>
          <div class="footer">
              <p>
                * পরিক্ষার্থীকে অবশ্যই পরিক্ষার হলে প্রবেশ পত্র নিয়ে আসতে হবে।
              </p>
          </div>
        </div>
    </div>
  @endforeach
</body>
</html>