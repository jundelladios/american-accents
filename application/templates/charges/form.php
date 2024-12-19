<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="inputs.index!=null">Edit Charge</span>
                <span v-else>New Charge</span>
            </h1>
            <form action="/" @submit.prevent="saveCharge" class="mt-3" autocomplete="off">

                <div class="mb-3">
                    <label class="d-block mb-2">Enter Charge Name</label>
                    <input class="full-width" type="text" v-model="inputs.charge_name" v-validate="'required'" name="charge_name" data-vv-as="charge name" />
                    <span class="v-error">{{errors.first('charge_name')}}</span>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2">Charge ICON</label>
                    <input type="text" v-model="inputs.icon" v-validate="'required'" name="icon" data-vv-as="icon" />
                    <p>ICON Preview: <span :class="`icon ${inputs.icon} icon-dark`"></span></p>
                    <small class="d-block mb-2">You can choose icon from Settings > Theme Icons</small>
                    <span class="v-error">{{errors.first('icon')}}</span>
                </div>

                <div class="mb-3">
                    <button type="submit" id="btn" v-if="inputs.index!=null" class="button button-primary">Save Changes</button>
                    <button type="submit" id="btn" v-else class="button button-primary">Save Charge</button>
                </div>

            </form>
        </div>
    </div>
</div>