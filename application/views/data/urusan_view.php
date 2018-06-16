<style type="text/css">
	.table-common, .table-display{
			border: 2px solid #000;
	}

	.table-common>thead>tr>th, .table-common>tbody>tr>th, .table-common>tfoot>tr>th, .table-common>thead>tr>td, .table-common>tbody>tr>td, .table-common>tfoot>tr>td ,
	.table-display>thead>tr>th, .table-display>tbody>tr>th, .table-display>tfoot>tr>th, .table-display>thead>tr>td, .table-display>tbody>tr>td, .table-display>tfoot>tr>td
	{
			border: 1px solid #000 !important;
	}
	tr.tr-click:hover{
		background-color: pink;
	}
	td.td-click{
		cursor: pointer;
	}
</style>

<script type="text/javascript">
	var element_urusan;
	$(document).ready(function(){

	    $(".tbh_urusan").click(function(){
			close_all();
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/cru_urusan"); ?>',
				data: {},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".edit_urusan").click(function(){
			var idur = $(this).attr("id-ur");
			close_all();
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/cru_urusan"); ?>',
				data: {idur : idur},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".delete_urusan").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data urusan ini?')) {
				$.blockUI({
					message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
					css: window._css,
					overlayCSS: window._ovcss
				});

				var idur = $(this).attr("id-ur");
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("data/delete_urusan"); ?>',
					data: {idur : idur},
					success: function(msg){
						if (msg!="") {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							location.reload();
						}
					}
				});
			}
		});

		$("#urusan td.td-click").click(function(){
			close_all();
			if($(this).parent().next().is(":visible")){
				$(this).parent().next().fadeOut();
				return false;
			};

			$("tr.tr-frame-bidang").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			element_urusan = $(this).parent();
			var idur = $(this).parent().attr("id-ur");
			var this_element = $(this);
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/view_bidang"); ?>',
				data: {idur : idur},
				success: function(msg){
					if (msg!="") {
						element_urusan.next().children().html(msg);
						element_urusan.next().fadeIn();
						element_urusan = this_element;
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

	function close_all(){
		$("#program-frame article").remove();
	}
</script>
<article class="module width_full">
 	<header>
 		<table style="border: 0px !important; margin: 0px !important;">
 			<tr>
 				<td style="border: 0px !important">
 					<h3>
						Setting Master Data Urusan & Bidang
					</h3>
 				</td>
 			</tr>
 		</table>
 	</header>
 	<div class="module_content">
		<table id="urusan" class="table-common" style="width: 100%">
			<tr>
				<th colspan="3">
					Data Urusan
					<a href="javascript:void(0)" class="icon-plus-sign tbh_urusan" style="float: right" title="Tambah Urusan"></a>
				</th>
			</tr>
			<tr>
				<th width="60px">Kode</th>
				<th>Nama Urusan</th>
				<th width="70px">Action</th>
			</tr>
			<?php if (!empty($urusan)): ?>
				<?php foreach ($urusan as $key => $value): ?>
					<tr class="tr-click" id-ur="<?php echo $value->id; ?>">
						<td class="td-click"><?php echo $value->id.'.'; ?></td>
						<td class="td-click"><?php echo $value->nama; ?></td>
						<td align="center">
							<a href="javascript:void(0)" id-ur="<?php echo $value->id; ?>" class="icon-pencil edit_urusan" title="Edit Urusan"/>
							<a href="javascript:void(0)" id-ur="<?php echo $value->id; ?>" class="icon-remove delete_urusan" title="Hapus Urusan"/>
						</td>
					</tr>
					<tr class="tr-frame-bidang" style="display: none">
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

<div id="program-frame">
</div>
