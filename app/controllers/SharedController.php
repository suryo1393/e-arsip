<?php 

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends BaseController{
	
	/**
     * databerkas_namateknisi_option_list Model Action
     * @return array
     */
	function databerkas_namateknisi_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT nama_teknisi AS value , nama_teknisi AS label FROM nama_teknisi ORDER BY label ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * databerkas_projectmesin_option_list Model Action
     * @return array
     */
	function databerkas_projectmesin_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_project_mesin AS value,nama_project AS label FROM project_mesin";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * databerkas_kantorwilayah_option_list Model Action
     * @return array
     */
	function databerkas_kantorwilayah_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_kantor_wilayah AS value,kantor_wilayah AS label FROM kantor_wilayah";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * databerkas_kantorcabang_option_list Model Action
     * @return array
     */
	function databerkas_kantorcabang_option_list($lookup_kantorwilayah){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_kantor_cabang AS value,kantor_cabang AS label FROM kantor_cabang WHERE id_kantor_wilayah= ? ORDER BY kantor_cabang ASC" ;
		$queryparams = array($lookup_kantorwilayah);
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * databerkas_jenisberkas_option_list Model Action
     * @return array
     */
	function databerkas_jenisberkas_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id AS value,jenisberkas AS label FROM jenisberkas";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * kantor_cabang_kantor_cabang_value_exist Model Action
     * @return array
     */
	function kantor_cabang_kantor_cabang_value_exist($val){
		$db = $this->GetModel();
		$db->where("kantor_cabang", $val);
		$exist = $db->has("kantor_cabang");
		return $exist;
	}

	/**
     * kantor_cabang_id_kantor_wilayah_option_list Model Action
     * @return array
     */
	function kantor_cabang_id_kantor_wilayah_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_kantor_wilayah AS value,kantor_wilayah AS label FROM kantor_wilayah";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * user_nama_value_exist Model Action
     * @return array
     */
	function user_nama_value_exist($val){
		$db = $this->GetModel();
		$db->where("nama", $val);
		$exist = $db->has("user");
		return $exist;
	}

	/**
     * user_email_value_exist Model Action
     * @return array
     */
	function user_email_value_exist($val){
		$db = $this->GetModel();
		$db->where("email", $val);
		$exist = $db->has("user");
		return $exist;
	}

	/**
     * data_request_barang_kantor_cabang_option_list Model Action
     * @return array
     */
	function data_request_barang_kantor_cabang_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT kantor_cabang AS value , kantor_cabang AS label FROM kantor_cabang ORDER BY label ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * data_request_barang_project_mesin_option_list Model Action
     * @return array
     */
	function data_request_barang_project_mesin_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT nama_project AS value , nama_project AS label FROM project_mesin ORDER BY label ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * data_request_barang_status_option_list Model Action
     * @return array
     */
	function data_request_barang_status_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT status_barang AS value , status_barang AS label FROM status_barang ORDER BY label ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * databarang_nama_teknisi_option_list Model Action
     * @return array
     */
	function databarang_nama_teknisi_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_nama_teknisi AS value,nama_teknisi AS label FROM nama_teknisi ORDER BY nama_teknisi ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * databarang_kantor_cabang_option_list Model Action
     * @return array
     */
	function databarang_kantor_cabang_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_kantor_cabang AS value,kantor_cabang AS label FROM kantor_cabang ORDER BY kantor_cabang ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * databerkas_databerkaskantorcabang_option_list Model Action
     * @return array
     */
	function databerkas_databerkaskantorcabang_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id_kantor_cabang AS value,kantor_cabang AS label FROM kantor_cabang ORDER BY kantor_cabang ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * getcount_kantorwilayah Model Action
     * @return Value
     */
	function getcount_kantorwilayah(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM kantor_wilayah";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_kantorcabang Model Action
     * @return Value
     */
	function getcount_kantorcabang(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM kantor_cabang";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_datadokumen Model Action
     * @return Value
     */
	function getcount_datadokumen(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM databerkas";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_jumlahteknisi Model Action
     * @return Value
     */
	function getcount_jumlahteknisi(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM nama_teknisi";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

}
