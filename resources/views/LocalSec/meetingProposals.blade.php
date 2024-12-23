@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style2 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('localsec.meetings') }}">Local Meetings</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Proposals</li>
            </ol>
        </nav>


        <div class="card">
            <div class="card-header">
                <h3 style="font-family: Helvetica, Arial, sans-serif;">Proposals for Meeting:
                    <span class="fw-bold">{{ $meeting->meeting_description }}</span>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Venue:</strong> <span
                                class="fw-bold fs-6">{{ $meeting->venue->venue_description ?? 'N/A' }}</span></p>
                        <p><strong>Meeting Date:</strong>
                            <span
                                class="fw-bold fs-6">{{ $meeting->meeting_date ? $meeting->meeting_date->format('Y-m-d') : 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Submission Start:</strong>
                            <span class="fw-bold fs-6">
                                {{ $meeting->submission_start ? \Carbon\Carbon::parse($meeting->submission_start)->format('M-d-Y') : 'N/A' }}
                            </span>
                        </p>
                        <p><strong>Submission End:</strong>
                            <span
                                class="fw-bold fs-6">{{ $meeting->submission_end ? \Carbon\Carbon::parse($meeting->submission_end)->format('M-d-Y') : 'N/A' }}</span>
                        </p>
                    </div>
                </div>
            </div>


            <h4 class="fw-bold" style="font-family: Helvetica, Arial, sans-serif;">Proposals</h4>
            <form onsubmit="listtAgenda(this, event)">
                <div class="table-responsive text-nowrap">
                    <table id="proposalsTable" class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">
                                    <input type="checkbox" id="selectAll" onchange="selectAllMeeting(this)">
                                </th>
                                <th class="fw-bold fs-7">Title</th>
                                <th class="fw-bold fs-7">Proponent</th>
                                <th class="fw-bold fs-7">Type</th>
                                <th class="fw-bold fs-7">Subtype</th>
                                <th class="fw-bold fs-7">Status</th>
                                <th class="fw-bold fs-7">File</th>
                                <th class="fw-bold fs-7">Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proposals as $proposal)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="proposal-checkbox"
                                            value="{{ $proposal->proposal_id }}">
                                    </td>
                                    <td class="fw-bold fs-6" style="font-family: Helvetica, Arial, sans-serif;">
                                        {{ $proposal->title }}</td>

                                    <td class="fw-bold fs-6" style="font-family: Helvetica, Arial, sans-serif;">
                                        {{ $proposal->first_name }} {{ $proposal->last_name }}</td>

                                    <td class="fw-bold fs-6" style="font-family: Helvetica, Arial, sans-serif;">
                                        {{ $proposal->type ?? 'N/A' }}</td>

                                    <td class="fw-bold fs-6" style="font-family: Helvetica, Arial, sans-serif;">
                                        {{ $proposal->subtype ?? 'N/A' }}</td>


                                    <td class="fw-bold fs-6" style="font-family: Helvetica, Arial, sans-serif;">
                                        {{ $proposal->status ?? 'N/A' }}
                                    </td>

                                    <td>
                                        @if (isset($proposalVersions))
                                            @php
                                                $proposalFile = $proposalVersions
                                                    ->where('proposals_id', $proposal->proposal_id)
                                                    ->sortByDesc('created_at')
                                                    ->first();
                                            @endphp
                                            @if ($proposalFile)
                                            <button class="btn btn-primary btn-sm" onclick="viewProposalFile('{{ $proposalFile->file_paths }}'); return false;">
                                                View File
                                            </button>
                                            @else
                                                <span class="text-muted">No File</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No File</span>
                                        @endif
                                    </td>

                                    <td>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="fw-bold fs-6"
                                        style="font-family: Helvetica, Arial, sans-serif;">No proposals found for this
                                        meeting.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="demo-inline-spacing">
                        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="viewFileModal" tabindex="-1" aria-labelledby="viewFileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFileModalLabel">Proposal File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="fileViewer" src="" frameborder="1" style="width: 100%; height: 500px;"></iframe>
                </div>
            </div>
        </div>
    </div>
    

    <script>
       function viewProposalFile(filePath) {
    // Set the file path in the iframe
    const fileViewer = document.getElementById('fileViewer');
    fileViewer.src = `/storage/${filePath}`; // Ensure the file path is correctly prefixed with the storage URL

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('viewFileModal'));
    modal.show();
}

        function selectAllMeeting(el) {
            if ($(el).prop('checked')) {
                $('.proposal-checkbox').each(function(index, value) {
                    $(this).attr('checked', true)
                })
            } else {
                $('.proposal-checkbox').each(function(index, value) {
                    $(this).attr('checked', false)
                })
            }
        }

        function listAgenda(el, event) {
            event.preventDefault();

            let selectedProposals = [];

            $('.proposal-checkbox').each(function(index, value) {
                if ($(this).attr('checked', true)) {
                    selectedProposals.push($(this).val())
                }
            })

            $.ajax({
                type: "POST",
                url: "/localSec/meeting/list-agenda",
                data: {
                    selectedProposals
                },
                dataType: "json",
                success: function(response) {
                    if (response.status_code == 0) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
