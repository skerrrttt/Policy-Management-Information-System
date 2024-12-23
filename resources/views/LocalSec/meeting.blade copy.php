@extends('layouts.app')

@section('content')

<!-- Hoverable Table rows -->
<div class="card">
    <h5 class="card-header">Local Meetings</h5>
    <div class="card-body">
        <!-- Add New Meeting Button -->
        <button type="button" class="btn btn-primary position-absolute" style="top: 10px; right: 10px;" data-bs-toggle="modal" data-bs-target="#addMeetingModal">
            <i class="bx bx-plus bx-sm me-sm-1"></i>Create New Meeting
        </button>
        
        <div class="card-datatable table-responsive">
            <table class="datatables-basic table border-top dataTable no-footer dtr-column collapsed" id="meetingTable" aria-describedby="DataTables_Table_0_info">
                <thead class="bg-primary">
                    <tr>
                        <th class="text-center text-primary">#</th> <!-- Add index column header -->
                        <th class="text-center text-primary">Quarter</th>
                        <th class="text-center text-primary">Meeting Description</th>
                        <th class="text-center text-primary">Modality</th>
                        <th class="text-center text-primary">Meeting Venue</th>
                        <th class="text-center text-primary">Meeting Date</th>
                        <th class="text-center text-primary">Council Type</th>
                        <th class="text-center text-primary">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($meetings as $index => $meeting)
                    <tr data-meeting-id="{{ $meeting->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td class="text-center">{{ $quarters[$meeting->quarter] ?? 'N/A' }}</td> <!-- Display the quarter name -->
                        <td class="text-center">{{ $meeting->meeting_description }}</td>
                        <td class="text-center">{{ $meeting->modality->modality_description ?? 'N/A' }}</td>
                        <td class="text-center">{{ $meeting->venue->venue_description ?? 'N/A' }}</td>
                        <td class="text-center">{{ $meeting->meeting_date ? $meeting->meeting_date->format('Y-m-d') : 'N/A' }}</td>
                        <td class="text-center">{{ $meeting->councilType->type_description ?? 'N/A' }}</td>
                        <td class="text-center">
                            <!-- Action Dropdown -->
                            <div class="d-inline-block">
                                <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded bx-md"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end m-0">
                                    <li><a href="{{ route('localSec.meeting.view', encrypt($meeting->id)) }}" class="dropdown-item bg-label-info">View</a></li>
                                    <li><a href="#" class="dropdown-item edit-meeting-btn bg-label-dark">Edit</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li><a href="#" class="dropdown-item text-danger delete-meeting-btn" data-id="{{ $meeting->id }}">Delete</a></li>
                                </ul>
                            </div>
                            <!-- End Action Dropdown -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
<!--/ Hoverable Table rows -->

