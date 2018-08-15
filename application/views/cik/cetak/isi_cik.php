<?php
	$no_ur=0; $no_bi=0; $no_pr=0; $no_kg=0;

	$max_col_keg=1;
	$tot_rencana=0; $tot_realisasi=0;
	$p=0;
	foreach($urusan as $row_urusan){
		$no_ur++;
		if($row_urusan->id == ""){
		echo "<tr><td colspan='13' align='center'><strong>Data Belum Terisikan..</strong></td></tr>";
	}else{
if (!empty($row_urusan->sumrencana)) {
		$bidang = $this->m_cik->get_bidang_cik($row_urusan->kd_urusan,$bulan,$ta,$id_skpd);
		// print_r($this->db->last_query());exit();
		$cik_pro_keg_urusan = round(0, 2);
		if ($row_urusan->sumrealisasi != 0) {
			$cik_pro_keg_urusan = round(($row_urusan->sumrealisasi/$row_urusan->sumrencana)*100,2);
		}
		// $cik_pro_keg_urusan = (empty($row_urusan->sumrealisasi))?0:round(($row_urusan->sumrealisasi/$row_urusan->sumrencana)*100,2);

?>
	 	<tr bgcolor="#78cbfd">
            <td style="border-bottom: 0;" colspan="2"></td>
            <td style="border-bottom: 0;" ><?php echo $row_urusan->kd_urusan; ?></td>
            <td style="border-bottom: 0;" ></td>
            <td style="border-bottom: 0;" ></td>
            <td style="border-bottom: 0;" ></td>
			<td style="border-bottom: 0;" ><?php echo $row_urusan->nama_urusan; ?></td>
            <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_urusan->sumrencana, 2); ?>
            </td>
            <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_urusan->sumrealisasi, 2); ?>
            </td>
            <td style="border-bottom: 0;" align="right"><?php echo Formatting::two_comma($cik_pro_keg_urusan); ?></td>
            <td colspan="4"></td>
            <td style="border-bottom: 0;" align="center">-</td>
            <td style="border-bottom: 0;" align="center" colspan="2"></td>
        </tr>
<?php
	foreach($bidang as $row_bidang){
		$no_bi++;
		$program = $this->m_cik->get_program_cik($id_skpd,$bulan,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);
		$cik_pro_keg_bidang = round(0, 2);
		if ($row_bidang->sumrealisasi != 0) {
			$cik_pro_keg_bidang = round(($row_bidang->sumrealisasi/$row_bidang->sumrencana)*100,2);
		}
		// $cik_pro_keg_bidang = (empty($row_bidang->sumrealisasi))?0:round(($row_bidang->sumrealisasi/$row_bidang->sumrencana)*100,2);
?>
		<tr bgcolor="#00FF33">
            <td style="border-bottom: 0;"  colspan="2"></td>
            <td style="border-bottom: 0;" ><?php echo $row_urusan->kd_urusan; ?></td>
            <td style="border-bottom: 0;" ><?php echo $row_bidang->kd_bidang; ?></td>
            <td style="border-bottom: 0;" ></td>
            <td style="border-bottom: 0;" ></td>
            <td style="border-bottom: 0;" ><?php echo $row_bidang->nama_bidang; ?></td>
            <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_bidang->sumrencana, 2); ?>
            </td>
            <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_bidang->sumrealisasi, 2); ?>
            </td>
            <td style="border-bottom: 0;" align="right"><?php echo Formatting::two_comma($cik_pro_keg_bidang); ?></td>
            <td colspan="4"></td>
            <td style="border-bottom: 0;" align="center">-</td>
            <td style="border-bottom: 0;" align="center" colspan="2"></td>
        </tr>
<?php
	foreach($program as $prog){
		$no_pr++;
		$p++;
		$tot_rencana += $prog->rencana;
		$tot_realisasi += $prog->realisasi;
		$result = $this->m_cik->get_kegiatan_cik_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$prog->kd_program,$id_skpd, $bulan,$ta);
		// echo $this->db->last_query();

		$cik_pro_keg = round(0, 2);
		if ($prog->realisasi != 0) {
			$cik_pro_keg = round(($prog->realisasi/$prog->rencana)*100,2);
		}
		// $cik_pro_keg = (empty($prog->realisasi))?0:round(($prog->realisasi/$prog->rencana)*100,2);
		

		$kegiatan = $result->result();
		$indikator_program = $this->m_cik->get_indikator_prog_keg_preview($prog->id, $bulan, FALSE, TRUE);
		$temp = $indikator_program->result();
		$total_temp = $indikator_program->num_rows();

		$col_indikator=1;
		$go_2_keg = FALSE;
		$total_for_iteration = $total_temp;
		if($total_temp > $max_col_keg){
			$total_temp = $max_col_keg;
			$go_2_keg = TRUE;
		}
