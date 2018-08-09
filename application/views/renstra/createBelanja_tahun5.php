
<script type="text/javascript">

if( typeof helper == 'undefined' ) {
  var helper = { } ;
}
helper.arr = {
    multisort: function(arr, columns, order_by) {
        if(typeof columns == 'undefined') {
            columns = []
            for(x=0;x<arr[0].length;x++) {
                columns.push(x);
            }
        }
        if(typeof order_by == 'undefined') {
            order_by = []
            for(x=0;x<arr[0].length;x++) {
                order_by.push('ASC');
            }
        }
        function multisort_recursive(a,b,columns,order_by,index) {
            var direction = order_by[index] == 'DESC' ? 1 : 0;
            var is_numeric = !isNaN(+a[columns[index]] - +b[columns[index]]);
            var x = is_numeric ? +a[columns[index]] : a[columns[index]].toLowerCase();
            var y = is_numeric ? +b[columns[index]] : b[columns[index]].toLowerCase();
            if(x < y) {
                    return direction == 0 ? -1 : 1;
            }
            if(x == y)  {
                return columns.length-1 > index ? multisort_recursive(a,b,columns,order_by,index+1) : 0;
            }
            return direction == 0 ? 1 : -1;
        }
        return arr.sort(function (a,b) {
            return multisort_recursive(a,b,columns,order_by,0);
        });
    }
};

$(document).ready(function(){
  window.asdfghjkl=[] ;
  $('#nominal_satuan_5').autoNumeric(numOptionsNotRound);
  $('#volume_5').autoNumeric(numOptionsNotRound);
  $('#nominal_5').autoNumeric(numOptionsNotRound);

  jQuery.validator.addMethod("kode_autocomplete_5", function(value, element, params){
      if ($("input[name="+ params +"]").val()=="") {
        return false;
      }else{
        return true;
      }
  }, "Data tidak valid/belum di pilih, mohon pilih data setelah melakukan pencarian pada kolom ini.");
  <?php if(@$id_groups!='6'){
  ?>

  $("#nama_dewan").hide();
  <?php
  }
  ?>
  $("form#belanja_renstra").validate({
    rules: {
      kode_urusan_autocomplete_5 : {
        required : true,
        kode_autocomplete_5 : "kd_urusan_5"
      },
      kode_bidang_autocomplete_5 : {
        required : true,
        kode_autocomplete_5 : "kd_bidang_5"
      },
       kode_jenis_belanja_autocomplete_5 : {
        required : true,
        kode_autocomplete_5 : "kd_jenis_belanja_5"
      },
       kode_kategori_belanja_autocomplete_5 : {
        required : true,
        kode_autocomplete_5 : "kd_kategori_belanja_5"
      },
       kode_subkategori_belanja_autocomplete_5 : {
        required : true,
        kode_autocomplete_5 : "kd_subkategori_belanja_5"
      },
       kode_belanja_autocomplete_5 : {
        required : true,
        kode_autocomplete_5 : "kd_belanja_5"
      },
      kode_kegiatan_autocomplete_5 : {
        required : true,
        kode_autocomplete_5 : "kd_keg_5"
      },
      jenis_pekerjaan_5 : "required",
      volume : {
        required : true,
        number: true
      },
      satuan : "required",
      lokasi : "required"
    }
    });

//baru ----------------------------------->>>>>>>>>>>>>>>>>

prepare_chosen();
$(document).on("change", "#cb_jenis_belanja_5", function () {

  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_kategori_belanja_5"); ?>',
    data: {cb_jenis_belanja_5: $(this).val()},
    success: function(msg){
      $("#combo_kategori_5").html(msg);
      $("#cb_subkategori_belanja_5").val(" ");
      $("#cb_belanja_5").val(" ");
      $("#cb_subkategori_belanja_5").trigger("chosen:updated");
      $("#cb_belanja_5").trigger("chosen:updated");
      prepare_chosen();
    }
  });
});

$(document).on("change", "#cb_kategori_belanja_5", function () {
  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_subkategori_belanja_5"); ?>',
    data: {cb_jenis_belanja_5:$("#cb_jenis_belanja_5").val(), cb_kategori_belanja_5: $(this).val()},
    success: function(msg){
      $("#combo_subkategori_5").html(msg);
      $("#cb_belanja_5").val("");
      $("#cb_belanja_5").trigger("chosen:updated");
      prepare_chosen();
    }
  });
});

