<div v-if="charges.pline" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content md">
            <a href="javascript:void(0)" @click.stop="closeModal" class="aa-close">&times;</a>

            <h3 class="wp-heading-inline mb-3">
                <span>Breakdown for</span>
                <div class="d-block mt-2">
                    <span>{{charges.pline.subcat.sub_name}} - </span>
                    <span :style="`color: ${charges.pline.printmethod.method_hex}`">{{charges.pline.printmethod.method_name2}}</span>
                </div>
            </h3>

            <div v-if="charges.step == 1">

                <div class="d-block mt-4 mb-4">
                    <a href="javascript:void(0)" @click.stop="charges.step = 2" class="page-title-action m-0">Add New Breakdown</a>
                </div>

                <div class="mt-3">

                    <?php require_once( american_accent_plugin_base_dir() . 'application/templates/product-lines/charges/lists.php' ); ?>

                </div>

            </div>

            
            <div v-if="charges.step == 2">

                <div class="mt-3">

                    <?php include american_accent_plugin_base_dir() . 'application/templates/product-lines/charges/form.php'; ?>

                </div>

            </div>

        </div>
    </div>
</div>