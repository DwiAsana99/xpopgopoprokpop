<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();

		$(document).on("click", "#cetak", function(){
			var ta = "<?php echo $this->session->userdata('t_anggaran_aktif'); ?>";
			var id_usulan = $('#m_asal').html();
			var id_status = $('#m_status').html();
			var id_kec = $('#m_kec').html();
			var id_desa = $('#m_desa').html();
			if (id_usulan != '') {
				var link = '<?php echo site_url("usulanpro/cetak_all_usulan"); ?>/' + ta + '/' + id_usulan + '/' + id_status + '/' + id_kec + '/' + id_desa;
				window.open(link);
			}
			// $(location).attr('href',link);
		});

//kecamatan --------------------------->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

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
		});

		$(document).on("click", ".lihat", function(){
			$.blockUI({
				message: 'Mohon Menunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});
			var ta = "<?php echo $this->session->userdata('t_anggaran_aktif'); ?>";
			var id_usulan = $('#select1').val();
			var id_status = $('#select2').val();
			var id_pilihan = $('#select3').val();
			var id_kec = $('#cb_kecamatan').val();
			var id_desa = $('#cb_desa').val();
			$.ajax({
				type 	: "POST",
				url 	: "<?php echo site_url('usulanpro/isi_all_usulan'); ?>/" + ta,
				data 	: {id_usulan:id_usulan, id_status:id_status, id_pilihan:id_pilihan, id_kec:id_kec, id_desa:id_desa},
				success : function(data) {
					$('#m_asal').html(id_usulan);
					$('#m_status').html(id_status);
					$('#m_kec').html(id_kec);
					$('#m_desa').html(id_desa);

					var textUsulan = $('#select1 option:selected').text()+" ("+$('#select2 option:selected').text()+") : ";
					// textUsulan = textUsulan+" "+$('#select3 option:selected').text()+" : ";
					if (id_kec != 'all'){
						textUsulan = textUsulan+" Kecamatan "+$('#cb_kecamatan option:selected').text()+" : ";
					}else{
						textUsulan = textUsulan+" "+$('#cb_kecamatan option:selected').text()+" : ";
					}
					textUsulan = textUsulan+" "+$('#cb_desa option:selected').text();
					$('#usulan_type').html(textUsulan);
					$('#body_usulan').html(data);
					$.blockUI({
						timeout: 1000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				}
			});
		});

	});
</script>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<div class="content">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<select class="s2 chosen-select" data-placeholder=" " id="select1">
						<option value="all">Semua Usulan</option>
						<option value="2">Pokir</option>
						<option value="3">Temu Wirasa</option>
						<option value="6">Musrenbangcam</option>
						<option value="7">Forum SKPD</option>
					</select>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<select class="s2 chosen-select" data-placeholder=" " id="select2">
						<option value="all">Semua Status</option>
						<option value="1">Belum Ditentukan</option>
						<option value="2">Terakomodir</option>
						<option value="3">Tidak Terakomodir</option>
					</select>
				</div>	
			</div>

			<!-- <div class="col-md-12">
				<div class="form-group">
					<select class="s2 chosen-select" data-placeholder=" " id="select3">
						<option value="all">Semua Pilihan</option>
						<option value="1">Renja Induk</option>
						<option value="2">Renja Perubahan</option>
					</select>
				</div>
			</div> -->

			<div class="col-md-6">
				<div class="form-group">
					<?php echo $cb_kecamatan; ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?php echo $cb_desa; ?>
				</div>
			</div>
		</div>
		<button type="button" class="lihat">Lihat</button>
	</div>
	<header>
	  <h3>Rekap Usulan</h3>
	  <div style="display: none;">
		  <p id="m_asal"></p>
		  <p id="m_status"></p>
		  <p id="m_kec"></p>
		  <p id="m_desa"></p>
	  </div>
	</header>
	<div class="module_content";>
		<table class="table-display" style="width:100%">
			<thead>
				<tr>
					<th colspan="14" align="center" style="text-transform: uppercase; font-size: 16px;" id="usulan_type"></th>
				</tr>
			</thead>		
		</table>
		<table>
			<thead>
				<tr>
					<th colspan="4">Kode</th>
					<th >Program dan Kegiatan</th>
					<th >Jenis Pekerjaan</th>
					<th >Volume</th>
					<th >Satuan</th>
					<th >Jumlah Dana (Rp.)</th>
			        <th >Nama Desa</th>
			        <th >Nama Kecamatan</th>
			        <th >SKPD Penanggungjawab</th>
			        <th >Asal Usulan</th>
			        <th >Alasan</th>
				</tr>
			</thead>
			<tbody id="body_usulan">
				
			</tbody>
		</table>			
	</div>		
	<footer>
		<div class="submit_link">
 			<input id="cetak" type="button" value="Cetak" />
			<input type="button" value="Back" onclick="history.go(-1)" />
		</div>
	</footer>
</article>


