<div v-cloak id="chargesController" class="wrap">

    <h1 class="wp-heading-inline">
    Charge Types</h1>

    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...defaultValue})" class="page-title-action">Add New</a>

    <hr class="wp-header-end">

    <ul class="subsubsub">
        <li class="all">
            <a href="javascript:void(0)" @click.prevent="toggleActive" class="current" aria-current="page">
                <span v-if="inactive">Active Charges</span>
                <span v-else>Inactive Charges</span>
            </a>
        </li>
    </ul>

    <?php require_once(american_accent_plugin_base_dir() . 'application/templates/charges/lists.php'); ?>

    <?php require_once(american_accent_plugin_base_dir() . 'application/templates/charges/form.php'); ?>

</div>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/charges/charges.js'; ?>"></script>