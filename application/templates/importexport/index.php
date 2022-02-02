<div v-cloak id="importExportVue" class="wrap">
    
<h1 class="wp-heading-inline">
Import/Export Tool</h1>



<h2>Products Settings</h2>

<a href="#" @click.prevent="importProducts" class="button mr-1 mb-3">Import</a>
<input type="file" id="importfile" name="importfile" accept=".csv" style="display: none;" @change="executeImport" />
<a href="#" @click.prevent="exportProducts" class="button mr-1 mb-3">Export</a>

<hr style="margin-top: 20px;">

<h2>Products Combination Settings</h2>

<a href="#"  @click.prevent="importProductCombo" class="button mr-1 mb-3">Import</a>
<input type="file" id="importfilecombo" name="importfilecombo" accept=".csv" style="display: none;" @change="executeImportCombo" />
<a href="#" @click.prevent="exportProductCombo(false)" class="button mr-1 mb-3">Export</a>

<a href="#"  @click.prevent="importProductComboPricing" class="button mr-1 mb-3">Import Pricing</a>
<input type="file" id="importfilepricing" name="importfilepricing" accept=".csv" style="display: none;" @change="executeImportPricing" />
<a href="#" @click.prevent="exportProductCombo(true)" class="button mr-1 mb-3">Export Pricing</a>

<hr style="margin-top: 20px;">


<h2>ProductLines Settings</h2>

<a href="#"  @click.prevent="importProductLine" class="button mr-1 mb-3">Import</a>
<input type="file" id="importfilepline" name="importfilepline" accept=".csv" style="display: none;" @change="executeImportProductLine" />
<a href="#" @click.prevent="exportProductLine" class="button mr-1 mb-3">Export</a>

<a href="#"  @click.prevent="importProductLinePricingData" class="button mr-1 mb-3">Import Pricing Data</a>
<input type="file" id="importfileplinepricingdata" name="importfileplinepricingdata" accept=".csv" style="display: none;" @change="executeImportProductLinePricingData" />
<a href="#" @click.prevent="exportProductLinePricingData" class="button mr-1 mb-3">Export Pricing Data</a>


<a href="#"  @click.prevent="importProductLinePricing" class="button mr-1 mb-3">Import Pricing</a>
<input type="file" id="importfileplinepricing" name="importfileplinepricing" accept=".csv" style="display: none;" @change="executeImportProductLinePricing" />
<a href="#" @click.prevent="exportProductLinePricing" class="button mr-1 mb-3">Export Pricing</a>

</div>

<script src="<?php echo american_accent_plugin_base_url() . 'application/templates/importexport/index.js'; ?>"></script>