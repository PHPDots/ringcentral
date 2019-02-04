@if(isset($rows) && count($rows))
	@foreach($rows as $row)
		<div class="customer-section">
		    <div class="customer-detail">
		        <h4 class="text-bold">First Last Name</h4>
		        <h5>Ext 101</h5>
		        <h5>Call Status <span class="label label-success" >Active</span></h5>
		        <h5>Queue Status <span class="label label-success" >Active</span></h5>
		    </div>
		</div>	
	@endforeach
@else

@endif
