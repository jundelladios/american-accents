<table class="wp-list-table widefat fixed striped pages mt-4">
    <thead>
        <tr>
            <th><strong>File</strong></th>
            <th><strong>Backup Date</strong></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $files = list_files( american_accent_plugin_base_dir() . 'migrations');
            if( $files ):
            foreach( $files as $file ):
                $getFile = explode( american_accent_plugin_base_dir() . 'migrations/', $file );
                if(count($getFile)) {
                    $date = explode("_", $getFile[1]);
                    $theDate = date_create($date[0]);
                    $url = american_accent_plugin_base_url() . 'migrations/' . $getFile[1];
                    ?>
                    <tr>
                        <td><?php echo $getFile[1]; ?></td>
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