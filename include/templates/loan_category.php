<?php
require_once 'moneyFormatIndia.php';
@session_start();
if (isset($_SESSION["userid"])) {
	$userid = $_SESSION["userid"];
}

$id = 0;
$emicheck = true;
$calcheck = 0;
$typeofaccount;
$loanCategoryCreationList = $userObj->getLoanCategoryCreation($mysqli);

if (isset($_POST['submitLoanCategory']) && $_POST['submitLoanCategory'] != '') {
	if (isset($_POST['id']) && $_POST['id'] > 0 && is_numeric($_POST['id'])) {
		$id = $_POST['id'];
		$userObj->updateLoanCategoryDetails($mysqli, $id, $userid);
?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_category&msc=2';
		</script>
	<?php	} else {
		$userObj->addLoanCategoryDetails($mysqli, $userid);
	?>
		<script>
			location.href = '<?php echo $HOSTPATH;  ?>edit_loan_category&msc=1';
		</script>
	<?php
	}
}

$del = 0;
$costcenter = 0;
if (isset($_GET['del'])) {
	$del = $_GET['del'];
}
if ($del > 0) {
	$userObj->deleteLoanCategoryDetails($mysqli, $del, $userid);
	?>
	<script>
		location.href = '<?php echo $HOSTPATH;  ?>edit_loan_category&msc=3';
	</script>
<?php
}

if (isset($_GET['upd'])) {
	$idupd = $_GET['upd'];
}
$agent_loan ='';
$status = 0;
if ($idupd > 0) {
	$getLoanCategoryDetails = $userObj->getLoanCategoryDetails($mysqli, $idupd);
	if (sizeof($getLoanCategoryDetails) > 0) {
		for ($i = 0; $i < sizeof($getLoanCategoryDetails); $i++) {
			$loan_category_id                 	 = $getLoanCategoryDetails['loan_category_id'];
			$loan_category_name          		     = $getLoanCategoryDetails['loan_category_name'];
			// $sub_category_name      			     = $getLoanCategoryDetails['sub_category_name'];
			$loan_limit      			     = $getLoanCategoryDetails['loan_limit'];
			$loan_category_ref_id       			 = $getLoanCategoryDetails['loan_category_ref_id'];
			$loan_category_ref_name                	 = $getLoanCategoryDetails['loan_category_ref_name'];
			$agent_loan                	 = $getLoanCategoryDetails['agent_loan'];
		}
	}
}

if ($idupd > 0) {
	$getLoanCalculation = $userObj->getLoanCalculation($mysqli, $idupd);
$profit_method ='';
	if (sizeof($getLoanCalculation) > 0) {
		for ($ibranch = 0; $ibranch < sizeof($getLoanCalculation); $ibranch++) {

			$loan_cal_id            = $getLoanCalculation['loan_cal_id'];
			$due_type               = $getLoanCalculation['due_type'];
			$profit_method          = $getLoanCalculation['profit_method'];
			$calculate_method       = $getLoanCalculation['calculate_method'];
			$intrest_rate_min       = $getLoanCalculation['intrest_rate_min'];
			$intrest_rate_max       = $getLoanCalculation['intrest_rate_max'];
			$due_period_min         = $getLoanCalculation['due_period_min'];
			$due_period_max         = $getLoanCalculation['due_period_max'];
			$doc_charge_type         = $getLoanCalculation['doc_charge_type'];
			$document_charge_min    = $getLoanCalculation['document_charge_min'];
			$document_charge_max    = $getLoanCalculation['document_charge_max'];
			$proc_fee_type    = $getLoanCalculation['proc_fee_type'];
			$processing_fee_min     = $getLoanCalculation['processing_fee_min'];
			$processing_fee_max     = $getLoanCalculation['processing_fee_max'];
			// $overdue_type           = $getLoanCalculation['overdue_type'];
			$overdue                = $getLoanCalculation['overdue'];
			$collection_info        = $getLoanCalculation['collection_info'];
		}
	}
//FOR INTREST LOAN 

	// $emicheck = strpos($due_type, 'emi') !== false;
	// $calcheck = strpos($due_type, 'intrest') !== false;

	$profit_method = explode(',', $profit_method);
}

