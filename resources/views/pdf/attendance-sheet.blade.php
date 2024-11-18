<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Admit Card</title>
        <style>
            body {
                font-family: "nikosh", Arial, sans-serif;
                font-size: 14px;
                margin: 0;
                padding: 0;
                line-height: 1.5;
            }
            h1, h2, h3, h4, h5, h6, p{
              margin: 0;
              padding:0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            
            table thead tr th{
              text-align: center;
              border: 1px solid #000;
              padding: 2px 5px;
              width: calc(100%/({{ count($class->subjects) + 1 }}));
            }
            
            
            table tbody tr td{
              border:1px solid #000;
              padding: 5px 5px;
              width: calc(287mm/({{ count($class->subjects) + 1 }}));
            }
            

            .header {
                text-align: center;
                margin-top: 20px;
            }
            .logo{
              width: 120px;
            }
        </style>
    </head>
    <body>
        <div class="sheet" style="position: relative">
            <htmlpageheader name="header">
              <div>
                <br>
              </div>
              <div class="header" style="text-align: center">
                  <div style="float: left; width: 120px;">
                      <img class="logo" src="{{ base_path('storage/images/logo.png') }}" alt="">
                  </div>
  
                  <div style="float: right; width: calc(100% - 240px);">
                    <h2>{{ $institute->name }}</h2>
                    <p>{{ $institute->established_at }}</p>
                    <p>{{ $institute->address }}</p>
                    <p>{{ $exam->name }}</p>
                    <p>{{ $class->name }}</p>
                    <p>
                      হাজিরা সিট
                    </p>
                  </div>
  
                  <div style="clear: both; margin-top: 5px; padding: 0pt;"></div>
              </div>
            </htmlpageheader>
            <sethtmlpageheader name="header" valie="on" show-this-page="1" />
            <div class="content">
              <table>
                <thead>
                  <tr>
                    <th style="width:50px;"></th>
                    @foreach($class->subjects as $subject)
                      <th>{{$subject->name}}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @foreach($students as $student)
                  <tr>
                    <td>
                      <span style="font-size: 18px;">{{ $student->name }}</span>
                      <p style="">রোল:
                        <strong>{{ bnum($student->roll) }}</strong>
                      </p>
                    </td>
                    @for($i=0; $i < count($class->subjects); $i++)
                      <td></td>
                    @endfor
                  </tr>
                  
                  @endforeach
                </tbody>
              </table>
              
            </div>
                  
                

        </div>
    </body>
</h>