$(document).on("change", "#cb_subkategori_belanja_5", function () {
  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_belanja_5"); ?>',
    data: {cb_jenis_belanja_5:$("#cb_jenis_belanja_5").val(), cb_kategori_belanja_5:$("#cb_kategori_belanja_5").val(), cb_subkategori_belanja_5: $(this).val()},
    success: function(msg){
      $("#combo_belanja_5").html(msg);
      prepare_chosen();
    }
  });
});



  $("#kode_jenis_belanja_autocomplete_5").autocomplete({
      appendTo: "#autocomplete_element_jenis_belanja_5",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_jenis_belanja_5").val("");
          var s = $("#kode_jenis_belanja_autocomplete_5").val();

          $.ajax({
              url: "<?php echo base_url('common/autocomplete_kdjenisbelanja'); ?>",
              dataType: 'json',
              type: 'POST',
              data: {"term" : s},
              success:
              function(data){
                add(data);

              },
          });
      },
      select:
      function(event, ui) {
        $("#kd_jenis_belanja_5").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });


  $("#kode_kategori_belanja_autocomplete_5").autocomplete({
      appendTo: "#autocomplete_element_kategori_belanja_5",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_kategori_belanja_5").val("");
          var kdjenis = $("#kd_jenis_belanja_5").val();
          var s = $("#kode_kategori_belanja_autocomplete_5").val();


          $.ajax({
              url: "<?php echo base_url('common/autocomplete_kdkategoribelanja'); ?>",
              dataType: 'json',
              type: 'POST',
              data: {"kd_jenis_belanja": kdjenis,"term" : s},
              success:
              function(data){
                add(data);

              },
          });
      },
      select:
      function(event, ui) {
        $("#kd_kategori_belanja_5").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });

  $("#kode_subkategori_belanja_autocomplete_5").autocomplete({
      appendTo: "#autocomplete_element_subkategori_belanja_5",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_subkategori_belanja_5").val("");
          var kdjenis= $("#kd_jenis_belanja_5").val();
          var kdkategori = $("#kd_kategori_belanja_5").val();
          var s = $("#kode_subkategori_belanja_autocomplete_5").val();


          $.ajax({
              url: "<?php echo base_url('common/autocomplete_kdsubkategoribelanja'); ?>",
              dataType: 'json',
              type: 'POST',
              data: {"kd_jenis_belanja": kdjenis,"kd_kategori_belanja": kdkategori,"term" : s},
              success:
              function(data){
                add(data);

              },
          });
      },
      select:
      function(event, ui) {
        $("#kd_subkategori_belanja_5").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });

  $("#kode_belanja_autocomplete_5").autocomplete({
      appendTo: "#autocomplete_element_belanja_5",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_belanja_5").val("");
          var kdjenis= $("#kd_jenis_belanja_5").val();
          var kdkategori = $("#kd_kategori_belanja_5").val();
          var kdsubkategori = $("#kd_subkategori_belanja_5").val();

          var s = $("#kode_belanja_autocomplete_5").val();


          $.ajax({
              url: "<?php echo base_url('common/autocomplete_kdkodebelanja'); ?>",
              dataType: 'json',
              type: 'POST',
              data: {"kd_jenis_belanja": kdjenis,"kd_kategori_belanja": kdkategori,"kd_subkategori_belanja":kdsubkategori,"term" : s},
              success:
              function(data){
                add(data);

              },
          });
      },
      select:
      function(event, ui) {
        $("#kd_belanja_5").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });


  $("#kode_urusan_autocomplete_5").autocomplete({
      appendTo: "#autocomplete_element_urusan_5",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_urusan_5").val("");
          $.ajax({
              url: "<?php echo base_url('common/autocomplete_kdurusan'); ?>",
              dataType: 'json',
              type: 'POST',
              data: req,
              success:
              function(data){
                add(data);
              },
          });
      },
      select:
      function(event, ui) {
        $("#kd_urusan").val(ui.item.id);
      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });
});

</script>


