<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("databerkas/add");
$can_edit = ACL::is_allowed("databerkas/edit");
$can_view = ACL::is_allowed("databerkas/view");
$can_delete = ACL::is_allowed("databerkas/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "list-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data From Controller
$view_data = $this->view_data;
$records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
$field_name = $this->route->field_name;
$field_value = $this->route->field_value;
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_footer = $this->show_footer;
$show_pagination = $this->show_pagination;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="list"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-3 ">
                    <form method="get" action="<?php print_link($current_page) ?>" class="form filter-form">
                        <div class="card mb-3">
                            <div class="card-header h4 h4">Filter by Tanggal Dokumen</div>
                            <div class="p-2">
                                <input class="form-control datepicker"  value="<?php echo $this->set_field_value('databerkas_tanggalberkas') ?>" type="datetime"  name="databerkas_tanggalberkas" placeholder="" data-enable-time="" data-date-format="Y-m-d" data-alt-format="M j, Y" data-inline="false" data-no-calendar="false" data-mode="range" />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group text-center">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                    <div class="col ">
                        <h4 class="record-title"></h4>
                    </div>
                    <div class="col-sm-3 comp-grid">
                        <?php if($can_add){ ?>
                        <?php $modal_id = "modal-" . random_str(); ?>
                        <button data-toggle="modal" data-target="#<?php  echo $modal_id ?>"  class="btn btn btn-primary my-1">
                            <i class="fa fa-plus"></i>                                  
                            Tambah Data Dokumen 
                        </button>
                        <div data-backdrop="true" id="<?php  echo $modal_id ?>" class="modal fade"  role="dialog" aria-labelledby="<?php  echo $modal_id ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-0 reset-grids">
                                        <div class=" ">
                                            <?php  
                                            $this->render_page("databerkas/add"); 
                                            ?>
                                        </div>
                                    </div>
                                    <div style="top: 5px; right:5px; z-index: 999;" class="position-absolute">
                                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">&times;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-sm-4 ">
                        <form  class="search" action="<?php print_link('databerkas'); ?>" method="get">
                            <div class="input-group">
                                <input value="<?php echo get_value('search'); ?>" class="form-control" type="text" name="search"  placeholder="Search" />
                                    <div class="input-group-append">
                                        <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 comp-grid">
                            <form method="get" action="<?php print_link($current_page) ?>" class="form filter-form">
                                <div class="card mb-3">
                                    <div class="card-header h4 h4">Filter by Kantor Cabang</div>
                                    <div class="p-2">
                                        <?php 
                                        $databerkas_kantorcabang_options = $comp_model -> databerkas_databerkaskantorcabang_option_list();
                                        if(!empty($databerkas_kantorcabang_options)){
                                        foreach($databerkas_kantorcabang_options as $option){
                                        $value = (!empty($option['value']) ? $option['value'] : null);
                                        $label = (!empty($option['label']) ? $option['label'] : $value);
                                        $checked = $this->set_field_checked('databerkas_kantorcabang', $value);
                                        ?>
                                        <label class="form-check form-check-inline">
                                            <input id="" class="form-check-input" <?php echo $checked; ?> value="<?php echo $value; ?>" type="radio"  name="databerkas_kantorcabang"  />
                                                <span class="form-check-label"><?php echo $label; ?></span>
                                            </label>
                                            <?php
                                            }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-group text-center">
                                        <button class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
                <div  class="">
                    <div class="container-fluid">
                        <div class="row ">
                            <div class="col-md-12 comp-grid">
                                <?php $this :: display_page_errors(); ?>
                                <div class="filter-tags mb-2">
                                    <?php
                                    if(!empty($_GET['databerkas_tanggalberkas'])){
                                    ?>
                                    <div class="filter-chip card bg-light">
                                        <b>Databerkas Tanggalberkas :</b> 
                                        <?php
                                        $date_val = get_value('databerkas_tanggalberkas');
                                        $formated_date = "";
                                        if(str_contains('-to-', $date_val)){
                                        //if value is a range date
                                        $vals = explode('-to-' , str_replace(' ' , '' , $date_val));
                                        $startdate = $vals[0];
                                        $enddate = $vals[1];
                                        $formated_date = format_date($startdate, 'jS F, Y') . ' <span class="text-muted">&#10148;</span> ' . format_date($enddate, 'jS F, Y');
                                        }
                                        elseif(str_contains(',', $date_val)){
                                        //multi date values
                                        $vals = explode(',' , str_replace(' ' , '' , $date_val));
                                        $formated_arrs = array_map(function($date){return format_date($date, 'jS F, Y');}, $vals);
                                        $formated_date = implode(' <span class="text-info">&#11161;</span> ', $formated_arrs);
                                        }
                                        else{
                                        $formated_date = format_date($date_val, 'jS F, Y');
                                        }
                                        echo  $formated_date;
                                        $remove_link = unset_get_value('databerkas_tanggalberkas', $this->route->page_url);
                                        ?>
                                        <a href="<?php print_link($remove_link); ?>" class="close-btn">
                                            &times;
                                        </a>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if(!empty(get_value('databerkas_kantorcabang'))){
                                    ?>
                                    <div class="filter-chip card bg-light">
                                        <b>Kantor Cabang :</b> 
                                        <?php 
                                        if(get_value('databerkas_kantorcabanglabel')){
                                        echo get_value('databerkas_kantorcabanglabel');
                                        }
                                        else{
                                        echo get_value('databerkas_kantorcabang');
                                        }
                                        $remove_link = unset_get_value('databerkas_kantorcabang', $this->route->page_url);
                                        ?>
                                        <a href="<?php print_link($remove_link); ?>" class="close-btn">
                                            &times;
                                        </a>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div  class=" animated fadeIn page-content">
                                    <div id="databerkas-list-records">
                                        <div id="page-report-body" class="table-fixed">
                                            <table class="table  table-striped table-sm text-left">
                                                <thead class="table-header bg-light">
                                                    <tr>
                                                        <?php if($can_delete){ ?>
                                                        <th class="td-checkbox">
                                                            <label class="custom-control custom-checkbox custom-control-inline">
                                                                <input class="toggle-check-all custom-control-input" type="checkbox" />
                                                                <span class="custom-control-label"></span>
                                                            </label>
                                                        </th>
                                                        <?php } ?>
                                                        <th class="td-sno">#</th>
                                                        <th  class="td-tanggalmasuk"> Tanggal Masuk</th>
                                                        <th  class="td-namateknisi"> Nama Teknisi</th>
                                                        <th  class="td-projectmesin"> Project Mesin</th>
                                                        <th  class="td-kantorwilayah"> Kantor Wilayah</th>
                                                        <th  class="td-kantorcabang"> Kantor Cabang</th>
                                                        <th  class="td-jenisberkas"> Jenis Dokumen</th>
                                                        <th  class="td-keterangan"> Keterangan</th>
                                                        <th  class="td-tanggalberkas"> Tanggal Dokumen</th>
                                                        <th  class="td-foto"> Foto</th>
                                                        <th class="td-btn"></th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                if(!empty($records)){
                                                ?>
                                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                                    <!--record-->
                                                    <?php
                                                    $counter = 0;
                                                    foreach($records as $data){
                                                    $rec_id = (!empty($data['id']) ? urlencode($data['id']) : null);
                                                    $counter++;
                                                    ?>
                                                    <tr>
                                                        <?php if($can_delete){ ?>
                                                        <th class=" td-checkbox">
                                                            <label class="custom-control custom-checkbox custom-control-inline">
                                                                <input class="optioncheck custom-control-input" name="optioncheck[]" value="<?php echo $data['id'] ?>" type="checkbox" />
                                                                    <span class="custom-control-label"></span>
                                                                </label>
                                                            </th>
                                                            <?php } ?>
                                                            <th class="td-sno"><?php echo $counter; ?></th>
                                                            <td class="td-tanggalmasuk"> <?php echo $data['tanggalmasuk']; ?></td>
                                                            <td class="td-namateknisi">
                                                                <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("nama_teknisi/view/" . urlencode($data['namateknisi'])) ?>">
                                                                    <i class="fa fa-eye"></i> <?php echo $data['nama_teknisi_nama_teknisi'] ?>
                                                                </a>
                                                            </td>
                                                            <td class="td-projectmesin">
                                                                <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("project_mesin/view/" . urlencode($data['projectmesin'])) ?>">
                                                                    <i class="fa fa-eye"></i> <?php echo $data['project_mesin_nama_project'] ?>
                                                                </a>
                                                            </td>
                                                            <td class="td-kantorwilayah">
                                                                <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("kantor_wilayah/view/" . urlencode($data['kantorwilayah'])) ?>">
                                                                    <i class="fa fa-eye"></i> <?php echo $data['kantor_wilayah_kantor_wilayah'] ?>
                                                                </a>
                                                            </td>
                                                            <td class="td-kantorcabang">
                                                                <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("kantor_cabang/view/" . urlencode($data['kantorcabang'])) ?>">
                                                                    <i class="fa fa-eye"></i> <?php echo $data['kantor_cabang_kantor_cabang'] ?>
                                                                </a>
                                                            </td>
                                                            <td class="td-jenisberkas">
                                                                <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("jenisberkas/view/" . urlencode($data['jenisberkas'])) ?>">
                                                                    <i class="fa fa-eye"></i> <?php echo $data['jenisberkas_jenisberkas'] ?>
                                                                </a>
                                                            </td>
                                                            <td class="td-keterangan"> <?php echo $data['keterangan']; ?></td>
                                                            <td class="td-tanggalberkas"> <?php echo $data['tanggalberkas']; ?></td>
                                                            <td class="td-foto"><?php Html :: page_link_file($data['foto']); ?></td>
                                                            <th class="td-btn">
                                                                <?php if($can_view){ ?>
                                                                <a class="btn btn-sm btn-success has-tooltip" title="View Record" href="<?php print_link("databerkas/view/$rec_id"); ?>">
                                                                    <i class="fa fa-eye"></i> Lihat
                                                                </a>
                                                                <?php } ?>
                                                                <?php if($can_edit){ ?>
                                                                <a class="btn btn-sm btn-info has-tooltip" title="Edit This Record" href="<?php print_link("databerkas/edit/$rec_id"); ?>">
                                                                    <i class="fa fa-edit"></i> Ubah
                                                                </a>
                                                                <?php } ?>
                                                                <?php if($can_delete){ ?>
                                                                <a class="btn btn-sm btn-danger has-tooltip record-delete-btn" title="Delete this record" href="<?php print_link("databerkas/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus catatan ini?" data-display-style="modal">
                                                                    <i class="fa fa-times"></i>
                                                                    Hapus
                                                                </a>
                                                                <?php } ?>
                                                            </th>
                                                        </tr>
                                                        <?php 
                                                        }
                                                        ?>
                                                        <!--endrecord-->
                                                    </tbody>
                                                    <tbody class="search-data" id="search-data-<?php echo $page_element_id; ?>"></tbody>
                                                    <?php
                                                    }
                                                    ?>
                                                </table>
                                                <?php 
                                                if(empty($records)){
                                                ?>
                                                <h4 class="bg-light text-center border-top text-muted animated bounce  p-3">
                                                    <i class="fa fa-ban"></i> No record found
                                                </h4>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if( $show_footer && !empty($records)){
                                            ?>
                                            <div class=" border-top mt-2">
                                                <div class="row justify-content-center">    
                                                    <div class="col-md-auto justify-content-center">    
                                                        <div class="p-3 d-flex justify-content-between">    
                                                            <?php if($can_delete){ ?>
                                                            <button data-prompt-msg="Are you sure you want to delete these records?" data-display-style="modal" data-url="<?php print_link("databerkas/delete/{sel_ids}/?csrf_token=$csrf_token&redirect=$current_page"); ?>" class="btn btn-sm btn-danger btn-delete-selected d-none">
                                                                <i class="fa fa-times"></i> Delete Selected
                                                            </button>
                                                            <?php } ?>
                                                            <div class="dropup export-btn-holder mx-1">
                                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-save"></i> Export
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                    <?php $export_print_link = $this->set_current_page_link(array('format' => 'print')); ?>
                                                                    <a class="dropdown-item export-link-btn" data-format="print" href="<?php print_link($export_print_link); ?>" target="_blank">
                                                                        <img src="<?php print_link('assets/images/print.png') ?>" class="mr-2" /> PRINT
                                                                        </a>
                                                                        <?php $export_csv_link = $this->set_current_page_link(array('format' => 'csv')); ?>
                                                                        <a class="dropdown-item export-link-btn" data-format="csv" href="<?php print_link($export_csv_link); ?>" target="_blank">
                                                                            <img src="<?php print_link('assets/images/csv.png') ?>" class="mr-2" /> CSV
                                                                            </a>
                                                                            <?php $export_excel_link = $this->set_current_page_link(array('format' => 'excel')); ?>
                                                                            <a class="dropdown-item export-link-btn" data-format="excel" href="<?php print_link($export_excel_link); ?>" target="_blank">
                                                                                <img src="<?php print_link('assets/images/xsl.png') ?>" class="mr-2" /> EXCEL
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <?php Html :: import_form('databerkas/import_data' , "Import Data", 'CSV , JSON'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col">   
                                                                    <?php
                                                                    if($show_pagination == true){
                                                                    $pager = new Pagination($total_records, $record_count);
                                                                    $pager->route = $this->route;
                                                                    $pager->show_page_count = true;
                                                                    $pager->show_record_count = true;
                                                                    $pager->show_page_limit =true;
                                                                    $pager->limit_count = $this->limit_count;
                                                                    $pager->show_page_number_list = true;
                                                                    $pager->pager_link_range=5;
                                                                    $pager->render();
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
