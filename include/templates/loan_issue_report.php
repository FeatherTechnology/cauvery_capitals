<link rel="stylesheet" type="text/css" href="css/ledger_report.css">
<br><br>
<div class="page-header">
	<div style="background-color:#0c70ab; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Cauvery Capitals - Loan Issue Report
	</div>

</div><br>
<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="loan_issue_report_form" name="loan_issue_report_form" action="" method="post" enctype="multipart/form-data">

		<div class="row gutters" id="loan_issue_card">
			<div class="toggle-container col-12">
				<input type="date" id='from_date' name='from_date' class="toggle-button" value=''>
				<input type="date" id='to_date' name='to_date' class="toggle-button" value=''>
				<input type="button" id='reset_btn' name='reset_btn' class="toggle-button" style="background-color: #0c70ab;color:white" value='Reload'>
			</div>
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">Loan Issue Report</div>
					<div class="card-body">
						<div id="loan_issue_table_div" class="table-divs" style="overflow-x: auto;">
							<table id="loan_issue_report_table" class="table custom-table">
								<thead>
									<th>S.No</th>
									<th>Loan ID</th>
									<th>Doc ID</th>
									<th>Cust. ID</th>
									<th>Cust. Name</th>
									<th>Guarantor Name</th>
									<th>Relationship</th>
									<th>Area</th>
									<th>Sub Area</th>
									<th>Line</th>
									<th>Branch</th>
									<th>Loan Category</th>
									<th>Sub Category</th>
									<th>Agent</th>
									<th>Responsible</th>
									<th>Loan Date</th>
									<th>Loan Amount</th>
									<th>Principal Amount</th>
									<th>Interest Amount</th>
									<th>Document Charge</th>
									<th>Processing Fee</th>
									<th>Total Amount</th>
									<th>Net Cash</th>
									<th>Due Amount</th>
									<th>No of Due</th>
									<th>First Loan Date</th>
									<th>Maturity Date</th>
									<th>Received By</th>
									<th>Relation Name</th>
								</thead>
								<tbody></tbody>
								<tfoot>
									<tr>
										<td colspan="16"></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td colspan="5"></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>