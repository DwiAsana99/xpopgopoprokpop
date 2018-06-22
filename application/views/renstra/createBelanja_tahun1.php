
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
  $('#nominal_satuan_1').autoNumeric(numOptionsNotRound);
  $('#volume_1').autoNumeric(numOptionsNotRound);
  $('#nominal_1').autoNumeric(numOptionsNotRound);


  jQuery.validator.addMethod("kode_autocomplete_1", function(value, element, params){
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
      kode_urusan_autocomplete_1 : {
        required : true,
        kode_autocomplete_1 : "kd_urusan_1"
      },
      kode_bidang_autocomplete_1 : {
        required : true,
        kode_autocomplete_1 : "kd_bidang_1"
      },
       kode_jenis_belanja_autocomplete_1 : {
        required : true,
        kode_autocomplete_1 : "kd_jenis_belanja_1"
      },
       kode_kategori_belanja_autocomplete_1 : {
        required : true,
        kode_autocomplete_1 : "kd_kategori_belanja_1"
      },
       kode_subkategori_belanja_autocomplete_1 : {
        required : true,
        kode_autocomplete_1 : "kd_subkategori_belanja_1"
      },
       kode_belanja_autocomplete_1 : {
        required : true,
        kode_autocomplete_1 : "kd_belanja_1"
      },
      kode_kegiatan_autocomplete_1 : {
        required : true,
        kode_autocomplete_1 : "kd_keg_1"
      },
      jenis_pekerjaan_1 : "required",
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
$(document).on("change", "#cb_jenis_belanja_1", function () {

  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_kategori_belanja_1"); ?>',
    data: {cb_jenis_belanja_1: $(this).val()},
    success: function(msg){
      $("#combo_kategori_1").html(msg);
      $("#cb_subkategori_belanja_1").val(" ");
      $("#cb_belanja_1").val(" ");
      $("#cb_subkategori_belanja_1").trigger("chosen:updated");
      $("#cb_belanja_1").trigger("chosen:updated");
      prepare_chosen();
    }
  });
});

$(document).on("change", "#cb_kategori_belanja_1", function () {
  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_subkategori_belanja_1"); ?>',
    data: {cb_jenis_belanja_1:$("#cb_jenis_belanja_1").val(), cb_kategori_belanja_1: $(this).val()},
    success: function(msg){
      $("#combo_subkategori_1").html(msg);
      $("#cb_belanja_1").val("");
      $("#cb_belanja_1").trigger("chosen:updated");
      prepare_chosen();
    }
  });
});

$(document).on("change", "#cb_subkategori_belanja_1", function () {
  $.ajax({
    type: "POST",
    url: '<?php echo site_url("common/cmb_belanja_1"); ?>',
    data: {cb_jenis_belanja_1:$("#cb_jenis_belanja_1").val(), cb_kategori_belanja_1:$("#cb_kategori_belanja_1").val(), cb_subkategori_belanja_1: $(this).val()},
    success: function(msg){
      $("#combo_belanja_1").html(msg);
      prepare_chosen();
    }
  });
});

