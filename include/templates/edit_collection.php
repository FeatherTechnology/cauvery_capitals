<!-- Page header start -->
<br><br>
<div class="page-header">
	<div style="background-color:#0c70ab; width:100%; padding:12px; color: #ffff; font-size: 20px; border-radius:5px;">
		Cauvery Capitals - Collection
	</div>
</div><br>
<!-- Page header end -->

<!-- Main container start -->
<div class="main-container">
	<!-- Row start -->
	<div class="card" style="height:110px;" id="dull_nill_div">
		<div class="row">
		<div class="col-12 sub_status" style="display: flex; align-items: center; justify-content: center; margin-top:13px">
			<h4>Customer Status</h4>
		</div>
		<div class="col-12 sub_status" style="display: flex; align-items: center; justify-content: center; margin-top: 13px;">
			<button type="button" class="btn btn-primary" id="due_nill_btn" value="Due Nil">Due Nill</button>
			<button type="button" class="btn btn-primary" id="all_btn" value="All" style="display: none;">All</button>
			</div>
		</div>
		<br><br>
	</div>
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="table-container">

				<div class="table-responsive">
					<?php
					$mscid = 0;
					$id = 0;
					if (isset($_GET['msc'])) {
						$mscid = $_GET['msc'];
						$id = $_GET['id'];
						if ($mscid == 1 and $id != '') { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Collection Submitted Successfully! </div>
								<!-- To show print page and assign id value as collection id from collection.php -->
								<input type="hidden" id='id' name='id' value=<?php echo $id; ?>>
							</div>
						<?php
						}
						if ($mscid == 2) { ?>
							<div class="alert alert-success" role="alert">
								<div class="alert-text"> Collection Removed Successfully! </div>
							</div>
						<?php
						}
					} else { //for print page not to show define id as 0
						?>
						<input type="hidden" id='id' name='id' value=<?php echo $id; ?>>
					<?php
					}
					?>
					<input type="hidden" id='duenill_id' name='duenill_id' value=<?php if(isset($_GET['duestatus'])){echo $_GET['duestatus'];}?>>

					<table id="collection_table" class="table custom-table">
						<thead>
							<tr>
								<th width="50">S.No.</th>
								<th>Customer ID</th>
								<th>Customer Name</th>
								<th>Area</th>
								<th>Sub Area</th>
								<th>Branch</th>
								<th>Line</th>
								<th>Mobile</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Row end -->
</div>
<!-- Main container end -->
<div id="printcollection" style="display: none"></div>


<script>
	var id = $('#id').val();
	if (id != 0) {
		setTimeout(() => {
			Swal.fire({
				title: 'Print',
				text: 'Do you want to print this collection?',
				imageUrl: 'img/printer.png',
				imageWidth: 300,
				imageHeight: 210,
				imageAlt: 'Custom image',
				showCancelButton: true,
				confirmButtonColor: '#0c70ab',
				cancelButtonColor: '#d33',
				cancelButtonText: 'No',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: 'collectionFile/print_collection.php',
						data: {
							'coll_id': id
						},
						type: 'post',
						cache: false,
						success: function(html) {
							$('#printcollection').html(html)
							// Get the content of the div element
							var content = $("#printcollection").html();

							// Create a new window
							var w = window.open();

							// Write the content to the new window
							$(w.document.body).html(content);

							// Print the new window
							w.print();

							// Close the new window
							w.close();
						}
					})
				}
			})
		}, 2000)
	}
</script>