<?php 
/**
 * Databarang Page Controller
 * @category  Controller
 */
class DatabarangController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "databarang";
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
		$fields = array("databarang.id", 
			"databarang.tanggal_terima", 
			"databarang.nama_barang", 
			"databarang.nama_teknisi", 
			"nama_teknisi.nama_teknisi AS nama_teknisi_nama_teknisi", 
			"databarang.kantor_cabang", 
			"kantor_cabang.kantor_cabang AS kantor_cabang_kantor_cabang", 
			"databarang.keterangan");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				databarang.id LIKE ? OR 
				databarang.tanggal_terima LIKE ? OR 
				databarang.nama_barang LIKE ? OR 
				databarang.nama_teknisi LIKE ? OR 
				databarang.kantor_cabang LIKE ? OR 
				databarang.keterangan LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "databarang/search.php";
		}
		$db->join("nama_teknisi", "databarang.nama_teknisi = nama_teknisi.id_nama_teknisi", "INNER");
		$db->join("kantor_cabang", "databarang.kantor_cabang = kantor_cabang.id_kantor_cabang", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("databarang.id", ORDER_TYPE);
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
		$page_title = $this->view->page_title = "Data Pengembalian Barang";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("databarang/list.php", $data); //render the full page
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
		$this->redirect("databarang");
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
		$fields = array("databarang.id", 
			"databarang.tanggal_terima", 
			"databarang.nama_barang", 
			"databarang.nama_teknisi", 
			"nama_teknisi.nama_teknisi AS nama_teknisi_nama_teknisi", 
			"databarang.kantor_cabang", 
			"kantor_cabang.kantor_cabang AS kantor_cabang_kantor_cabang", 
			"databarang.keterangan");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("databarang.id", $rec_id);; //select record based on primary key
		}
		$db->join("nama_teknisi", "databarang.nama_teknisi = nama_teknisi.id_nama_teknisi", "INNER");
		$db->join("kantor_cabang", "databarang.kantor_cabang = kantor_cabang.id_kantor_cabang", "INNER");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "Lihat Data Pengembalian Barang";
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
		return $this->render_view("databarang/view.php", $record);
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
			$fields = $this->fields = array("tanggal_terima","nama_barang","nama_teknisi","kantor_cabang","keterangan");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'tanggal_terima' => 'required',
				'nama_barang' => 'required',
				'nama_teknisi' => 'required',
				'kantor_cabang' => 'required',
				'keterangan' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal_terima' => 'sanitize_string',
				'nama_barang' => 'sanitize_string',
				'nama_teknisi' => 'sanitize_string',
				'kantor_cabang' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Catatan berhasil ditambahkan", "success");
					return	$this->redirect("databarang");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Tambah Data Pengembalian Barang";
		$this->render_view("databarang/add.php");
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
		$fields = $this->fields = array("id","tanggal_terima","nama_barang","nama_teknisi","kantor_cabang","keterangan");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'tanggal_terima' => 'required',
				'nama_barang' => 'required',
				'nama_teknisi' => 'required',
				'kantor_cabang' => 'required',
				'keterangan' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal_terima' => 'sanitize_string',
				'nama_barang' => 'sanitize_string',
				'nama_teknisi' => 'sanitize_string',
				'kantor_cabang' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("databarang.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Catatan berhasil diubah", "success");
					return $this->redirect("databarang");
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
						return	$this->redirect("databarang");
					}
				}
			}
		}
		$db->where("databarang.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Ubah Data Pengembalian Barang";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("databarang/edit.php", $data);
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
		$db->where("databarang.id", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Catatan berhasil dihapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("databarang");
	}
}
