<div class="mt-3 mb-3">

    <div v-for="(color, index) in inputs.colors" :key="`color-field-${index}`" class="mb-5">

        <div class="mb-3 p-3" style="border: 1px solid #c3c3c3; border-radius: 5px; background: #f3f3f3;">

            <label for="" class="d-block mb-2">Enter Color Type:</label>
            <input type="text" v-model="color.type" class="full-width">

            <a href="javascript:void(0)" @click.prevent="collapseColors(index)" class="mt-3 mb-3 d-block">Collapse</a>

            <div :data-form-collapse="`collapse-${index}`" style="display: none;">

            <strong class="d-block mt-3 mb-3">Colors:</strong>

            <div v-for="(c, cindex) in color.colors" :key="`color-lists-${cindex}`" class="d-block mb-2">

                <div class="mb-2">
                    <colorpicker 
                    v-model="c.hex" 
                    name="method_hex"></colorpicker>
                </div>

                <input type="text" placeholder="Color Name" v-model="c.name">

                <input type="text" placeholder="Pantone" v-model="c.pantone">

                <a href="javascript:void(0)" v-if="cindex!=0" @click.stop="removeColorSection(index, cindex)" class="button">Remove</a>

                <hr class="mt-3 mb-4">

            </div>

            <a href="javascript:void(0)" @click.stop="addColor(index)" class="button">Add Color</a>

            </div>

        </div>

        <a href="javascript:void(0)" class="button" @click.stop="removeSection(index)" v-if="index!=0">Remove Section</a>

    </div>

    <a href="javascript:void(0)" class="button" @click.stop="addColorSection">Add Color Section</a>

</div>