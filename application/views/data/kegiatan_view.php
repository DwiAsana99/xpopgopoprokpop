<script type="text/javascript">
	var element_kegiatan;
	$(document).ready(function(){

	    $(".tbh_kegiatan").click(function(){
			var idur = $(this).attr("id-ur");
			var idbi = $(this).attr("id-bi");
			var idpr = $(this).attr("id-pr");
			
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/cru_kegiatan"); ?>',
				data: {idur : idur, idbi : idbi, idpr : idpr},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".edit_kegiatan").click(function(){
			var idur = $(this).parent().parent().attr("id-ur");
			var idbi = $(this).parent().parent().attr("id-bi");
			var idpr = $(this).parent().parent().attr("id-pr");
			var idkg = $(this).attr("id-kg");
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/cru_kegiatan"); ?>',
				data: {idur : idur, idbi : idbi, idpr : idpr, idkg : idkg},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".delete_kegiatan").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data kegiatan ini?')) {
				$.blockUI({
					message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
					css: window._css,
					overlayCSS: window._ovcss
				});

				element_program.parent().next().hide();
				var idkg = $(this).attr("id-kg");
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("data/delete_kegiatan"); ?>',
					data: {idkg : idkg},
					success: function(msg){
						if (msg!="") {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							element_program.trigger('click');
						}
					}
				});
			}
		});


	});
</script>
<table id="kegiatan" class="table-common" style="width: 100%">
	<tr>
		<th colspan="3">
			Data Kegiatan
			<a href="javascript:void(0)" id-ur="<?php echo $id_urusan; ?>" id-bi="<?php echo $id_bidang; ?>" id-pr="<?php echo $id_prog; ?>" class="icon-plus-sign tbh_kegiatan" style="float: right" title="Tambah Bidang"></a>
		</th>
	</tr>
	<tr>
		<th width="40px">Kode</th>
		<th>Nama Kegiatan</th>
		<th width="70px">Action</th>
	</tr>
	<?php if (!empty($kegiatan)): ?>
		<?php foreach ($kegiatan as $key => $value): ?>
			<tr id-kg="<?php echo $value->id; ?>" id-pr="<?php echo $id_prog; ?>" id-bi="<?php echo $id_bidang; ?>" id-ur="<?php echo $id_urusan; ?>">
				<td><?php echo $id_urusan.'. '.$value->Kd_Bidang.'. '.$value->Kd_Prog.'. '.$value->Kd_Keg; ?></td>
				<td><?php echo $value->Ket_Kegiatan; ?></td>
				<td align="center">
					<a href="javascript:void(0)" id-kg="<?php echo $value->id; ?>" class="icon-pencil edit_kegiatan" title="Edit Kegiatan"/>
					<a href="javascript:void(0)" id-kg="<?php echo $value->id; ?>" class="icon-remove delete_kegiatan" title="Hapus Kegiatan"/>
				</td>
			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr>
			<td colspan="3" align="center"><strong>Tidak Ada Data</strong></td>
		</tr>
	<?php endif ?>
</table>