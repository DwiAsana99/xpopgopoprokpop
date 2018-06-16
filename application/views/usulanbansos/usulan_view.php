<script type="text/javascript">
	var dt;
	$.fn.dataTable.Api.register('sum()', function () {
	    return this.flatten().reduce( function (a, b) {
	      if (typeof a === 'string') {
	        a = a.replace(/[^\d.-]/g, '') * 1;
	      }
	      if (typeof b === 'string') {
	        b = b.replace(/[^\d.-]/g, '') * 1;
	      }
	      return a + b;
	    }, 0);
	});
	$(document).ready(function(){
		prepare_chosen();
	    dt = $("#usulan_table").DataTable({
	    	"processing": true,
			"stateSave": true,
        	"serverSide": true,
        	"aoColumnDefs": [
        		{
        			"aTargets": [ 8 ],
        			"render": $.fn.dataTable.render.number( '.', ',', 2),
        			"class" : "text-right"
        		},
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo $url_load_data; ?>",
	            "type": "POST",
	            "data" : {"is_hibah" : "<?php echo $is_hibah_ng; ?>", "cb_jenis" : $('#cb_jenis').val()}
	        },
			drawCallback: function () {
				var api = this.api();
				$(api.column(8).footer()).html(
				  $.fn.dataTable.render.number( '.', ',', 2).display(api.column(8).data().sum())
				);
			}
	    });

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		});


		$(document).on('change', '#cb_jenis', function(){
			window.location = "<?php echo base_url('usulanbansos/usulanhibahbansos').'/'.$status_ng; ?>/" + $(this).val();
		});
	});

	function edit_usulan_table(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
	}

	function delete_usulan_table(id){

		if (confirm('Apakah anda yakin untuk menghapus data usulan ini?')) {
			$.blockUI({
				message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			$.ajax({
				type: "POST",
				url: '<?php echo $url_delete_data; ?>',
				data: {id: id},
				dataType: "json",
				success: function(msg){
					catch_expired_session2(msg);
					if (msg.success==1) {
						dt.draw();
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});


					};
				}
			});
		};
	}

	function cetak_data_usulan() {
		var link = "<?php echo site_url('usulanbansos/cetak_data_usulan'); ?>/"+"<?php echo $is_hibah_ng; ?>/"+$('#cb_jenis').val();
		// alert(link);
		window.open(link);
		// window.location = link;
	}
</script>
<article class="module width_full">
	<header>
	  <h3 style="text-transform: capitalize;">Tabel Data Usulan <?php echo $status_ng; ?></h3>
	</header>
	<div class="module_content"; style="overflow:auto;">
	    <div style='width: 80%; float:right;'>
	        <a href="<?php echo $url_add_data ?>"><input style="margin: 3px 10px 0px 0px; float: right;" type="button" value="Tambah Data Usulan" /></a>
	        <a href="javascript:void();" onclick="cetak_data_usulan();"><input style="margin: 3px 10px 0px 0px; float: right;" type="button" value="Cetak Data Usulan" /></a>
	    </div>
		<div style="width: 20%; float:right;">
			<?php echo $cb_jenis; ?>
		</div>
    <div style="padding-top: 50px;"></div>
		<table id="usulan_table" class="table-common tablesorter" style="width:100%; padding-top: 30px;">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th>Group</th>
					<th>Jenis Hibah</th>
					<th>SKPD Evaluator</th>
					<th>Kecamatan</th>
					<th>Desa</th>
					<th>Jenis Pekerjaan</th>
					<th>Volume</th>
					<th>Nilai Usulan (Rp)</th>
					<th>Lokasi</th>
					<th>Catatan</th>
					<th class="no-sort">Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="8" align="center">TOTAL</th>
					<th class="text-right"></th>
					<th colspan="3"></th>
				</tr>
			</tfoot>
		</table>
	</div>
</article>
