<?php
	$max_col_keg=1;
	$tot_nominal=0; 
	$tot_nominal_thndpn=0;
?>
<?php
	if(!empty($ta))
	{
		$tahun_ppas = $ta;
	}
	else
	{
		$tahun_ppas = $this->session->userdata('t_anggaran_aktif');
	}
?>
<thead>
	<tr>
		<th rowspan="2" colspan="2">Kode</th>
		<th rowspan="2">Program dan Kegiatan</th>
		<th >Rencana Tahun <?php echo $tahun_ppas?></th>
		<th >Perkiraan Maju Rencana Tahun <?php echo $tahun_ppas+1;?></th>
	</tr>
	<tr>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
	</tr>
</thead>
<tbody>
<?php
	foreach($skpd_select as $row_select){
		$skpd = $this->db->query("
		SELECT SUM(jos.sum_nominal) AS sum_nominal, SUM(jos.sum_nominal_thndpn) AS sum_nominal_thndpn FROM (
			SELECT t.*,u.Nm_Urusan as nama_urusan from (
			SELECT
				keg.kd_urusan,keg.kd_bidang,keg.kd_program,keg.kd_kegiatan,
				SUM(keg.nominal) AS sum_nominal,
				SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
			FROM
				(SELECT * FROM t_ppas_prog_keg WHERE is_prog_or_keg=1 AND id_skpd > 0) AS pro
			INNER JOIN
				(SELECT * FROM t_ppas_prog_keg WHERE is_prog_or_keg=2 AND id_skpd > 0 AND id IN (SELECT id_prog_keg 
FROM t_ppas_indikator_prog_keg WHERE target > 0)) AS keg ON keg.parent=pro.id
			WHERE keg.id_skpd = ".$row_select->id_skpd."
			AND	keg.tahun= ".$ta."
			AND pro.id IN (SELECT id_prog_keg FROM t_ppas_indikator_prog_keg WHERE id_prog_keg = pro.id AND target > 0)
			GROUP BY keg.kd_urusan
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			LEFT JOIN m_urusan u
			ON t.kd_urusan = u.Kd_Urusan 
		) AS jos WHERE sum_nominal > 0 OR sum_nominal_thndpn > 0
			")->result();

		foreach ($skpd as $row_skpd) {
if ($row_skpd->sum_nominal > 0 || $row_skpd->sum_nominal_thndpn > 0) {
					$tot_nominal += $row_skpd->sum_nominal;
					$tot_nominal_thndpn += $row_skpd->sum_nominal_thndpn;
					$tot_nominal_skpd=0;
					$tot_nominal_thndpn_skpd=0;
					$urusan = $this->db->query("
						SELECT t.*,u.Nm_Urusan as nama_urusan from (
						SELECT
							keg.kd_urusan,keg.kd_bidang,keg.kd_program,keg.kd_kegiatan,
							SUM(keg.nominal) AS sum_nominal,
							SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
						FROM
							(SELECT * FROM t_ppas_prog_keg WHERE is_prog_or_keg=1 AND id_skpd > 0) AS pro
						INNER JOIN
							(SELECT * FROM t_ppas_prog_keg WHERE is_prog_or_keg=2 AND id_skpd > 0 AND id IN (SELECT id_prog_keg 
FROM t_ppas_indikator_prog_keg WHERE target > 0)) AS keg ON keg.parent=pro.id
						WHERE keg.id_skpd = ".$row_select->id_skpd."
						AND	keg.tahun= ".$ta."
						AND pro.id IN (SELECT id_prog_keg FROM t_ppas_indikator_prog_keg WHERE id_prog_keg = pro.id AND target > 0)
						GROUP BY keg.kd_urusan
						ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
						) t
						LEFT JOIN m_urusan u
						ON t.kd_urusan = u.Kd_Urusan
					")->result();
					echo "
					<tr>
						<td colspan=\"3 \">
							<strong>".strtoupper($row_select->nama_skpd)."</strong>
						</td>
						<td align=\"right\">".Formatting::currency($row_skpd->sum_nominal,2)."</td>
						<td align=\"right\">".Formatting::currency($row_skpd->sum_nominal_thndpn,2)."</td>
					</tr>";
					$id_skpd = $row_select->id_skpd;
			foreach ($urusan as $row_urusan) {
				$tot_nominal_skpd+=$row_urusan->sum_nominal;
				$tot_nominal_thndpn_skpd+=$row_urusan->sum_nominal_thndpn;
				echo "
				<tr bgcolor=\"#78cbfd\">
					<td>".$row_urusan->kd_urusan."</td>
					<td></td>
					<td >
						<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
					</td>
					<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal,2)."</td>
					<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal_thndpn,2)."</td>
		
				</tr>";
		
				$bidang = $this->db->query("
					SELECT t.*,b.Nm_Bidang as nama_bidang from (
					SELECT
						keg.kd_urusan,keg.kd_bidang,keg.kd_program,keg.kd_kegiatan,
						SUM(keg.nominal) AS sum_nominal,
						SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
					FROM
						(SELECT * FROM t_ppas_prog_keg WHERE is_prog_or_keg=1 AND id_skpd > 0) AS pro
					INNER JOIN
						(SELECT * FROM t_ppas_prog_keg WHERE is_prog_or_keg=2 AND id_skpd > 0 AND id IN (SELECT id_prog_keg 
FROM t_ppas_indikator_prog_keg WHERE target > 0)) AS keg ON keg.parent=pro.id
					WHERE keg.id_skpd = ".$row_select->id_skpd."
					AND keg.kd_urusan = ".$row_urusan->kd_urusan."
					AND keg.tahun=".$ta."
					AND pro.id IN (SELECT id_prog_keg FROM t_ppas_indikator_prog_keg WHERE id_prog_keg = pro.id AND target > 0)
					GROUP BY keg.kd_urusan, keg.kd_bidang
					ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
					) t
					LEFT JOIN m_bidang b
					ON t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
				")->result();
		
					foreach ($bidang as $row_bidang) {
						echo "
						<tr bgcolor=\"#00FF33\">
							<td>".$row_urusan->kd_urusan."</td>
							<td>".$row_bidang->kd_bidang."</td>
							<td >
								<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
							</td>
							<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal,2)."</td>
							<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal_thndpn,2)."</td>
						</tr>";
				}
		}
?>
		<tr bgcolor="#FFFF00">
        	<td colspan = "2"></td>
			<td >
            	<strong>
                	<?php echo "Total Nominal PPAS SKPD ".$row_select->nama_skpd." Tahun ".$ta?>
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($tot_nominal_skpd,2) ;?>
                <strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($tot_nominal_thndpn_skpd,2); ?>
                </strong>
            </td>
		</tr>
<?php
}
	}
}
?>
		<tr bgcolor="#999999">
        	<td colspan = "2"></td>
			<td >
            	<strong>
                	Total Nominal Tahun PPAS
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($tot_nominal,2) ;?>
                <strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($tot_nominal_thndpn,2); ?>
                </strong>
            </td>
		</tr>
</tbody>
