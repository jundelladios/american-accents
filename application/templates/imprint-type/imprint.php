<div v-cloak id="imprintController" class="wrap">

    <h1 class="wp-heading-inline">
    Imprint Types</h1>

    <a href="javascript:void(0)" @click.prevent="formInputs(true, {...defaultValue})" class="page-title-action">Add New</a>

    <hr class="wp-header-end">

    <ul class="subsubsub">
        <li class="all">
            <a href="javascript:void(0)" @click.prevent="toggleActive" class="current" aria-current="page">
                <span v-if="inactive">Active Imprint Type</span>
                <span v-else>Inactive Imprint Type</span>
            </a>
        </li>
    </ul>
    
    <form action="/" @submit.prevent="init">
        <p class="search-box mb-3">
            <input type="search" v-model="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Imprint Type">
        </p>
    </form>

    <?php require_once(american_accent_plugin_base_dir() . 'application/templates/imprint-type/lists.php'); ?>

    <?php require_once(american_accent_plugin_base_dir() . 'application/templates/imprint-type/form.php'); ?>

</div>
<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/imprint-type/imprint.js'; ?>"></script>