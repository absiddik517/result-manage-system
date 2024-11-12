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
            margin-bottom: 5px;
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
          background: #000;
          color:#fff;
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
        <div class="school-info">
            <h2>সেন্ট ক্যাথারিনা প্রাথমিক ও নিম্ন মাধ্যমিক বিদ্যালয়</h2>
            <p>স্থাপিতঃ ১৯৫২ ইংরেজী<br> বালমাশিয়া, বাঘাইহাট, লংগদু, ময়মনসিংহ</p>
            <p>প্রথম সাময়িক পরীক্ষা - ২০২৪</p>
        </div>
        <hr>

        <table class="student-info">
            <tr>
                <td style="width: 33.33%;">নাম: <strong>বিজয়া দাস</strong></td>
                <td style="width: 33.33%; text-align:center;">শ্রেণি: <strong>নবম শ্রেণি</strong></td>
                <td style="width: 33.33%; text-align: right;">রোল নং: <strong>19</strong></td>
            </tr>
        </table>
        <table class="marks-table">
            <thead>
                <tr>
                    <th colspan="2">বিষয়</th>
                    <th>পূর্ণমান</th>
                    <th>সৃজনশীল</th>
                    <th>বহু-বিকল্প</th>
                    <th>প্রাকটিক্যাল</th>
                    <th>শ্রেণি মূল্যায়ন</th>
                    <th>মোট</th>
                    <th>গ্রেড</th>
                </tr>
            </thead>
            <tbody>
              @for($i=0; $i<12; $i++)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>বাংলা ১ম পত্র</td>
                    <td>100</td>
                    <td>27</td>
                    <td>30</td>
                    <td>15</td>
                    <td>72</td>
                    <td>A</td>
                    <td>4.5</td>
                </tr>
                @endfor
                <!-- Repeat <tr> for each subject as shown in the image -->
                <tr>
                    <td>12</td>
                    <td>স্বাস্থ্য সুরক্ষা</td>
                    <td>100</td>
                    <td>38</td>
                    <td>19</td>
                    <td>12</td>
                    <td>69</td>
                    <td>A</td>
                    <td>5.0</td>
                </tr>
                <tr>
                    <td colspan="2" class="total-label">মোট</td>
                    <td>1200</td>
                    <td>783</td>
                </tr>
            </tbody>
        </table>

        <table class="grade-summary">
            <tr>
                <td style="width: 33.33%">গ্রেড: F</td>
                <td style="text-align:center; width:33.33%">GPA: 0</td>
                <td style="text-align:right; width: 33.33%">সাফল্য: 65%</td>
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