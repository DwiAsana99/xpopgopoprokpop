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
		//$('#det_uraian').autoNumeric('set',value);
	    dt = $("#usulan_table").DataTable({
	    	"processing": true,
			"stateSave": true,
        	"serverSide": true,
        	"paging": false,
        	"info":false,
        	"aoColumnDefs": [
        		{
        			"aTargets": [ 3, 6 ],
        			"render": $.fn.dataTable.render.number( '.', ',', 2),
        			"class" : "text-right"
        		},
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                },
                { className: "text-right", "targets": [ 3 ] }
            ],
            "ajax": {
	            "url": "<?php echo $url_load_data; ?>",
	            "type": "POST"
	        },
			drawCallback: function () {
				var api = this.api();
				$(api.column(3).footer()).html(
				  $.fn.dataTable.render.number( '.', ',', 2).display(api.column(3).data().sum())
				);
				$(api.column(6).footer()).html(
				  $.fn.dataTable.render.number( '.', ',', 2).display(api.column(6).data().sum())
				);
			}
	    });

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		} );

	});

	$(document).on("click", "#cetak", function(){
		$.blockUI({
			message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
			css: window._css,
			timeout: 2000,
			overlayCSS: window._ovcss
		});
		var link = '<?php echo site_url("usulanbansos/cetakpersetujuanusulanbansos"); ?>';
		$(location).attr('href',link);
	});

	$(document).on("change", "#cb_kecamatan", function(){
		$.ajax({
			type    : "POST",
			url     : "<?php echo site_url('common/cmb_desa2'); ?>",
			data    : {id_kec : $(this).val()},
			success : function(msg) {
				$("#cb_desa").html(msg);
				$("#cb_desa").trigger("chosen:updated");
				prepare_chosen();
			} 
		});

		dt.ajax.url("<?php echo site_url('usulanbansos/load_data_persetujuan_rekap').'/'.$is_hibah; ?>/"+$('#cb_kecamatan').val()+'/'+$('#cb_desa').val()+'/'+$('#select1').val()+'/'+$('#select2').val()).load();
	});

	$(document).on("change", "#cb_desa", function(){
		dt.ajax.url("<?php echo site_url('usulanbansos/load_data_persetujuan_rekap').'/'.$is_hibah; ?>/"+$('#cb_kecamatan').val()+'/'+$('#cb_desa').val()+'/'+$('#select1').val()+'/'+$('#select2').val()).load();
	});

	$(document).on("change", "#select1", function(){
		dt.ajax.url("<?php echo site_url('usulanbansos/load_data_persetujuan_rekap').'/'.$is_hibah; ?>/"+$('#cb_kecamatan').val()+'/'+$('#cb_desa').val()+'/'+$('#select1').val()+'/'+$('#select2').val()).load();
	});

	$(document).on("change", "#select2", function(){
		dt.ajax.url("<?php echo site_url('usulanbansos/load_data_persetujuan_rekap').'/'.$is_hibah; ?>/"+$('#cb_kecamatan').val()+'/'+$('#cb_desa').val()+'/'+$('#select1').val()+'/'+$('#select2').val()).load();
	});

	function kup(nama){
		var idnama = nama.name;
		$(nama).autoNumeric(numOptions);
		// var x = document.getElementById("det_uraian");
   // alert('jos');
    	//x.autoNumeric(numOptions);
	}

	function do_cetak() {
		var link = "<?php echo site_url('usulanbansos/cetak_data_persetujuan_rekap').'/'.$is_hibah; ?>/"+$('#cb_kecamatan').val()+'/'+$('#cb_desa').val()+'/'+$('#select1').val()+'/'+$('#select2').val();
		window.open(link);
	}
</script>
<article class="module width_full">
	<header>
	  <h3>Rekap Data Persetujuan Usulan <?php echo $jenis_us; ?> </h3>
	</header>

	<div class="module_content"; style="overflow:auto; min-height: 350px;">
		<div class="col-md-2">
			<?php echo $cb_kecamatan; ?>
		</div>
		<div class="col-md-3">
			<?php echo $cb_desa; ?>
		</div>
		<div class="col-md-2">
			<select id="select1" class="chosen-select">
				<option value="all">Semua Pilihan</option>
				<option value="1">Renja Induk</option>
				<option value="2">Renja Perubahan</option>
			</select>
		</div>
		<div class="col-md-2">
			<select id="select2" class="chosen-select">
				<option value="all">Semua</option>
				<option value="1">Ada Rekomendasi</option>
				<option value="0">Tidak Ada Rekomendasi</option>
			</select>
		</div>
		<div class="col-md-3">
			<input onclick="do_cetak()" style="float: right;" type="button" value="Cetak" />
		</div>

		<div class="col-md-12">
			&nbsp;
		</div>

		<div class="col-md-12 scroll">
			<table id="usulan_table" class="table-common tablesorter" style="width:100%; max-width: 100%">
				<thead>
					<tr>
						<th class="no-sort">No</th>
						<th>Pengusul</th>
						<th>Jenis Pekerjaan</th>
						<th>Nilai Usulan (Rp)</th>
						<th>SKPD</th>
						<th class="no-sort" style="min-width: 153px;">Status RKPD</th>
						<th>Nominal Rekomendasi</th>
						<th class="no-sort" style="min-width: 205px;">No. Rekomendasi</th>
						<th class="no-sort">Tgl. Rekomendasi</th>
						<th class="no-sort">Tgl. Input Rekomendasi</th>


					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3">TOTAL</th>
						<th></th>
						<th colspan="2"></th>
						<th></th>
						<th colspan="3"></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</article>