if ($idupd > 0) {
	$getLoanScheme = $userObj->getLoanScheme($mysqli, $loan_category_id);

	$schemeIds = "";

	if (sizeof($getLoanScheme) > 0) {
		$ids = array();

		foreach ($getLoanScheme as $row) {
			$ids[] = $row['scheme_id'];
		}

		$schemeIds = implode(',', $ids);
	}
}

?>

<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#0c70ab; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Cauvery Capitals - Loan Category
	</div>
</div><br>
<div class="text-right" style="margin-right: 25px;">
	<a href="edit_loan_category">
		<button type="button" class="btn btn-primary"><span class="icon-arrow-left"></span>&nbsp; Back</button>
	</a>
</div><br><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!--form start-->
	<form id="report_creation" name="report_creation" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" class="form-control" value="<?php if (isset($loan_category_id)) echo $loan_category_id; ?>" id="id" name="id" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" name="loan_id_upd" id="loan_id_upd" class="form-control" value="<?php if (isset($loan_cal_id)) echo $loan_cal_id; ?>">
		<input type="hidden" class="form-control" value="<?php if (isset($schemeIds)) echo $schemeIds; ?>" id="scheme_name2" name="scheme_name2" aria-describedby="id" placeholder="Enter id">
		<input type="hidden" class="form-control" value="" id="scheme_id" name="scheme_id">
		<!-- Row start -->
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title">General Info</div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Loan Category</label>&nbsp;<span class="required">*</span>
											<select type="text" class="form-control" id="loan_category_name" name="loan_category_name" tabindex="1">
												<option value="">Select Loan Category</option>
												<?php if (sizeof($loanCategoryCreationList) > 0) {
													for ($j = 0; $j < count($loanCategoryCreationList); $j++) { ?>
														<option <?php if (isset($loan_category_name)) {
																	if ($loanCategoryCreationList[$j]['loan_category_creation_id'] == $loan_category_name)  echo 'selected';
																}  ?> value="<?php echo $loanCategoryCreationList[$j]['loan_category_creation_id']; ?>">
															<?php echo $loanCategoryCreationList[$j]['loan_category_creation_name']; ?></option>
												<?php }
												} ?>
											</select>
											<span id="loanCategoryCheck" class="text-danger" style="display: none;">Select Loan Category</span>
										</div>
									</div>
									<div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-12" style="margin-top: 19px;">
										<div class="form-group">
											<button type="button" tabindex="2" class="btn btn-primary" id="add_loanCategoryDetails" name="add_loanCategoryDetails" data-toggle="modal" data-target=".addloanCategoryModal" style="padding: 5px 35px;"><span class="icon-add"></span></button>
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Loan Limit</label><span class="required">&nbsp;*</span>
											<input type="text" tabindex="3" id="loan_limit" name="loan_limit" class="form-control" placeholder="Enter Loan Limit" value="<?php if (isset($loan_limit)) echo moneyFormatIndia($loan_limit); ?>">
											<span id="loan_limitCheck" class="text-danger" style="display: none;">Enter Loan limit</span>
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Agent Loan</label><span class="required">&nbsp;*</span>
											<select class='form-control' id='agent_loan' name='agent_loan' tabindex="4">
												<option value="">Select Agent Loan</option>
												<option value="0" <?php if ($agent_loan == '0') echo 'selected'; ?>>Yes</option>
												<option value="1" <?php if ($agent_loan == '1') echo 'selected'; ?>>No</option>
											</select>
											<span id="agent_loanCheck" class="text-danger" style="display: none;">Enter Agent Loan</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="card-title">Category Info&nbsp;<span class="required">*</span></div>
					</div>
					<div class="card-body">
						<div class="row ">
							<!--Fields -->
							<div class="col-md-12 ">
								<div class="row">
									<div class="col-md-12">
										<label><span class="text-danger" id="loanCategoryTableCheck">Category Info Mandatory Field</span></label>
										<table id="moduleTable" class="table custom-table">
											<thead>
												<tr>
													<th>Category Info</th>
													<th></th>
													<th>Action</th>
												</tr>
											</thead>
											<?php if ($idupd <= 0) { ?>
												<tbody>
													<tr>
														<td>
															<input type="text" tabindex="5" name="loan_category_ref_name[]" id="loan_category_ref_name" class="form-control" value="<?php if (isset($loan_category_ref_name)) {
																																														echo $loan_category_ref_name[$i];
																																													} ?>">
														</td>
														<td>
															<button type="button" tabindex="6" id="add_category_ref[]" name="add_category_ref" value="Submit" class="btn btn-primary add_category_ref">Add</button>
														</td>
														<td>
															<span class='icon-trash-2' tabindex="7"></span>
														</td>
													</tr>
												</tbody>
												<?php }
											if ($idupd > 0) {
												if (isset($loan_category_ref_name)) {
													$k = 30; ?>
													<tbody>
														<?php for ($i = 0; $i <= sizeof($loan_category_ref_name) - 1; $i++) { ?>
															<tr>
																<input type="hidden" name="loan_category_ref_id[]" id="loan_category_ref_id" value="<?php if (isset($loan_category_ref_id)) {
																																						echo $loan_category_ref_id[$i];
																																					} ?>">
																<td>
																	<input type="text" tabindex="<?php echo $k; ?>" name="loan_category_ref_name[]" id="loan_category_ref_name" class="form-control" value="<?php if (isset($loan_category_ref_name)) {
																																																				echo $loan_category_ref_name[$i];
																																																			} ?>">
																</td>
																<td>
																	<button type="button" tabindex="<?php echo $k; ?>" id="add_category_ref[]" name="add_category_ref" value="Submit" class="btn btn-primary add_category_ref">Add</button>
																</td>
																<td>
																	<span class='deleterow icon-trash-2' tabindex="<?php echo $k; ?>" id="delete_row"></span>
																</td>
															</tr>
														<?php $k++;
														} ?>
													</tbody>
											<?php }
											} ?>
										</table>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-header">Loan Calculation</div>
					<div class="card-body row">
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="disabledInput">Due Method</label>
								<input type="text" id="monthly_due_method" name="monthly_due_method" class="form-control" value="Monthly" tabindex='8' readonly>
							</div>
						</div>
						<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="disabledInput">Due Type</label><span class="required">&nbsp;*</span>
								<input type="hidden" class="form-control" id="monthly_due_type" name="due_type" value="emi">
								<input tabindex="6" type="text" class="form-control" id="monthly_duetype" name="monthly_duetype" value="EMI" title="Select Due Type" readonly>
							</div>
						</div> -->
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="disabledInput">Due Type</label><span class="required">&nbsp;*</span>
								<select tabindex="9" type="text" class="form-control" id="monthly_duetype" name="monthly_duetype" title="Select Due Type" required onmousedown="event.preventDefault()">
									<option value=''>Select Due Type</option>
									<option <?php #if (isset($due_type)) {if ($due_type == "emi") echo 'selected';}?> value="emi" selected>EMI</option>
									<!-- <option <?php #if (isset($due_type)) {if ($due_type == "intrest") echo 'selected';}?> value="intrest">Interest</option> -->
								</select>
							</div>
						</div>
						<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 emi_method" style="display: <?php #echo ($emicheck ? 'block' : 'none'); ?>;"></div>  FOR INTREST LOAN-->
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 emi_method">
							<div class="form-group">
								<label for="disabledInput">Profit Method</label><span class="required">&nbsp;*</span>
								<select tabindex="10" type="text" class="form-control selectpicker" id="monthly_profit_method" name="monthly_profit_method[]" data-live-search="true" multiple data-actions-box="true" title="Select Profit Method">
									<option <?php if (isset($profit_method)) {
												if ($profit_method[0] == "pre_intrest") echo 'selected';
											} ?> value="pre_intrest">Pre Benefit</option>
									<option <?php if (isset($profit_method)) {
												if ($profit_method[0] == "after_intrest") {
													echo 'selected';
												} elseif (isset($profit_method[1]) and $profit_method[1] == "after_intrest") {
													echo 'selected';
												}
											}
											?> value="after_intrest">After Benefit</option>
								</select>
							</div>
						</div>
						<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 intrest_method" style="display: <?php #echo ($calcheck ? 'block' : 'none'); ?>;">  FOR INTREST LOAN-->
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 intrest_method"  style="display:none">
							<div class="form-group">
								<label for="disabledInput">Profit Method</label>
								<input type="text" readonly id="int_profit_method" name="int_profit_method" class="form-control" value="After Benefit">
							</div>
						</div>
						<!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 intrest_method" style="display: <?php #echo ($calcheck ? 'block' : 'none'); ?>;"> FOR INTREST LOAN -->
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 intrest_method" style="display:none">
							<div class="form-group">
								<label for="inputReadOnly">Calculate Method</label><span class="required">&nbsp;*</span>
								<select type="text" class="form-control" id="calculate_method" name="calculate_method" title="Select Calculate Method">
									<option value=''>Select Calculate Method</option>
									<option <?php if (isset($calculate_method)) {
												if ($calculate_method == "Monthly") echo 'selected';
											}
											?> value="Monthly">Monthly</option>
									<option <?php if (isset($calculate_method)) {
												if ($calculate_method == "Days") echo 'selected';
											}
											?> value="Days">Days</option>
								</select>
							</div>
						</div>
					</div>
					<div class="card-header">Condition Info</div>
					<div class="card-body row">
						<div class="col-xl-4 col-lg-4 col-md-12 col-sm-6 col-12">
							<div class="form-group">
								<h5>Interest Rate %</h5>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="monthly_disabledInput">Min</label><span class="required">&nbsp;*</span>
								<input type="number" step="0.01" tabindex="11" id="monthly_intrests_rate_min" name="monthly_intrests_rate_min" class="form-control" placeholder="Rate Of Interest Min" value="<?php if (isset($intrest_rate_min)) echo $intrest_rate_min; ?>"
								onchange="checkMinMaxValue('#monthly_intrests_rate_min','#monthly_intrests_rate_max')">
							</div>
						</div>	
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="monthly_disabledInput">Max</label><span class="required">&nbsp;*</span>
								<input type="number" step="0.01" tabindex="12" id="monthly_intrests_rate_max" name="monthly_intrest_rate_max" class="form-control" placeholder="Rate Of Interest Max" value="<?php if (isset($intrest_rate_max)) echo $intrest_rate_max; ?>"
								onchange="checkMinMaxValue('#monthly_intrests_rate_min','#monthly_intrests_rate_max')">
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-12 col-sm-6 col-12">
							<div class="form-group">
								<h5>Due Period %</h5>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="monthly_disabledInput">Min</label><span class="required">&nbsp;*</span>
								<input type="number" step="0.01" tabindex="13" id="monthly_due_periods_min" name="monthly_due_period_min" class="form-control" placeholder="Due Period Min" value="<?php if (isset($due_period_min)) echo $due_period_min; ?>"
								onchange="checkMinMaxValue('#monthly_due_periods_min','#monthly_due_periods_max')">
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="monthly_disabledInput">Max</label><span class="required">&nbsp;*</span>
								<input type="number" step="0.01" tabindex="14" id="monthly_due_periods_max" name="monthly_due_period_max" class="form-control" placeholder="Due Period Max" value="<?php if (isset($due_period_max)) echo $due_period_max; ?>"
								onchange="checkMinMaxValue('#monthly_due_periods_min','#monthly_due_periods_max')">
							</div>
						</div>
						<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="form-group">
								<label style="font-size:1.35em;padding-right:2%">Document Charge: <span class="text-danger">*</span></label>
								<input type="radio" name="monthly_doc_charges_type" id="monthly_docamt" value="amt" <?php if (isset($doc_charge_type) and $doc_charge_type == 'amt') echo 'checked'; ?> tabindex='15'></input><label for='docamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="monthly_doc_charges_type" id="monthly_docpercentage" value="percentage" <?php if (isset($doc_charge_type) and $doc_charge_type == 'percentage') echo 'checked'; ?> tabindex='16'></input><label for='docpercentage'>&nbsp;&nbsp;%</label>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="monthly_disabledInput" id="monthly_docmin">Min</label><span class="required">&nbsp;*</span>
								<input type="number" step="0.01" tabindex="17" id="monthly_document_charges_min" name="monthly_document_charge_min" class="form-control" placeholder="Document Charge Min" value="<?php if (isset($document_charge_min)) echo $document_charge_min; ?>"
								onchange="checkMinMaxValue('#monthly_document_charges_min','#monthly_document_charges_max')">
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="monthly_disabledInput" id="monthly_docmax">Max</label><span class="required">&nbsp;*</span>
								<input type="number" step="0.01" tabindex="18" id="monthly_document_charges_max" name="monthly_document_charge_max" class="form-control" placeholder="Document Charge Max" value="<?php if (isset($document_charge_max)) echo $document_charge_max; ?>"
								onchange="checkMinMaxValue('#monthly_document_charges_min','#monthly_document_charges_max')">
							</div>
						</div>
						<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="form-group">
								<label style="font-size:1.35em;padding-right:6%">Processing Fee: <span class="text-danger">*</span></label>
								<input type="radio" name="proc_fees_type" id="monthly_procamt" value="amt" tabindex="19" <?php if (isset($proc_fee_type) and $proc_fee_type == 'amt') echo 'checked'; ?>></input><label for='procamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="proc_fees_type" id="monthly_procpercentage" value="percentage" tabindex="20" <?php if (isset($proc_fee_type) and $proc_fee_type == 'percentage') echo 'checked'; ?>></input><label for='procpercentage'>&nbsp;&nbsp;%</label>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="monthly_disabledInput" id="monthly_procmin">Min</label><span class="required">&nbsp;*</span>
								<input type="number" step="0.01" tabindex="21" id="monthly_processing_fees_min" name="monthly_processing_fee_min" class="form-control" placeholder="Processing Fee Min" value="<?php if (isset($processing_fee_min)) echo $processing_fee_min; ?>"
								onchange="checkMinMaxValue('#monthly_processing_fees_min','#monthly_processing_fees_max')">
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group">
								<label for="monthly_disabledInput" id="monthly_procmax">Max</label><span class="required">&nbsp;*</span>
								<input type="number" step="0.01" tabindex="22" id="monthly_processing_fees_max" name="monthly_processing_fee_max" class="form-control" placeholder="Processing Fee Max" value="<?php if (isset($processing_fee_max)) echo $processing_fee_max; ?>"
								onchange="checkMinMaxValue('#monthly_processing_fees_min','#monthly_processing_fees_max')">
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 overdue_penalty">
							<div class="form-group">
								<label style="font-size:1.35em;padding-right:4.5%" for="overdue">
									Overdue Penalty: <span class='text-danger'>*</span>
									<span id="emi_symbol" style="display:none;">&nbsp;%</span>
								</label>

								<!-- Interest: Rupees / Percentage radio buttons -->
								<div class="form-check form-check-inline interest_only" style="margin-top:5px;">
									<input class="form-check-input" type="radio" name="overdue_type" id="overdueamt" value="amt"
										<?php if (isset($overdue_type) and $overdue_type == 'amt') echo 'checked'; ?>>
									<label class="form-check-label" for="overdueamt">&nbsp;<b>₹</b></label>&nbsp;
								</div>
								<div class="form-check form-check-inline interest_only">
									<input class="form-check-input" type="radio" name="overdue_type" id="overduepercentage" value="percentage"
										<?php if (isset($overdue_type) and $overdue_type == 'percentage') echo 'checked'; ?>>
									<label class="form-check-label" for="overduepercentage"><b>%</b></label>
								</div>

								<!-- EMI/Interest: single input field -->
								<input type="number" step="0.01" tabindex="23" id="monthly_overdues" name="monthly_overdues"
									class="form-control emi_only mt-2" placeholder="Enter Overdue"
									value="<?php if (isset($overdue)) echo $overdue; ?>">
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
							<div class="form-group" style="margin-top:25px;"><br>
								<label for="monthly_disabledInput">Advance</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input checked type="radio" tabindex="24" name="monthly_collection_info" id="yes" value="Yes" <?php if (isset($collection_info))
																																	echo ($collection_info == 'yes') ? 'checked' : '' ?>> &nbsp;&nbsp; <label for="yes">Yes </label> &nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" tabindex='25' name="monthly_collection_info" id="no" value="No" <?php if (isset($collection_info))
																														echo ($collection_info == 'No') ? 'checked' : '' ?>> &nbsp;&nbsp; <label for="no">No </label>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12"></div>


					</div>
				</div>
				<!--- ---------------------- Loan scheme  START----------------------------- -->
				<!-- <div class="card" id="loan_scheme_card" style="display: <?php #echo ($emicheck ? 'block' : 'none'); ?>;" >   FOR INTREST LOAN ADDED -->  
				<div class="card" id="loan_scheme_card" >
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title mb-0">Loan Scheme</h5>
						<button type="button" class="btn btn-primary modalBtnCss card-head-btn" data-toggle="modal" data-target="#add_loan_scheme_modal" tabindex="26" onclick="getSchemeTable();"><span class="icon-add"></span></button>
					</div>
					<div class="card-body bdy-cls">
						<div class="row mb-3">
							<!-- Fields -->
							<div class="col-md-3 col-sm-4">
								<div class="form-group">
									<label for="scheme_name">Scheme Name</label><span class="text-danger">*</span>
									<input type="hidden" id="scheme_name2">
									<select class="form-control" id="scheme_name" name="scheme_name[]" tabindex="27" multiple>
										<option value="">Select Scheme Name</option>
									</select>
								</div>
							</div>
						</div>

						<div class="row" style="overflow-x: auto; white-space: nowrap;">
							<div class="col-12">
								<table id="loan_scheme_outer_table" class="table custom-table">
									<thead>
										<tr>
											<th width="50">S. No.</th>
											<th>Scheme Name</th>
											<th>Scheme Short Name</th>
											<th>Due Method</th>
											<th>Profit Type</th>
											<th>Total Due</th>
											<th>Advance Due</th>
											<th>Due Period</th>
											<th>Intrest Type</th>
											<th>Min Intrest</th>
											<th>Max Intrest</th>
											<th>Document charge type</th>
											<th>Min Document Charge</th>
											<th>Max Document Charge</th>
											<th>Processing Fees Type</th>
											<th>Min Processing Fees</th>
											<th>Max Processing Fees</th>
											<th>Over Due</th>
											<th>Status</th>
											<!-- <th>Action</th> -->
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12 ">
					<div class="text-right">
						<button type="submit" name="submitLoanCategory" id="submitLoanCategory" class="btn btn-primary" value="Submit" tabindex="28"><span class="icon-check"></span>&nbsp;Submit</button>
						<button type="reset" class="btn btn-outline-secondary" tabindex="29">Clear</button>
					</div>
				</div>

			</div>

		</div>



		<!-- Add Course Category Modal -->
		<div class="modal fade addloanCategoryModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content" style="background-color: white">
					<div class="modal-header">
						<h5 class="modal-title" id="myLargeModalLabel">Add Loan Category</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="DropDownCourse()">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- alert messages -->
						<div id="categoryInsertNotOk" class="unsuccessalert">Category Already Exists, Please Enter a Different Name!
							<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
						</div>

						<div id="categoryInsertOk" class="successalert">Loan Category Added Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
						</div>

						<div id="categoryUpdateOk" class="successalert">Loan Category Updated Succesfully!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
						</div>

						<div id="categoryDeleteNotOk" class="unsuccessalert">You Don't Have Rights To Delete This Category!
							<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
						</div>

						<div id="categoryDeleteOk" class="successalert">Loan Category Has been Inactivated!<span class="custclosebtn" onclick="this.parentElement.style.display='none';"><span class="icon-squared-cross"></span></span>
						</div>

						<br />
						<div class="row">
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12"></div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
								<div class="form-group">
									<label class="label">Enter Loan Category</label>
									<input type="hidden" name="loan_category_creation_id" id="loan_category_creation_id">
									<input type="text" name="loan_category_creation_name" id="loan_category_creation_name" class="form-control" placeholder="Enter Category">
									<span class="text-danger" id="loancategorynameCheck">Enter Loan Category</span>
								</div>
							</div>
							<div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
								<label class="label" style="visibility: hidden;">Category</label><br>
								<button type="button" name="submitLoanCategoryModal" id="submitLoanCategoryModal" class="btn btn-primary">Submit</button>
							</div>
						</div>

						<div id="updatedloancategoryTable">
							<table class="table custom-table" id="coursecategoryTable">
								<thead>
									<tr>
										<th width="15px">S. No</th>
										<th>LOAN CATEGORY</th>
										<th>ACTION</th>
									</tr>
								</thead>
								<tbody>
									<?php if (sizeof($loanCategoryCreationList) > 0) {
										for ($j = 0; $j < count($loanCategoryCreationList); $j++) { ?>
											<tr>
												<td class="col-md-2 col-xl-2"></td>
												<td><?php echo $loanCategoryCreationList[$j]['loan_category_creation_name']; ?></td>
												<td>
													<a id="edit_category" value="<?php echo $loanCategoryCreationList[$j]['loan_category_creation_id'] ?>"><span class="icon-border_color"></span></a> &nbsp
													<a id="delete_category" value="<?php echo $loanCategoryCreationList[$j]['loan_category_creation_id'] ?>"><span class='icon-trash-2'></span></a>
												</td>
											</tr>
									<?php }
									} ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="DropDownCourse()">Close</button>
					</div>

				</div>
			</div>
		</div>
		<!-- /////////////////////////////////////////////////////////////////// Loan Scheme Modal Start ////////////////////////////////////////////////////////////////////// -->
		<div class="modal fade" id="add_loan_scheme_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-lg " role="document">
				<div class="modal-content" style="background-color: white">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Add Scheme</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getSchemeDropdown()">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<form name="add_scheme_details" id="add_scheme_details">
								<h5 class="card-title">Loan Scheme</h5>
								<div class="row">
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="add_scheme_name">Scheme Name</label><span class="text-danger">*</span>
											<input class="form-control" name="add_scheme_name" id="add_scheme_name" placeholder="Enter Scheme">
											<input type="hidden" id="add_scheme_id" value="0">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Scheme Short Name</label>
											<input type="text" name="scheme_short" id="scheme_short" value="<?php if (isset($scheme_short)) echo $scheme_short; ?>" placeholder="Enter Scheme Short Name" class="form-control">
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="scheme_due_method">Due Method</label><span class="text-danger">*</span>
											<select class="form-control" id="scheme_due_method" name="scheme_due_method">
												<option value="">Select Due Method</option>
												<option value="monthly">Monthly</option>
												<option value="weekly">Weekly</option>
												<option value="daily">Daily</option>
											</select>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 advance_div">
										<div class="form-group"><br>
											<label for="advance">Advance</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input checked type="radio" tabindex="21" name="advance" id="advance_yes" value="Yes"> &nbsp;&nbsp; <label for="yes">Yes </label> &nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" tabindex='22' name="advance" id="advance_no" value="No"> &nbsp;&nbsp; <label for="no">No </label>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 total_due" style="display: none;">
										<div class="form-group">
											<label for="disabledInput">Total Due</label>&nbsp;<span class="text-danger">*</span>
											<input type="number" name="total_due" id="total_due" value="" placeholder="Enter Total Due" class="form-control">
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 advance_due" style="display: none;">
										<div class="form-group">
											<label for="disabledInput">Advance Due</label>
											<input type="number" name="advance_due" id="advance_due" value="" placeholder="Enter Advance Due" class="form-control">
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
										<div class="form-group">
											<label for="disabledInput">Due Period</label>
											<input type="text" name="due_period" id="due_period" readonly value="" placeholder="Enter Total & Advance Due" class="form-control">
										</div>
									</div>

									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="form-group">
											<label for="profit_methods">Profit Method</label><span class="text-danger">*</span>
											<select class="form-control" id="profit_methods" name="profit_methods">
												<option value="">Select Profit Method</option>
												<option value="pre_intrest">Pre Benefit</option>
												<option value="after_intrest">After Benefit</option>
											</select>
										</div>
									</div>
								</div>

								<h5 class="card-title">Condition Info</h5>
								<div class="row ">
									<!--Fields -->
									<div class="col-md-12 ">
										<div class="row">
											<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
												<div class="form-group">
													<label style="font-size:1.35em;padding-right:2%">Interest Rate: <span class="text-danger">*</span></label>
													<input type="radio" name="intreset_type" id="interestamt" value="amt" <?php if (isset($intreset_type) and $intreset_type == 'amt') echo 'checked'; ?>></input><label for='interestamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<input type="radio" name="intreset_type" id="interestpercentage" value="percentage" <?php if (isset($intreset_type) and $intreset_type == 'percentage') echo 'checked'; ?>></input><label for='interestpercentage'>&nbsp;&nbsp;%</label>
												</div>
											</div>
											<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
												<div class="form-group">
													<label for="disabledInput" id="intresetmin">Min</label>&nbsp;<span class="text-danger">*</span>
													<input type="number" step="0.01" id="intreset_min" name="intreset_min" class="form-control" placeholder="Enter Minimum Interest" value="<?php if (isset($intreset_min)) echo $intreset_min; ?>">
												</div>
											</div>
											<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
												<div class="form-group">
													<label for="disabledInput" id="intersetmax">Max</label>&nbsp;<span class="text-danger">*</span>
													<input type="number" step="0.01" id="intreset_max" name="intreset_max" class="form-control" placeholder="Enter Maximum Interest" value="<?php if (isset($intreset_max)) echo $intreset_max; ?>">
												</div>
											</div>
											<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
												<div class="form-group">
													<label style="font-size:1.35em;padding-right:2%">Document Charge: <span class="text-danger">*</span></label>
													<input type="radio" name="doc_charge_type" id="docamt" value="amt" <?php if (isset($doc_charge_type) and $doc_charge_type == 'amt') echo 'checked'; ?>></input><label for='docamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<input type="radio" tabindex="13" name="doc_charge_type" id="docpercentage" value="percentage" <?php if (isset($doc_charge_type) and $doc_charge_type == 'percentage') echo 'checked'; ?>></input><label for='docpercentage'>&nbsp;&nbsp;%</label>
												</div>
											</div>
											<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
												<div class="form-group">
													<label for="disabledInput" id="docmin">Min</label>&nbsp;<span class="text-danger">*</span>
													<input type="number" step="0.01" id="doc_charge_min" name="doc_charge_min" class="form-control" placeholder="Enter Document Charge Min" value="<?php if (isset($doc_charge_min)) echo $doc_charge_min; ?>">
												</div>
											</div>
											<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
												<div class="form-group">
													<label for="disabledInput" id="docmax">Max</label>&nbsp;<span class="text-danger">*</span>
													<input type="number" step="0.01" id="doc_charge_max" name="doc_charge_max" class="form-control" placeholder="Enter Document Charge Max" value="<?php if (isset($doc_charge_max)) echo $doc_charge_max; ?>">
												</div>
											</div>
											<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
												<div class="form-group">
													<label style="font-size:1.35em;padding-right:2%">Processing Fee: <span class="text-danger">*</span></label>
													<input type="radio" name="proc_fee_type" id="procamt" value="amt"></input><label for='procamt'>&nbsp;&nbsp;<b>₹</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<input type="radio" name="proc_fee_type" id="procpercentage" value="percentage"></input><label for='procpercentage'>&nbsp;&nbsp;%</label>
												</div>
											</div>
											<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
												<div class="form-group">
													<label for="disabledInput" id="procmin">Min</label>&nbsp;<span class="text-danger">*</span>
													<input type="number" step="0.01" id="proc_fee_min" name="proc_fee_min" class="form-control" placeholder="Enter Processing Fee Min" value="<?php if (isset($proc_fee_min)) echo $proc_fee_min; ?>">
												</div>
											</div>
											<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
												<div class="form-group">
													<label for="disabledInput" id="procmax">Max</label>&nbsp;<span class="text-danger">*</span>
													<input type="number" step="0.01" id="proc_fee_max" name="proc_fee_max" class="form-control" placeholder="Enter Processing Fee Max" value="<?php if (isset($proc_fee_max)) echo $proc_fee_max; ?>">
												</div>
											</div>
											<br><br><br><br><br><br><br><br>
											<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
												<div class="form-group">
													<label for="disabledInput">Overdue Penalty %</label><span class='text-danger' style="font-size:11px">&nbsp;*</span>
													<input type="number" id="overdue" name="overdue" class="form-control" placeholder="Enter Overdue" value="<?php if (isset($overdue)) echo $overdue; ?>" title="Penalty if Exceeded Due Date">
												</div>
											</div>
											<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12"></div>

										</div>
									</div>
								</div>
								<div class="col-md-12 ">
									<div class="text-right">
										<button type="submit" name="submit_loan_scheme" id="submit_loan_scheme" class="btn btn-primary" value="Submit"><span class="icon-check"></span>&nbsp;Submit</button>
									</div>
								</div>
								</br>
							</form>
						</div>

						<div class="row" style="overflow-x: auto; white-space: nowrap;">
							<div class="col-12">
								<table id="loan_scheme_inner_table" class="table custom-table">
									<thead>
										<tr>
											<th width="50">S. No.</th>
											<th>Scheme Name</th>
											<th>Scheme Short Name</th>
											<th>Due Method</th>
											<th>Profit Type</th>
											<th>Total Due</th>
											<th>Advance Due</th>
											<th>Due Period</th>
											<th>Intrest Type</th>
											<th>Min Intrest</th>
											<th>Max Intrest</th>
											<th>Document charge type</th>
											<th>Min Document Charge</th>
											<th>Max Document Charge</th>
											<th>Processing Fees Type</th>
											<th>Min Processing Fees</th>
											<th>Max Processing Fees</th>
											<th>Over Due</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" data-dismiss="modal" onclick="getSchemeDropdown()">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /////////////////////////////////////////////////////////////////// Loan Scheme Modal END ////////////////////////////////////////////////////////////////////// -->
	</form>
</div>