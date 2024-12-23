@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-datatable table-responsive">
                @csrf
                <select name="" id="" class="form-select mb-3" onchange="selectYear(this)">
                    @foreach ($allMeetingYears as $allMeetingYear)
                        <option value="{{ $allMeetingYear }}">{{ $allMeetingYear }}</option>
                    @endforeach
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>





                </select>

                <table class="datatables-basic table border-top dataTable no-footer dtr-column collapsed" id="meetingsTable"
                    aria-describedby="DataTables_Table_0_info">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center text-primary">Quarter</th>
                            <th class="text-center text-primary">Academic</th>
                            <th class="text-center text-primary">Administrative</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        @foreach ($meetings as $meeting)
                            @php
                                switch ($meeting['quarter']) {
                                    case 1:
                                        $quarter = '1st Quarter';
                                        break;
                                    case 2:
                                        $quarter = '2nd Quarter';
                                        break;
                                    case 3:
                                        $quarter = '3rd Quarter';
                                        break;
                                    case 4:
                                        $quarter = '4th Quarter';
                                        break;
                                }
                                $type = '';

                                if (isset($meeting['council_type_id'])) {
                                    if ($meeting['council_type_id'] == 1) {
                                        $type = 'Academic';
                                    } elseif ($meeting['council_type_id'] == 2) {
                                        $type = 'Administrative';
                                    } else {
                                        $type = 'Joint';
                                    }
                                }

                            @endphp
                            <tr class="text-center">
                                <td>{{ $quarter }}</td>
                                @if (isset($meeting['council_type_id']))
                                    @if ($meeting['council_type_id'] == 1)
                                        <td><span class="badge bg-success" style="cursor: pointer"
                                                onclick="meetingDetails('{{ encrypt($meeting['id']) }}')">Available</span>
                                        </td>
                                        <td><span class="badge bg-danger">Not Available</span></td>
                                    @elseif($meeting['council_type_id'] == 2)
                                        <td><span class="badge bg-danger">Not Available</span></td>
                                        <td><span class="badge bg-success" style="cursor: pointer"
                                                onclick="meetingDetails('{{ encrypt($meeting['id']) }}')">Available</span>
                                        </td>
                                    @elseif($meeting['council_type_id'] == 3)
                                        <td colspan="2"> <span class="badge bg-success" colspan="2"
                                                style="cursor: pointer"
                                                onclick="meetingDetails('{{ encrypt($meeting['id']) }}')">Local Joint
                                                Meeting is Available</span></td>
                                        <td hidden></td>
                                    @endif
                                @else
                                    <td><span class="badge bg-danger">Not Available</span></td>
                                    <td><span class="badge bg-danger">Not Available</span></td>
                                @endif
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
<script>
    function selectYear(el) {
        let year = $(el).val();

        $.ajax({
            type: "POST",
            url: "/agenda",
            data: {
                "year": year,
            },
            success: function(response) {
                $('#meetingsTable tbody').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error: ", error);
            }
        });

    }


    function meetingDetails(meetingId) {
        location.href = `/join-admin/agenda/${meetingId}/view`
    }
</script>
