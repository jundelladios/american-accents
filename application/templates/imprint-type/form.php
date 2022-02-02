<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="input.index!=null">Edit imprint type</span>
                <span v-else>New imprint type</span>
            </h1>
            <form action="/" @submit.prevent="saveImprint" class="mt-3" autocomplete="off">

                <div class="mb-3">
                    <label class="d-block mb-2" for="title">Enter title</label>
                    <input type="text" v-model="input.title" v-validate="'required'" id="title" name="title" class="full-width" />
                    <span class="v-error">{{errors.first('title')}}</span>
                </div>

                <div class="mb-3">
                    <label class="d-block mb-2" for="">Description</label>
                    <editor v-model="input.body" />
                </div>

                <!-- <div class="mb-3">
                    <label class="d-block mb-2" for="">Priority #</label>
                    <input type="number" v-model="input.priority">
                </div> -->

                <div class="mb-3">
                    <button type="submit" id="btn" v-if="input.index!=null" class="button button-primary">Save Changes</button>
                    <button type="submit" id="btn" v-else class="button button-primary">Save Imprint Type</button>
                </div>

            </form>
        </div>
    </div>
</div>
        