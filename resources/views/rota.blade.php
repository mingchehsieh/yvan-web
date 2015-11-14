@extends('layouts.app')

@section('title', '當值人員')

@section('content')
    <ol class="breadcrumb">
        <li><a href="/">首頁</a></li>
        <li class="active">當值人員</li>
    </ol>
    <?php
    $raw = $year . '/' . $month . '/1';
    $start = DateTime::createFromFormat('Y/n/j', $raw);
    $start_w = $start->format('w');
    $start_t = $start->format('t');
    ?>
    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th></th>
                <th>星期日</th>
                <th>星期一</th>
                <th>星期二</th>
                <th>星期三</th>
                <th>星期四</th>
                <th>星期五</th>
                <th>星期六</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < ceil(($start_t+$start_w)/7); $i ++)
                <tr>
                    <td></td>
                    @for ($k = 1; $k <= 7; $k ++)
                        <td>
                            @if (($i * 7 + $k - $start_w) > 0 and ($i * 7 + $k - $start_w) <= $start_t)
                                {{ $month }} / {{ $i * 7 + $k - $start_w }}
                            @endif
                        </td>
                    @endfor
                </tr>
                <tr>
                    <td>
                        <span style="color: #d9534f">駐部官</span><br>
                        <span style="color: #5cb85c">理事官</span><br>
                        <span style="color: #f0ad4e">值日官</span>
                    </td>
                    @for ($k = 1; $k <= 7; $k ++)
                        <td>
                            @if (($i * 7 + $k - $start_w) > 0 and ($i * 7 + $k - $start_w) <= $start_t)
                                <span style="color: #d9534f">{{ $title1->{'d'.($i * 7 + $k - $start_w)} or '' }}</span><br>
                                <span style="color: #5cb85c">{{ $title2->{'d'.($i * 7 + $k - $start_w)} or '' }}</span><br>
                                <span style="color: #f0ad4e">{{ $title3->{'d'.($i * 7 + $k - $start_w)} or '' }}</span>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
@endsection
