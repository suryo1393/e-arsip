<?php 
/**
 * Data_request_barang Page Controller
 * @category  Controller
 */
class Data_request_barangController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "data_request_barang";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("data_request_barang.id_request_barang", 
			"data_request_barang.tanggal_request", 
			"data_request_barang.nama_barang", 
			"data_request_barang.kontak_penerima_barang", 
			"data_request_barang.kantor_cabang", 
			"kantor_cabang.kantor_cabang AS kantor_cabang_kantor_cabang", 
			"data_request_barang.project_mesin", 
			"project_mesin.nama_project AS project_mesin_nama_project", 
			"data_request_barang.keterangan", 
			"data_request_barang.tujuan_kirim", 
			"data_request_barang.stok", 
			"data_request_barang.status", 
			"status_barang.status_barang AS status_barang_status_barang", 
			"data_request_barang.resi_pengiriman");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				data_request_barang.id_request_barang LIKE ? OR 
				data_request_barang.tanggal_request LIKE ? OR 
				data_request_barang.nama_barang LIKE ? OR 
				data_request_barang.kontak_penerima_barang LIKE ? OR 
				data_request_barang.kantor_cabang LIKE ? OR 
				data_request_barang.project_mesin LIKE ? OR 
				data_request_barang.keterangan LIKE ? OR 
				data_request_barang.tujuan_kirim LIKE ? OR 
				data_request_barang.stok LIKE ? OR 
				data_request_barang.status LIKE ? OR 
				data_request_barang.resi_pengiriman LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "data_request_barang/search.php";
		}
		$db->join("kantor_cabang", "data_request_barang.kantor_cabang = kantor_cabang.kantor_cabang", "INNER");
		$db->join("project_mesin", "data_request_barang.project_mesin = project_mesin.nama_project", "INNER");
		$db->join("status_barang", "data_request_barang.status = status_barang.status_barang", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("data_request_barang.id_request_barang", ORDER_TYPE);
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Data Permintaan Barang";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("data_request_barang/list.php", $data); //render the full page
	}
	/**
     * Load csv|json data
     * @return data
     */
	function import_data(){
		if(!empty($_FILES['file'])){
			$finfo = pathinfo($_FILES['file']['name']);
			$ext = strtolower($finfo['extension']);
			if(!in_array($ext , array('csv','json'))){
				$this->set_flash_msg("File format not supported", "danger");
			}
			else{
			$file_path = $_FILES['file']['tmp_name'];
				if(!empty($file_path)){
					$request = $this->request;
					$db = $this->GetModel();
					$tablename = $this->tablename;
					if($ext == "csv"){
						$options = array('table' => $tablename, 'fields' => '', 'delimiter' => ',', 'quote' => '"');
						$data = $db->loadCsvData($file_path , $options , false);
					}
					else{
						$data = $db->loadJsonData($file_path, $tablename , false);
					}
					if($db->getLastError()){
						$this->set_flash_msg($db->getLastError(), "danger");
					}
					else{
						$this->set_flash_msg("Data imported successfully", "success");
					}
				}
				else{
					$this->set_flash_msg("Error uploading file", "success");
				}
			}
		}
		else{
			$this->set_flash_msg("No file selected for upload", "warning");
		}
		$this->redirect("data_request_barang");
	}
	/**
     * View record detail 
	 * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("data_request_barang.id_request_barang", 
			"data_request_barang.tanggal_request", 
			"data_request_barang.nama_barang", 
			"data_request_barang.kontak_penerima_barang", 
			"data_request_barang.kantor_cabang", 
			"kantor_cabang.kantor_cabang AS kantor_cabang_kantor_cabang", 
			"data_request_barang.project_mesin", 
			"project_mesin.nama_project AS project_mesin_nama_project", 
			"data_request_barang.keterangan", 
			"data_request_barang.tujuan_kirim", 
			"data_request_barang.stok", 
			"data_request_barang.status", 
			"status_barang.status_barang AS status_barang_status_barang", 
			"data_request_barang.resi_pengiriman");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("data_request_barang.id_request_barang", $rec_id);; //select record based on primary key
		}
		$db->join("kantor_cabang", "data_request_barang.kantor_cabang = kantor_cabang.kantor_cabang", "INNER");
		$db->join("project_mesin", "data_request_barang.project_mesin = project_mesin.nama_project", "INNER");
		$db->join("status_barang", "data_request_barang.status = status_barang.status_barang", "INNER");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "Lihat Data Request Barang";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("data_request_barang/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("tanggal_request","nama_barang","kontak_penerima_barang","kantor_cabang","project_mesin","keterangan","tujuan_kirim","stok","status","resi_pengiriman");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'tanggal_request' => 'required',
				'nama_barang' => 'required',
				'kontak_penerima_barang' => 'required',
				'kantor_cabang' => 'required',
				'project_mesin' => 'required',
				'keterangan' => 'required',
				'tujuan_kirim' => 'required',
				'stok' => 'required',
				'status' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal_request' => 'sanitize_string',
				'nama_barang' => 'sanitize_string',
				'kontak_penerima_barang' => 'sanitize_string',
				'kantor_cabang' => 'sanitize_string',
				'project_mesin' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'tujuan_kirim' => 'sanitize_string',
				'stok' => 'sanitize_string',
				'status' => 'sanitize_string',
				'resi_pengiriman' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Catatan berhasil ditambahkan", "success");
					return	$this->redirect("data_request_barang");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Tambah Data Permintaan Barang";
		$this->render_view("data_request_barang/add.php");
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id_request_barang","tanggal_request","nama_barang","kontak_penerima_barang","kantor_cabang","project_mesin","keterangan","tujuan_kirim","stok","status","resi_pengiriman");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'tanggal_request' => 'required',
				'nama_barang' => 'required',
				'kontak_penerima_barang' => 'required',
				'kantor_cabang' => 'required',
				'project_mesin' => 'required',
				'keterangan' => 'required',
				'tujuan_kirim' => 'required',
				'stok' => 'required',
				'status' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal_request' => 'sanitize_string',
				'nama_barang' => 'sanitize_string',
				'kontak_penerima_barang' => 'sanitize_string',
				'kantor_cabang' => 'sanitize_string',
				'project_mesin' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'tujuan_kirim' => 'sanitize_string',
				'stok' => 'sanitize_string',
				'status' => 'sanitize_string',
				'resi_pengiriman' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("data_request_barang.id_request_barang", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Catatan berhasil diubah", "success");
					return $this->redirect("data_request_barang");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("data_request_barang");
					}
				}
			}
		}
		$db->where("data_request_barang.id_request_barang", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Ubah Data Request Barang";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("data_request_barang/edit.php", $data);
	}
	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("data_request_barang.id_request_barang", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Catatan berhasil dihapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("data_request_barang");
	}
}
