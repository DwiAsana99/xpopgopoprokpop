<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#usulan_table").DataTable({
	    	"processing": true,
			"stateSave": true,
        	"serverSide": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo $url_load_data; ?>",
	            "type": "POST",
	            "data" : {"is_hibah" : "<?php echo $is_hibah_ng; ?>", "cb_jenis" : $('#cb_jenis').val()}
	        }
	    });

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		});

		$(document).on('change', '#cb_jenis', function(){
			window.location = "<?php echo base_url('usulanbansos/evaluasiusulanbansos').'/'.$status_ng; ?>/" + $(this).val();
		});
	});

	function edit_usulan_table(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
	}

	function preview_modal(location){
		// alert(location);
		if (location) {
			window.open(location);
		}else{
			alert('Dokumen tidak ada...');
		}
	}

</script>
<article class="module width_full">
	<header>
	  <h3 style="text-transform: capitalize;">Tabel Data Evaluasi Usulan <?php echo $status_ng; ?> </h3>
	</header>
	<div style='width: 80%; float:right;'>
		&nbsp;
    </div>
	<div style="width: 19%; float:right;">
		<?php echo $cb_jenis; ?>
	</div>
	<div class="module_content"; style="overflow:auto">
		<table id="usulan_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th>Group</th>
                    <th>Nama Dewan</th>
					<th>Kecamatan</th>
					<th>Desa</th>
					<th>Jenis Pekerjaan</th>
					<th>Volume</th>
					<th>Nilai Usulan (Rp)</th>
					<th>Nominal Rekomendasi (Rp)</th>
					<th>Lokasi</th>
					<th>Nama Pengusul</th>
					<th>Catatan</th>
					<th class="no-sort">Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</article>
