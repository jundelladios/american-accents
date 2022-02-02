<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="input.index!=null">Edit Code</span>
                <span v-else>New Code</span>
            </h1>
            <form action="/" @submit.prevent="saveCode" class="mt-3" autocomplete="off">

                <div class="mb-3">
                    <label class="d-block mb-2" for="code">Enter code</label>
                    <input type="text" v-model="input.code" v-validate="'required'" id="code" name="code" />
                    <span class="v-error">{{errors.first('code')}}</span>
                </div>

                <div class="mb-3">
                    <button type="submit" id="btn" v-if="input.index!=null" class="button button-primary">Save Changes</button>
                    <button type="submit" id="btn" v-else class="button button-primary">Save Code</button>
                </div>

            </form>
        </div>
    </div>
</div>
        