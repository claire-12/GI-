	<div class="container-quotations">
		<h2 class="title">My Quotations</h2>
		<?php if (!empty($data)) : ?>
		<div class="quotations-wrap">
			<div class="table-wrap">
				<table class="sample-data">
					<thead>
						<tr>
							<th>Quote Number</th>
							<th>Date</th>
							<th>Detail</th>
							<th>Total</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $datum) : ?>
						<tr>
							<td class="q_number">
								#<?php echo $datum->id ?? '*' ?>
							</td>
							<td><?php echo date("d/m/Y", strtotime($datum->date)) ?? '' ?></td>
							<td>4900-90 Nitrile Seals x300</td>
							<td>$26.99 (inc VAT)</td>
							<td>
								<button class="btn-checkout"><i class="fa-light fa-shopping-cart me-2"></i> Add To Card</button>
							</td>
							<td>
								<a href="#" data-bs-toggle="modal" data-bs-target="#quote-<?php echo $datum->id ?>" type="button"
									class="more-detail">More Detail <i class="fa-solid fa-caret-right"></i></a>

								<!-- Modal -->
								<div class="modal  modal-lg fade" id="quote-<?php echo $datum->id ?>" tabindex="-1"
									aria-labelledby="quote-<?php echo $datum->id ?>Label" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="quote-<?php echo $datum->id ?>Label">Quote Detail</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<ul class="list-group list-group-horizontal">
													<div class="row w-100 px-3">
														<label class="col-4 text-start">Email</label>
														<div class="col-8 text-start"><?php echo $datum->email ?? '' ?></div>
														<label class="col-4 text-start">Name</label>
														<div class="col-8 text-start"><?php echo $datum->name ?? '' ?></div>
														<label class="col-4 text-start">Company</label>
														<div class="col-8 text-start"><?php echo $datum->company ?? '' ?></div>
														<label class="col-4 text-start">Company Sector</label>
														<div class="col-8 text-start"><?php echo $datum->company_sector ?? '' ?></div>
														<label class="col-4 text-start">Company Address</label>
														<div class="col-8 text-start"><?php echo $datum->company_address ?? '' ?></div>
														<label class="col-4 text-start">Cadditional information</label>
														<div class="col-8 text-start"><?php echo $datum->additional_information ?? '' ?></div>
														<label class="col-4 text-start">object ID</label>
														<div class="col-8 text-start"><?php echo $datum->object_id ?? '' ?></div>
														<label class="col-4 text-start">Company Sector</label>
														<div class="col-8 text-start"><?php echo $datum->company_sector ?? '' ?></div>
														<label class="col-4 text-start">status</label>
														<div class="col-8 text-start"><?php echo $datum->status ?? '' ?></div>
														<label class="col-4 text-start">Product Of Interest</label>
														<div class="col-8 text-start"><?php echo $datum->product_of_interest ?? '' ?></div>
														<label class="col-4 text-start">When Needed</label>
														<div class="col-8 text-start"><?php echo $datum->when_needed ?? '' ?></div>
														<label class="col-4 text-start">Volume</label>
														<div class="col-8 text-start"><?php echo $datum->volume ?? '' ?></div>
														<label class="col-4 text-start">Dimension</label>
														<div class="col-8 text-start"><?php echo $datum->dimension ?? '' ?></div>
														<label class="col-4 text-start">Part Number</label>
														<div class="col-8 text-start"><?php echo $datum->part_number  ?? '' ?></div>
														<label class="col-4 text-start">Country Of Origin</label>
														<div class="col-8 text-start"><?php echo $datum->country_of_origin  ?? '' ?></div>
														<label class="col-4 text-start">Current Suppliers</label>
														<div class="col-8 text-start"><?php echo $datum->current_suppliers ?? '' ?></div>
														<label class="col-4 text-start">Quote Number</label>
														<div class="col-8 text-start"><?php echo $datum->quote_number  ?? '' ?></div>
														<label class="col-4 text-start">Quote Price</label>
														<div class="col-8 text-start"><?php echo $datum->quote_price  ?? '' ?></div>
														<label class="col-4 text-start">Date</label>
														<div class="col-8 text-start"><?php echo $datum->date  ?? '' ?></div>
													</div>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php endif ?>
	</div>

	<!-- DataTables -->
	<script type="text/javascript">
(function($) {
	$(document).ready(function() {
		$('.sample-data').DataTable({
			paging: true,
			pageLength: 5,
			layout: {
				topEnd: {
					search: {
						placeholder: ''
					}
				},
				bottomStart: {
					info: {
						text: '_START_ to _END_ of _TOTAL_ '
					}
				}
			},
			"columnDefs": [{
				"orderable": false,
				"targets": [4, 5]
			}]
		});
	});
})(jQuery);
	</script>