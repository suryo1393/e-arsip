
<?php 
	//check if current user role is allowed access to the pages
	$can_add = ACL::is_allowed("databerkas/add");
	$can_edit = ACL::is_allowed("databerkas/edit");
	$can_view = ACL::is_allowed("databerkas/view");
	$can_delete = ACL::is_allowed("databerkas/delete");
?>
<table>
    <thead>
        <tr>
            <th>Tanggal Masuk</th>
            <th>Nama Teknisi</th>
            <th>Project Mesin</th>
            <th>Kantor Wilayah</th>
            <th>Kantor Cabang</th>
            <th>Jenis Berkas</th>
            <th>Keterangan</th>
            <th>Tanggal Berkas</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($records)): ?>
            <?php foreach ($records as $data): ?>
                <?php $rec_id = urlencode($data['id']); ?>
                <tr>
                    <td><a href="<?php print_link("databerkas/view/$rec_id"); ?>" class="search-link"><?php echo $data['tanggalmasuk']; ?></a></td>
                    <td><?php echo $data['namateknisi']; ?></td>
                    <td><?php echo $data['projectmesin']; ?></td>
                    <td><?php echo $data['kantorwilayah']; ?></td>
                    <td><?php echo $data['kantorcabang']; ?></td>
                    <td><?php echo $data['jenisberkas']; ?></td>
                    <td><?php echo $data['keterangan']; ?></td>
                    <td><?php echo $data['tanggalberkas']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-muted text-center no-record-found">No Record Found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
