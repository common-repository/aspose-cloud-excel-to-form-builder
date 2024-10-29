<h1>Aspose.Cells Forms </h1>
<div class="wrap">
    <form method="post" action="">
        <table>
            <tr>
                <th colspan="3" style="line-height: 30px">
                    <hr/>
                </th>
            </tr>

            <tr>
                <td>
                    <input type="text" readonly name="excel_file_name" placeholder="Click on the Select Excel File button" style="width: 300px;" id="excel_file_name" />
                </td>
                <td>
                    <?php
                    $image_library_url = get_upload_iframe_src( );
                    $image_library_url = remove_query_arg( array('TB_iframe'), $image_library_url );
                    $image_library_url = add_query_arg( array( 'context' => 'APC-Select-Excel-File', 'TB_iframe' => 1 ), $image_library_url );
                    ?>
                    <p>
                        <a title="Select Excel File" href="<?php echo esc_url( $image_library_url ); ?>" id="select-excel-file" class="button thickbox">Select Excel File</a>
                    </p>
                </td>
                <td>
                    <input type="submit" name="apc_generate_short_code" value="Generate ShortCodes" />
                </td>
            </tr>

        </table>
    </form>
    <?php require_once('aspose-cloud-excel-to-form-builder-listing-page.php') ?>
</div>