?>
<tr bgcolor="#FF9933">
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $no_pr.'.'; ?></td>
	<td style="border-top: 0;border-bottom: 0;" ></td>	
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $prog->kd_urusan; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $prog->kd_bidang; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $prog->kd_program; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $prog->kd_kegiatan; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $prog->nama_prog_or_keg; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($prog->rencana, 2); ?>
	</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::two_comma($prog->realisasi); ?>
	</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::two_comma($cik_pro_keg); ?></td>
	<td>
		<?php
			echo $temp[0]->indikator;
		?>
	</td>
	<td align="right">
		<?php
			echo Formatting::two_comma($temp[0]->target);
		?>
	</td>
	<td align="right">
		<?php
			echo (empty($temp[0]->realisasi)) ? '0,00' :Formatting::two_comma($temp[0]->realisasi);
		?>
	</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right">
				<?php
					echo Formatting::two_comma($prog->capaian);
				?>
			</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="center"><?php echo $prog->status;?></td>
    <?php
		$status = "status_".$bulan;
		if($prog->$status==1 || $prog->$status==3){
	?>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">
    <a href="javascript:void(0)" idK="<?php echo $prog->id; ?>" class="icon-pencil edit-program" id="edit" title="Input Realisasi"/></a>
    </td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">
    <a href="javascript:void(0)" idK="<?php echo $prog->id; ?>" class="icon-file upload-datacik" id="upload" title="Upload Data"/></a>
    </td>
    <?php
		} else {
	?>
    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">&nbsp;</td>
    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">&nbsp;</td>
	<?php
		}
		if ($total_for_iteration > 1) {
			for ($i=1; $i < $total_for_iteration; $i++) {
				$col_indikator++;
	?>
		<tr bgcolor="#FF9933">
			<?php
					if ($go_2_keg && $col_indikator > $max_col_keg) {
				?>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
				<?php
					}
				?>
				<td>
					<?php
						echo $temp[$i]->indikator;
					?>
				</td>
				<td align="right">
					<?php
						echo Formatting::two_comma($temp[$i]->target);
					?>
				</td>
				<td align="right">
					<?php
						echo Formatting::two_comma($temp[$i]->realisasi);
					?>
				</td>
				<td style="border-top: 0;border-bottom: 0;"></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
                <td style="border-top: 0;border-bottom: 0;" ></td>
			</tr>
		<?php
			}
		}
			foreach ($kegiatan as $row) {
			$no_kg++;
			$indikator_kegiatan = $this->m_cik->get_indikator_prog_keg_preview($row->id, $bulan, FALSE, TRUE);
			$temp = $indikator_kegiatan->result();
			$total_temp = $indikator_kegiatan->num_rows();

			$go_2_keg = FALSE;
			$col_indikator_keg=1;
			$total_for_iteration = $total_temp;
			if ($total_temp > $max_col_keg) {
				$total_temp = $max_col_keg;
				$go_2_keg = TRUE;
			}
		?>
		<tr>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $no_kg.'.'; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_urusan; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_bidang; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_program; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_kegiatan; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->nama_prog_or_keg; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::two_comma($row->rencana); ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::two_comma($row->realisasi); ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo (empty($row->realisasi)) ? '0,00' :Formatting::two_comma(round(($row->realisasi/$row->rencana)*100,2)); ?></td>
			<td>
				<?php
					echo $temp[0]->indikator;
				?>
			</td>
			<td align="right">
				<?php
					echo Formatting::two_comma($temp[0]->target);
				?>
			</td>
			<td align="right">
				<?php
					echo (empty($temp[0]->realisasi)) ? '0,00' :Formatting::two_comma($temp[0]->realisasi);
				?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right">
				<?php
					echo Formatting::two_comma($row->capaian);
				?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="center"><?php echo $row->status;?></td>
            <?php
				$status = "status_".$bulan;
				if($row->$status==1 || $row->$status==3){
			?>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">
				<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" idP="<?php echo $row->parent; ?>" class="icon-pencil edit-kegiatan" id="edit" title="Input Realisasi"/></a>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">
				<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="icon-file upload-datacik" id="upload" title="Upload Data"/></a>
			</td>
            <?php
				} else {
			?>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">&nbsp;</td>
            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">&nbsp;</td>
	 <?php
				}
		if ($total_for_iteration > 1) {
			for ($i=1; $i < $total_for_iteration; $i++) {
				$col_indikator++;
	?>
		<tr>
			<?php
					if ($go_2_keg && $col_indikator > $max_col_keg) {
				?>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
				<?php
					}
				?>
				<td>
					<?php
						echo $temp[$i]->indikator;
					?>
				</td>
				<td align="right">
					<?php
						echo Formatting::two_comma($temp[$i]->target);
					?>
				</td>
				<td align="right">
					<?php
						echo (empty($temp[$i]->realisasi)) ? '0,00' :Formatting::two_comma($temp[$i]->realisasi);
					?>
				</td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>

			</tr>
<?php
		}
	}
}
	}
}
	}
	}
	}
	?>
	<tr>
    	<td colspan="5">&nbsp;</td>
		<td colspan="4" align="right">TOTAL PROGRAM</td>
		<td colspan="4" align="right"><?php echo $jumlah_prog; ?></td>
        <td colspan="3">&nbsp;</td>
	</tr>
	<tr>
    	<td colspan="5">&nbsp;</td>
		<td colspan="4" align="right">TOTAL KEGIATAN</td>
		<td colspan="4" align="right"><?php echo $jumlah_keg; ?></td>
        <td colspan="3">&nbsp;</td>
	</tr>
    <tr>
    	<td colspan="5">&nbsp;</td>
        <td align="right">JUMLAH&nbsp;&nbsp;&nbsp;</td>
        <td align="right"><?php echo Formatting::currency($tot_rencana, 2); ?></td>
        <td align="right"><?php echo Formatting::currency($tot_realisasi, 2); ?></td>
        <td align="right"><?php echo (empty($tot_realisasi)) ? '0,00' :Formatting::two_comma(round(($tot_realisasi/$tot_rencana)*100,2)); ?></td>
        <td colspan="3" align="right">Rata-rata Capaian Program</td>
        <td align="right"><?php echo Formatting::two_comma(round($tot_prog,2)); ?></td>
        <td colspan="3">&nbsp;</td>
    </tr>
    <?php $sisa = $tot_rencana-$tot_realisasi; ?>
    <tr>
    	<td colspan="5">&nbsp;</td>
        <td align="right">SISA&nbsp;&nbsp;&nbsp;</td>
        <td colspan="2" align="right"><?php echo Formatting::currency($sisa,2); ?></td>
        <td align="right"><?php echo (empty($sisa)) ? '0,00' :Formatting::two_comma(round(($sisa/$tot_rencana)*100,2)); ?></td>
        <td colspan="3" align="right">Rata-rata Capaian Kegiatan</td>
        <td align="right"><?php echo Formatting::two_comma(round($tot_keg,2)); ?></td>
        <td colspan="3">&nbsp;</td>
    </tr>
<input type="hidden" name="id_bulan" value="<?php echo $bulan; ?>" />
<script type="text/javascript">
$(document).ready(function(){

	$(".edit-program").click(function(){

		var bulan = $("#id_bulan").val();

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("cik/cru_perprogram"); ?>',
			data: { bulan: $("#id_bulan").val(), id: $(this).attr("idK") },
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
					$.blockUI({
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	});

	$(".edit-kegiatan").click(function(){

		var bulan = $("#id_bulan").val();

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("cik/cru_perkegiatan"); ?>',
			data: { bulan: $("#id_bulan").val(), id: $(this).attr("idK"), id_program: $(this).attr("idP") },
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
					$.blockUI({
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	});

	$(".upload-datacik").click(function(){

		var bulan = $("#id_bulan").val();

		//alert("The Upload File was clicked.");

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("cik/upload_datacik"); ?>',
			data: { bulan: $("#id_bulan").val(), id: $(this).attr("idK") },
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
					$.blockUI({
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	});

});
</script>