<article class="module width_full">
 	<div class="module_content">

 			<input type="hidden" name="id_belanja_renstra_5"  id='id_belanja_renstra_5' value="<?php if(!empty($id_belanja_renstra_5)){echo $id_belanja_renstra_5;} ?>" />
			<tr>
				<td>&nbsp;&nbsp;Lokasi Tahun 5</td>
				<td>
					<textarea class="common" id="lokasi_5" name="lokasi_5"><?php echo (!empty($kegiatan->lokasi_5))?$kegiatan->lokasi_5:''; ?></textarea>
				</td>
			</tr>
      <table class="fcari" width="100%" style="display: none;">
        <tbody>
          <input type="hidden" id="inIndex_5" name="inIndex_5" value="1"/>
          <input type="hidden" id="isEdit_5" value="0"/>

											<textarea style="display: none;" class="common" id="uraian_kegiatan_5" name="uraian_kegiatan_5">-<?php echo (!empty($kegiatan->uraian_kegiatan_5))?'':''; ?></textarea>

          <tr>
              <td width="20%">Kelompok Belanja</td>
            	<td width="80%" id="combo_jenis_belanja_5">
                <?php echo $cb_jenis_belanja_5; ?>

              </td>
          </tr>
          <tr>
            	<td>Jenis  Belanja</td>
            	<td id="combo_kategori_5">
                <?php echo $cb_kategori_belanja_5; ?>

              </td>
          </tr>
          <tr>
          	<td>Obyek Belanja</td>
          	<td id="combo_subkategori_5">
          	    <?php echo $cb_subkategori_belanja_5; ?>
            </td>
          </tr>
          <tr >
          	<td>Rincian Obyek</td>
          	<td id="combo_belanja_5">
          		   <?php echo $cb_belanja_5; ?>
            </td>
          </tr>
					<tr>
          	<td>Rincian Belanja</td>
          	<td>
                  <input type="text" id="uraian_5" name="uraian_5" class="common" value="<?php if(!empty($uraian_5)){echo $uraian_5;} ?>" />
            </td>
          </tr>
          <tr >
          	<td>Sumber Dana </td>
          	<td id="combo_sumberdana_5">
              <?php echo form_dropdown('sumberdana_5', $sumber_dana, NULL, 'data-placeholder="Pilih Sumber Dana" class="common chosen-select" id="sumberdana_5" name="sumberdana_5"'); ?>
          		 <!-- <select id="sumberdana_5" name="sumberdana_5" class="common" >
          			  <option value="1"  >DAU/PAD</option>
                  <option value="2"  >DAU Infrastruktur</option>
                  <option value="3"  >DAK</option>
                  <option value="4"  >BKK Provisi</option>
                  <option value="5"  >BKK Badung</option>

		           </select> -->
          </tr>
          <tr>
          	<td>Sub Rincian Belanja</td>
          	<td>
                  <input type="text" id="det_uraian_5" name="det_uraian_5" class="common" value="<?php if(!empty($deturaian_5)){echo $deturaian_5;} ?>" />
            </td>
          </tr>
          <tr>
						<td>Volume</td>
						<td><input class="common" type="text" name="volume_5" id="volume_5" value="<?php if(!empty($volume_5)){echo $volume_5;} ?>"/></td>
					</tr>
					<tr>
          	<td>Satuan</td>
          	<td>
              <input class="common" type="text" name="satuan_5" id="satuan_5" />
          		<!-- <?php echo form_dropdown('satuan_5', $satuan, NULL, 'class="common " id="satuan_5" name="satuan_5"'); ?> -->
            </td>
          </tr>
          <tr>
            <td>
              Volume2 <input class="common" type="text" name="volume2_5" id="volume2_5"/>
              Satuan2 <input class="common" type="text" name="satuan2_5" id="satuan2_5"/>
            </td>
            <td>
              Volume3 <input class="common" type="text" name="volume3_5" id="volume3_5"/>
              Satuan3 <input class="common" type="text" name="satuan3_5" id="satuan3_5"/>
            </td>
          </tr>
          <tr>
						<td>Nominal Satuan</td>
						<td><input class="common" type="text" name="nominal_satuan_5" id="nominal_satuan_5" value="<?php if(!empty($nominal_satuan_5)){echo $nominal_satuan_5;} ?>"/></td>
					</tr>


 				</tbody>
 			</table>

 	</div>
 	<footer>

    <input type="hidden" id="id_belanja_5" value="">

    <div class="alert alert-warning alert-white rounded" id="cusAlert_5" role="alert" style="display:none;">
      <div class="icon">
          <i class="fa fa-warning"></i>
      </div>
      <font color="#d68000" size="4px"> <strong >Perhatian..!! </strong>
        <span id="pesan_5"></span>
      </font>
    </div>

		<div class="submit_link">
      <input type='button' id="ambilbelanjasebelumnya" onclick="copyrowng(5);" style="cursor:pointer;" value='+ Ambil Tahun -1'>
      <!-- <input type='button' id="tambahjnsbelanja" onclick="save_belanja_renstra(5, 'jns');" style="cursor:pointer;" value='+ Kelompok Belanja'>
      <input type='button'  id="tambahkatbelanja" onclick="save_belanja_renstra(5,'kat');" style="cursor:pointer;" value='+ Jenis Belanja'>
      <input type='button'  id="tambahsubkatbelanja" onclick="save_belanja_renstra(5,'subkat');" style="cursor:pointer;" value='+ Obyek Belanja'>
      <input type='button'  id="tambahbelanja" onclick="save_belanja_renstra(5,'belanja');" style="cursor:pointer;" value='+ Rincian Obyek'>
      <input type='button'  id="tambahuraian" onclick="save_belanja_renstra(5,'uraian');" style="cursor:pointer;" value='+ Rincian Belanja'>
      <input type='button'  id="tambahdeturaian" onclick="save_belanja_renstra(5,'deturaian');" style="cursor:pointer;" value='+ Sub Rincian Belanja'> -->
      <!-- <input type='button'  id="tambahdeturaian" onclick="save_belanja_renstra(5,'deturaian');" style="cursor:pointer;" value='Tambah Belanja'> -->


		</div>
		
  <tr>
    <td>Nominal Tahun 5 (Rp.)</td>
    <td><input readonly="readonly" type="text" id="nominal_5" name="nominal_5" value="<?php if(!empty($kegiatan->nominal_5)){echo $kegiatan->nominal_5;} ?>"/></td>
  </tr>
  
