<?php 
//check if current user role is allowed access to the pages
$can_add = ACL::is_allowed("databerkas/add");
$can_edit = ACL::is_allowed("databerkas/edit");
$can_view = ACL::is_allowed("databerkas/view");
$can_delete = ACL::is_allowed("databerkas/delete");
?>
<?php
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$field_name = $this->route->field_name;
$field_value = $this->route->field_value;
$view_data = $this->view_data;
$records = $view_data->records;
$record_count = $view_data->record_count;
$total_records = $view_data->total_records;
if (!empty($records)) {
?>
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
        <td class="td-foto"><?php Html :: page_img($data['foto'],100,100,1); ?></td>
        <th class="td-btn">
            <?php if($can_view){ ?>
            <a class="btn btn-sm btn-success has-tooltip page-modal" title="View Record" href="<?php print_link("databerkas/view/$rec_id"); ?>">
                <i class="fa fa-eye"></i> Lihat
            </a>
            <?php } ?>
            <?php if($can_edit){ ?>
            <a class="btn btn-sm btn-info has-tooltip page-modal" title="Edit This Record" href="<?php print_link("databerkas/edit/$rec_id"); ?>">
                <i class="fa fa-edit"></i> Ubah
            </a>
            <?php } ?>
            <?php if($can_delete){ ?>
            <a class="btn btn-sm btn-danger has-tooltip record-delete-btn" title="Delete this record" href="<?php print_link("databerkas/delete/$rec_id/?csrf_token=$csrf_token&redirect=$current_page"); ?>" data-prompt-msg="Are you sure you want to delete this record?" data-display-style="modal">
                <i class="fa fa-times"></i>
                Hapus
            </a>
            <?php } ?>
        </th>
    </tr>
    <?php 
    }
    ?>
    <?php
    } else {
    ?>
    <td class="no-record-found col-12" colspan="100">
        <h4 class="text-muted text-center ">
            No Record Found
        </h4>
    </td>
    <?php
    }
    ?>
    