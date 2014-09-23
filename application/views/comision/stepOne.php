<div class="block" style="margin: 10px 20px 25px 0px; padding-bottom: 0px;">
    <div class="block_head">
        <div class="bheadl"></div>
        <div class="bheadr"></div>
        <h2 style="margin: 0;">Zoho Data Sync Automated Tools Settings</h2>
    </div>
    <div class="block_content">
        <?php include_once("$error_page_url") ?>

        <form id="uploadFile" name="uploadFile" onsubmit="return validate_form_step_1();" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>index.php/comision/comisionUploadStepTwo">
            <input type="hidden" id="uploaded_file_name" name="uploaded_file_name" value=""/>
            <h3 style="text-decoration: underline;">Step One</h3>
            <h4>Please upload your file to migrate data into ZOHO CRM.</h4>
            <div class="message info"><p>You can download the report of your last import action from <a href="<?php echo base_url() ?>index.php/comision/sampleFileDownload?file=report" target="_blank" style="color: #ec8526;">here</a>.</p></div>
            <div class="message info"><p>Please upload only Microsoft Excel (.xls or .xlsx format) type file. You <span style="color: #ec8526;">MUST</span> follow this <a href="<?php echo base_url() ?>index.php/comision/sampleFileDownload" target="_blank" style="color: #ec8526;">sample</a> data format for your data in the file.</p></div>
            <div class="message info"><p>"Referencia Unica" and "Fecha Factura" fields <span style="color: #ec8526;">MUST BE</span> in the format of <span style="color: #ec8526;">YYYY-MM-DD</span> or else this two fields won't be migrated.</p></div>
            <div class="message info"><p>You have to use <span style="color: #ec8526;">"CUPS"</span> as your <span style="color: #ec8526;">"Comision Name"</span> and must be <span style="color: #ec8526;">unique</span>. Otherwise it will update the existing record or be ignored as per your instruction in the next step.</p></div>
            <p class="fileupload">
                <label>Your file: </label><br/>
                <input id="fileupload" type="file"/>
                <span id="uploadmsg">Max size depends on your server uploading configuration.</span>
            </p>
            <hr />
            <p>
                <input type="submit" class="submit small" value="Submit" />
            </p>
        </form>
    </div>
    <div class="bendl"></div>
    <div class="bendr"></div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    function validate_form_step_1(){
        if ($('#uploaded_file_name').val() === '') {
            alert("Please upload the comision file first.");
            return false;
        }
        return true;
    }
</script>