<br>
  <div class="row">
    <div class="col-md-12" style="margin-bottom: 15px;">
      <b id="text_lihat_th5"></b>
    </div>
    <div class="col-md-2">
      <button type="button" class="col-md-12 btn custom" id="btn_lihat1_th5" onclick='select_lihat1("5", true, "5.2")'>Jenis Belanja</button>
      <button type="button" class="col-md-12 btn custom" id="btn_lihat2_th5" disabled>Obyek Belanja</button>
      <button type="button" class="col-md-12 btn custom" id="btn_lihat3_th5" disabled>Rincian Obyek</button>
      <button type="button" class="col-md-12 btn custom" id="btn_lihat4_th5" disabled>Rincian Belanja</button>
    </div>
    <div class="col-md-10" style="border: 1px solid #ddd; background-color: #f9f9f9; min-height: 150px;" id="box_lihat_th5">
      <?php if (!empty($detil_kegiatan_th5)): ?>
        <?php foreach ($detil_kegiatan_th5 as $key => $row): ?>
          <?php if (!empty($row->kode_sumber_dana)): ?>
            <button type="button" class="custom2" style="margin: 5px 0px 5px 0px !important; text-align: left !important;" onclick="select_lihat2('5', false, '5.2', '<?php echo $row->kode_kategori_belanja ?>')"><?php echo $row->kode_kategori_belanja.". ".$row->kategori_belanja; ?></button><br>
          <?php endif ?>
        <?php endforeach ?>
      <?php endif ?>
    </div>
  </div>

	</footer>

  <p>
  <p>

</article>