<!-- Add Meeting Modal -->
<div class="modal fade" id="addMeetingModal" tabindex="-1" aria-labelledby="addMeetingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMeetingModalLabel">Create Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form to save meeting -->
            <form id="addMeetingForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quarter" class="form-label">Quarter</label>
                        <select class="form-control" id="quarter" name="quarter" required>
                            <option value="" disabled selected>Select Quarter</option>
                            <option value="1">1st Quarter</option>
                            <option value="2">2nd Quarter</option>
                            <option value="3">3rd Quarter</option>
                            <option value="4">4th Quarter</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Meeting Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Brief description of the meeting" required>
                    </div>

                    <div class="mb-3">
                        <label for="modality_id" class="form-label">Modality</label>
                        <div class="input-group">

                        <select class="form-control" id="modality_id" name="modality_id" >
                            <option value="" disabled selected>Select Modality</option>
                            @foreach($modalities as $modality)
                                <option value="{{ $modality->id }}">{{ $modality->modality_description }}</option>
                            @endforeach
                        </select>

                        <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addModalityModal">
                            <i class="bx bx-plus fs-5"></i>
                        </button>
                    </div>
                </div>

                    <div class="mb-3">
                        <label for="venue_id" class="form-label">Meeting Venue</label>
                        <div class="input-group">
                        <select class="form-control" id="venue_id" name="venue_id" >
                            <option value="" disabled selected>Select Venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}">{{ $venue->venue_description }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addVenueModal">
                            <i class="bx bx-plus fs-5"></i>
                        </button>
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="meeting_date" class="form-label">Meeting Date (optional)</label>
                        <input type="date" class="form-control" id="meeting_date" name="meeting_date">
                    </div>
                    


                    <div class="mb-3">
                        <label for="council_type_id" class="form-label">Council Type</label>
                        <select class="form-control" id="council_type_id" name="council_type_id" required>
                            <option value="" disabled selected>Select Council Type</option>
                            @foreach($councilTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->type_description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="submission_start" class="form-label">Submission Start Date</label>
                        <input type="date" class="form-control" id="submission_start" name="submission_start" required>
                    </div>
                    <div class="mb-3">
                        <label for="submission_end" class="form-label">Submission End Date</label>
                        <input type="date" class="form-control" id="submission_end" name="submission_end" required>
                    </div>
                </div>

                


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--/ Add Meeting Modal -->

<!-- Add Venue Modal -->
<div class="modal fade" id="addVenueModal" tabindex="-1" aria-labelledby="addVenueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVenueModalLabel">Add Meeting Venue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addVenueForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="venue_description" class="form-label">Venue Description</label>
                        <input type="text" class="form-control" id="venue_description" name="venue_description" placeholder="Enter venue name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Modality Modal -->
<div class="modal fade" id="addModalityModal" tabindex="-1" aria-labelledby="addModalityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalityModalLabel">Add Meeting Modality</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addModalityForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modality_description" class="form-label">Modality Description</label>
                        <input type="text" class="form-control" id="modality_description" name="modality_description" placeholder="Enter modality name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Meeting Modal -->
<div class="modal fade" id="editMeetingModal" tabindex="-1" aria-labelledby="editMeetingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMeetingModalLabel">Edit Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMeetingForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_meeting_id" name="meeting_id">
                    <div class="mb-3">
                        <label for="edit_quarter" class="form-label">Quarter</label>
                        <input type="text" class="form-control" id="edit_quarter" name="quarter" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Meeting Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_venue_id" class="form-label">Meeting Venue</label>
                        <select class="form-control" id="edit_venue_id" name="venue_id" >
                            <option value="" disabled selected>Select Venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}">{{ $venue->venue_description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_meeting_date" class="form-label">Meeting Date</label>
                        <input type="date" class="form-control" id="edit_meeting_date" name="meeting_date">
                    </div>
                    <div class="mb-3">
                        <label for="edit_modality_id" class="form-label">Modality</label>
                        <select class="form-control" id="edit_modality_id" name="modality_id" >
                            <option value="" disabled selected>Select Modality</option>
                            @foreach($modalities as $modality)
                                <option value="{{ $modality->id }}">{{ $modality->modality_description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_council_type_id" class="form-label">Council Type</label>
                        <select class="form-control" id="edit_council_type_id" name="council_type_id" required>
                            <option value="" disabled selected>Select Council Type</option>
                            @foreach($councilTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->type_description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_submission_start" class="form-label">Submission Start Date</label>
                        <input type="date" class="form-control" id="edit_submission_start" name="submission_start" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_submission_end" class="form-label">Submission End Date</label>
                        <input type="date" class="form-control" id="edit_submission_end" name="submission_end" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        $('#meetingTable').DataTable({
            responsive: true,
             scrollCollapse: true,
            scrollY: '500px',
            language: {
                search: "Filter records:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    
        $('#addMeetingForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
    
            let formData = $(this).serialize();
    
            $.ajax({
                url: "{{ route('local-sec.store-meeting') }}", 
                type: "POST",
                data: formData,
                success: function(response) {
                    // Display success notification
                    toastr.success(response.message);
                    $('#addMeetingModal').modal('hide'); 
                    $('#addMeetingForm')[0].reset(); 
                    
    
                },
                error: function(xhr) {
                    // Display error notification
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        console.log(xhr.responseText);
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error('An unexpected error occurred.');
                    }
                }
            });
        });

        $('.edit-meeting-btn').on('click', function() {
        var meetingId = $(this).closest('tr').data('meeting-id'); // Correct way to get the meeting ID
        
        if (meetingId) { 
            $.ajax({
                url: '/local-sec/meetings/' + meetingId, // Correct route to get meeting data by ID
                type: 'GET',
                success: function(response) {
                    // Populate the form fields with the meeting data
                    $('#edit_meeting_id').val(response.meeting.id);
                    $('#edit_quarter').val(response.meeting.quarter);
                    $('#edit_description').val(response.meeting.meeting_description);
                    $('#edit_venue_id').val(response.meeting.meeting_venue_id);
                    $('#edit_meeting_date').val(response.meeting.meeting_date ? response.meeting.meeting_date : '');
                    $('#edit_modality_id').val(response.meeting.meeting_modality_id);
                    $('#edit_council_type_id').val(response.meeting.council_type_id);
                    $('#edit_submission_start').val(response.meeting.submission_start);
                    $('#edit_submission_end').val(response.meeting.submission_end);

                    // Show the modal
                    $('#editMeetingModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Failed to fetch meeting data.');
                }
            });
        } else {
            toastr.error('Meeting ID is missing.');
        }
    });



        // Add Venue
    $('#addVenueForm').on('submit', function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('local-sec.store-venue') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                toastr.success(response.message);
                $('#addVenueModal').modal('hide');
                $('#addVenueForm')[0].reset();
                // Reload dropdown
                $('#venue_id').append(`<option value="${response.data.id}">${response.data.venue_description}</option>`);
            },
            error: function (xhr) {
                toastr.error('Failed to add venue.');
            },
        });
    });

    $('#addModalityForm').on('submit', function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('local-sec.store-modality') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                toastr.success(response.message);
                $('#addModalityModal').modal('hide');
                $('#addModalityForm')[0].reset();
                // Reload dropdown
                $('#modality_id').append(`<option value="${response.data.id}">${response.data.modality_description}</option>`);
            },
            error: function (xhr) {
                toastr.error('Failed to add modality.');
            },
        });
    });

    $('#editMeetingForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        let formData = $(this).serialize();
        let meetingId = $('#edit_meeting_id').val(); // Get meeting ID from the form

        $.ajax({
            url: '/local-sec/meetings/' + meetingId, // Correct route to update the meeting
            type: 'PUT',
            data: formData,
            success: function(response) {
                toastr.success(response.message);
                $('#editMeetingModal').modal('hide');
                location.reload(); // Reload the page to show the updated meeting
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    toastr.error(xhr.responseJSON.error);
                } else {
                    toastr.error('An unexpected error occurred.');
                }
            }
        });
    });

    $(document).on('click', '.delete-meeting-btn', function(e) {
    e.preventDefault(); // Prevent default action (navigation)

    var meetingId = $(this).data('id');  // Get the meeting ID from the data-id attribute

    // SweetAlert2 confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: "This meeting will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            //  Ajax request to delete the meeting
            $.ajax({
                url: '/local-sec/meetings/' + meetingId, 
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',  
                },
                success: function(response) {
                    // Show success message using SweetAlert
                    Swal.fire(
                        'Deleted!',
                        'The meeting has been deleted.',
                        'success'
                    );

                    // Remove the deleted meeting row from the table
                    $('tr[data-meeting-id="' + meetingId + '"]').remove();
                },
                error: function(xhr, status, error) {
                console.log(xhr.responseText); // Log the response from the server
                Swal.fire('Error!', 'There was an error deleting the meeting.', 'error');
            }
            });
        }
    });
});



});



    </script>

@endsection
