@extends('layouts.app')

@section('title','Log Files')

@section('content')
<h3 class="page-header">Application Log Files</h3>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <table class="table table-condensed">
                <thead>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Size</th>
                    <th>Time</th>
                    <th>{{ trans('app.action') }}</th>
                </thead>
                <tbody>
                    @if (File::exists('error_log'))
                    <tr>
                        <td>0</td>
                        <td>error_log</td>
                        <td>{{ format_size_units(File::size('error_log')) }}</td>
                        <td>{{ date('Y-m-d H:i:s', File::lastModified('error_log')) }}</td>
                        <td>
                            {!! html_link_to_route('log-files.server-error-log','',[],[
                                'class'=>'btn btn-default btn-xs',
                                'icon' => 'search',
                                'id' => 'view-error-log',
                                'title' => 'View file error_log',
                                'target' => '_blank',
                            ]) !!}
                        </td>
                    </tr>
                    @endif
                    @forelse($logFiles as $key => $logFile)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $logFile->getFilename() }}</td>
                        <td>{{ format_size_units($logFile->getSize()) }}</td>
                        <td>{{ date('Y-m-d H:i:s', $logFile->getMTime()) }}</td>
                        <td>
                            {{ link_to_route('log-files.show', 'Lihat', [$logFile->getFilename()],[
                                'class'=>'btn btn-default btn-xs',
                                'id' => 'view-' . $logFile->getFilename(),
                                'title' => 'View file ' . $logFile->getFilename(),
                                'target' => '_blank'
                            ]) }}
                            {{ link_to_route('log-files.download', 'Download', [$logFile->getFilename()],[
                                'class'=>'btn btn-default btn-xs',
                                'id' => 'download-' . $logFile->getFilename(),
                                'title' => 'Download file ' . $logFile->getFilename()
                            ]) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">No Log File Exists</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
