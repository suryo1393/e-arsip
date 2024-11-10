<?php
/**
 * Menu Items
 * All Project Menu
 * @category  Menu List
 */

class Menu{
	
	
			public static $navbarsideleft = array(
		array(
			'path' => 'home', 
			'label' => 'Beranda', 
			'icon' => '<i class="fa fa-home "></i>'
		),
		
		array(
			'path' => 'databerkas', 
			'label' => 'Data Dokumen', 
			'icon' => '<i class="fa fa-file "></i>'
		),
		
		array(
			'path' => 'data_request_barang', 
			'label' => 'Data Permintaan Barang', 
			'icon' => '<i class="fa fa-archive "></i>'
		),
		
		array(
			'path' => 'databarang', 
			'label' => 'Data Pengembalian Barang', 
			'icon' => '<i class="fa fa-recycle "></i>'
		),
		
		array(
			'path' => 'kantor_wilayah', 
			'label' => 'Kantor Wilayah', 
			'icon' => '<i class="fa fa-database "></i>'
		),
		
		array(
			'path' => 'kantor_cabang', 
			'label' => 'Kantor Cabang', 
			'icon' => '<i class="fa fa-clone "></i>'
		),
		
		array(
			'path' => 'nama_teknisi', 
			'label' => 'Nama Teknisi', 
			'icon' => '<i class="fa fa-dribbble "></i>'
		),
		
		array(
			'path' => 'jenisberkas', 
			'label' => 'Jenis Berkas', 
			'icon' => '<i class="fa fa-navicon "></i>'
		),
		
		array(
			'path' => 'project_mesin', 
			'label' => 'Project Mesin', 
			'icon' => '<i class="fa fa-shopping-cart "></i>'
		),
		
		array(
			'path' => 'status_barang', 
			'label' => 'Status Barang', 
			'icon' => '<i class="fa fa-tags "></i>'
		),
		
		array(
			'path' => 'user', 
			'label' => 'User', 
			'icon' => '<i class="fa fa-user "></i>'
		)
	);
		
	
	
			public static $level = array(
		array(
			"value" => "admin", 
			"label" => "admin", 
		),
		array(
			"value" => "user", 
			"label" => "user", 
		),);
		
			public static $account_status = array(
		array(
			"value" => "Active", 
			"label" => "Active", 
		),
		array(
			"value" => "Pending", 
			"label" => "Pending", 
		),
		array(
			"value" => "Blocked", 
			"label" => "Blocked", 
		),);
		
			public static $stok = array(
		array(
			"value" => "Gudang Lantai 2", 
			"label" => "Gudang Lantai 2", 
		),
		array(
			"value" => "Gudang Lantai 4", 
			"label" => "Gudang Lantai 4", 
		),
		array(
			"value" => "Gudang Semarang", 
			"label" => "Gudang Semarang", 
		),
		array(
			"value" => "Lantai 5", 
			"label" => "Lantai 5", 
		),);
		
}