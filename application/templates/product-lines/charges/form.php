<div class="mt-3 mb-3 row">

    <div class="col-lg-6">
        <form action="" @submit.prevent="saveCharge" data-vv-scope="charge">
            <div class="mb-3">
                <div v-if="chargeTypes.loading">Loading...</div>
                <div v-else>
                    <div v-if="chargeTypes.data.length">
                        <select name="charge_type_id" v-validate="'required'" data-vv-as="charge type" name="charge_type_id" v-model="chargesInputs.charge_type_id">
                            <option v-for="(type, i) in chargeTypes.data" :key="`charge-type-${i}`" :value="type.hid">{{ type.charge_name }}</option>
                        </select>
                        <small class="d-block">You can add charge types <a href="<?php menu_page_url( aa_app_suffix() . 'charge-types' ); ?>">here</a></small>
                        <span class="v-error">{{errors.first('charge.charge_type_id')}}</span>
                    </div>
                    <div v-else>
                        <p>Please create charge type first.</p>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="d-block mb-2">Note Value (optional)</label>
                <small class="d-block mb-2">If you enter  this field, breakdown charges will be ignored.</small>
                <input 
                type="text" 
                name="note_value"
                v-model="chargesInputs.note_value"
                class="full-width"
                />
            </div>

            <div class="mb-3">
                <label class="d-block mb-2">Priority #</label>
                <input 
                type="number" 
                v-validate="'required'"
                data-vv-as="priority"
                name="priority"
                v-model="chargesInputs.priority"
                />
                <span class="v-error">{{errors.first('charge.priority')}}</span>
            </div>
            <div class="mb-3">
                <label>
                    <input type="checkbox" :true-value="1" :false-value="0" v-model="chargesInputs.is_additional_spot"> Is additional spot color charge?
                </label>
            </div>
            <div class="mb-3">
                <label>
                    value:
                </label>
                <input type="text" v-model="chargesInputs.spot_color_value">
            </div>

            <hr class="mb-3">

            <div class="mb-3">
                <label>
                    <input type="checkbox" :true-value="1" :false-value="0" v-model="chargesInputs.per_color"> per color
                </label>
            </div>
            <div class="mb-3">
                <label>
                    value:
                </label>
                <input type="text" v-model="chargesInputs.per_color_value">
            </div>

            <hr class="mb-3">

            <div class="mb-3">
                <label>
                    <input type="checkbox" :true-value="1" :false-value="0" v-model="chargesInputs.per_piece"> per piece
                </label>
            </div>
            <div class="mb-3">
                <label>
                    value:
                </label>
                <input type="text" v-model="chargesInputs.per_piece_value">
            </div>

            <hr class="mb-3">

            <div class="mb-3">
                <label>
                    <input type="checkbox" :true-value="1" :false-value="0" v-model="chargesInputs.per_side"> per side
                </label>
            </div>
            <div class="mb-3">
                <label>
                    value:
                </label>
                <input type="text" v-model="chargesInputs.per_side_value">
            </div>

            <hr class="mb-3">

            <div class="mb-3">
                <label>
                    <input type="checkbox" :true-value="1" :false-value="0" v-model="chargesInputs.per_thousand"> per thousand
                </label>
            </div>
            <div class="mb-3">
                <label>
                    value:
                </label>
                <input type="text" v-model="chargesInputs.per_thousand_value">
            </div>

            <hr class="mb-3">

            <div class="mb-3">
                <label>
                    <input type="checkbox" :true-value="1" :false-value="0" v-model="chargesInputs.auto_format"> auto format values
                </label>
            </div>
            <div class="mb-3">
                <button type="submit" class="button button-primary">Save Charge</button>
                <button class="button" @click.stop="cancelCharge">Cancel</button>
            </div>
        </form>
    </div>


    <div class="col-lg-6" v-if="chargesInputs.hid">
        <h2>Charges Breakdowns</h2>

        <form action="/" @submit.prevent="saveBreakdown" data-vv-scope="cval">
            <div class="d-block">
                <div class="d-block">
                    <input 
                    class="full-width"
                    type="number" 
                    v-model="chargesValuesInputs.quantity" 
                    placeholder="Enter Quantity"
                    v-validate="'required'"
                    data-vv-as="quantity"
                    name="quantity"
                    min="0"
                    >
                    <span class="v-error">{{errors.first('cval.quantity')}}</span>
                </div>
                <div class="d-block mt-2">
                    <input type="number" min="0" step="any" v-model="chargesValuesInputs.value" placeholder="Enter Value" class="full-width">
                </div>
            </div>

            <div class="mt-2">
                <input type="text" v-model="chargesValuesInputs.decimal_value" placeholder="Decimal Point" class="full-width">
            </div>

            <div class="mt-2">
                <input type="text" v-model="chargesValuesInputs.unit_value" placeholder="Unit" class="full-width">
                <small class="d-block">Mostly used for Hi-Speed (M)</small>
            </div>

            <div class="mt-2">
                <input type="text" v-model="chargesValuesInputs.alternative_value" placeholder="Alternative Value" class="full-width">
                <small class="d-block">If using non-numeric value.</small>
            </div>

            <div class="mt-2">
                <label>
                    <input type="checkbox" :true-value="1" :false-value="0" v-model="chargesValuesInputs.asterisk"> This value has asterisk?
                </label>
            </div>
            <div class="mt-2">
                <button type="submit" v-if="chargesValuesInputs.index==null" class="button button-primary">Save Breakdown</button>
                <div v-else>
                    <button type="submit" class="button button-primary">Save Changes</button>
                    <button type="submit" @click.stop="cancelBreakdown" class="button">Cancel</button>
                </div>
            </div>
        </form>

        <table class="wp-list-table widefat fixed striped pages mt-3 mb-3">
            <thead>
                <tr>
                    <th>Quantity</th>
                    <th>Values</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(qb, i) in pbreakdowns" :key="`qbreakdowns-${i}`">
                    <td>{{qb.quantity}}</td>
                    <td>
                        <span v-if="qb.value">
                            {{parseFloat(qb.value).toFixed(qb.decimal_value)}}{{qb.unit_value}}
                        </span>
                        <span v-else>{{ qb.alternative_value }}</span>
                        <span v-if="qb.asterisk" class="asterisk">*</span>
                    </td>
                    <td>
                        <a href="javascript:void(0)" @click.stop="chargesValuesInputs={...qb, index: i,value: parseFloat(qb.value).toFixed(qb.decimal_value)}" class="button">edit</a>
                        <a href="javascript:void(0)" @click.stop="removeBreakdown(qb.hid, i)" class="button">remove</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>