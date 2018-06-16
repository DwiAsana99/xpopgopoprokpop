<style type="text/css">
	#header_kocok {
        color: black;
        width: 100%;
        height: 90px;          
        top: 0;
        font-size: 16px;
        font-weight: bold;
        border-bottom: 0.1pt solid #aaa;
    }
	.no-border{
		border-collapse: collapse;
	}

	td.print-no-border{
		border: none;
	}
</style>

<div>
	<div style="page-break-after:always">
	<div id="header_kocok">
	    <table style="margin: auto; width: 100%;">
		    <tr>
		        <td style="padding-right: 10px;" width="25%" align="right"></td>
		        <td width="50%" align="center"><?php echo $header_kocok; ?></td>
		        <td width="25%" align="right" valign="top"></td>
		    </tr>
		</table>
	</div>
    <center><h3>BELUM DITENTUKAN</h3></center>
	<table class="full_width collapse" border="1px" style="font-size: 8px;">		
		<?php
			echo $rekapitulasi1;
		?>
	</table>
	</div>
	<div style="page-break-after:always">
	<div id="header_kocok">
	    <table style="margin: auto; width: 100%;">
		    <tr>
		        <td style="padding-right: 10px;" width="25%" align="right"></td>
		        <td width="50%" align="center"><?php echo $header_kocok; ?></td>
		        <td width="25%" align="right" valign="top"></td>
		    </tr>
		</table>
	</div>
    <center><h3>DITERIMA</h3></center>
	<table class="full_width collapse" border="1px" style="font-size: 8px;">
		<?php
			echo $rekapitulasi2;
		?>
	</table>
	</div>
	<div >
	<div id="header_kocok">
	    <table style="margin: auto; width: 100%;">
		    <tr>
		        <td style="padding-right: 10px;" width="25%" align="right"></td>
		        <td width="50%" align="center"><?php echo $header_kocok; ?></td>
		        <td width="25%" align="right" valign="top"></td>
		    </tr>
		</table>
	</div>
    <center><h3>DITOLAK</h3></center>
	<table class="full_width collapse" border="1px" style="font-size: 8px;">
		<?php
			echo $rekapitulasi3;
		?>
	</table>
	</div>
</div>

