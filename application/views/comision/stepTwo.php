<div class="block" style="margin: 10px 20px 25px 0px; padding-bottom: 0px;">
    <div class="block_head">
        <div class="bheadl"></div>
        <div class="bheadr"></div>
        <h2 style="margin: 0;">Zoho Data Sync Automated Tools Settings</h2>
        <h2 style="margin: 0; float: right;"><a href="#" onclick="location.reload(true);">Back to Step one</a></h2>
    </div>
    <div class="block_content">
        <?php include_once("$error_page_url") ?>

        <?php if (count($mapData['module_column_name']) > 0) { ?>
            <form id="mapZohoData" name="mapZohoData" onsubmit="return validate_form_step_2();" method="post" action="">
                <input type="hidden" name="action" value="step3"/>
                <h3 style="text-decoration: underline;">Step Two</h3>
                <h2>Map the Zoho CRM field names with the appropriate column names of the source file that you import.</h2>

                <h3><span style="color: red;font-size: 1.3em;font-weight: bolder;">*</span> denotes the mandatory field.</h3>
                <p>
                    <label>If record already exist<span style="color: red;font-size: 1.3em;font-weight: bolder;">*</span>:</label> <br />
                    <select id="duplicateCheck" style="width: 200px" name="duplicateCheck">
                        <option value="2">Update existing record</option>
                        <option value="1">Ignore new record</option>
                    </select>
                </p>

                <?php foreach ($mapData['module_column_name'] as $key => $value) { ?>
                    <p style="display: inline-block; width: 49%;">
                        <label for="<?php echo $key ?>">For Zoho Field "<?php echo trim(str_replace(COMISION_MODULE, "", $value)) ?>":</label>
                        <select id="<?php echo $key ?>" style="width: 200px" name="zoho_column_matching[<?php echo $key ?>]">
                            <option selected="selected" value="">None</option>
                            <?php foreach ($mapData['csv_column_name'][0] as $key2 => $value2) { ?>
                                <option value="<?php echo $key2 ?>"><?php echo $value2 ?></option>
                            <?php } ?>
                        </select>
                    </p>
                    <script type="text/javascript">
                        $(function() {
                            $("#<?php echo $key ?>").select2();
                        });
                    </script>
                <?php } ?>
                <hr />
                <p>
                    <input type="hidden" name="fileName" value="<?php echo $mapData['fileName'] ?>" />
                    <input type="hidden" name="mendatoryArray" value="<?php echo str_replace('"', '', $mapData['mendatoryArray']) ?>" />
                    <input type="submit" class="submit small" value="Submit" />
                </p>
            </form>
        <?php } ?>

    </div>
    <div class="bendl"></div>
    <div class="bendr"></div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    function validate_form_step_2(){
        var mendatoryField = [<?php echo $mapData['mendatoryArray'] ?>];

        for (var i = 0; i < mendatoryField.length; i++) {
            var element = document.getElementById(mendatoryField[i]);
            var value = element.options[element.selectedIndex].value;
            if (value == '') {
                alert('Please select the mendatory Zoho CRM field\'s value first.');
                return false;
            }
        }

        return true;
    }

    $(function() {
        $("#duplicateCheck").select2();
    });
</script>