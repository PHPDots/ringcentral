@if(isset($rows) && count($rows))
	@foreach($rows as $row)
		<div class="customer-section">
		    <div class="customer-detail">
		        <h4 class="text-bold">		        	
		        	@if(isset($row['to_name']))
		        		{{ $row['to_name'] }}
		        	@else		        	
		        		{{ $row['to_number'] }}
		        	@endif	
		        </h4>
		        <h5>Ext 101</h5>
		        <h5>
		        	Call Status <span class="label label-success" >{{ $row['result'] }}</span>
		        </h5>
		        <h5>Queue Status <span class="label label-success" >Active</span></h5>
		    </div>
		</div>	
	@endforeach
@else
	<div class="customer-section">
		No Active Calls found!
	</div>
@endif