<script type="text/javascript">
  $(document).ready(function() {
    // $('#btn_lihat1_th1').trigger('click');
    select_lihat1('5', false, '5.2')
  });

  function ubahrowng_5(id_belanja){
    var tahun = 5;
    var check = $('#id_belanja_'+tahun).val();
    var id_kegiatan = $('input[name="id_kegiatan"]').val();

    if (check == '' || check == null) {
      $('#id_belanja_'+tahun).val(id_belanja);

      $.ajax({
        type: "POST",
        url: '<?php echo site_url("renstra/belanja_kegiatan_edit"); ?>',
        dataType: 'json',
        data: {
          id_kegiatan : id_kegiatan,
          id_belanja : id_belanja,
          tahun : tahun
        },
        success: function(msg){
          select_lihat5('5', 666, msg.edit.kode_jenis_belanja, msg.edit.kode_kategori_belanja, msg.edit.kode_sub_kategori_belanja, msg.edit.kode_belanja, msg.edit.uraian_belanja, id_belanja);

          var total = 0.00;
          for (var i = 0; i < msg.list.length; i++) {
            total = parseFloat(total) + parseFloat(msg.list[i].subtotal);
          }
          
          var jenis = msg.edit.kode_jenis_belanja;
          var kategori = msg.edit.kode_kategori_belanja;
          var sub = msg.edit.kode_sub_kategori_belanja;
          var belanja = msg.edit.kode_belanja;
          var sumber_dana = msg.edit.kode_sumber_dana;
          setTimeout(function(){ 
            sumber_dananya_1("lihat5_sumberdana_th5", sumber_dana, 'lihat5_sumberdana_th5');
            $("#lihat5_subrincian_th5").val(msg.edit.detil_uraian_belanja);
            $("#lihat5_vol1_th5").val(msg.edit.volume);
            $("#lihat5_satuan1_th5").val(msg.edit.satuan);
            $("#lihat5_vol2_th5").val(msg.edit.volume_2);
            $("#lihat5_satuan2_th5").val(msg.edit.satuan_2);
            $("#lihat5_vol3_th5").val(msg.edit.volume_3);
            $("#lihat5_satuan3_th5").val(msg.edit.satuan_3);
            $("#lihat5_nominalsatuan_th5").val(msg.edit.nominal_satuan);
          }, 2500);

          jenis_belanjanya_5("cb_jenis_belanja_5", jenis);
          kategori_belanjanya_5("cb_kategori_belanja_5", jenis, kategori);
          sub_belanjanya_5("cb_subkategori_belanja_5", jenis, kategori, sub);
          belanja_belanjanya_5("cb_belanja_5", jenis, kategori, sub, belanja);
          sumber_dananya_5("sumberdana_5", sumber_dana);
          $('#uraian_5').val(msg.edit.uraian_belanja);
          $('#det_uraian_5').val(msg.edit.detil_uraian_belanja);
          $('#volume_5').autoNumeric('set', msg.edit.volume);
          $('#satuan_5').val(msg.edit.satuan);
          $('#volume2_5').val(msg.edit.volume_2);
          $('#satuan2_5').val(msg.edit.satuan_2);
          $('#volume3_5').val(msg.edit.volume_3);
          $('#satuan3_5').val(msg.edit.satuan_3);
          $('#nominal_satuan_5').autoNumeric('set', msg.edit.nominal_satuan);

          $('#nominal_5').autoNumeric('set', total);
        }
      });
    }
  }

  function hapusrowng_5(id_belanja){
    var tahun = 5;
    var id_kegiatan = $('input[name="id_kegiatan"]').val();

    $.ajax({
        type: "POST",
        url: '<?php echo site_url("renstra/belanja_kegiatan_hapus"); ?>',
        dataType: 'json',
        data: {
          id_kegiatan : id_kegiatan,
          id_belanja : id_belanja,
          tahun : tahun
        },
        success: function(msg){
          select_lihat5('5', false, msg.edit.kode_jenis_belanja, msg.edit.kode_kategori_belanja, msg.edit.kode_sub_kategori_belanja, msg.edit.kode_belanja, msg.edit.uraian_belanja);

          var total = 0.00;
          for (var i = 0; i < msg.list.length; i++) {
            total = parseFloat(total) + parseFloat(msg.list[i].subtotal);
          }
          $('#nominal_5').autoNumeric('set', total);
        }
    });
  }
</script>
<script src="<?php echo base_url('assets/renstra/createbelanja_tahun5.js');?>"></script>
<script src="<?php echo base_url('assets/renstra/custom-alert.js');?>"></script>
<link href="<?php echo base_url('assets/renstra/custom-alert.css') ?>" rel="stylesheet" type="text/css" />