$(document).on("change", "#sumberdana_1", function () {
  prepare_chosen();
});


  $("#kode_jenis_belanja_autocomplete_1").autocomplete({
      appendTo: "#autocomplete_element_jenis_belanja_1",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_jenis_belanja_1").val("");
          var s = $("#kode_jenis_belanja_autocomplete_1").val();

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
        $("#kd_jenis_belanja_1").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });


  $("#kode_kategori_belanja_autocomplete_1").autocomplete({
      appendTo: "#autocomplete_element_kategori_belanja_1",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_kategori_belanja_1").val("");
          var kdjenis = $("#kd_jenis_belanja_1").val();
          var s = $("#kode_kategori_belanja_autocomplete_1").val();


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
        $("#kd_kategori_belanja_1").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });

  $("#kode_subkategori_belanja_autocomplete_1").autocomplete({
      appendTo: "#autocomplete_element_subkategori_belanja_1",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_subkategori_belanja_1").val("");
          var kdjenis= $("#kd_jenis_belanja_1").val();
          var kdkategori = $("#kd_kategori_belanja_1").val();
          var s = $("#kode_subkategori_belanja_autocomplete_1").val();


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
        $("#kd_subkategori_belanja_1").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });

  $("#kode_belanja_autocomplete_1").autocomplete({
      appendTo: "#autocomplete_element_belanja_1",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_belanja_1").val("");
          var kdjenis= $("#kd_jenis_belanja_1").val();
          var kdkategori = $("#kd_kategori_belanja_1").val();
          var kdsubkategori = $("#kd_subkategori_belanja_1").val();

          var s = $("#kode_belanja_autocomplete_1").val();


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
        $("#kd_belanja_1").val(ui.item.id);
    //console.log($("#id_groups").val());

      }
    }).focus(function(){
        $(this).trigger('keydown.autocomplete');
    });


  $("#kode_urusan_autocomplete_1").autocomplete({
      appendTo: "#autocomplete_element_urusan_1",
      minLength: 0,
      source:
      function(req, add){
          $("#kd_urusan_1").val("");
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

 			<input type="hidden" name="id_belanja_renstra_1"  id='id_belanja_renstra_1' value="<?php if(!empty($id_belanja_renstra_1)){echo $id_belanja_renstra_1;} ?>" />
 			<table class="fcari" width="100%">
 				<tbody>
          <input type="hidden" id="inIndex_1" name="inIndex_1" value="1"/>
          <input type="hidden" id="isEdit_1" value="0"/>
                  <tr>
                    <td>&nbsp;&nbsp;Lokasi Tahun 1</td>
                    <td>
                      <textarea class="common" id="lokasi_1" name="lokasi_1" onchange="<?php ?>"><?php echo (!empty($kegiatan->lokasi_1))?$kegiatan->lokasi_1:''; ?></textarea>
                    </td>
                  </tr>

						<textarea style="display: none;" class="common" id="uraian_kegiatan_1" name="uraian_kegiatan_1">-<?php echo (!empty($kegiatan->uraian_kegiatan_1))?'':''; ?></textarea>

          <tr>
              <td width="20%">Kelompok Belanja</td>
            	<td width="80%" id="combo_jenis_belanja_1">
                <?php echo $cb_jenis_belanja_1; ?>

              </td>
          </tr>
          <tr>
            	<td>Jenis Belanja</td>
            	<td id="combo_kategori_1">
                <?php echo $cb_kategori_belanja_1; ?>

              </td>
          </tr>
          <tr>
          	<td>Obyek Belanja</td>
          	<td id="combo_subkategori_1">
          	    <?php echo $cb_subkategori_belanja_1; ?>
            </td>
          </tr>
          <tr >
          	<td>Rincian Obyek</td>
          	<td id="combo_belanja_1">
          		   <?php echo $cb_belanja_1; ?>
            </td>
          </tr>
					<tr>
          	<td>Rincian Belanja</td>
          	<td>
                  <input type="text" id="uraian_1" name="uraian_1" class="common" value="<?php if(!empty($uraian_1)){echo $uraian_1;} ?>" />
            </td>
          </tr>
          <tr >
          	<td>Sumber Dana </td>
          	<td id="combo_sumberdana_1">
              <?php echo form_dropdown('sumberdana_1', $sumber_dana, NULL, 'data-placeholder="Pilih Sumber Dana" class="common chosen-select" id="sumberdana_1" name="sumberdana_1"'); ?>
          		 <!-- <select id="sumberdana_1" name="sumberdana_1" class="common" >
          			  <option value="1"  >DAU/PAD</option>
                  <option value="2"  >DAU Infrastruktur</option>
                  <option value="3"  >DAK</option>
                  <option value="4"  >BKK Provisi</option>
                  <option value="5"  >BKK Badung</option>

		           </select> -->
             </td>
          </tr>
          <tr>
          	<td>Sub Rincian Belanja</td>
          	<td>
                  <input type="text" id="det_uraian_1" name="det_uraian_1" class="common" value="<?php if(!empty($deturaian_1)){echo $deturaian_1;} ?>" />
            </td>
          </tr>
          <tr>
						<td>Volume</td>
						<td><input class="common" type="text" name="volume_1" id="volume_1" value="<?php if(!empty($volume_1)){echo $volume_1;} ?>"/></td>
					</tr>
					<tr>
          	<td>Satuan</td>
          	<td>
              <!-- <?php echo form_dropdown('satuan_1', $satuan, NULL, 'class="common" id="satuan_1" name="satuan_1"'); ?> -->
              <input class="common" type="text" name="satuan_1" id="satuan_1" />
            </td>
          </tr>
          <tr>
						<td>Nominal Satuan</td>
						<td><input class="common" type="text" name="nominal_satuan_1" id="nominal_satuan_1" value="<?php if(!empty($nominal_satuan_1)){echo $nominal_satuan_1;} ?>"/></td>
					</tr>


 				</tbody>
 			</table>

 	</div>
 	<footer>

    <input type="hidden" id="id_belanja_1" value="">

    <div class="alert alert-warning alert-white rounded" id="cusAlert_1" role="alert" style="display:none;">
      <div class="icon">
          <i class="fa fa-warning"></i>
      </div>
      <font color="#d68000" size="4px"> <strong >Perhatian..!! </strong>
        <span id="pesan_1"></span>
      </font>
    </div>

		<div class="submit_link">
      <input type='button' id="tambahjnsbelanja" onclick="save_belanja_renstra(1, 'jns');" style="cursor:pointer;" value="+ Kelompok Belanja">
      <input type='button'  id="tambahkatbelanja" onclick="save_belanja_renstra(1, 'kat');" style="cursor:pointer;" value='+ Jenis Belanja'>
      <input type='button'  id="tambahsubkatbelanja" onclick="save_belanja_renstra(1, 'subkat');" style="cursor:pointer;" value='+ Obyek Belanja'>
      <input type='button'  id="tambahbelanja" onclick="save_belanja_renstra(1, 'belanja');" style="cursor:pointer;" value='+ Rincian Obyek'>
      <input type='button'  id="tambahuraian" onclick="save_belanja_renstra(1, 'uraian');" style="cursor:pointer;" value='+ Rincian Belanja'>
      <input type='button'  id="tambahdeturaian" onclick="save_belanja_renstra(1, 'deturaian');" style="cursor:pointer;" value='+ Sub Rincian Belanja'>

		</div>
		
		<tr>
			<td>Nominal Tahun 1 (Rp.)</td>
			<td><input readonly="readonly" type="text" id="nominal_1" name="nominal_1" value="<?php if(!empty($kegiatan->nominal_1)){echo $kegiatan->nominal_1;} ?>"/></td>
		</tr>
		
		
<br>
  <div class="row">
    <div class="col-md-12" style="margin-bottom: 15px;">
      <b id="text_lihat_th1"></b>
    </div>
    <div class="col-md-3">
      <button type="button" class="col-md-12 custom" id="btn_lihat1_th1" onclick='select_lihat1("1", true, "5.2")'>Jenis Belanja</button>
      <button type="button" class="col-md-12 custom" id="btn_lihat2_th1" disabled>Obyek Belanja</button>
      <button type="button" class="col-md-12 custom" id="btn_lihat3_th1" disabled>Rincian Obyek</button>
      <button type="button" class="col-md-12 custom" id="btn_lihat4_th1" disabled>Rincian Belanja</button>
    </div>
    <div class="col-md-9" style="border: 1px solid #ddd; background-color: #f9f9f9; min-height: 150px;" id="box_lihat_th1">
      <?php if (!empty($detil_kegiatan_th1)): ?>
        <?php foreach ($detil_kegiatan_th1 as $key => $row): ?>
          <?php if (!empty($row->kode_sumber_dana)): ?>
            <button type="button" class="custom2" style="margin: 5px 0px 5px 0px !important; text-align: left !important;" onclick="select_lihat2('1', false, '5.2', '<?php echo $row->kode_kategori_belanja ?>')"><?php echo $row->kode_kategori_belanja.". ".$row->kategori_belanja; ?></button><br>
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
    select_lihat1('1', false, '5.2')
  });

  function ubahrowng_1(id_belanja){
    var tahun = 1;
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
          select_lihat5('1', 666, msg.edit.kode_jenis_belanja, msg.edit.kode_kategori_belanja, msg.edit.kode_sub_kategori_belanja, msg.edit.kode_belanja, msg.edit.uraian_belanja, id_belanja);

          var total = 0.00;
          for (var i = 0; i < msg.list.length; i++) {
            total = parseFloat(total) + parseFloat(msg.list[i].subtotal);
          }
          
          var jenis = msg.edit.kode_jenis_belanja;
          var kategori = msg.edit.kode_kategori_belanja;
          var sub = msg.edit.kode_sub_kategori_belanja;
          var belanja = msg.edit.kode_belanja;
          var sumber_dana = msg.edit.kode_sumber_dana;
          jenis_belanjanya_1("cb_jenis_belanja_1", jenis);
          kategori_belanjanya_1("cb_kategori_belanja_1", jenis, kategori);
          sub_belanjanya_1("cb_subkategori_belanja_1", jenis, kategori, sub);
          belanja_belanjanya_1("cb_belanja_1", jenis, kategori, sub, belanja);
          sumber_dananya_1("sumberdana_1", sumber_dana);
          $('#uraian_1').val(msg.edit.uraian_belanja);
          $('#det_uraian_1').val(msg.edit.detil_uraian_belanja);
          $('#volume_1').autoNumeric('set', msg.edit.volume);
          $('#satuan_1').val(msg.edit.satuan);
          $('#nominal_satuan_1').autoNumeric('set', msg.edit.nominal_satuan);
          $('#nominal_1').autoNumeric('set', total);
        }
      });
    }
  }

  function hapusrowng_1(id_belanja){
    var tahun = 1;
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
          select_lihat5('1', false, msg.edit.kode_jenis_belanja, msg.edit.kode_kategori_belanja, msg.edit.kode_sub_kategori_belanja, msg.edit.kode_belanja, msg.edit.uraian_belanja);

          var total = 0.00;
          for (var i = 0; i < msg.list.length; i++) {
            total = parseFloat(total) + parseFloat(msg.list[i].subtotal);
          }
          $('#nominal_1').autoNumeric('set', total);
        }
    });
  }
