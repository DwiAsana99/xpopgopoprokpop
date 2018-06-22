<?php
	$urusan = $this->db->query("SELECT t.*,u.Nm_Urusan AS nama_urusan FROM (
		SELECT *, SUM(jumlah_dana) AS sum_jumlah_dana FROM t_musrenbang
		WHERE tahun = '".$ta."'
		AND flag_delete = 0
		".$id_usulan."
		".$status."
		".$kec."
		".$desa."
		GROUP BY kd_urusan
		ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
		) AS t
		LEFT JOIN m_urusan AS u ON t.kd_urusan = u.Kd_Urusan")->result();

	foreach ($urusan as $row_urusan) {
		if($row_urusan->nama_urusan!=NULL){
			$nama_urusan = $row_urusan->nama_urusan;
		}else{
			$nama_urusan = "Kode Urusan Belum Ditentukan";
		}
?>
		<tr bgcolor="#78cbfd">
			<td><?php echo $row_urusan->kd_urusan;?></td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="4">
				<strong><?php echo strtoupper($nama_urusan); ?></strong>
			</td>
			<td align="right"><?php echo Formatting::currency($row_urusan->sum_jumlah_dana); ?></td>
			<td colspan="5"></td>
		</tr>
<?php
		if($row_urusan->kd_urusan!=0){
			$kode_urusan = $row_urusan->kd_urusan;
		}else{
			$kode_urusan = NULL;
		}

		$bidang = $this->db->query("SELECT t.*, b.Nm_Bidang AS nama_bidang FROM (
			SELECT *, SUM(jumlah_dana) AS sum_jumlah_dana FROM t_musrenbang
			WHERE tahun = '".$ta."'
			AND flag_delete = 0
			".$id_usulan."
			".$status."
			".$kec."
			".$desa."
			AND kd_urusan = '".$kode_urusan."'
			GROUP BY kd_bidang
			ORDER BY kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
			) AS t
			LEFT JOIN m_bidang AS b ON t.kd_urusan = b.kd_urusan AND t.kd_bidang = b.kd_bidang")->result();
		foreach ($bidang as $row_bidang) {
			if($row_bidang->nama_bidang!=NULL){
				$nama_bidang = $row_bidang->nama_bidang;
			}else{
				$nama_bidang = "Kode Bidang Belum Ditentukan";
			}
?>
			<tr bgcolor="#00FF33">
				<td><?php echo $row_urusan->kd_urusan; ?></td>
				<td><?php echo $row_bidang->kd_bidang; ?></td>
				<td></td>
				<td></td>
				<td colspan="4">
					<strong><?php echo strtoupper($nama_bidang); ?></strong>
				</td>
				<td align="right"><?php echo Formatting::currency($row_bidang->sum_jumlah_dana); ?></td>
				<td colspan="5"></td>
			</tr>
<?php
			if($row_bidang->kd_bidang!=0){
				$kode_bidang = $row_bidang->kd_bidang;
			}else{
				$kode_bidang = NULL;
			}

			$program = $this->db->query("SELECT t.*, p.ket_program AS nama_program FROM (
				SELECT *, SUM(jumlah_dana) AS sum_jumlah_dana FROM t_musrenbang
				WHERE tahun = '".$ta."'
				AND flag_delete = 0
				".$id_usulan."
				".$status."
				".$kec."
				".$desa."
				AND kd_urusan = '".$kode_urusan."'
				AND kd_bidang = '".$kode_bidang."'
				GROUP BY kd_program
				ORDER BY kd_program ASC, kd_kegiatan ASC
				) AS t
				LEFT JOIN m_program AS p ON t.kd_urusan = p.kd_urusan AND t.kd_bidang = p.kd_bidang 
				AND t.kd_program = p.kd_prog")->result();
			foreach ($program as $row_program){
				if($row_program->nama_program!=NULL){
					$nama_program = $row_program->nama_program;
				}else{
					$nama_program = "Kode Program Belum Ditentukan";
				}
?>
				<tr bgcolor="#FF8000">
                    <td><?php echo $row_urusan->kd_urusan; ?></td>
                    <td><?php echo $row_bidang->kd_bidang; ?></td>
                    <td><?php echo $row_program->kd_program; ?></td>
                    <td></td>
                    <td colspan="4">
                        <strong><?php echo strtoupper($nama_program); ?></strong>
                    </td>
                    <td align="right"><?php echo Formatting::currency($row_program->sum_jumlah_dana); ?></td>
                    <td colspan="5"></td>
                </tr>
<?php
				if($row_program->kd_program!=0){
					$kode_program = $row_program->kd_program;
				}else{
					$kode_program = NULL;
				}

				$kegiatan = $this->db->query("SELECT t.*, k.ket_kegiatan AS nama_kegiatan FROM (
					SELECT * FROM t_musrenbang
					WHERE tahun = '".$ta."'
					AND flag_delete = 0
					".$id_usulan."
					".$status."
					".$kec."
					".$desa."
					AND kd_urusan = '".$kode_urusan."'
					AND kd_bidang = '".$kode_bidang."'
					AND kd_program = '".$kode_program."'
					ORDER BY kd_kegiatan ASC
					) AS t
					LEFT JOIN m_kegiatan AS k ON t.kd_urusan = k.kd_urusan AND t.kd_bidang = k.kd_bidang 
					AND t.kd_program = k.kd_prog AND t.kd_kegiatan = k.kd_keg")->result();

				foreach ($kegiatan as $row_kegiatan){
					if($row_kegiatan->nama_kegiatan!=NULL){
						$nama_kegiatan = $row_kegiatan->nama_kegiatan;
					}else{
						$nama_kegiatan = "Kode Kegiatan Belum Ditentukan";
					}
?>
					<tr >
                        <td><?php echo $row_urusan->kd_urusan; ?></td>
                        <td><?php echo $row_bidang->kd_bidang; ?></td>
                        <td><?php echo $row_program->kd_program; ?></td>
                        <td><?php echo $row_kegiatan->kd_kegiatan; ?></td>
                        <td><?php echo $nama_kegiatan; ?></td>
                        <td><?php echo $row_kegiatan->jenis_pekerjaan; ?></td>
                        <td><?php echo $row_kegiatan->volume; ?></td>
                        <td><?php echo $row_kegiatan->satuan; ?></td>
                        <td align="right"><?php echo Formatting::currency($row_kegiatan->jumlah_dana); ?></td>
                        <td><?php echo $row_kegiatan->nama_desa; ?></td>
                        <td><?php echo $row_kegiatan->nama_kecamatan; ?></td>
                        <td><?php echo $row_kegiatan->nama_skpd; ?></td>
                        <td><?php echo $row_kegiatan->asal_usulan; ?></td>
                        <td><?php echo $row_kegiatan->alasan_keputusan; ?></td>
                    </tr>
<?php
				}
			}
		}
	}
?>