<script>
  function errorMessage_5(clue) {
    var lokasi = $('#lokasi_5').val();
    var uraian_kegiatan = $('#uraian_kegiatan_5').val();
    var jenis_belanja = $('#cb_jenis_belanja_5').val();
    var kategori_belanja = $('#cb_kategori_belanja_5').val();
    var subkategori_belanja = $('#cb_subkategori_belanja_5').val();
    var kode_belanja = $('#cb_belanja_5').val();
    var uraian = $('#uraian_5').val();
    var det_uraian = $('#det_uraian_5').val();
    var volume = $('#volume_5').val();
    var satuan = $('#satuan_5').val();
    var nominal = $('#nominal_satuan_5').val();
    var sumberdana = $('#sumberdana_5').val();
    eliminationName(lokasi, uraian_kegiatan, jenis_belanja, kategori_belanja, subkategori_belanja, kode_belanja, uraian, det_uraian, volume, satuan, nominal, sumberdana, clue, '#cusAlert_5', 'pesan_5');

  }


  function jenis_belanjanya_5(p_nama, p_jenis) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_jenis_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis},
      success: function(msg){
        $("#combo_jenis_belanja_5").html(msg);
        prepare_chosen();
      }
    });
  }
  function kategori_belanjanya_5(p_nama, p_jenis, p_kategori) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_kategori_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori},
      success: function(msg){
        $("#combo_kategori_5").html(msg);
        prepare_chosen();
      }
    });
  }
  function sub_belanjanya_5(p_nama, p_jenis, p_kategori, p_sub) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_sub_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub},
      success: function(msg){
        $("#combo_subkategori_5").html(msg);
        prepare_chosen();
      }
    });
  }
  function belanja_belanjanya_5(p_nama, p_jenis, p_kategori, p_sub, p_belanja) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_belanja_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub, belanja: p_belanja},
      success: function(msg){
        $("#combo_belanja_5").html(msg);
        prepare_chosen();
      }
    });
  }

  function sumber_dananya_5(p_nama, p_id) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_sumber_dana"); ?>',
      data: {nama: p_nama, id: p_id},
      success: function(msg){
        $("#combo_sumberdana_5").html(msg);
        prepare_chosen();
      }
    });
  }

  // function hapusrowng_5(id_belanja){
  //   var tahun = 5;
  //   var id_kegiatan = $('input[name="id_kegiatan"]').val();

  //   $.ajax({
  //         type: "POST",
  //         url: '<?php //echo site_url("renstra/belanja_kegiatan_hapus"); ?>',
  //         dataType: 'json',
  //         data: {
  //         id_kegiatan : id_kegiatan,
  //         id_belanja : id_belanja,
  //         tahun : tahun
  //         },
  //         success: function(msg){
  //           $('#list_tahun_'+tahun).html('');
  //           var no = 1;
  //           var total = 0;
  //           for (var i = 0; i < msg.list.length; i++) {
              
  //             var row = '<tr>';
  //             row += '<td>'+no+'</td>';
  //             row += '<td>'+msg.list[i].kode_jenis_belanja+'. '+msg.list[i].jenis_belanja+'</td>';
  //             row += '<td>'+msg.list[i].kode_kategori_belanja+'. '+msg.list[i].kategori_belanja+'</td>';
  //             row += '<td>'+msg.list[i].kode_sub_kategori_belanja+'. '+msg.list[i].sub_kategori_belanja+'</td>';
  //             row += '<td>'+msg.list[i].kode_belanja+'. '+msg.list[i].belanja+'</td>';
  //             row += '<td>'+msg.list[i].uraian_belanja+'</td>';
  //             row += '<td>'+msg.list[i].Sumber_dana+'</td>';
  //             row += '<td>'+msg.list[i].detil_uraian_belanja+'</td>';
  //             row += '<td>'+float_to_num(msg.list[i].volume)+'</td>';
  //             row += '<td>'+msg.list[i].satuan+'</td>';
  //             row += '<td>'+float_to_num(msg.list[i].nominal_satuan)+'</td>';
  //             row += '<td>'+float_to_num(msg.list[i].subtotal)+'</td>';
  //             row += "<td><span id='ubahrowng' class='icon-pencil' onclick='ubahrowng_5("+msg.list[i].id+")' style='cursor:pointer' title='Ubah Belanja'></span></td>";
  //             row += "<td><span id='hapusrowng' class='icon-remove' onclick='hapusrowng_5("+msg.list[i].id+")' style='cursor:pointer' title='Hapus Belanja'></span></td>";
  //             row += '</tr>';
  //             $('#list_tahun_'+tahun).append(row);
  //             no++;
  //             total += parseFloat(msg.list[i].subtotal);
  //           }
  //           $('#nominal_5').autoNumeric('set', total);
  //         }
  //     });

  // }

  // function ubahrowng_5(id_belanja){
  //   var tahun = 5;
  //   var check = $('#id_belanja_'+tahun).val();
  //   var id_kegiatan = $('input[name="id_kegiatan"]').val();

  //   if (check == '' || check == null) {
  //     $('#id_belanja_'+tahun).val(id_belanja);

  //     $.ajax({
  //         type: "POST",
  //         url: '<?php //echo site_url("renstra/belanja_kegiatan_edit"); ?>',
  //         dataType: 'json',
  //         data: {
  //         id_kegiatan : id_kegiatan,
  //         id_belanja : id_belanja,
  //         tahun : tahun
  //         },
  //         success: function(msg){
  //           $('#list_tahun_'+tahun).html('');
            
  //           var no = 1;
  //           var total = 0;
  //           for (var i = 0; i < msg.list.length; i++) {
              
  //             var row = '<tr>';
  //             row += '<td>'+no+'</td>';
  //             row += '<td>'+msg.list[i].kode_jenis_belanja+'. '+msg.list[i].jenis_belanja+'</td>';
  //             row += '<td>'+msg.list[i].kode_kategori_belanja+'. '+msg.list[i].kategori_belanja+'</td>';
  //             row += '<td>'+msg.list[i].kode_sub_kategori_belanja+'. '+msg.list[i].sub_kategori_belanja+'</td>';
  //             row += '<td>'+msg.list[i].kode_belanja+'. '+msg.list[i].belanja+'</td>';
  //             row += '<td>'+msg.list[i].uraian_belanja+'</td>';
  //             row += '<td>'+msg.list[i].Sumber_dana+'</td>';
  //             row += '<td>'+msg.list[i].detil_uraian_belanja+'</td>';
  //             row += '<td>'+float_to_num(msg.list[i].volume)+'</td>';
  //             row += '<td>'+msg.list[i].satuan+'</td>';
  //             row += '<td>'+float_to_num(msg.list[i].nominal_satuan)+'</td>';
  //             row += '<td>'+float_to_num(msg.list[i].subtotal)+'</td>';
  //             row += "<td><span id='ubahrowng' class='icon-pencil' onclick='ubahrowng_5("+msg.list[i].id+")' style='cursor:pointer' title='Ubah Belanja'></span></td>";
  //             row += "<td><span id='hapusrowng' class='icon-remove' onclick='hapusrowng_5("+msg.list[i].id+")' style='cursor:pointer' title='Hapus Belanja'></span></td>";
  //             row += '</tr>';
  //             $('#list_tahun_'+tahun).append(row);
  //             no++;
  //             total += parseFloat(msg.list[i].subtotal);
  //           }
  //           var jenis = msg.edit.kode_jenis_belanja;
  //           var kategori = msg.edit.kode_kategori_belanja;
  //           var sub = msg.edit.kode_sub_kategori_belanja;
  //           var belanja = msg.edit.kode_belanja;
  //           var sumber_dana = msg.edit.kode_sumber_dana;
  //           jenis_belanjanya_5("cb_jenis_belanja_5", jenis);
  //           kategori_belanjanya_5("cb_kategori_belanja_5", jenis, kategori);
  //           sub_belanjanya_5("cb_subkategori_belanja_5", jenis, kategori, sub);
  //           belanja_belanjanya_5("cb_belanja_5", jenis, kategori, sub, belanja);
  //           sumber_dananya_5("sumberdana_5", sumber_dana);
  //           $('#uraian_5').val(msg.edit.uraian_belanja);
  //           $('#det_uraian_5').val(msg.edit.detil_uraian_belanja);
  //           $('#volume_5').autoNumeric('set', msg.edit.volume);
  //           $('#satuan_5').val(msg.edit.satuan);
  //           $('#nominal_satuan_5').autoNumeric('set', msg.edit.nominal_satuan);

  //           $('#nominal_5').autoNumeric('set', total);
  //         }
  //     });

      
  //   }
  // }

</script>
