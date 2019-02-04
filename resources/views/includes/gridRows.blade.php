@if(isset($only_pagination))
      {!! $model->render() !!}
@else
      @if(isset($rows) && count($rows) > 0)
            @foreach($rows as $row)
              <tr>
                  <td data-label="Type">
                        <img src="{{ $row['image'] }}" width="24" />
                  </td>
                  <td data-label="From">
                        {{ $row['from_no'] }}
                  </td>
                  <td data-label="To">
                        {{ $row['to_no'] }}
                  </td>
                  <td data-label="Ext">
                        {{ $row['extension'] }}
                  </td>
                  <td data-label="Forwarded to">
                        {{ $row['forward_to'] }}
                  </td>
                  <td data-label="First Name">
                        {{ $row['to_name'] }}
                  </td>
                  <td data-label="date-time">
                        {{ $row['date'] }}
                  </td>
                  <td data-label="Recording">
                        {{ $row['recording'] }}
                  </td>
                  <td data-label="Result">
                        {{ $row['result'] }}
                  </td>
                  <td data-label="Length">
                        {{ $row['duration'] }}
                  </td>
              </tr>
            @endforeach
      @else
            <tr>
                  <td colspan="10" align="center">
                        No results
                  </td>
            </tr>
      @endif
@endif