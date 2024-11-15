<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("databerkas/add");
$can_edit = ACL::is_allowed("databerkas/edit");
$can_view = ACL::is_allowed("databerkas/view");
$can_delete = ACL::is_allowed("databerkas/delete");
?>
<?php
$comp_model = new SharedController;
$page_element_id = "view-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
//Page Data Information from Controller
$data = $this->view_data;
//$rec_id = $data['__tableprimarykey'];
$page_id = $this->route->page_id; //Page id from url
$view_title = $this->view_title;
$show_header = $this->show_header;
$show_edit_btn = $this->show_edit_btn;
$show_delete_btn = $this->show_delete_btn;
$show_export_btn = $this->show_export_btn;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="view"  data-display-type="table" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Lihat Data Dokumen</h4>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div  class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="card animated fadeIn page-content">
                        <?php
                        $counter = 0;
                        if(!empty($data)){
                        $rec_id = (!empty($data['id']) ? urlencode($data['id']) : null);
                        $counter++;
                        ?>
                        <div id="page-report-body" class="">
                            <table class="table  table-borderless table-striped">
                                <!-- Table Body Start -->
                                <tbody class="page-data" id="page-data-<?php echo $page_element_id; ?>">
                                    <tr  class="td-tanggalmasuk">
                                        <th class="title"> Tanggal Masuk: </th>
                                        <td class="value"> <?php echo $data['tanggalmasuk']; ?></td>
                                    </tr>
                                    <tr  class="td-namateknisi">
                                        <th class="title"> Nama Teknisi: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("nama_teknisi/view/" . urlencode($data['namateknisi'])) ?>">
                                                <i class="fa fa-eye"></i> <?php echo $data['nama_teknisi_nama_teknisi'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-projectmesin">
                                        <th class="title"> Project Mesin: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("project_mesin/view/" . urlencode($data['projectmesin'])) ?>">
                                                <i class="fa fa-eye"></i> <?php echo $data['project_mesin_nama_project'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-kantorwilayah">
                                        <th class="title"> Kantor Wilayah: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("kantor_wilayah/view/" . urlencode($data['kantorwilayah'])) ?>">
                                                <i class="fa fa-eye"></i> <?php echo $data['kantor_wilayah_kantor_wilayah'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-kantorcabang">
                                        <th class="title"> Kantor Cabang: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("kantor_cabang/view/" . urlencode($data['kantorcabang'])) ?>">
                                                <i class="fa fa-eye"></i> <?php echo $data['kantor_cabang_kantor_cabang'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-jenisberkas">
                                        <th class="title"> Jenis Dokumen: </th>
                                        <td class="value">
                                            <a size="sm" class="btn btn-sm btn-primary page-modal" href="<?php print_link("jenisberkas/view/" . urlencode($data['jenisberkas'])) ?>">
                                                <i class="fa fa-eye"></i> <?php echo $data['jenisberkas_jenisberkas'] ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr  class="td-keterangan">
                                        <th class="title"> Keterangan: </th>
                                        <td class="value"> <?php echo $data['keterangan']; ?></td>
                                    </tr>
                                    <tr  class="td-tanggalberkas">
                                        <th class="title"> Tanggal Dokumen: </th>
                                        <td class="value"> <?php echo $data['tanggalberkas']; ?></td>
                                    </tr>
                                    <tr  class="td-foto">
                                        <th class="title"> Foto: </th>
                                        <td class="value"><?php Html :: page_img($data['foto'],100,100,1); ?></td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>
                        <div class="p-3 d-flex">
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
                                        <?php if($can_edit){ ?>
                                        <a class="btn btn-sm btn-info"  href="<?php print_link("databerkas/edit/$rec_id"); ?>">
                                            <i class="fa fa-edit"></i> Ubah
                                        </a>
                                        <?php } ?>
                                        <?php if($can_delete){ ?>
                                        <a class="btn btn-sm btn-danger record-delete-btn mx-1"  href="<?php print_link("databerkas/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Apakah Anda yakin ingin menghapus catatan ini?" data-display-style="modal">
                                            <i class="fa fa-times"></i> Hapus
                                        </a>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    }
                                    else{
                                    ?>
                                    <!-- Empty Record Message -->
                                    <div class="text-muted p-3">
                                        <i class="fa fa-ban"></i> No Record Found
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
