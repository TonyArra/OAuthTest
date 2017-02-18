@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Repositories</h2>
                                <ul>
                                    @foreach ($repos as $repo)
                                        <li><a href="{{ $repo->html_url }}">{{ $repo->full_name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h2>Issues</h2>
                                <ul>
                                    @foreach ($issues as $issue)
                                        <li><a href="{{ $issue->html_url }}">{{ $issue->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
