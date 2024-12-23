@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">SUBMIT PROPOSAL</h3>
      </div>
      <div class="card-body">
        @if($activeSchedule)
        <div class="alert alert-info fw-bold fs-5">
          <strong>Meeting Description:</strong> {{ $activeSchedule->meeting_description }}
          <br>
          <strong>Submission Period:</strong> 
          {{ \Carbon\Carbon::parse($activeSchedule->submission_start)->format('F j, Y g:i A') }} 
          to 
          {{ \Carbon\Carbon::parse($activeSchedule->submission_end)->format('F j, Y g:i A') }}
          <br>
          <strong>Campus:</strong> {{ $userCampus }}

          
        </div>
        <form id="submit-proposal-form">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="proposal-title">Title</label>
            <div class="input-group input-group-merge">
              <span id="proposal-title-icon" class="input-group-text"><i class="bx bx-book"></i></span>
              <input type="text" class="form-control" id="proposal-title" name="title" placeholder="Enter Proposal Title" required />
            </div>
          </div>

          {{-- <!-- Role Selection -->
          @if (Auth::user()->academicCouncilMembership && Auth::user()->adminCouncilMembership)
          <div class="mb-3">
            <label class="form-label">Submit As</label>
            <div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="submit_as" id="academic-member" value="academic" required>
                <label class="form-check-label" for="academic-member">Academic Council Member</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="submit_as" id="admin-member" value="admin" required>
                <label class="form-check-label" for="admin-member">Administrative Council Member</label>
              </div>
            </div>
          </div>
          @endif --}}

          <div class="mb-3">
            <label class="form-label" for="type-of-matter">Type of Matter</label>
            <div class="input-group input-group-merge">
              <span id="type-of-matter-icon" class="input-group-text"><i class="bx bx-category"></i></span>
              <select id="type-of-matter" name="type" class="form-control" required>
                @if (Auth::user()->academicCouncilMembership && Auth::user()->adminCouncilMembership)
                <option selected disabled>Select Type of Matter</option>
                <option value="Academic Matter">Academic Matter</option>
                <option value="Administrative Matter">Administrative Matter</option>
                @elseif (Auth::user()->academicCouncilMembership)
                <option value="Academic Matter" selected>Academic Matter</option>
                @elseif (Auth::user()->adminCouncilMembership)
                <option value="Administrative Matter" selected>Administrative Matter</option>
                @endif
              </select>
            </div>
          </div>

          <div class="mb-3 {{ Auth::user()->academicCouncilMembership && !Auth::user()->adminCouncilMembership ? 'd-none' : '' }}" id="subtype-wrapper">
            <label class="form-label" for="subtype">Subtype</label>
            <div class="input-group input-group-merge">
              <span id="subtype-icon" class="input-group-text"><i class="bx bx-subdirectory-right"></i></span>
              <select id="subtype" name="subtype" class="form-control">
                <option selected disabled>Select Subtype</option>
                <option value="Financial Matter">Financial Matter</option>
                <option value="Legal Matter">Legal Matter</option>
                <option value="Personnel Matter">Personnel Matter</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="proposal-file">File</label>
            <div class="input-group input-group-merge">
              <span id="proposal-file-icon" class="input-group-text"><i class="bx bx-file"></i></span>
              <input type="file" id="proposal-file" name="file" class="form-control" accept="application/pdf" required />
            </div>
            <div class="form-text text-primary">Only <strong>PDF</strong> files are allowed.</div>
          </div>

          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        @else
        <div class="alert alert-warning text-center">
          <strong>Submission Closed:</strong> There is no active schedule for proposal submissions. Please wait for the schedule to be set.
        </div>
      @endif
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    const typeOfMatterElement = $('#type-of-matter');
    const subtypeWrapper = $('#subtype-wrapper');

    function handleSubtypeVisibility() {
      const typeOfMatter = typeOfMatterElement.val();
      if (typeOfMatter === 'Administrative Matter') {
        subtypeWrapper.removeClass('d-none');
      } else {
        subtypeWrapper.addClass('d-none');
        $('#subtype').val('');
      }
    }

    typeOfMatterElement.on('change', handleSubtypeVisibility);

    handleSubtypeVisibility();

    $('#submit-proposal-form').on('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      $.ajax({
        url: "{{ route('proponent.submitProposal') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
          toastr.info("Submitting your proposal...");
        },
        success: function (response) {
          toastr.success(response.message);
          $('#submit-proposal-form')[0].reset();
          handleSubtypeVisibility();
        },
        error: function (xhr) {
          console.log(xhr.responseText); 

          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            for (const field in errors) {
              toastr.error(errors[field][0]);
            }
          } else {
            toastr.error("An unexpected error occurred. Please try again.");
          }
        }
      });
    });
  });
</script>
@endsection
