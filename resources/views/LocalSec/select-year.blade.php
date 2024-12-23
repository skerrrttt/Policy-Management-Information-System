@if(count($meetings) < 0)
    <tr>
        <td colspan="3" class="text-center">No Date</td>
    </tr>
@else
@foreach ($meetings as $meeting)
@php
    switch($meeting['quarter']){
        case 1:
            $quarter = "1st Quarter";
            break;
        case 2;
            $quarter = "2nd Quarter";
            break;
        case 3;
            $quarter = "3rd Quarter";
            break;
        case 4;
            $quarter = "4th Quarter";
            break;
    }
    $type = "";

    if(isset($meeting['council_type_id'])){

        if($meeting['council_type_id'] == 1){
            $type = "Academic";
        }elseif($meeting['council_type_id'] == 2){
            $type ="Administrative";
        }else{
            $type ="Joint";
        }
    }
    
@endphp
<tr class="text-center">
    <td>{{$quarter}}</td>
    @if(isset($meeting['council_type_id']))
        @if($meeting['council_type_id'] == 1)
            <td><span class="badge bg-success">Available</span></td>
            <td><span class="badge bg-danger">Not Available</span></td>
        @elseif($meeting['council_type_id'] == 2)
            <td><span class="badge bg-danger">Not Available</span></td>
            <td><span class="badge bg-success">Available</span></td>
        @elseif($meeting['council_type_id'] == 3)
            <td colspan="2"> <span class="badge bg-success" colspan="2">Local Joint Meeting is Available</span></td>
            <td hidden></td>
        @endif
    @else 
        <td><span class="badge bg-danger">Not Available</span></td>
        <td><span class="badge bg-danger">Not Available</span></td>
    @endif
</tr>

@endforeach
@endif