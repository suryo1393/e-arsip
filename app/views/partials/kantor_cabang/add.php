<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add"  data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if( $show_header == true ){
    ?>
    <div  class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col ">
                    <h4 class="record-title">Add New Kantor Cabang</h4>
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
                <div class="col-md-7 comp-grid">
                    <?php $this :: display_page_errors(); ?>
                    <div  class="bg-light p-3 animated fadeIn page-content">
                        <form id="kantor_cabang-add-form"  novalidate role="form" enctype="multipart/form-data" class="form multi-form page-form" action="<?php print_link("kantor_cabang/add?csrf_token=$csrf_token") ?>" method="post" >
                            <div>
                                <table class="table table-striped table-sm" data-maxrow="10" data-minrow="1">
                                    <thead>
                                        <tr>
                                            <th class="bg-light"><label for="kantor_cabang">Kantor Cabang</label></th>
                                            <th class="bg-light"><label for="id_kantor_wilayah">Kantor Wilayah</label></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for( $row = 1; $row <= 1; $row++ ){
                                        ?>
                                        <tr class="input-row">
                                            <td>
                                                <div id="ctrl-kantor_cabang-row<?php echo $row; ?>-holder" class="">
                                                    <input id="ctrl-kantor_cabang-row<?php echo $row; ?>"  value="<?php  echo $this->set_field_value('kantor_cabang',"", $row); ?>" type="text" placeholder="Enter Kantor Cabang"  required="" name="row<?php echo $row ?>[kantor_cabang]"  data-url="api/json/kantor_cabang_kantor_cabang_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control  ctrl-check-duplicate" />
                                                        <div class="check-status"></div> 
                                                    </div>
                                                </td>
                                                <td>
                                                    <div id="ctrl-id_kantor_wilayah-row<?php echo $row; ?>-holder" class="">
                                                        <select required=""  id="ctrl-id_kantor_wilayah-row<?php echo $row; ?>" name="row<?php echo $row ?>[id_kantor_wilayah]"  placeholder="Select a value ..."    class="custom-select" >
                                                            <option value="">Select a value ...</option>
                                                            <?php 
                                                            $id_kantor_wilayah_options = $comp_model -> kantor_cabang_id_kantor_wilayah_option_list();
                                                            if(!empty($id_kantor_wilayah_options)){
                                                            foreach($id_kantor_wilayah_options as $option){
                                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                                            $selected = $this->set_field_selected('id_kantor_wilayah',$value, "");
                                                            ?>
                                                            <option <?php echo $selected; ?> value="<?php echo $value; ?>">
                                                                <?php echo $label; ?>
                                                            </option>
                                                            <?php
                                                            }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <th class="text-center">
                                                    <button type="button" class="close btn-remove-table-row">&times;</button>
                                                </th>
                                            </tr>
                                            <?php 
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="100" class="text-right">
                                                    <?php $template_id = "table-row-" . random_str(); ?>
                                                    <button type="button" data-template="#<?php echo $template_id ?>" class="btn btn-sm btn-light btn-add-table-row"><i class="fa fa-plus"></i></button>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="form-group form-submit-btn-holder text-center mt-3">
                                    <div class="form-ajax-status"></div>
                                    <button class="btn btn-primary" type="submit">
                                        Simpan
                                        <i class="fa fa-send"></i>
                                    </button>
                                </div>
                            </form>
                            <!--[table row template]-->
                            <template id="<?php echo $template_id ?>">
                                <tr class="input-row">
                                    <?php $row = 1; ?>
                                    <td>
                                        <div id="ctrl-kantor_cabang-row<?php echo $row; ?>-holder" class="">
                                            <input id="ctrl-kantor_cabang-row<?php echo $row; ?>"  value="<?php  echo $this->set_field_value('kantor_cabang',"", $row); ?>" type="text" placeholder="Enter Kantor Cabang"  required="" name="row<?php echo $row ?>[kantor_cabang]"  data-url="api/json/kantor_cabang_kantor_cabang_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control  ctrl-check-duplicate" />
                                                <div class="check-status"></div> 
                                            </div>
                                        </td>
                                        <td>
                                            <div id="ctrl-id_kantor_wilayah-row<?php echo $row; ?>-holder" class="">
                                                <select required=""  id="ctrl-id_kantor_wilayah-row<?php echo $row; ?>" name="row<?php echo $row ?>[id_kantor_wilayah]"  placeholder="Select a value ..."    class="custom-select" >
                                                    <option value="">Select a value ...</option>
                                                    <?php 
                                                    $id_kantor_wilayah_options = $comp_model -> kantor_cabang_id_kantor_wilayah_option_list();
                                                    if(!empty($id_kantor_wilayah_options)){
                                                    foreach($id_kantor_wilayah_options as $option){
                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                    $selected = $this->set_field_selected('id_kantor_wilayah',$value, "");
                                                    ?>
                                                    <option <?php echo $selected; ?> value="<?php echo $value; ?>">
                                                        <?php echo $label; ?>
                                                    </option>
                                                    <?php
                                                    }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <th class="text-center">
                                            <button type="button" class="close btn-remove-table-row">&times;</button>
                                        </th>
                                    </tr>
                                </template>
                                <!--[/table row template]-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
