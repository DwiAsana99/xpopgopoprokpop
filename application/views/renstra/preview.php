<div style="width: 1000px;">
<table class="fcari" width="800 px">
	<tbody>
		<tr>
			<td width="22%">SKPD</td>
			<td width="77%"><?php echo $renstra->nama_skpd; ?></td>
		</tr>
		<tr>
			<td>Tujuan</td>
			<td><?php echo $renstra->tujuan; ?></td>
		</tr>
		<tr>
			<td>Sasaran</td>
			<td><?php echo $renstra->sasaran; ?></td>
		</tr>
		<tr>
			<td>Indikator Sasaran</td>
			<td>
				<?php
					$i=0;
					foreach ($indikator_sasaran as $row1) {
						$i++;
						echo $i .". ". $row1->indikator ."<BR>";
					}
				?>
			</td>
		</tr>
		<tr style="background-color: white;">
			<td colspan="2"><hr></td>
		</tr>
		<tr>
			<td>Urusan</td>
			<td><?php echo $renstra->kd_urusan.". ".$renstra->Nm_Urusan; ?></td>
		</tr>
		<tr>
			<td>Bidang Urusan</td>
			<td style="padding-left: 20px;"><?php echo $renstra->kd_bidang.". ".$renstra->Nm_Bidang; ?></td>
		</tr>
		<tr>
			<td>Program</td>
			<td style="padding-left: 43px;"><?php echo $renstra->kd_program.". ".$renstra->Ket_Program; ?></td>
		</tr>
		<tr>
			<td>Kegiatan</td>
			<td style="padding-left: 65px;"><?php echo $renstra->kd_kegiatan.". ".$renstra->nama_prog_or_keg; ?></td>
		</tr>
		<tr>
			<td>Indikator Kinerja</td>
			<td>
				<?php
					$i=0;
					foreach ($indikator_kegiatan as $row_kegiatan) {
						$i++;
						echo $i .". ". $row_kegiatan->indikator ."<BR>";
						echo "Kategori Indikator :  $row_kegiatan->nama_status_indikator | $row_kegiatan->nama_kategori_indikator <BR>";
				?>
				<table class="table-common" width="100%">
					<tr>
						<th width="14%">Kondisi Awal</th>
						<th width="14%">Target 1</th>
						<th width="14%">Target 2</th>
						<th width="14%">Target 3</th>
						<th width="14%">Target 4</th>
						<th width="14%">Target 5</th>
						<th width="14%">Kondisi Akhir</th>
					</tr>
					<tr>
						<td align="center"><?php echo $row_kegiatan->kondisi_awal." ".$row_kegiatan->nama_value; ?></td>
						<td align="center"><?php echo $row_kegiatan->target_1." ".$row_kegiatan->nama_value; ?></td>
						<td align="center"><?php echo $row_kegiatan->target_2." ".$row_kegiatan->nama_value; ?></td>
						<td align="center"><?php echo $row_kegiatan->target_3." ".$row_kegiatan->nama_value; ?></td>
						<td align="center"><?php echo $row_kegiatan->target_4." ".$row_kegiatan->nama_value; ?></td>
						<td align="center"><?php echo $row_kegiatan->target_5." ".$row_kegiatan->nama_value; ?></td>
						<td align="center"><?php echo $row_kegiatan->target_kondisi_akhir." ".$row_kegiatan->nama_value; ?></td>
					</tr>
				</table>
				<hr>
				<?php
					}
				?>
			</td>
		</tr>
		<tr>
			<td>Penanggung Jawab</td>
			<td><?php echo $renstra->penanggung_jawab; ?></td>
		</tr>
		<tr>
			<td>Lokasi</td>
			<td><?php echo $renstra->lokasi; ?></td>
		</tr>
		<tr style="background-color: white;">
			<td colspan="2"><hr></td>
		</tr>
	</tbody>