</script>


<script src="<?php echo base_url('assets/renstra/createbelanja_tahun1.js');?>"></script>
<script src="<?php echo base_url('assets/renstra/custom-alert.js');?>"></script>
<link href="<?php echo base_url('assets/renstra/custom-alert.css') ?>" rel="stylesheet" type="text/css" />

<script>
  function errorMessage_1(clue) {
    var lokasi = $('#lokasi_1').val();
    var uraian_kegiatan = $('#uraian_kegiatan_1').val();
    var jenis_belanja = $('#cb_jenis_belanja_1').val();
    var kategori_belanja = $('#cb_kategori_belanja_1').val();
    var subkategori_belanja = $('#cb_subkategori_belanja_1').val();
    var kode_belanja = $('#cb_belanja_1').val();
    var uraian = $('#uraian_1').val();
    var det_uraian = $('#det_uraian_1').val();
    var volume = $('#volume_1').val();
    var satuan = $('#satuan_1').val();
    var nominal = $('#nominal_satuan_1').val();
    var sumberdana = $('#sumberdana_1').val();
    eliminationName(lokasi, uraian_kegiatan, jenis_belanja, kategori_belanja, subkategori_belanja, kode_belanja, uraian, det_uraian, volume, satuan, nominal, sumberdana, clue, '#cusAlert_1', 'pesan_1');

  }


  function jenis_belanjanya_1(p_nama, p_jenis) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_jenis_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis},
      success: function(msg){
        $("#combo_jenis_belanja_1").html(msg);
        prepare_chosen();
      }
    });
  }
  function kategori_belanjanya_1(p_nama, p_jenis, p_kategori) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_kategori_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori},
      success: function(msg){
        $("#combo_kategori_1").html(msg);
        prepare_chosen();
      }
    });
  }
  function sub_belanjanya_1(p_nama, p_jenis, p_kategori, p_sub) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_sub_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub},
      success: function(msg){
        $("#combo_subkategori_1").html(msg);
        prepare_chosen();
      }
    });
  }
  function belanja_belanjanya_1(p_nama, p_jenis, p_kategori, p_sub, p_belanja) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_belanja_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub, belanja: p_belanja},
      success: function(msg){
        $("#combo_belanja_1").html(msg);
        prepare_chosen();
      }
    });
  }

  function sumber_dananya_1(p_nama, p_id) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_sumber_dana"); ?>',
      data: {nama: p_nama, id: p_id},
      success: function(msg){
        $("#combo_sumberdana_1").html(msg);
        prepare_chosen();
      }
    });
  }

  // function hapusrowng_1(id_belanja){
  //   var tahun = 1;
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
  //           var total = 0.00;
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
  //             row += '<td>'+parseFloat(msg.list[i].volume)+'</td>';
  //             row += '<td>'+msg.list[i].satuan+'</td>';
  //             row += '<td>'+parseFloat(msg.list[i].nominal_satuan)+'</td>';
  //             row += '<td>'+parseFloat(msg.list[i].subtotal)+'</td>';
  //             row += "<td><span id='ubahrowng' class='icon-pencil' onclick='ubahrowng_1("+msg.list[i].id+")' style='cursor:pointer' title='Ubah Belanja'></span></td>";
  //             row += "<td><span id='hapusrowng' class='icon-remove' onclick='hapusrowng_1("+msg.list[i].id+")' style='cursor:pointer' title='Hapus Belanja'></span></td>";
  //             row += '</tr>';
  //             $('#list_tahun_'+tahun).append(row);
  //             no++;
  //             total = parseFloat(total) + parseFloat(msg.list[i].subtotal);
  //           }
  //           $('#nominal_1').autoNumeric('set', total);
  //         }
  //     });

  // }

  // function ubahrowng_1(id_belanja){
  //   var tahun = 1;
  //   var check = $('#id_belanja_'+tahun).val();
  //   var id_kegiatan = $('input[name="id_kegiatan"]').val();

  //   if (check == '' || check == null) {
  //     $('#id_belanja_'+tahun).val(id_belanja);

  //     $.ajax({
  //         type: "POST",
  //         url: '<?php echo site_url("renstra/belanja_kegiatan_edit"); ?>',
  //         dataType: 'json',
  //         data: {
  //         id_kegiatan : id_kegiatan,
  //         id_belanja : id_belanja,
  //         tahun : tahun
  //         },
  //         success: function(msg){
  //           $('#list_tahun_'+tahun).html('');
            
  //           var no = 1;
  //           var total = 0.00;
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
  //             row += "<td><span id='ubahrowng' class='icon-pencil' onclick='ubahrowng_1("+msg.list[i].id+")' style='cursor:pointer' title='Ubah Belanja'></span></td>";
  //             row += "<td><span id='hapusrowng' class='icon-remove' onclick='hapusrowng_1("+msg.list[i].id+")' style='cursor:pointer' title='Hapus Belanja'></span></td>";
  //             row += '</tr>';
  //             $('#list_tahun_'+tahun).append(row);
  //             no++;
  //             total = parseFloat(total) + parseFloat(msg.list[i].subtotal);
  //           }
  //           var jenis = msg.edit.kode_jenis_belanja;
  //           var kategori = msg.edit.kode_kategori_belanja;
  //           var sub = msg.edit.kode_sub_kategori_belanja;
  //           var belanja = msg.edit.kode_belanja;
  //           var sumber_dana = msg.edit.kode_sumber_dana;
  //           jenis_belanjanya_1("cb_jenis_belanja_1", jenis);
  //           kategori_belanjanya_1("cb_kategori_belanja_1", jenis, kategori);
  //           sub_belanjanya_1("cb_subkategori_belanja_1", jenis, kategori, sub);
  //           belanja_belanjanya_1("cb_belanja_1", jenis, kategori, sub, belanja);
  //           sumber_dananya_1("sumberdana_1", sumber_dana);
  //           $('#uraian_1').val(msg.edit.uraian_belanja);
  //           $('#det_uraian_1').val(msg.edit.detil_uraian_belanja);
  //           $('#volume_1').autoNumeric('set', msg.edit.volume);
  //           $('#satuan_1').val(msg.edit.satuan);
  //           $('#nominal_satuan_1').autoNumeric('set', msg.edit.nominal_satuan);

  //           $('#nominal_1').autoNumeric('set', total);
  //         }
  //     });

      
  //   }
  // }
</script>