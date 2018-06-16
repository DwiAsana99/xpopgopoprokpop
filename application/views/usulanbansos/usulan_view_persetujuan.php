<script type="text/javascript">
	var dt;
	$(document).ready(function(){
		//$('#det_uraian').autoNumeric('set',value);
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
		} );

	$("#simpan").click(function(){
		    var valid = $("form#persetujuanusulanbansos").valid();

				var idfile;
				var iduserfile;
				try {
					idfile=document.getElementById('userfile').value
					iduserfile=document.getElementById('id_file').value
				//				alert(iduserfile)
				} catch (e) {
					idfile='';
					iduserfile='';

				} finally {

				}
				if(idfile != '' || iduserfile !='' ){
					if (valid) {
				    	//$("#det_uraian").val($("#det_uraian").autoNumeric('get'));

				    	$("form#persetujuanusulanbansos").submit();
				    };
				}else{
					alert('Persetujuan Hibah/Bansos harus disertai dokumen pendukung!')
				}

	});

		$(document).on('change', '#cb_jenis', function(){
			window.location = "<?php echo base_url('usulanbansos/persetujuanusulanbansos').'/'.$status_ng; ?>/" + $(this).val();
		});

	});
	$(document).on("click", "#cetak", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("usulanbansos/cetakpersetujuanusulanbansos").'/'.$is_hibah_ng.'/'.$id_jenishibah_ng; ?>';
			$(location).attr('href',link);
		});

	function edit_usulan_table(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
	}

	function kup(nama){
		var idnama = nama.name;
		$(nama).autoNumeric(numOptions);
		// var x = document.getElementById("det_uraian");
   // alert('jos');
    	//x.autoNumeric(numOptions);
	}
	function preview_modal(location, file) {
		if (file) {
			window.open(location);
		}else{
			alert('Dokumen tidak ada...');
		}
	}
</script>
<article class="module width_full">
	<header>
	  <h3 style="text-transform: capitalize;">Tabel Data Persetujuan Usulan <?php echo $status_ng; ?> </h3>
	</header>
	<div style='width: 80%; float:right;'>
		&nbsp;
    </div>
	<div style="width: 19%; float:right;">
		<?php echo $cb_jenis; ?>
	</div>

	<div class="module_content"; style="overflow:auto">
   <form action="<?php echo site_url('usulanbansos/savepersetujuan').'/'.$status_ng;?>" method="POST" name="persetujuanusulanbansos" id="persetujuanusulanbansos" accept-charset="UTF-8" enctype="multipart/form-data" >
<div class="scroll">
		<table id="usulan_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th>Pengusul</th>
					<th>Jenis Pekerjaan</th>
					<th>Nilai Usulan (Rp)</th>
					<th>SKPD</th>
					<th class="no-sort" style="min-width: 153px;">Status RKPD</th>
					<th class="no-sort" style="min-width: 205px;">Nominal Anggaran</th>
					<th class="no-sort">Nominal Rekomendasi (Rp.)</th>
					<th class="no-sort">No. rekomendasi</th>
					<th class="no-sort">Tgl. rekomendasi</th>
					<th class="no-sort">File</th>


				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
</div>
	<table class="fcari" width="50%">
		<tbody>
			<tr >
        	<td>Keterangan</td>
        	</tr>
        	<tr>
        	<td>
        		<input type = "text" class="commmon" name="keterangan" id="keterangan" value="<?php if(!empty($keterangan_rapat)){echo $keterangan_rapat;} ?>">
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>

			<tr>
				<td>
                   <?php
        		    include_once("file_upload.php");
         			?>
	            </td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</tbody>
    </table>
    <input type='button' id="simpan" name="simpan" value='Simpan' />
		<input type="button" class="button-action" id="cetak" value="Cetak" />

	</div>
</article>

</form>
