<script type="text/javascript">
	var element_bidang;
	$(document).ready(function() {

		$(".tbh_bidang").click(function(){
			var idur = $(this).attr("id-ur");
			close_all();
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/cru_bidang"); ?>',
				data: {idur : idur},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".edit_bidang").click(function(){
			var idur = $(this).parent().parent().attr("id-ur");
			var idbi = $(this).attr("id-bi");
			close_all();
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/cru_bidang"); ?>',
				data: {idur : idur, idbi : idbi},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".delete_bidang").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data bidang ini?')) {
				$.blockUI({
					message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
					css: window._css,
					overlayCSS: window._ovcss
				});

				element_urusan.parent().next().hide();
				var idbi = $(this).attr("id-bi");
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("data/delete_bidang"); ?>',
					data: {idbi : idbi},
					success: function(msg){
						if (msg!="") {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							element_urusan.trigger('click');
						}
					}
				});
			}
		});

		
		$("#bidang td.td-click").click(function(){
			// $("#program-frame").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			element_bidang = $(this).parent();
			var idur = $(this).parent().attr("id-ur");
			var idbi = $(this).parent().attr("id-bi");
			var this_element = $(this);
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/view_program"); ?>',
				data: {idur : idur, idbi : idbi},
				success: function(msg){
					if (msg!="") {
						$("#program-frame").html(msg);
						element_bidang = this_element;
						$.blockUI({
							timeout: 1000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});

	});
</script>

<table id="bidang" class="table-common" style="width: 100%">
	<tr>
		<th colspan="4">
			Data Bidang
			<a href="javascript:void(0)" id-ur="<?php echo $id_urusan; ?>" class="icon-plus-sign tbh_bidang" style="float: right" title="Tambah Bidang"></a>
		</th>
	</tr>
	<tr>
		<th width="40px">Kode</th>
		<th>Nama Bidang</th>
		<th>Nama Fungsi</th>
		<th width="70px">Action</th>
	</tr>
	<?php if (!empty($bidang)): ?>
		<?php foreach ($bidang as $key => $value): ?>
			<tr class="tr-click" id-bi="<?php echo $value->id; ?>" id-ur="<?php echo $id_urusan; ?>">
				<td class="td-click"><?php echo $id_urusan.'. '.$value->Kd_Bidang; ?></td>
				<td class="td-click"><?php echo $value->Nm_Bidang; ?></td>
				<td class="td-click"><?php echo $value->Kd_Fungsi.'. '.$value->Nm_Fungsi; ?></td>
				<td align="center">
					<a href="javascript:void(0)" id-bi="<?php echo $value->id; ?>" class="icon-pencil edit_bidang" title="Edit Bidang"/>
					<a href="javascript:void(0)" id-bi="<?php echo $value->id; ?>" class="icon-remove delete_bidang" title="Hapus Bidang"/>
				</td>
			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr>
			<td colspan="3" align="center"><strong>Tidak Ada Data</strong></td>
		</tr>
	<?php endif ?>
</table>