<div class="block small center login">
    <div class="block_head">
        <div class="bheadl"></div>
        <div class="bheadr"></div>
        <h2>Zoho Data Migrator</h2>
    </div>
    <!-- .block_head ends -->
    <div class="block_content">
        <?php include_once 'error.php' ?>
        <?php echo form_open_multipart('login'); ?>
        <p>
            <label>Username:</label> <br/>
            <input type="text" name="username" class="text" required/>
        </p>

        <p>
            <label>Password:</label> <br/>
            <input type="password" name="password" class="text" required/>
        </p>

        <p>
            <input type="submit" class="submit" value="Login"/>
        </p>
        <?php echo form_close() ?>
    </div>
    <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
</div>        <!-- .login ends -->
