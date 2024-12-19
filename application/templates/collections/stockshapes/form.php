<div v-if="form" class="aa-popup">
    <div class="aa-content-wrap">
        <div class="aa-content md">
            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="aa-close">&times;</a>
            <h1 class="wp-heading-inline">
                <span v-if="inputs.index!=null">Edit Stock Shape Collection</span>
                <span v-else>New Stock Shape Collection</span>
            </h1>
            
            <form action="/" @submit.prevent="saveCollection" class="mt-3" autocomplete="off">
                <div class="row">
                    <div class="col-lg-3 col-sm-12">
                        <div class="mb-3">
                            <label class="d-block mb-2">Enter Collection Name</label>
                            <input type="text" class="full-width" v-model="inputs.title" v-validate="'required'" id="title" name="title" data-vv-as="collection name" />
                            <span class="v-error">{{errors.first('title')}}</span>
                        </div>

                        <div class="mb-3">
                            <label class="d-block mb-2">Priority #</label>
                            <input type="text" class="full-width" v-model="inputs.priority" v-validate="'required'" id="priority" name="priority" data-vv-as="priority" />
                            <span class="v-error">{{errors.first('priority')}}</span>
                        </div>

                        <div class="floating-button-save">
                            <a href="javascript: void(0)" @click.stop="addCollection" class="button mr-2">Add Stock Shape</a>
                            <button type="submit" id="btn" v-if="inputs.index!=null" class="button button-primary mr-2">Save Changes</button>
                            <button type="submit" id="btn" v-else class="button button-primary mr-2">Save Collection</button>

                            <a href="javascript:void(0)" @click.prevent="formInputs(false, {...defaultValue})" class="button button-default">Cancel</a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-sm-12">

                        <div class="row">
                            <div class="col-md-12">
                                <p>Note: You can drag and drop stock shape lists on this collection. Empty stock code and image will be ignored.</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" @click="selectAll_" class="button mr-2">Select All</button>
                            <button type="button" @click="unselectAll_" class="button mr-2">Unselect</button>
                            <button type="button" @click="removeCheckedItems_" v-if="getIsSelectedStockshapes.length" class="button mr-2">Remove ({{getIsSelectedStockshapes.length}})</button>
                        </div>

                        <draggable 
                        v-model="inputs.collection" 
                        class="v-draggable"
                        tag="div" 
                        v-bind="vueDragOptions"
                        @start="dragGrid = true"
                        @end="dragGrid = false"
                        >
                            <transition-group type="transition" tag="div" class="row" :name="!dragGrid ? 'flip-list' : null">

                            <div v-for="(cc, index) in inputs.collection" :key="`color-index-${index}`" class="col-lg-6 mb-3">
                                <div class="p-3" style="border: 1px solid #afafaf;">

                                    <div style="float:right;">
                                        <input type="checkbox" 
                                        @input="() => $set(inputs.collection, index, {...cc, _isSelected: !cc._isSelected})" 
                                        :checked="cc._isSelected">
                                    </div>

                                    <div class="mb-2">
                                        <div class="mb-2" v-if="inputs.collection[index].image" style="padding:10px; background: #E6E6E6;
                                        width: 100px;
                                        height: 100px;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        ">
                                            <img :src="inputs.collection[index].image" alt="" style="max-height: 100%; width: auto; height: auto; max-width: 100%;">
                                        </div>
                                        <a href="javascript: void(0)" class="button" @click.stop="chooseImage(index)">Choose Stock Shape</a>
                                    </div>

                                    <div class="mb-2">
                                        <input type="text" class="full-width" placeholder="stock shape code" v-model="cc.code">
                                    </div>

                                    <div class="mb-2">
                                        <input type="text" class="full-width" placeholder="stock shape" v-model="cc.stockname">
                                    </div>

                                    <a href="javascript: void(0)" @click.stop="removeStockShape(index)" class="button mb-2">Remove</a>
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