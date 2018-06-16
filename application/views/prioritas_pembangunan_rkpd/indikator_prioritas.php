<?php $pagu = 0; ?>
<table>
  <tr>
    <td width="10px"><b>Indikator</b></td>
    <td>
      <table>
        <?php if (!empty($indikator->result())){
			$pagu = $prog_keg->pagu;
          foreach ($indikator->result() as $row) {
          ?>
          <tr>
            <td colspan="7"><?php echo $row->indikator ?></td>
          </tr>
          <tr>
            <td><b>Target</b></td>
            <td colspan="6"><?php echo $row->target.' '.$row->satuan_target ?></td>
          </tr>
          <tr>
            <td><b>Kategori Indikator</b></td>
            <td colspan="3"><?php echo $row->status_nya ?></td>
            <td colspan="3"><?php echo $row->kategori_nya ?></td>
          </tr>
          <tr>
            <td colspan="7"  style="height:1px;"></td>
          </tr>
        <?php }}else{echo "Tidak ada data.";} ?>
      </table>
    </td>
  </tr>
</table>
<?php if (!empty($pagu)): ?>
	Pagu : Rp. <?php Formatting::currency($pagu); ?>
<?php endif ?>
