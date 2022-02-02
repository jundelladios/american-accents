
<div v-cloak id="migrationController" class="wrap">

<h1 class="wp-heading-inline">
    Back-up & Migrations</h1>

<div class="notice notice-error mt-4">
    <p><strong>Developer Mode!</strong> please always check if you are making any changes here, specially on importing the database.</p>
</div>

<div class="notice notice-success mt-4">
    <p><strong>Security Purpose!</strong> You must refresh the page when doing any actions.</p>
</div>

<div class="row mt-3">

    <div class="col-md-3">
        <h2 class="mb-2">Export Database</h2>
        <small>Note: Export database and add it to the backup lists.</small>

        <div class="d-block mt-3">
            <button @click="exportdb" id="btn" class="button button-primary">Execute Backup</button>
        </div>
    </div>

    <div class="col-md-3">

        <h2 class="mb-2">Update URLs</h2>
        <small>Note: Update assets URLs if you are moving from old domain to a new domain name.</small>


        <div class="d-block mt-3">

            <form action="/" @submit.prevent="updateURLs">

                <div class="d-block mb-3">
                    <input 
                    type="text" 
                    v-model="inputs.old" 
                    placeholder="https://old.com" 
                    style="max-width: 300px;"
                    v-validate="'required'"
                    name="old"
                    class="full-width"
                    data-vv-as="old url">
                    <span class="v-error">{{errors.first('old')}}</span>
                </div>

                <div class="d-block mb-3">
                    <input 
                    type="text" 
                    v-model="inputs.new" 
                    placeholder="https://new.com" 
                    style="max-width: 300px;"
                    v-validate="'required'"
                    name="new"
                    class="full-width"
                    data-vv-as="new url">
                    <span class="v-error">{{errors.first('new')}}</span>
                </div>

                <button type="submit" id="btn" class="button button-primary">Update URL</button>
            </form>

        </div>
    </div>

    <div class="col-md-6">

        <h2 class="mb-2">Back-up Lists</h2>
        <small>Note: You can restore your previous database backup or download backup.</small>
        <?php require_once(american_accent_plugin_base_dir() . 'application/templates/migrations/lists.php'); ?>

    </div>

</div>

<div v-if="message.text" class="message_popup d-flex justify-content-center align-items-center">
    <div class="content_wrap">
        <p>{{message.text}}</p>
        <button v-if="message.closable" @click.stop="close" class="button">Okay</button>
    </div>
</div>

</div>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/migrations/migrations.js'; ?>"></script>