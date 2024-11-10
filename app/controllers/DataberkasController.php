<?php 
/**
 * Databerkas Page Controller
 * @category  Controller
 */
class DataberkasController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "databerkas";
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
		$fields = array("databerkas.id", 
			"databerkas.tanggalmasuk", 
			"databerkas.namateknisi", 
			"nama_teknisi.nama_teknisi AS nama_teknisi_nama_teknisi", 
			"databerkas.projectmesin", 
			"project_mesin.nama_project AS project_mesin_nama_project", 
			"databerkas.kantorwilayah", 
			"kantor_wilayah.kantor_wilayah AS kantor_wilayah_kantor_wilayah", 
			"databerkas.kantorcabang", 
			"kantor_cabang.kantor_cabang AS kantor_cabang_kantor_cabang", 
			"databerkas.jenisberkas", 
			"jenisberkas.jenisberkas AS jenisberkas_jenisberkas", 
			"databerkas.keterangan", 
			"databerkas.tanggalberkas", 
			"databerkas.foto");
		$pagination = $this->get_pagination(50); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				databerkas.id LIKE ? OR 
				databerkas.tanggalmasuk LIKE ? OR 
				databerkas.namateknisi LIKE ? OR 
				databerkas.projectmesin LIKE ? OR 
				databerkas.kantorwilayah LIKE ? OR 
				databerkas.kantorcabang LIKE ? OR 
				databerkas.jenisberkas LIKE ? OR 
				databerkas.keterangan LIKE ? OR 
				databerkas.tanggalberkas LIKE ? OR 
				databerkas.foto LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "databerkas/search.php";
		}
		$db->join("nama_teknisi", "databerkas.namateknisi = nama_teknisi.nama_teknisi", "INNER");
		$db->join("project_mesin", "databerkas.projectmesin = project_mesin.id_project_mesin", "INNER");
		$db->join("kantor_wilayah", "databerkas.kantorwilayah = kantor_wilayah.id_kantor_wilayah", "INNER");
		$db->join("kantor_cabang", "databerkas.kantorcabang = kantor_cabang.id_kantor_cabang", "INNER");
		$db->join("jenisberkas", "databerkas.jenisberkas = jenisberkas.id", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("databerkas.id", ORDER_TYPE);
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		if(!empty($request->databerkas_tanggalberkas)){
			$vals = explode("-to-", str_replace(" ", "", $request->databerkas_tanggalberkas));
			$startdate = $vals[0];
			$enddate = $vals[1];
			$db->where("databerkas.tanggalberkas BETWEEN '$startdate' AND '$enddate'");
		}
		if(!empty($request->databerkas_kantorcabang)){
			$val = $request->databerkas_kantorcabang;
			$db->where("databerkas.kantorcabang", $val , "=");
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
		$page_title = $this->view->page_title = "Data Dokumen";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("databerkas/list.php", $data); //render the full page
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
		$this->redirect("databerkas");
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
		$fields = array("databerkas.id", 
			"databerkas.tanggalmasuk", 
			"databerkas.namateknisi", 
			"nama_teknisi.nama_teknisi AS nama_teknisi_nama_teknisi", 
			"databerkas.projectmesin", 
			"project_mesin.nama_project AS project_mesin_nama_project", 
			"databerkas.kantorwilayah", 
			"kantor_wilayah.kantor_wilayah AS kantor_wilayah_kantor_wilayah", 
			"databerkas.kantorcabang", 
			"kantor_cabang.kantor_cabang AS kantor_cabang_kantor_cabang", 
			"databerkas.jenisberkas", 
			"jenisberkas.jenisberkas AS jenisberkas_jenisberkas", 
			"databerkas.keterangan", 
			"databerkas.tanggalberkas", 
			"databerkas.foto");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("databerkas.id", $rec_id);; //select record based on primary key
		}
		$db->join("nama_teknisi", "databerkas.namateknisi = nama_teknisi.nama_teknisi", "INNER");
		$db->join("project_mesin", "databerkas.projectmesin = project_mesin.id_project_mesin", "INNER");
		$db->join("kantor_wilayah", "databerkas.kantorwilayah = kantor_wilayah.id_kantor_wilayah", "INNER");
		$db->join("kantor_cabang", "databerkas.kantorcabang = kantor_cabang.id_kantor_cabang", "INNER");
		$db->join("jenisberkas", "databerkas.jenisberkas = jenisberkas.id", "INNER");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "Lihat Data Dokumen";
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
		return $this->render_view("databerkas/view.php", $record);
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
			$fields = $this->fields = array("tanggalmasuk","namateknisi","projectmesin","kantorwilayah","kantorcabang","jenisberkas","keterangan","tanggalberkas","foto");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'namateknisi' => 'required',
				'projectmesin' => 'required',
				'kantorwilayah' => 'required',
				'jenisberkas' => 'required',
			);
			$this->sanitize_array = array(
				'namateknisi' => 'sanitize_string',
				'projectmesin' => 'sanitize_string',
				'kantorwilayah' => 'sanitize_string',
				'kantorcabang' => 'sanitize_string',
				'jenisberkas' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'tanggalberkas' => 'sanitize_string',
				'foto' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['tanggalmasuk'] = datetime_now();
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Catatan berhasil ditambahkan", "success");
					return	$this->redirect("databerkas");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Tambah Data Dokumen";
		$this->render_view("databerkas/add.php");
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
		$fields = $this->fields = array("id","tanggalmasuk","namateknisi","projectmesin","kantorwilayah","kantorcabang","jenisberkas","keterangan","tanggalberkas","foto");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'namateknisi' => 'required',
				'projectmesin' => 'required',
				'kantorwilayah' => 'required',
				'jenisberkas' => 'required',
			);
			$this->sanitize_array = array(
				'namateknisi' => 'sanitize_string',
				'projectmesin' => 'sanitize_string',
				'kantorwilayah' => 'sanitize_string',
				'kantorcabang' => 'sanitize_string',
				'jenisberkas' => 'sanitize_string',
				'keterangan' => 'sanitize_string',
				'tanggalberkas' => 'sanitize_string',
				'foto' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['tanggalmasuk'] = datetime_now();
			if($this->validated()){
				$db->where("databerkas.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Catatan berhasil diubah", "success");
					return $this->redirect("databerkas");
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
						return	$this->redirect("databerkas");
					}
				}
			}
		}
		$db->where("databerkas.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Ubah Data Dokumen";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("databerkas/edit.php", $data);
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
		$db->where("databerkas.id", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Catatan berhasil dihapus", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("databerkas");
	}
}
