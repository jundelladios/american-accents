<table class="wp-list-table widefat fixed striped pages mt-4">
    <thead>
        <tr>
            <th><strong>File</strong></th>
            <th><strong>Size</strong></th>
            <th><strong>Backup Date</strong></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $migrationuploadfolder = "/aa-inventory-migrations/";
            $filedirpath = wp_upload_dir()['basedir'] . $migrationuploadfolder;
            $files = list_files( $filedirpath );
            if( $files ):
            foreach( $files as $file ):
                $getFile = explode( $filedirpath, $file );
                if(count($getFile)) {
                    $date = explode("_", $getFile[1]);
                    $theDate = date_create($date[0]);
                    $url = wp_upload_dir()['baseurl'] . $migrationuploadfolder . $getFile[1];
                    ?>
                    <tr>
                        <td><?php echo $getFile[1]; ?></td>
                        <td><?php echo filesize($file); ?> bytes</td>
                        <td><?php echo date("F d Y H:i:s A", filemtime($file)); ?></td>
                        <td>
                            <button @click="restoredb('<?php echo $getFile[1]; ?>')" class="button mb-2">Restore</button>
                            <a href="<?php echo $url; ?>" download class="button mb-2">Download</a>
                            <button @click="deletebackup('<?php echo $getFile[1]; ?>')" class="button mb-2">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
            endforeach; 
            endif; 
        ?>
    </tbody>
</table>