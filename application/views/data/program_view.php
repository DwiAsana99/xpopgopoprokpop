<script type="text/javascript">
	var element_program;
	$(document).ready(function(){

		$(".tbh_program").click(function(){
			var idur = $(this).attr("id-ur");
			var idbi = $(this).attr("id-bi");
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/cru_program"); ?>',
				data: {idur : idur, idbi : idbi},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});


		$(".edit_program").click(function(){
			var idur = $(this).parent().parent().attr("id-ur");
			var idbi = $(this).parent().parent().attr("id-bi");
			var idpr = $(this).attr("id-pr");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/cru_program"); ?>',
				data: {idur : idur, idbi : idbi, idpr : idpr},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".delete_program").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data program ini?')) {
		    	close_all();
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

				var idpr = $(this).attr("id-pr");
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("data/delete_program"); ?>',
					data: {idpr : idpr},
					success: function(msg){
						if (msg!="") {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							element_bidang.trigger('click');
						}
					}
				});
			}
		});

		
		$("#program td.td-click").click(function(){
			if($(this).parent().next().is(":visible")){
				$(this).parent().next().fadeOut();
				return false;
			};

			$("tr.tr-frame-kegiatan").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			element_program = $(this).parent();
			var idur = $(this).parent().attr("id-ur");
			var idbi = $(this).parent().attr("id-bi");
			var idpr = $(this).parent().attr("id-pr");
			var this_element = $(this);
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/view_kegiatan"); ?>',
				data: {idur : idur, idbi : idbi, idpr : idpr},
				success: function(msg){
					if (msg!="") {
						element_program.next().children().html(msg);
						element_program.next().fadeIn();
						element_program = this_element;
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
<article class="module width_full">
	<header>
	  <h3>Setting Master Data Program & Kegiatan</h3>
	</header>
	<div class="module_content">
		<table id="program" class="table-common" style="width: 100%">
			<tr>
				<th colspan="3">
					Data Program
					<a href="javascript:void(0)" id-ur="<?php echo $id_urusan; ?>" id-bi="<?php echo $id_bidang; ?>" class="icon-plus-sign tbh_program" style="float: right" title="Tambah Program"></a>
				</th>
			</tr>
			<tr>
				<th width="60px">Kode</th>
				<th>Nama Program</th>
				<th width="70px">Action</th>
			</tr>
			<?php if (!empty($program)): ?>
				<?php foreach ($program as $key => $value): ?>
					<tr class="tr-click" id-pr="<?php echo $value->id_prog; ?>" id-bi="<?php echo $id_bidang; ?>" id-ur="<?php echo $id_urusan; ?>">
						<td class="td-click"><?php echo $id_urusan.'. '.$kd_bidang.'. '.$value->Kd_Prog; ?></td>
						<td class="td-click"><?php echo $value->Ket_Program; ?></td>
						<td align="center">
							<a href="javascript:void(0)" id-pr="<?php echo $value->id_prog; ?>" class="icon-pencil edit_program" title="Edit Program"/>
							<a href="javascript:void(0)" id-pr="<?php echo $value->id_prog; ?>" class="icon-remove delete_program" title="Hapus Program"/>
						</td>
					</tr>
					<tr class="tr-frame-kegiatan" style="display: none">
						<td colspan="3"></td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan="3" align="center"><strong>Tidak Ada Data</strong></td>
				</tr>
			<?php endif ?>
		</table>
	</div>
</article>