</table>
<?php $th_anggaran = $this->m_settings->get_tahun_anggaran_db(); ?>
<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tahun1" data-toggle="tab"><b>Tahun 1</b></a></li>
			<li><a href="#tahun2" data-toggle="tab"><b>Tahun 2</b></a></li>
			<li><a href="#tahun3" data-toggle="tab"><b>Tahun 3</b></a></li>
			<li><a href="#tahun4" data-toggle="tab"><b>Tahun 4</b></a></li>
			<li><a href="#tahun5" data-toggle="tab"><b>Tahun 5</b></a></li>
		</ul>
		<div class="tab-content">
			<!-- /.tab-pane -->
			<div class="active tab-pane" id="tahun1">
				<!-- /.tab-tahun 1 -->
				<div class="tab-pane" id="tahun1">
					<table class="fcari">
						<tbody>
							<tr>
								<td width="22%">Lokasi Tahun 1</th>
								<td align="left"><?php echo $renstra->lokasi_1; ?></td>
							</tr>
							<tr>
								<td>Uraian Kegiatan Tahun 1</td>
								<td align="left"><?php echo $renstra->uraian_kegiatan_1; ?></td>
							</tr>
							<tr>
								<td width="22%">Nominal Tahun 1</td>
								<td>Rp. <?php echo Formatting::currency($renstra->nominal_1); ?></td>
							</tr>
							<tr>
								<table id="listbelanja_1">
									<?php 
							          $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
							          $idk_ng = $renstra->id;
							          $ta_ng = $th_anggaran[0]->tahun_anggaran;
							        ?>
							        <?php foreach ($belanja_1 as $key_rowth => $rowth): ?>
							          <?php if ($rowth->kode_jenis_belanja == $jenis): ?>
							            <?php if ($rowth->kode_kategori_belanja == $kategori): ?>
							              <?php if ($rowth->kode_sub_kategori_belanja == $subkategori): ?>
							                <?php if ($rowth->kode_belanja == $kdbelanja): ?>
							                  <?php if ($rowth->uraian_upper == $uraianbelanja): ?>
							                    <tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;">
							                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
							                      	</div>
							                      </td>
							                      <td align='right'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
							                    </tr>
							                  <?php else: ?>
							                    <?php 
							                      $uraianbelanja = $rowth->uraian_upper;
							                      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							                      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							                    ?>
							                    <tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
							                      </td>
							                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                    </tr>
							                    <tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;">
							                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
							                      	</div>
							                      </td>
							                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
							                    </tr>
							                  <?php endif ?>
							                <?php else: ?>
							                  <?php  
							                    $kdbelanja = $rowth->kode_belanja;
							                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
							                  ?>
							                  <tr>
							                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
							                    <td>
							                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
							                    </td>
							                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                  </tr>
							                  <?php  
							                    $uraianbelanja = $rowth->uraian_upper;
							                    $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							                    $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							                  ?>
							                  	<tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
							                      </td>
							                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                    </tr>
							                    <tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;">
							                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
							                      	</div>
							                      </td>
							                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
							                    </tr>
							                <?php endif ?>
							              <?php else: ?>
							                <?php  
							                  $subkategori = $rowth->kode_sub_kategori_belanja;
							                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
							                ?>
							                <tr>
								              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
								              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
								              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
								            </tr>
							                <?php  
							                  $kdbelanja = $rowth->kode_belanja;
							                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
							                ?>
							                <tr>
							                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
							                    <td>
							                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
							                    </td>
							                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                </tr>
							                <?php  
							                  $uraianbelanja = $rowth->uraian_upper;
							                  $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							                  $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							                ?>
							                <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
							              <?php endif ?>
							            <?php else: ?>
							              <?php  
							                $kategori = $rowth->kode_kategori_belanja;
							                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
							              ?>
							              	<tr>
								              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
								              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
								              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
								            </tr>
							              <?php  
							                $subkategori = $rowth->kode_sub_kategori_belanja;
							                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
							              ?>
							              	<tr>
								              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
								              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
								              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
								            </tr>
							              <?php  
							                $kdbelanja = $rowth->kode_belanja;
							                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
							              ?>
							              	<tr>
							                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
							                    <td>
							                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
							                    </td>
							                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                </tr>
							              <?php  
							                $uraianbelanja = $rowth->uraian_upper;
							                $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							                $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							              ?>
							              	<tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
							            <?php endif ?>
							          <?php else: ?>
							            <?php  
							              $jenis = $rowth->kode_jenis_belanja; 
							              $jenisText = substr_replace($jenis,"", 0, -1);
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis'")->row();
							            ?>
							            <tr>
							              <td>5 . <?php echo $jenisText; ?></td>
							              <td><div style="font-weight: bold;"><?php echo $rowth->jenis_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
							            <?php  
							              $kategori = $rowth->kode_kategori_belanja;
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
							            ?>
							            <tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
							              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
							            <?php  
							              $subkategori = $rowth->kode_sub_kategori_belanja;
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
							            ?>
							            <tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
							              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
							            <?php  
							              $kdbelanja = $rowth->kode_belanja;
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
							            ?>
							            <tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                </tr>
							            <?php  
							              $uraianbelanja = $rowth->uraian_upper;
							              $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							              $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							            ?>
							            <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
					                      </td>
					                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                    </tr>
					                    <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;">
					                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
					                      	</div>
					                      </td>
					                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
					                    </tr>
							          <?php endif ?>
							        <?php endforeach ?>
								</table>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- /.tab-tahun 2 -->
			<div class="tab-pane" id="tahun2">
				<table class="fcari">
					<tbody>
						<tr>
							<td width="22%">Lokasi Tahun 2</th>
							<td align="left"><?php echo $renstra->lokasi_2; ?></td>
						</tr>
						<tr>
							<td>Uraian Kegiatan Tahun 2</td>
							<td align="left"><?php echo $renstra->uraian_kegiatan_2; ?></td>
						</tr>
						<tr>
							<td width="22%">Nominal Tahun 2</td>
							<td>Rp. <?php echo Formatting::currency($renstra->nominal_2); ?></td>
						</tr>
						<tr>
							<table id="listbelanja_2">
								<?php 
						          $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
						          $idk_ng = $renstra->id;
						          $ta_ng = $th_anggaran[1]->tahun_anggaran;
						        ?>
						        <?php foreach ($belanja_2 as $key_rowth => $rowth): ?>
						          <?php if ($rowth->kode_jenis_belanja == $jenis): ?>
						            <?php if ($rowth->kode_kategori_belanja == $kategori): ?>
						              <?php if ($rowth->kode_sub_kategori_belanja == $subkategori): ?>
						                <?php if ($rowth->kode_belanja == $kdbelanja): ?>
						                  <?php if ($rowth->uraian_upper == $uraianbelanja): ?>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                  <?php else: ?>
						                    <?php 
						                      $uraianbelanja = $rowth->uraian_upper;
						                      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                    ?>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                  <?php endif ?>
						                <?php else: ?>
						                  <?php  
						                    $kdbelanja = $rowth->kode_belanja;
						                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						                  ?>
						                  <tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                  </tr>
						                  <?php  
						                    $uraianbelanja = $rowth->uraian_upper;
						                    $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                    $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                  ?>
						                  	<tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                <?php endif ?>
						              <?php else: ?>
						                <?php  
						                  $subkategori = $rowth->kode_sub_kategori_belanja;
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						                ?>
						                <tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
							              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						                <?php  
						                  $kdbelanja = $rowth->kode_belanja;
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						                ?>
						                <tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                </tr>
						                <?php  
						                  $uraianbelanja = $rowth->uraian_upper;
						                  $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                  $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                ?>
						                <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
					                      </td>
					                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                    </tr>
					                    <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;">
					                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
					                      	</div>
					                      </td>
					                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
					                    </tr>
						              <?php endif ?>
						            <?php else: ?>
						              <?php  
						                $kategori = $rowth->kode_kategori_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
						              ?>
						              	<tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
							              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						              <?php  
						                $subkategori = $rowth->kode_sub_kategori_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						              ?>
						              	<tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
							              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						              <?php  
						                $kdbelanja = $rowth->kode_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						              ?>
						              	<tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                </tr>
						              <?php  
						                $uraianbelanja = $rowth->uraian_upper;
						                $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						              ?>
						              	<tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
					                      </td>
					                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                    </tr>
					                    <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;">
					                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
					                      	</div>
					                      </td>
					                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
					                    </tr>
						            <?php endif ?>
						          <?php else: ?>
						            <?php  
						              $jenis = $rowth->kode_jenis_belanja; 
						              $jenisText = substr_replace($jenis,"", 0, -1);
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText; ?></td>
						              <td><div style="font-weight: bold;"><?php echo $rowth->jenis_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $kategori = $rowth->kode_kategori_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
						              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $subkategori = $rowth->kode_sub_kategori_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
						              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $kdbelanja = $rowth->kode_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						            ?>
						            <tr>
					                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
					                    <td>
					                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
					                    </td>
					                    <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                </tr>
						            <?php  
						              $uraianbelanja = $rowth->uraian_upper;
						              $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						              $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						            ?>
						            <tr>
				                      <td></td>
				                      <td>
				                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
				                      </td>
				                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
				                    </tr>
				                    <tr>
				                      <td></td>
				                      <td>
				                      	<div style="padding-left: 40px;">
				                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
				                      	</div>
				                      </td>
				                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
				                    </tr>
						          <?php endif ?>
						        <?php endforeach ?>
							</table>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- /.tab-tahun 3 -->
			<div class="tab-pane" id="tahun3">
				<table class="fcari">
					<tbody>
						<tr>
							<td width="22%">Lokasi Tahun 3</th>
							<td align="left"><?php echo $renstra->lokasi_3; ?></td>
						</tr>
						<tr>
							<td>Uraian Kegiatan Tahun 3</td>
							<td align="left"><?php echo $renstra->uraian_kegiatan_3; ?></td>
						</tr>
						<tr>
							<td width="22%">Nominal Tahun 3</td>
							<td>Rp. <?php echo Formatting::currency($renstra->nominal_3); ?></td>
						</tr>
						<tr>
							<table id="listbelanja_3">
									<?php 
							          $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
							          $idk_ng = $renstra->id;
							          $ta_ng = $th_anggaran[2]->tahun_anggaran;
							        ?>
							        <?php foreach ($belanja_3 as $key_rowth => $rowth): ?>
							          <?php if ($rowth->kode_jenis_belanja == $jenis): ?>
							            <?php if ($rowth->kode_kategori_belanja == $kategori): ?>
							              <?php if ($rowth->kode_sub_kategori_belanja == $subkategori): ?>
							                <?php if ($rowth->kode_belanja == $kdbelanja): ?>
							                  <?php if ($rowth->uraian_upper == $uraianbelanja): ?>
							                    <tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;">
							                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
							                      	</div>
							                      </td>
							                      <td align='right'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
							                    </tr>
							                  <?php else: ?>
							                    <?php 
							                      $uraianbelanja = $rowth->uraian_upper;
							                      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							                      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							                    ?>
							                    <tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
							                      </td>
							                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                    </tr>
							                    <tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;">
							                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
							                      	</div>
							                      </td>
							                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
							                    </tr>
							                  <?php endif ?>
							                <?php else: ?>
							                  <?php  
							                    $kdbelanja = $rowth->kode_belanja;
							                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
							                  ?>
							                  <tr>
							                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
							                    <td>
							                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
							                    </td>
							                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                  </tr>
							                  <?php  
							                    $uraianbelanja = $rowth->uraian_upper;
							                    $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							                    $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							                  ?>
							                  	<tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
							                      </td>
							                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                    </tr>
							                    <tr>
							                      <td></td>
							                      <td>
							                      	<div style="padding-left: 40px;">
							                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
							                      	</div>
							                      </td>
							                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
							                    </tr>
							                <?php endif ?>
							              <?php else: ?>
							                <?php  
							                  $subkategori = $rowth->kode_sub_kategori_belanja;
							                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
							                ?>
							                <tr>
								              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
								              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
								              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
								            </tr>
							                <?php  
							                  $kdbelanja = $rowth->kode_belanja;
							                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
							                ?>
							                <tr>
							                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
							                    <td>
							                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
							                    </td>
							                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                </tr>
							                <?php  
							                  $uraianbelanja = $rowth->uraian_upper;
							                  $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							                  $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							                ?>
							                <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
							              <?php endif ?>
							            <?php else: ?>
							              <?php  
							                $kategori = $rowth->kode_kategori_belanja;
							                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
							              ?>
							              	<tr>
								              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
								              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
								              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
								            </tr>
							              <?php  
							                $subkategori = $rowth->kode_sub_kategori_belanja;
							                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
							              ?>
							              	<tr>
								              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
								              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
								              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
								            </tr>
							              <?php  
							                $kdbelanja = $rowth->kode_belanja;
							                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
							              ?>
							              	<tr>
							                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
							                    <td>
							                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
							                    </td>
							                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							                </tr>
							              <?php  
							                $uraianbelanja = $rowth->uraian_upper;
							                $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							                $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							              ?>
							              	<tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
							            <?php endif ?>
							          <?php else: ?>
							            <?php  
							              $jenis = $rowth->kode_jenis_belanja; 
							              $jenisText = substr_replace($jenis,"", 0, -1);
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis'")->row();
							            ?>
							            <tr>
							              <td>5 . <?php echo $jenisText; ?></td>
							              <td><div style="font-weight: bold;"><?php echo $rowth->jenis_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
							            <?php  
							              $kategori = $rowth->kode_kategori_belanja;
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
							            ?>
							            <tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
							              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
							            <?php  
							              $subkategori = $rowth->kode_sub_kategori_belanja;
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
							            ?>
							            <tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
							              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
							            <?php  
							              $kdbelanja = $rowth->kode_belanja;
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
							            ?>
							            <tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                </tr>
							            <?php  
							              $uraianbelanja = $rowth->uraian_upper;
							              $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
							              $uraianbelanja2 = '"'.$uraianbelanja2.'"';
							              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
							            ?>
							            <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
					                      </td>
					                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                    </tr>
					                    <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;">
					                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
					                      	</div>
					                      </td>
					                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
					                    </tr>
							          <?php endif ?>
							        <?php endforeach ?>
								</table>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- /.tab-tahun 4 -->
			<div class="tab-pane" id="tahun4">
				<table class="fcari">
					<tbody>
						<tr>
							<td width="22%">Lokasi Tahun 4</th>
							<td align="left"><?php echo $renstra->lokasi_4; ?></td>
						</tr>
						<tr>
							<td>Uraian Kegiatan Tahun 4</td>
							<td align="left"><?php echo $renstra->uraian_kegiatan_4; ?></td>
						</tr>
						<tr>
							<td width="22%">Nominal Tahun 4</td>
							<td>Rp. <?php echo Formatting::currency($renstra->nominal_4); ?></td>
						</tr>
						<tr>
							<table id="listbelanja_4">
								<?php 
						          $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
						          $idk_ng = $renstra->id;
						          $ta_ng = $th_anggaran[3]->tahun_anggaran;
						        ?>
						        <?php foreach ($belanja_4 as $key_rowth => $rowth): ?>
						          <?php if ($rowth->kode_jenis_belanja == $jenis): ?>
						            <?php if ($rowth->kode_kategori_belanja == $kategori): ?>
						              <?php if ($rowth->kode_sub_kategori_belanja == $subkategori): ?>
						                <?php if ($rowth->kode_belanja == $kdbelanja): ?>
						                  <?php if ($rowth->uraian_upper == $uraianbelanja): ?>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                  <?php else: ?>
						                    <?php 
						                      $uraianbelanja = $rowth->uraian_upper;
						                      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                    ?>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                  <?php endif ?>
						                <?php else: ?>
						                  <?php  
						                    $kdbelanja = $rowth->kode_belanja;
						                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						                  ?>
						                  <tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                  </tr>
						                  <?php  
						                    $uraianbelanja = $rowth->uraian_upper;
						                    $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                    $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                  ?>
						                  	<tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                <?php endif ?>
						              <?php else: ?>
						                <?php  
						                  $subkategori = $rowth->kode_sub_kategori_belanja;
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						                ?>
						                <tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
							              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						                <?php  
						                  $kdbelanja = $rowth->kode_belanja;
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						                ?>
						                <tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                </tr>
						                <?php  
						                  $uraianbelanja = $rowth->uraian_upper;
						                  $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                  $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                ?>
						                <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
					                      </td>
					                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                    </tr>
					                    <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;">
					                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
					                      	</div>
					                      </td>
					                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
					                    </tr>
						              <?php endif ?>
						            <?php else: ?>
						              <?php  
						                $kategori = $rowth->kode_kategori_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
						              ?>
						              	<tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
							              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						              <?php  
						                $subkategori = $rowth->kode_sub_kategori_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						              ?>
						              	<tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
							              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						              <?php  
						                $kdbelanja = $rowth->kode_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						              ?>
						              	<tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                </tr>
						              <?php  
						                $uraianbelanja = $rowth->uraian_upper;
						                $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						              ?>
						              	<tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
					                      </td>
					                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                    </tr>
					                    <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;">
					                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
					                      	</div>
					                      </td>
					                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
					                    </tr>
						            <?php endif ?>
						          <?php else: ?>
						            <?php  
						              $jenis = $rowth->kode_jenis_belanja; 
						              $jenisText = substr_replace($jenis,"", 0, -1);
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText; ?></td>
						              <td><div style="font-weight: bold;"><?php echo $rowth->jenis_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $kategori = $rowth->kode_kategori_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
						              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $subkategori = $rowth->kode_sub_kategori_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
						              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $kdbelanja = $rowth->kode_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						            ?>
						            <tr>
					                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
					                    <td>
					                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
					                    </td>
					                    <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                </tr>
						            <?php  
						              $uraianbelanja = $rowth->uraian_upper;
						              $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						              $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						            ?>
						            <tr>
				                      <td></td>
				                      <td>
				                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
				                      </td>
				                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
				                    </tr>
				                    <tr>
				                      <td></td>
				                      <td>
				                      	<div style="padding-left: 40px;">
				                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
				                      	</div>
				                      </td>
				                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
				                    </tr>
						          <?php endif ?>
						        <?php endforeach ?>
							</table>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- /.tab-tahun 5 -->
			<div class="tab-pane" id="tahun5">
				<table class="fcari">
					<tbody>
						<tr>
							<td width="22%">Lokasi Tahun 5</th>
							<td align="left"><?php echo $renstra->lokasi_5; ?></td>
						</tr>
						<tr>
							<td>Uraian Kegiatan Tahun 5</td>
							<td align="left"><?php echo $renstra->uraian_kegiatan_5; ?></td>
						</tr>
						<tr>
							<td width="22%">Nominal Tahun 5</td>
							<td>Rp. <?php echo Formatting::currency($renstra->nominal_5); ?></td>
						</tr>
						<tr>
							<table id="listbelanja_5">
								<?php 
						          $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
						          $idk_ng = $renstra->id;
						          $ta_ng = $th_anggaran[4]->tahun_anggaran;
						        ?>
						        <?php foreach ($belanja_5 as $key_rowth => $rowth): ?>
						          <?php if ($rowth->kode_jenis_belanja == $jenis): ?>
						            <?php if ($rowth->kode_kategori_belanja == $kategori): ?>
						              <?php if ($rowth->kode_sub_kategori_belanja == $subkategori): ?>
						                <?php if ($rowth->kode_belanja == $kdbelanja): ?>
						                  <?php if ($rowth->uraian_upper == $uraianbelanja): ?>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                  <?php else: ?>
						                    <?php 
						                      $uraianbelanja = $rowth->uraian_upper;
						                      $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                      $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                      $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                    ?>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                  <?php endif ?>
						                <?php else: ?>
						                  <?php  
						                    $kdbelanja = $rowth->kode_belanja;
						                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						                  ?>
						                  <tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                  </tr>
						                  <?php  
						                    $uraianbelanja = $rowth->uraian_upper;
						                    $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                    $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                    $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                  ?>
						                  	<tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
						                      </td>
						                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                    </tr>
						                    <tr>
						                      <td></td>
						                      <td>
						                      	<div style="padding-left: 40px;">
						                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
						                      	</div>
						                      </td>
						                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
						                    </tr>
						                <?php endif ?>
						              <?php else: ?>
						                <?php  
						                  $subkategori = $rowth->kode_sub_kategori_belanja;
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						                ?>
						                <tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
							              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						                <?php  
						                  $kdbelanja = $rowth->kode_belanja;
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						                ?>
						                <tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                </tr>
						                <?php  
						                  $uraianbelanja = $rowth->uraian_upper;
						                  $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                  $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                  $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						                ?>
						                <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
					                      </td>
					                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                    </tr>
					                    <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;">
					                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
					                      	</div>
					                      </td>
					                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
					                    </tr>
						              <?php endif ?>
						            <?php else: ?>
						              <?php  
						                $kategori = $rowth->kode_kategori_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
						              ?>
						              	<tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
							              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						              <?php  
						                $subkategori = $rowth->kode_sub_kategori_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						              ?>
						              	<tr>
							              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
							              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
							              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
							            </tr>
						              <?php  
						                $kdbelanja = $rowth->kode_belanja;
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						              ?>
						              	<tr>
						                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
						                    <td>
						                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
						                    </td>
						                    <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						                </tr>
						              <?php  
						                $uraianbelanja = $rowth->uraian_upper;
						                $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						                $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						                $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						              ?>
						              	<tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
					                      </td>
					                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                    </tr>
					                    <tr>
					                      <td></td>
					                      <td>
					                      	<div style="padding-left: 40px;">
					                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
					                      	</div>
					                      </td>
					                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
					                    </tr>
						            <?php endif ?>
						          <?php else: ?>
						            <?php  
						              $jenis = $rowth->kode_jenis_belanja; 
						              $jenisText = substr_replace($jenis,"", 0, -1);
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText; ?></td>
						              <td><div style="font-weight: bold;"><?php echo $rowth->jenis_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $kategori = $rowth->kode_kategori_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText.' . '.$kategori; ?></td>
						              <td><div style="padding-left: 10px; font-weight: bold;"><?php echo $rowth->kategori_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $subkategori = $rowth->kode_sub_kategori_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori'")->row();
						            ?>
						            <tr>
						              <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori; ?></td>
						              <td><div style="padding-left: 20px;"><?php echo $rowth->sub_kategori_belanja; ?></div></td>
						              <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
						            </tr>
						            <?php  
						              $kdbelanja = $rowth->kode_belanja;
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja'")->row();
						            ?>
						            <tr>
					                    <td>5 . <?php echo $jenisText.' . '.$kategori.' . '.$subkategori.' . '.$kdbelanja; ?></td>
					                    <td>
					                    	<div style="padding-left: 30px;"><?php echo $rowth->belanja; ?></div>
					                    </td>
					                    <td align='right'><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
					                </tr>
						            <?php  
						              $uraianbelanja = $rowth->uraian_upper;
						              $uraianbelanja2 = str_replace('"', '\"', $rowth->uraian_belanja);
						              $uraianbelanja2 = '"'.$uraianbelanja2.'"';
						              $sum_tot = $this->db->query("SELECT sum(subtotal) as sumtot FROM t_renstra_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_kegiatan = '$idk_ng' AND kode_jenis_belanja = '$jenis' AND kode_kategori_belanja = '$kategori' AND kode_sub_kategori_belanja = '$subkategori' AND kode_belanja = '$kdbelanja' AND uraian_belanja = $uraianbelanja2")->row();
						            ?>
						            <tr>
				                      <td></td>
				                      <td>
				                      	<div style="padding-left: 40px;"><?php echo $rowth->uraian_belanja; ?></div>
				                      </td>
				                      <td align='right' style="padding-right:10px;"><?php echo Formatting::currency($sum_tot->sumtot, 2); ?></td>
				                    </tr>
				                    <tr>
				                      <td></td>
				                      <td>
				                      	<div style="padding-left: 40px;">
				                      		- <?php echo $rowth->detil_uraian_belanja.' : '.Formatting::currency($rowth->volume, 2).' '.$rowth->satuan.' x '.Formatting::currency($rowth->nominal_satuan, 2); ?>		
				                      	</div>
				                      </td>
				                      <td align='right' style='padding-right:10px;'><?php echo Formatting::currency($rowth->subtotal, 2); ?></td>
				                    </tr>
						          <?php endif ?>
						        <?php endforeach ?>
							</table>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
</div>
</div>
