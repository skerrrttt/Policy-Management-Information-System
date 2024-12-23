@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3>Agenda List for Meeting</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Proponent</th>
                        <th>Type</th>
                        <th>Subtype</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendas as $agenda)
                    <tr>
                        <td>{{ $agenda->proposal->title }}</td>
                        <td>{{ $agenda->proposal->user->name }}</td>
                        <td>{{ $agenda->proposal->type }}</td>
                        <td>{{ $agenda->proposal->subtype }}</td>
                        <td>{{ $agenda->proposal_status->status_description ?? 'N/A' }}</td>
                        <td>
                            @if ($agenda->proposal->versions->isNotEmpty())
                                <a href="{{ asset('storage/' . $agenda->proposal->versions->first()->file_paths) }}" target="_blank">View File</a>
                            @else
                                <span>No File</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">No agendas found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
