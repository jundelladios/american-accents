<form action="/" @submit.prevent="saveBreakdown" data-vv-scope="breakdown">

    <div class="full-width" style="max-width: 300px;">
    
        <div class="mb-2">
            <div>
                <input 
                type="number" 
                v-model="priceInputs.quantity" 
                placeholder="Enter Quantity"
                v-validate="'required'"
                data-vv-as="quantity"
                name="quantity"
                class="full-width"
                >
                <span class="v-error">{{errors.first('breakdown.quantity')}}</span>
            </div>
        </div>

        <div class="mb-2">
            <div>
                <input class="full-width" type="number" min="0" step="any" v-model="priceInputs.value" placeholder="Enter Value">
            </div>
        </div>
        
        <div class="mt-2">
            <input type="text" v-model="priceInputs.decimal_value" placeholder="Decimal Point" class="full-width">
        </div>

        <div class="mt-2">
            <input type="text" v-model="priceInputs.unit_value" placeholder="Unit" class="full-width">
            <small class="d-block">Mostly used for Hi-Speed (M)</small>
        </div>

        <div class="mb-2">
            <input class="full-width" type="text" v-model="priceInputs.alternative_value" placeholder="Alternative Value">
            <small class="d-block">If value using non-numeric.</small>
        </div>

    </div>

    <div class="mt-2">
        <button type="submit" v-if="priceInputs.index==null" class="button button-primary">Save Breakdown</button>
        <div v-else>
            <button type="submit" class="button button-primary">Save Changes</button>
            <button type="submit" @click.stop="resetBreakdownInputs" class="button">Cancel</button>
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
        <tr v-for="(pb, i) in inputs.pricings" :key="`pricing-breakdowns-${i}`">
            <td>{{pb.quantity}}</td>
            <td>
                <span v-if="pb.value">
                    {{parseFloat(pb.value).toFixed(pb.decimal_value)}}{{pb.unit_value}}
                </span>
                <span v-else>{{ pb.alternative_value }}</span>
                <span v-if="pb.asterisk" class="asterisk">*</span>
            </td>
            <td>
                <a href="javascript:void(0)" @click.stop="priceInputs={...pb, index: i, value: parseFloat(pb.value).toFixed(pb.decimal_value)}" class="button mb-2">edit</a>
                <a href="javascript:void(0)" @click.stop="removeBreakdown(pb.hid, i)" class="button mb-2">remove</a>
            </td>
        </tr>
    </tbody>
</table>