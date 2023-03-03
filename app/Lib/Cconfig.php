<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cconfig
{
	public static function menuDashboard($icon, $nama, $keterangan, $prefix, $col)
    {
        /*<div class='col-md-$col'>
			<div class='info-box bg-aqua'>
				<span class='info-box-icon'><i class='$icon'></i></span>
				<div class='info-box-content'>
					<span class='info-box-number'>$nama</span>
					<span class='info-box-text'>$keterangan</span>
					<div class='progress'>
						<div class='progress-bar' style='width: 70%'></div>
					</div>
					<span class='progress-description'>
						<a href='".url("admin/$prefix/tambah")."' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-plus'></i> Tambah $prefix</a>
						<a href='".url("admin/$prefix/list")."' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-list'></i> List $prefix</a>
					</span>
				</div>
			</div>
		</div>*/

        $iMenu = "
		<div class='col-lg-3 col-sm-6'>
			<div class='card'>
				<div class='content'>
					<div class='row'>
						<div class='col-xs-5'>
							<div class='icon-big icon-warning text-center'>
								<i class='ti-server'></i>
							</div>
						</div>
						<div class='col-xs-7'>
							<div class='numbers'>
								<p>Capacity</p>
								105GB
							</div>
						</div>
					</div>
					<div class='footer'>
						<hr />
						<div class='stats'>
							<i class='ti-reload'></i> Updated now
						</div>
					</div>
				</div>
			</div>
		</div>

		";

		return $iMenu;
    }

	public static function pesanSukses($pesan)
	{
		$iPesan = "";
		$iPesan = "<div class='alert alert-success'>$pesan</div>";

		return $iPesan;
	}

	public static function pesanGagal($pesan)
	{
		$iPesan = "";
		$iPesan = "<div class='alert alert-danger'>$pesan</div>";

		return $iPesan;
	}

	public static function tampilDropDown($tabel, $value, $display)
	{
		$iOption = array();

		$result = DB::table($tabel)
					->select('*')
					->pluck($value, $display);

		$iOption[] = "<option value=''>-- Pilih Data --</option>";

		foreach ($result as $display => $value)
		{
			$iOption[] = "<option value='$value'>[$value] - $display</option>";
		}

		$iOption = join($iOption, "");
		return $iOption;
	}

}
