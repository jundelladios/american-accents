<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content md">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="inputs.index!=null">Edit Color Collection</span>
                <span v-else>New Color Collection</span>
            </h1>
            
            <form action="/" @submit.prevent="saveColors" class="mt-3" autocomplete="off">
                
                <div class="row">
                    <div class="col-lg-3 col-sm-12">
                        <div class="mb-3">
                            <label class="d-block mb-2">Enter Color Collection Name</label>
                            <input type="text" class="full-width" v-model="inputs.title" v-validate="'required'" id="title" name="title" data-vv-as="color collection name" />
                            <span class="v-error">{{errors.first('title')}}</span>
                        </div>

                        <div class="mb-3">
                            <label class="d-block mb-2">Priority #</label>
                            <input type="text" class="full-width" v-model="inputs.priority" v-validate="'required'" id="priority" name="priority" data-vv-as="priority" />
                            <span class="v-error">{{errors.first('priority')}}</span>
                        </div>

                        <div class="floating-button-save">
                            <a href="javascript: void(0)" @click.stop="addCollection" class="button mr-2">Add Color</a>
                            <button type="submit" id="btn" v-if="inputs.index!=null" class="button button-primary mr-2">Save Changes</button>
                            <button type="submit" id="btn" v-else class="button button-primary mr-2">Save Color Collection</button>

                            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="button button-default">Cancel</a>
                        </div>

                    </div>
                    <div class="col-lg-9 col-sm-12">

                        <div class="row">
                            <div class="col-md-12">
                                <p>Note: You can drag and drop colors on this collection.</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" @click="selectAll_" class="button mr-2">Select All</button>
                            <button type="button" @click="unselectAll_" class="button mr-2">Unselect</button>
                            <button type="button" @click="removeCheckedItems_" v-if="getIsSelectedColors.length" class="button mr-2">Remove ({{getIsSelectedColors.length}})</button>
                        </div>

                        <draggable 
                        v-model="inputs.colorjson" 
                        class="v-draggable"
                        tag="div" 
                        v-bind="vueDragOptions"
                        @start="colordrag = true"
                        @end="colordrag = false"
                        >
                            <transition-group type="transition" tag="div" class="row" :name="!colordrag ? 'flip-list' : null">

                            <div v-for="(cc, index) in inputs.colorjson" :key="`color-index-${index}`" class="col-lg-6 mb-3">
                                <div class="p-3" style="border: 1px solid #afafaf;">

                                    <div style="float:right;">
                                        <input type="checkbox" 
                                        @input="() => $set(inputs.colorjson, index, {...cc, _isSelected: !cc._isSelected})" 
                                        :checked="cc._isSelected">
                                    </div>

                                    <div v-if="!inputs.colorjson[index].isImage">
                                        <div class="mb-2">
                                            <span class="color-circle" :key="`color-hex-${index}`" :style="`background: ${inputs.colorjson[index].hex}; width: 30px; height: 30px; border-radius: 100%; display: inline-block;`"></span>
                                            <colorpicker 
                                            v-model="cc.hex" 
                                            name="method_hex"
                                            inputclass="full-width"
                                            ></colorpicker>
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="full-width" placeholder="Pantone" v-model="cc.pantone">
                                        </div>
                                    </div>

                                    <div class="mb-2" v-else>
                                        <span class="color-circle mr-2" :key="`color-image-${index}`" :style="`background: url(${inputs.colorjson[index].image}) center no-repeat; background-size: cover; width: 30px; height: 30px; border-radius: 100%; display: inline-block;`"></span>
                                        <a href="javascript: void(0)" class="button" @click.stop="chooseImage(index)">Choose Image Color</a>
                                    </div>

                                    <div class="mb-2">
                                        <label>
                                            <input type="checkbox" :true-value="true" :false-value="false" v-model="inputs.colorjson[index].isImage">
                                            Use image for the color.
                                        </label>
                                    </div>

                                    <div class="mb-2">
                                        <input type="text" class="full-width" placeholder="color name" v-model="cc.name">
                                    </div>

                                    <a href="javascript: void(0)" @click.stop="removeColor(index)" class="button mb-2">Remove</a>
                                </div>

                                </div>

                                </transition-group>
                            </draggable>

                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>