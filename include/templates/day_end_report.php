<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#0c70ab; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Cauvery Capitals - Day End Report
	</div>
</div><br>

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="day_end_report_form" name="day_end_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters" id="day_end_card">

			<div class="toggle-container col-12">
				<input type="date" id='search_date' name='search_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #0c70ab;color:white" value='Reload'>
			</div>
            
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Day End Report</div>
					<div class="card-body">
						<div id="day_end_div" class="table-divs" style="overflow-x: auto;">
							<table id="day_end_report_table" class="table custom-table">
								<thead>
                                <th></th>
                                <th>Hand Cash</th>
                                <th>KVB</th>
                                <th>CUB</th>
                                <th>Total</th>
                                <th>Till Now</th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>