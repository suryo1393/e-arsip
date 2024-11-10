<?php 
/**
 * Barang Page Controller
 * @category  Controller
 */
class BarangController extends BaseController{
	function __construct(){
		parent::__construct();
		$this->tablename = "barang";
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
		$fields = array("id_barang", 
			"id_type_barang", 
			"merek_barang", 
			"Spesifikasi", 
			"Sumber", 
			"Lokasi_barang", 
			"Kondisi_barang", 
			"tanggal_input");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				barang.id_barang LIKE ? OR 
				barang.id_type_barang LIKE ? OR 
				barang.merek_barang LIKE ? OR 
				barang.Spesifikasi LIKE ? OR 
				barang.Sumber LIKE ? OR 
				barang.Lokasi_barang LIKE ? OR 
				barang.Kondisi_barang LIKE ? OR 
				barang.tanggal_input LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "barang/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("barang.id_barang", ORDER_TYPE);
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
		$page_title = $this->view->page_title = "Barang";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("barang/list.php", $data); //render the full page
	}
// No View Function Generated Because No Field is Defined as the Primary Key on the Database Table
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
			$fields = $this->fields = array("id_barang","id_type_barang","merek_barang","Spesifikasi","Sumber","Lokasi_barang","Kondisi_barang","tanggal_input");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'id_barang' => 'required|numeric',
				'id_type_barang' => 'required',
				'merek_barang' => 'required',
				'Spesifikasi' => 'required',
				'Sumber' => 'required',
				'Lokasi_barang' => 'required',
				'Kondisi_barang' => 'required',
				'tanggal_input' => 'required',
			);
			$this->sanitize_array = array(
				'id_barang' => 'sanitize_string',
				'id_type_barang' => 'sanitize_string',
				'merek_barang' => 'sanitize_string',
				'Spesifikasi' => 'sanitize_string',
				'Sumber' => 'sanitize_string',
				'Lokasi_barang' => 'sanitize_string',
				'Kondisi_barang' => 'sanitize_string',
				'tanggal_input' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Record added successfully", "success");
					return	$this->redirect("barang");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Barang";
		$this->render_view("barang/add.php");
	}
// No Edit Function Generated Because No Field is Defined as the Primary Key
// No Delete Function Generated Because No Field is Defined as the Primary Key on the Database Table/View
}